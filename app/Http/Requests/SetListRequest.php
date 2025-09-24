<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Log;

class SetListRequest
{
    public function getFdSetListRequest() {
        $ch = curl_init('https://liberty-jones.ru/index.php?module=ListSetFDView&key=aZBcNu0V8mXA');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $result = curl_exec($ch);
        curl_close($ch);

        if($result){
            return json_decode($result, true);
        } else {
            $error_msg = curl_error($ch);
            Log::error($error_msg);
            return null;
        }
    }
}
