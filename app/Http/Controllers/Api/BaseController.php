<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    public function sendReponse($response, $status="Success", $code=200): JsonResponse
    {
        return response()->json([
            'data'   => $response, 
            'status' => $status,
        ], $code);
    }
}
