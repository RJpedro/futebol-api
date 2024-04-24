<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public static function return_pattern(mixed $data, string $message, int $status)
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ], $status);
    }
}
