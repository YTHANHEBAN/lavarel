<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

}
