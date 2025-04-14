<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('user_orders.history', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('items.product')->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('user_orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|max:50'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }


    public function list()
    {
        // Lấy tất cả đơn hàng (ví dụ cho admin)
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
        return view('orders.list', compact('orders'));
    }

    public function revenue()
    {
        // Tổng doanh thu (chỉ tính đơn đã hoàn thành)
        $totalRevenue = Order::where('status', 'Đã Hoàn Thành')
            ->sum('total_price');
        $totalRevenueQuantity = OrderItem::whereHas('order', function ($query) {
            $query->where('status', 'Đã Hoàn Thành');
        })->sum('quantity');
        $totalRevenueInput = OrderItem::whereHas('order', function ($query) {
            $query->where('status', 'Đã Hoàn Thành');
        })->sum('input_price');

        $tinhlai =  $totalRevenueQuantity * $totalRevenueInput;
        $lai = $totalRevenue -  $tinhlai;
        $totalRevenueAll = Order::sum('total_price');

        // Doanh thu theo tháng (chỉ tính đơn đã hoàn thành)
        $monthlyRevenue = Order::selectRaw('MONTH(created_at) as month, SUM(total_price) as revenue')
            ->where('status', 'Đã Hoàn Thành')
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->get();

        // Tạo mảng nhãn cho biểu đồ (ví dụ: Tháng 1, Tháng 2,...)
        $chartLabels = $monthlyRevenue->pluck('month')->map(function ($m) {
            return 'Tháng ' . $m;
        });

        // Tổng số đơn hàng
        $totalOrders = Order::count();

        // Tổng số đơn đã giao thành công
        $completedOrders = Order::where('status', 'Đã Hoàn Thành')->count();

        // Tổng số lượng sản phẩm tồn kho
        $totalStockQuantity = Product::sum('quantity');

        return view('doanh_thu.list', compact(
            'totalRevenue',
            'monthlyRevenue',
            'totalOrders',
            'completedOrders',
            'totalStockQuantity', // thêm biến này
            'chartLabels',
            'lai'
        ));
    }
}
