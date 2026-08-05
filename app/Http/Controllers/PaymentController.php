<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Paystack;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Paystack redirects the customer here after the payment attempt.
     */
    public function callback(Request $request): RedirectResponse
    {
        $reference = $request->query('reference') ?: $request->query('trxref');
        $order = $reference ? Order::query()->where('payment_reference', $reference)->first() : null;

        if (! $order) {
            abort(404, 'Payment reference not found.');
        }

        if (! $order->paid_at) {
            try {
                $result = app(Paystack::class)->verify($reference);
                $data = $result->json('data');

                if (($data['status'] ?? null) === 'success' && (int) ($data['amount'] ?? 0) === Paystack::amountInKobo($order)) {
                    $order->update([
                        'status' => 'paid',
                        'paid_at' => now(),
                    ]);
                }
            } catch (RequestException $e) {
                Log::warning('Paystack verification failed.', [
                    'order' => $order->order_number,
                    'error' => $e->response->json('message') ?? $e->getMessage(),
                ]);
            }
        }

        return redirect()->route('checkout.confirmation', $order);
    }

    /**
     * Retry payment for an unpaid order.
     */
    public function pay(Order $order, Paystack $paystack): RedirectResponse
    {
        abort_if($order->paid_at, 404, 'This order has already been paid.');
        abort_if($order->payment_method !== 'paystack', 404, 'This order is not paid online.');

        if (! $order->payment_reference) {
            $order->update(['payment_reference' => $paystack->reference()]);
        }

        $url = $paystack->initialize($order, route('payments.callback'));

        return redirect()->away($url);
    }

    /**
     * Paystack server-to-server event notification.
     */
    public function webhook(Request $request, Paystack $paystack): JsonResponse
    {
        $signature = $request->header('x-paystack-signature', '');
        $payload = $request->getContent();

        if (! $paystack->verifyWebhookSignature($signature, $payload)) {
            return response()->json(['status' => 'invalid signature'], 401);
        }

        $event = json_decode($payload, true);
        $data = $event['data'] ?? [];

        if (($event['event'] ?? '') !== 'charge.success') {
            return response()->json(['status' => 'ignored']);
        }

        $reference = $data['reference'] ?? null;
        $order = $reference ? Order::query()->where('payment_reference', $reference)->first() : null;

        if ($order && ! $order->paid_at) {
            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);
        }

        return response()->json(['status' => 'ok']);
    }
}
