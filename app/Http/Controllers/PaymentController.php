<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function vnpay_payment(Request $request)
{
    $data = $request->all();
    $code_cart = rand(00, 9999);
    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    $vnp_Returnurl = "http://127.0.0.1:8000/checkout";
    $vnp_TmnCode = "1VYBIYQP";
    $vnp_HashSecret = "NOH6MBGNLQL9O9OMMFMZ2AX8NIEP50W1";

    $vnp_TxnRef = $code_cart;
    $vnp_OrderInfo = 'Thanh toán đơn hàng test';
    $vnp_OrderType = 'billpayment';
    $vnp_Amount = $data['total_vnpay'] * 100;
    $vnp_Locale = 'vn';
    $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

    $inputData = array(
        "vnp_Version" => "2.1.0",
        "vnp_TmnCode" => $vnp_TmnCode,
        "vnp_Amount" => $vnp_Amount,
        "vnp_Command" => "pay",
        "vnp_CreateDate" => date('YmdHis'),
        "vnp_CurrCode" => "VND",
        "vnp_IpAddr" => $vnp_IpAddr,
        "vnp_Locale" => $vnp_Locale,
        "vnp_OrderInfo" => $vnp_OrderInfo,
        "vnp_OrderType" => $vnp_OrderType,
        "vnp_ReturnUrl" => $vnp_Returnurl,
        "vnp_TxnRef" => $vnp_TxnRef,
    );

    ksort($inputData);
    $hashdata = urldecode(http_build_query($inputData));
    $query = http_build_query($inputData);

    $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
    $vnp_Url .= '?' . $query . '&vnp_SecureHash=' . $vnpSecureHash;

    $returnData = array(
        'code' => '00',
        'message' => 'success',
        'data' => $vnp_Url
    );

    if (isset($_POST['redirect'])) {
        header('Location: ' . $vnp_Url);
        die();
    } else {
        echo json_encode($returnData);
    }
}

}
