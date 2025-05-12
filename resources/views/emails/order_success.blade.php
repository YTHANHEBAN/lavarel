<h2>Xin chào {{ $order->user->name }}</h2>

<p>Cảm ơn bạn đã đặt hàng tại cửa hàng của chúng tôi.</p>

<p>Chi tiết đơn hàng #{{ $order->id }}:</p>
<ul>
    <li>Tổng tiền: {{ number_format($order->total_price, 0, ',', '.') }} VNĐ</li>
    <li>Phương thức thanh toán: {{ $order->paymentmethod }}</li>
    <li>Địa chỉ giao hàng: {{ $order->address }}, {{ $order->ward }}, {{ $order->district }}, {{ $order->province }}</li>
</ul>

<p>Chúng tôi sẽ liên hệ với bạn sớm nhất có thể để xác nhận đơn hàng.</p>

<p>Trân trọng,<br>Đội ngũ hỗ trợ</p>
