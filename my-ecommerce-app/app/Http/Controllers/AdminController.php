<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get some quick stats
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalCustomers = User::where('is_admin', 0)->count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');

        return view('admin.dashboard', compact('totalOrders', 'totalProducts', 'totalCustomers', 'totalRevenue'));
    }

    public function orders()
    {
        // Get all orders, newest first, with user data
        $orders = Order::with('user')->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        // Eager load user and products (with pivot data)
        $order->load('user', 'products');
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|string|in:pending,processing,completed,shipped,cancelled']);

        $order->status = $request->status;
        $order->save();

        return redirect()->route('admin.orders.show', $order)->with('success', 'Order status updated.');
    }
}

