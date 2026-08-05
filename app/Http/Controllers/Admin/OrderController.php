<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public const STATUSES = [
        'placed' => ['label' => 'Placed', 'color' => 'gray'],
        'paid' => ['label' => 'Paid', 'color' => 'blue'],
        'processing' => ['label' => 'Processing', 'color' => 'amber'],
        'shipped' => ['label' => 'Shipped', 'color' => 'indigo'],
        'delivered' => ['label' => 'Delivered', 'color' => 'green'],
        'cancelled' => ['label' => 'Cancelled', 'color' => 'red'],
    ];

    public function index(Request $request)
    {
        $query = Order::query()
            ->withCount('items')
            ->when($request->filled('q'), function ($q) use ($request) {
                $q->where(fn ($q) => $q
                    ->where('order_number', 'like', "%{$request->q}%")
                    ->orWhere('customer_name', 'like', "%{$request->q}%")
                    ->orWhere('customer_email', 'like', "%{$request->q}%"));
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest();

        return view('admin.orders.index', [
            'orders' => $query->paginate(15)->withQueryString(),
            'statuses' => self::STATUSES,
            'filters' => $request->only(['q', 'status']),
        ]);
    }

    public function show(Order $order)
    {
        $order->load('items');

        return view('admin.orders.show', [
            'order' => $order,
            'statuses' => self::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(self::STATUSES))],
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('status', 'Order status updated.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('status', 'Order deleted.');
    }
}
