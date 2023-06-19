<?php

namespace Config;

class ApiResponse
{
    public static function success($data = [], $message = 'Success', $statusCode = 200)
    {
        return [
            'status' => $statusCode,
            'message' => $message,
            'data' => $data,
        ];
    }

    public static function error($message = 'Error', $statusCode = 400)
    {
        return [
            'status' => $statusCode,
            'message' => $message,
        ];
    }
}
