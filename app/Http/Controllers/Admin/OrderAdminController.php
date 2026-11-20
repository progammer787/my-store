<?php
// ============================================================
// app/Http/Controllers/Admin/OrderAdminController.php
// ============================================================
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $query->where(function($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->q . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->q . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->q . '%');
            });
        }

        $orders = $query->paginate(20)->withQueryString();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        DB::transaction(function () use ($order, $request) {
            $order = Order::whereKey($order->getKey())->lockForUpdate()->firstOrFail();

            if ($order->status === 'cancelled' && $request->status !== 'cancelled') {
                abort(422, 'Cancelled orders cannot be reopened.');
            }

            if ($request->status === 'cancelled' && $order->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    if ($item->product_id) {
                        \App\Models\Product::whereKey($item->product_id)
                            ->increment('stock', $item->quantity);
                    }
                }
            }

            $order->update([
                'status'       => $request->status,
                'confirmed_at' => $request->status === 'confirmed' ? now() : $order->confirmed_at,
            ]);
        });

        return back()->with('success', 'Order status updated.');
    }
}
