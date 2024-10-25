<?php

namespace App\Http\Requests;

class ProductRequest
{
    public function getProductsBySku(array $productSku): ?array
    {
        $data = [
            'filter' => [
                'provider' => 'FineDesign',
                'vendorCode' => $productSku,
            ]
        ];
        $ch = curl_init('https://abc.kidsberry.org/getProducts');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        $resArr = json_decode($res, true);
        if($resArr['products']) {
            return $resArr['products'];
        }
        return null;
    }
}
