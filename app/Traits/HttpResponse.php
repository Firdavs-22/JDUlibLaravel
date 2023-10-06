<?php

namespace App\Traits;
trait HttpResponse
{
    protected function success($data = null, $message = null, $code = 200)
    {
        $json = ['status' => 'successful'];
        if (!empty($data)) $json['data'] = $data;
        if (!empty($message)) $json['message'] = $message;
        return response()->json($json, $code);
    }

    protected function error($data = null, $message = null, $code)
    {
        $json = ['status' => 'failed'];
        if (!empty($data)) $json['data'] = $data;
        if (!empty($message)) $json['message'] = $message;
        return response()->json($json, $code);
    }
}
