<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class Paystack
{
    private string $secretKey;

    public function __construct(?string $secretKey = null)
    {
        $this->secretKey = $secretKey ?? (string) config('paystack.secret_key');
    }

    public function available(): bool
    {
        return $this->secretKey !== '' && $this->secretKey !== null;
    }

    /**
     * Amount for Paystack: kobo = stored amount * 1000
     * (stored amounts are kobo-scale where naira = amount * 10).
     */
    public static function amountInKobo(Order $order): int
    {
        return $order->total * 1000;
    }

    /**
     * Create a transaction on Paystack and return the redirect URL.
     *
     * @throws RequestException|ConnectionException
     */
    public function initialize(Order $order, string $callbackUrl): string
    {
        $response = $this->request('POST', '/transaction/initialize', [
            'reference' => $order->payment_reference,
            'amount' => self::amountInKobo($order),
            'email' => $order->customer_email,
            'currency' => config('paystack.currency'),
            'callback_url' => $callbackUrl,
            'metadata' => [
                'order_number' => $order->order_number,
                'custom_fields' => [
                    ['display_name' => 'Order Number', 'variable_name' => 'order_number', 'value' => $order->order_number],
                ],
            ],
        ]);

        return $response->json('data.authorization_url');
    }

    /**
     * Fetch a transaction by reference and return the verified response.
     */
    public function verify(string $reference): Response
    {
        return $this->request('GET', "/transaction/verify/{$reference}");
    }

    /**
     * Confirm a webhook payload came from Paystack using the HMAC-SHA512 signature.
     */
    public function verifyWebhookSignature(string $signature, string $payload): bool
    {
        if (! $this->available() || $signature === '') {
            return false;
        }

        $hash = hash_hmac('sha512', $payload, $this->secretKey);

        return hash_equals($hash, $signature);
    }

    public function reference(): string
    {
        return 'PS-'.Str::upper(Str::random(12));
    }

    private function request(string $method, string $path, array $data = []): Response
    {
        return Http::baseUrl(config('paystack.api_url'))
            ->acceptJson()
            ->withToken($this->secretKey)
            ->timeout(15)
            ->{$method}($path, $data)
            ->throw();
    }
}
