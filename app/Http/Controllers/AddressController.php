<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    function getProvice()
    {
        $url = env('GHNHANH_API_URL') . "/shiip/public-api/master-data/province";
        $token = env('GHNHANH_API_KEY');
        $options = [
            'http' => [
                'header' => "Token: $token\r\n"
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $data = json_decode($response, true);
        return response()->json($data);
    }
    function getDistrict($provinceId)
    {
        $url = env('GHNHANH_API_URL') . "/shiip/public-api/master-data/district?province_id=$provinceId";
        $token = env('GHNHANH_API_KEY');
        $options = [
            'http' => [
                'header' => "Token: $token\r\n"
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $data = json_decode($response, true);
        return response()->json($data);
    }

    // public function getWard($districtId)
    // {
    //     $url = env('GHNHANH_API_URL') . "/shiip/public-api/master-data/ward?district_id=$districtId";
    //     $token = env('GHNHANH_API_KEY');
    //     $options = [
    //         'http' => [
    //             'header' => "Token: $token\r\n",

    //             'data' => "district_id=$districtId"
    //         ]

    //     ];
    //     $context = stream_context_create($options);
    //     $response = file_get_contents($url, false, $context);
    //     $data = json_decode($response, true);
    //     return response()->json($data);
    // }

    public function getWard($districtId)
    {
        $url = env('GHNHANH_API_URL') . "/shiip/public-api/master-data/ward?district_id=$districtId";
        $token = env('GHNHANH_API_KEY');
        $options = [
            'http' => [
                'header' => "Token: $token\r\n"
            ]
        ];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $data = json_decode($response, true);
        return response()->json($data);
    }

    public function calculateShippingFee2(Request $request)
    {
        $url = env('GHNHANH_API_URL') . '/shiip/public-api/v2/shipping-order/fee';
        $token = env('GHNHANH_API_KEY');
        $shopId = env('GHNHANH_SHOP_ID'); // Lấy từ GHN dashboard

        $data = [
            "from_district_id" => 1450, // ID quận cửa hàng
            "service_id" => 53320, // ID dịch vụ vận chuyển (tuỳ thuộc khu vực)
            "to_district_id" => (int)$request->district_id,
            "to_ward_code" => $request->ward_code,
            "height" => 10,
            "length" => 20,
            "weight" => (int)$request->weight ?? 1000,
            "width" => 15,
            "insurance_value" => $request->insurance_value ?? 0
        ];

        $options = [
            'http' => [
                'header' => [
                    "Content-Type: application/json",
                    "Token: $token",
                    "ShopId: $shopId"
                ],
                'method' => 'POST',
                'content' => json_encode($data)
            ]
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        return response()->json(json_decode($response, true));
    }


    public function index()
    {
        $addresses = Auth::user()->addresses;
        return view('address.list', compact('addresses'));
    }

    // Form thêm địa chỉ
    public function create()
    {
        return view('address.create');
    }

    // Lưu địa chỉ mới
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'nullable|string|max:255',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'ward'     => 'nullable|string|max:255',
        ]);

        Address::create([
            'user_id'  => Auth::id(),
            'name'     => $request->name,
            'phone'    => $request->phone,
            'address'  => $request->address,
            'province' => $request->province,
            'district' => $request->district,
            'ward'     => $request->ward,
        ]);

        return redirect()->route('addresses.index')->with('success', 'Đã thêm địa chỉ mới!');
    }

    public function edit($id)
    {
        $address = Address::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('address.edit', compact('address'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'     => 'nullable|string|max:255',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'ward'     => 'nullable|string|max:255',
        ]);

        $address = Address::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $address->update($request->only(['name', 'phone', 'address', 'province', 'district', 'ward']));

        return redirect()->route('addresses.index')->with('success', 'Cập nhật địa chỉ thành công!');
    }


    // Xoá địa chỉ
    public function destroy($id)
    {
        $address = Address::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $address->delete();

        return redirect()->route('addresses.index')->with('success', 'Đã xoá địa chỉ.');
    }
}
