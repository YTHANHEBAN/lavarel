<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\ProductReview;

class OrderController extends Controller
{
    // public function index()
    // {
    //     $orders = Order::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
    //     return view('user_orders.history', compact('orders'));
    // }

    public function index(Request $request)
    {
        $query = Order::where('user_id', Auth::id());

        // Nếu có truyền trạng thái thì lọc theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        return view('user_orders.history', compact('orders'));
    }


    public function show_admin($id)
    {
        $order = Order::with('items.product')->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('orders.show', compact('order'));
    }
    public function show($id)
    {
        $order = Order::with('items.product')->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $products_reviews = ProductReview::query()->where('user_id', Auth::id())->get();
        return view('user_orders.show', compact('order','products_reviews'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|max:50'
        ]);

        $order = Order::findOrFail($id);

        // Nếu trạng thái thay đổi thành "Đã Hủy" và trước đó không phải là "Đã Hủy"
        if ($request->status === 'Đã Hủy' && $order->status !== 'Đã Hủy') {
            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->quantity += $item->quantity; // hoàn lại số lượng vào kho
                    $product->save();
                }
            }
        }

        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }


    //     public function list()
    // {
    //     $query = Order::with('user')->orderBy('created_at', 'desc');

    //     // Nếu có yêu cầu lọc theo trạng thái
    //     if (request()->has('status') && request()->status !== '') {
    //         $query->where('status', request()->status);
    //     }

    //     $orders = $query->get();

    //     // Truyền thêm status hiện tại để dùng lại trong view nếu muốn
    //     return view('orders.list', [
    //         'orders' => $orders,
    //         'currentStatus' => request()->status
    //     ]);
    // }

    public function list()
    {
        $query = Order::orderBy('id', 'desc');

        // Nếu request có truyền 'status' và không rỗng, lọc theo status
        if (request()->has('status') && request()->status !== '') {
            $query->where('status', request()->status);
        }

        $orders = $query->get();

        return view('orders.list', [
            'orders' => $orders,
            'currentStatus' => request()->status
        ]);
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

    $tinhlai = $totalRevenueQuantity * $totalRevenueInput;
    $lai = $totalRevenue - $tinhlai;
    $totalRevenueAll = Order::sum('total_price');

    // Doanh thu theo tháng
    $monthlyRevenue = Order::selectRaw('MONTH(created_at) as month, SUM(total_price) as revenue')
        ->where('status', 'Đã Hoàn Thành')
        ->groupByRaw('MONTH(created_at)')
        ->orderByRaw('MONTH(created_at)')
        ->get();

    // Doanh thu theo tuần
    $weeklyRevenue = Order::selectRaw('YEARWEEK(created_at, 1) as week, SUM(total_price) as revenue')
        ->where('status', 'Đã Hoàn Thành')
        ->groupByRaw('YEARWEEK(created_at, 1)')
        ->orderByRaw('YEARWEEK(created_at, 1)')
        ->get();

    // Doanh thu theo ngày
    $dailyRevenue = Order::selectRaw('DATE(created_at) as date, SUM(total_price) as revenue')
        ->where('status', 'Đã Hoàn Thành')
        ->groupByRaw('DATE(created_at)')
        ->orderByRaw('DATE(created_at)')
        ->get();

    // Nhãn cho biểu đồ tháng
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
        'weeklyRevenue',
        'dailyRevenue',
        'totalOrders',
        'completedOrders',
        'totalStockQuantity',
        'chartLabels',
        'lai'
    ));
}

}
