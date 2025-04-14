<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Address;
use Gloudemans\Shoppingcart\Cart as ShoppingcartCart;

class CartController extends Controller
{
    // Hiển thị danh sách sản phẩm trong giỏ hàng
    public function index()
    {
        $userId = Auth::id();

        // Lấy tất cả đơn hàng trong giỏ của user hiện tại
        $cartItems = Cart::where('user_id', $userId)->with('product')->get();

        // Tính tổng tiền
        $total = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return view('carts.list', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        // Bước 1: Validate đầu vào
        $data = $request->validate([
            'user_id' => 'required|integer',
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'input_price'=>'required|numeric|min:0',
        ]);

        // Bước 2: Kiểm tra tồn kho
        $product = Product::find($data['product_id']);

        if (!$product) {
            return back()->with('error', 'Sản phẩm không tồn tại.');
        }

        if ($product->quantity < $data['quantity']) {
            return back()->with('error', 'Không đủ hàng trong kho.');
        }

        // Bước 3: Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $existingItem = Cart::where('user_id', $data['user_id'])
            ->where('product_id', $data['product_id'])
            ->first();

        if ($existingItem) {
            // Nếu đã có, kiểm tra tổng số lượng mới có vượt quá số lượng trong kho không
            $newQuantity = $existingItem->quantity + $data['quantity'];

            if ($product->quantity < $newQuantity) {
                return back()->with('error', 'Số lượng yêu cầu vượt quá số lượng còn lại trong kho.');
            }

            $existingItem->quantity = $newQuantity;
            $existingItem->total_price = $newQuantity * $data['price'];
            $existingItem->save();
        } else {
            // Nếu chưa có, tạo mới
            $data['total_price'] = $data['quantity'] * $data['price'];
            Cart::create($data);
        }

        // Bước 4: Trừ số lượng trong kho
        $product->quantity -= $data['quantity'];
        $product->save();

        // Bước 5: Thông báo và chuyển hướng
        Session::flash('message', 'Thêm vào giỏ hàng thành công!');
        return redirect()->route('cart.index');
    }



    // Cập nhật số lượng sản phẩm trong giỏ hàng
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'quantity' => 'required|numeric|min:1',
        ]);

        $cartItem = Cart::findOrFail($id);

        // Kiểm tra user sở hữu cart
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }

        // Lấy sản phẩm liên quan
        $product = Product::find($cartItem->product_id);

        if (!$product) {
            Session::flash('error', 'Sản phẩm không tồn tại.');
            return redirect()->route('cart.index');
        }

        $oldQuantity = $cartItem->quantity;
        $newQuantity = $data['quantity'];
        $diff = $newQuantity - $oldQuantity;

        // Nếu tăng số lượng
        if ($diff > 0) {
            if ($product->quantity < $diff) {
                Session::flash('error', 'Không đủ số lượng trong kho.');
                return redirect()->route('cart.index');
            }
            $product->quantity -= $diff;
        }
        // Nếu giảm số lượng
        else {
            $product->quantity += abs($diff);
        }

        $product->save();

        // Cập nhật giỏ hàng
        $cartItem->quantity = $newQuantity;
        $cartItem->total_price = $newQuantity * $cartItem->price;
        $cartItem->save();

        Session::flash('message', 'Cập nhật giỏ hàng thành công!');
        return redirect()->route('cart.index');
    }


    // Xóa sản phẩm khỏi giỏ hàng
    public function destroy($id)
    {
        $cartItem = Cart::findOrFail($id);
        // Kiểm tra người dùng
        if ($cartItem->user_id !== Auth::id()) {
            abort(403);
        }
        // Lấy sản phẩm liên quan
        $product = Product::find($cartItem->product_id);
        if ($product) {
            // Cộng lại số lượng sản phẩm về kho
            $product->quantity += $cartItem->quantity;
            $product->save();
        }
        // Xóa khỏi giỏ hàng
        $cartItem->delete();
        Session::flash('message', 'Xóa sản phẩm khỏi giỏ hàng thành công!');
        return redirect()->route('cart.index');
    }


    // Xóa toàn bộ giỏ hàng (tuỳ chọn)
    public function clear()
    {
        $userId = Auth::id();

        // Lấy toàn bộ giỏ hàng của user
        $cartItems = Cart::where('user_id', $userId)->get();

        foreach ($cartItems as $item) {
            // Lấy sản phẩm liên quan
            $product = Product::find($item->product_id);

            if ($product) {
                // Cộng lại số lượng vào kho
                $product->quantity += $item->quantity;
                $product->save();
            }
        }

        // Xóa toàn bộ giỏ hàng
        Cart::where('user_id', $userId)->delete();

        Session::flash('message', 'Đã xóa toàn bộ giỏ hàng và hoàn sản phẩm về kho.');
        return redirect()->route('cart.index');
    }


    public function checkout()
    {
        $userId = Auth::id();
        $addresses = Auth::user()->addresses;
        $carts = Cart::where('user_id', $userId)->with('product')->get();

        $total = $carts->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return view('carts.checkout', compact('carts', 'total', 'addresses'));
    }

    public function AddressCheckout()
    {
        $addresses = Auth::user()->addresses;
        return view('carts.checkout', compact('addresses'));
    }


    public function processCheckout(Request $request)
    {
        $userId = Auth::id();
        $addresses = Auth::user()->addresses;
        $data = $request->validate([
            'paymentmethod' => 'required|max:255',
            'province' => 'required|max:255',
            'district' => 'required|max:255',
            'ward' => 'required|max:255',
            'address' => 'required|max:255',
        ]);

        $cartItems = Cart::where('user_id', $userId)->with('product')->get();

        if ($cartItems->isEmpty()) {
            Session::flash('error', 'Giỏ hàng của bạn đang trống.');
            return redirect()->route('cart.index');
        }

        $totalPrice = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        // Nếu chọn VNPAY thì chuyển hướng sang trang thanh toán
        if ($data['paymentmethod'] === 'VNPAY') {
            // Gửi dữ liệu tạm qua session (hoặc base64_encode nếu muốn đính vào redirect URL)
            Session::put('vnpay_checkout_data', [
                'total_price' => $totalPrice,
                'province' => $data['province'],
                'district' => $data['district'],
                'ward' => $data['ward'],
                'address' =>$data['address'],
            ]);

            return $this->processVNPay(time(), $totalPrice); // dùng time làm mã giao dịch
        }
        if ($data['paymentmethod'] === 'MOMO') {
            Session::put('momo_checkout_data', [
                'total_price' => $totalPrice,
                'province' => $data['province'],
                'district' => $data['district'],
                'ward' => $data['ward'],
                'address' =>$data['address'],

            ]);

            return $this->processMoMo(time(), $totalPrice); // dùng time làm mã giao dịch
        }


        // Xử lý thanh toán COD hoặc khác
        $order = Order::create([
            'user_id' => $userId,
            'total_price' => $totalPrice,
            'status' => 'Chờ Xác Nhận',
            'paymentmethod' => $data['paymentmethod'],
            'province' => $data['province'],
            'district' => $data['district'],
            'ward' => $data['ward'],
            'address' =>$data['address'],

        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total_price' => $item->quantity * $item->price,
                'input_price' => $item->input_price,
            ]);
        }

        Cart::where('user_id', $userId)->delete();

        Session::flash('message', 'Đặt hàng thành công!');
        return redirect()->route('carts.thanks', $order->id);
    }

    private function processVNPay($orderId, $amount)
    {
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html"; // Địa chỉ VNPAY
        $vnp_Returnurl = route('vnpay.return'); // URL trả về sau khi thanh toán
        $vnp_TmnCode = "HEDQ3E2I"; // Mã TMNCode của bạn
        $vnp_HashSecret = "H3E9ZUJ92KEGYSW8NK9HEJHZ2B3VLS3B"; // Secret Key của bạn

        $vnp_TxnRef = time(); // Sử dụng thời gian hiện tại làm tham chiếu giao dịch
        $vnp_OrderInfo = "Thanh toán đơn hàng #$orderId"; // Thông tin đơn hàng
        $vnp_Amount = $amount * 100; // VNPAY yêu cầu số tiền phải nhân với 100
        $vnp_Locale = "vn"; // Ngôn ngữ
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR']; // Địa chỉ IP của người dùng

        $inputData = [
            "vnp_Version"    => "2.1.0",
            "vnp_TmnCode"    => $vnp_TmnCode,
            "vnp_Amount"     => $vnp_Amount,
            "vnp_Command"    => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode"   => "VND",
            "vnp_IpAddr"     => $vnp_IpAddr,
            "vnp_Locale"     => $vnp_Locale,
            "vnp_OrderInfo"  => $vnp_OrderInfo,
            "vnp_OrderType"  => "billpayment",
            "vnp_ReturnUrl"  => $vnp_Returnurl,
            "vnp_TxnRef"     => $vnp_TxnRef,
        ];

        ksort($inputData);
        $hashdata = "";
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $vnp_Url .= "?" . http_build_query($inputData);

        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= '&vnp_SecureHash=' . $vnpSecureHash;

        return redirect($vnp_Url); // Redirect đến URL của VNPAY
    }


    public function vnpayReturn(Request $request)
    {
        $vnp_ResponseCode = $request->get('vnp_ResponseCode');
        $vnp_TxnRef = $request->get('vnp_TxnRef');
        $userId = Auth::id();

        if ($vnp_ResponseCode == '00') {
            // Thanh toán thành công
            $data = Session::get('vnpay_checkout_data');
            $cartItems = Cart::where('user_id', $userId)->with('product')->get();

            if (!$data || $cartItems->isEmpty()) {
                Session::flash('error', 'Dữ liệu giỏ hàng không hợp lệ.');
                return redirect()->route('cart.index');
            }

            $order = Order::create([
                'user_id' => $userId,
                'total_price' => $data['total_price'],
                'status' => 'Đã thanh toán',
                'paymentmethod' => 'VNPAY',
                'province' => $data['province'],
                'district' => $data['district'],
                'ward' => $data['ward'],
                'address' =>$data['address'],

            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'total_price' => $item->quantity * $item->price,
                ]);
            }

            Cart::where('user_id', $userId)->delete();
            Session::forget('vnpay_checkout_data');

            Session::flash('message', 'Thanh toán và đặt hàng thành công!');
            return redirect()->route('carts.thanks', $order->id);
        } else {
            Session::flash('error', 'Thanh toán thất bại!');
            return redirect()->route('cart.index');
        }
    }


    private function processMoMo($orderId, $amount)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = "MOMOTEST";
        $accessKey = "F8BBA842ECF85";
        $secretKey = "K951B6PE1waDMi640xX08PD3vg6EkVlz";
        $orderInfo = "Thanh toán qua MoMo - Đơn hàng #" . $orderId;
        $redirectUrl = route('momo.return');
        $ipnUrl = route('momo.return');
        $amount = strval($amount);
        $orderId = strval($orderId);
        $requestId = time() . "";
        $requestType = "captureWallet";

        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'accessKey' => $accessKey,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'extraData' => "",
            'requestType' => $requestType,
            'signature' => $signature,
            'lang' => 'vi'
        ];

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $result = curl_exec($ch);
        curl_close($ch);

        $jsonResult = json_decode($result, true);

        // ✅ Xử lý lỗi nếu không có 'payUrl'
        if (isset($jsonResult['payUrl'])) {
            return redirect($jsonResult['payUrl']);
        } else {
            // Ghi log lỗi hoặc debug
            Log::error('MoMo trả về lỗi:', $jsonResult);
            return redirect()->back()->with('error', 'Không thể tạo liên kết thanh toán MoMo. Vui lòng thử lại sau.');
        }
    }

public function momoReturn(Request $request)
{
    $resultCode = $request->get('resultCode');
    $orderId = $request->get('orderId');
    $userId = Auth::id();

    if ($resultCode == 0) {
        $data = Session::get('momo_checkout_data');
        $cartItems = Cart::where('user_id', $userId)->with('product')->get();

        if (!$data || $cartItems->isEmpty()) {
            Session::flash('error', 'Dữ liệu giỏ hàng không hợp lệ.');
            return redirect()->route('cart.index');
        }

        $order = Order::create([
            'user_id' => $userId,
            'total_price' => $data['total_price'],
            'status' => 'Đã thanh toán',
            'paymentmethod' => 'MOMO',
            'province' => $data['province'],
            'district' => $data['district'],
            'ward' => $data['ward'],
            'address' =>$data['address'],

        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total_price' => $item->quantity * $item->price,
            ]);
        }

        Cart::where('user_id', $userId)->delete();
        Session::forget('momo_checkout_data');

        Session::flash('message', 'Thanh toán bằng MoMo thành công!');
        return redirect()->route('carts.thanks', $order->id);
    } else {
        Session::flash('error', 'Thanh toán MoMo thất bại!');
        return redirect()->route('cart.index');
    }
}

}
