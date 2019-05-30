<?php

namespace App\Http\Controllers\Traits;

trait Output
{
    public function success($data = [])
    {
        return response(['code' => 200, 'data' => $data, 'msg' => '']);
    }

    public function error($msg = '', $code = 500, $data = [])
    {
        return response(['code' => $code, 'data' => $data, 'msg' => $msg ?: 'server error']);
    }
}