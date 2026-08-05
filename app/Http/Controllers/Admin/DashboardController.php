<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();
        $monthStart = $now->copy()->startOfMonth();
        $prevMonthStart = $monthStart->copy()->subMonth();
        $prevMonthEnd = $monthStart->copy()->subSecond();

        $baseOrders = Order::query()->where('status', '!=', 'cancelled');

        $revenue = (clone $baseOrders)->sum('total');
        $revenueThisMonth = (clone $baseOrders)->where('created_at', '>=', $monthStart)->sum('total');
        $revenuePrevMonth = (clone $baseOrders)
            ->whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])
            ->sum('total');

        $ordersCount = (clone $baseOrders)->count();
        $ordersThisMonth = (clone $baseOrders)->where('created_at', '>=', $monthStart)->count();
        $ordersPrevMonth = (clone $baseOrders)
            ->whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])
            ->count();

        $customers = User::query()->where('role', 'customer')->count();
        $customersThisMonth = User::query()
            ->where('role', 'customer')
            ->where('created_at', '>=', $monthStart)
            ->count();
        $customersPrevMonth = User::query()
            ->where('role', 'customer')
            ->whereBetween('created_at', [$prevMonthStart, $prevMonthEnd])
            ->count();

        $productsCount = Product::count();
        $lowStock = Product::query()->where('stock', '<', 10)->count();
        $outOfStock = Product::query()->where('stock', '=', 0)->count();

        $revenueSeries = collect(range(11, 0))->map(function (int $offset) use ($now) {
            $month = $now->copy()->subMonths($offset);

            return [
                'label' => $month->format('M'),
                'value' => Order::query()
                    ->where('status', '!=', 'cancelled')
                    ->whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])
                    ->sum('total'),
            ];
        });
        $maxRevenue = max($revenueSeries->max('value'), 1);

        $statusCounts = Order::query()
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $topProducts = Product::query()
            ->withSum(['items as units_sold' => fn ($q) => $q->whereHas('order', fn ($q) => $q->where('status', '!=', 'cancelled'))], 'quantity')
            ->withSum(['items as revenue' => fn ($q) => $q->whereHas('order', fn ($q) => $q->where('status', '!=', 'cancelled'))], 'line_total')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        $recentOrders = Order::query()
            ->withCount('items')
            ->latest()
            ->limit(8)
            ->get();

        return view('admin.dashboard.index', [
            'stats' => [
                ['label' => 'Revenue', 'value' => naira($revenueThisMonth), 'delta' => percent_delta($revenueThisMonth, $revenuePrevMonth), 'hint' => 'vs last month'],
                ['label' => 'Orders', 'value' => number_format($ordersThisMonth), 'delta' => percent_delta($ordersThisMonth, $ordersPrevMonth), 'hint' => 'vs last month'],
                ['label' => 'New Customers', 'value' => number_format($customersThisMonth), 'delta' => percent_delta($customersThisMonth, $customersPrevMonth), 'hint' => 'vs last month'],
                ['label' => 'Products', 'value' => number_format($productsCount), 'delta' => null, 'hint' => "{$lowStock} low stock · {$outOfStock} sold out"],
            ],
            'totals' => [
                'revenue' => naira($revenue),
                'orders' => number_format($ordersCount),
                'customers' => number_format($customers),
                'average' => $ordersCount ? naira((int) round($revenue / $ordersCount)) : '₦0',
            ],
            'revenueSeries' => $revenueSeries,
            'maxRevenue' => $maxRevenue,
            'statusCounts' => $statusCounts,
            'statusLabels' => [
                'placed' => 'Placed',
                'paid' => 'Paid',
                'processing' => 'Processing',
                'shipped' => 'Shipped',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
            ],
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders,
            'categories' => Category::withCount('products')->get(),
        ]);
    }
}
