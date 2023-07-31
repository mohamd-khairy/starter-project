<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

abstract class BaseController extends Controller
{
    /**
     * @param string $message
     * @return JsonResponse
     */
    public function ok(string $message = 'success'): JsonResponse
    {
        return $this->response(200, $message);
    }

    public function danger(string $message = 'danger'): JsonResponse
    {
        return $this->response(200, $message);
    }

    /**
     * @param $data
     * @param int $code
     * @param string $message
     * @return JsonResponse
     */
    public function success($data = null, string $message = 'success', int $code = 200): JsonResponse
    {
        return $this->response($code, $message, $data);
    }

    /**
     * @param int $code
     * @param string $message
     * @param $data
     * @return JsonResponse
     */
    public function error(string $message = 'error', int $code = 400, $data = null): JsonResponse
    {
        return $this->response($code, $message, $data);
    }

    /**
     * @param $code
     * @param $message
     * @param $data
     * @return JsonResponse
     */
    public function response($code, $message, $data = null): JsonResponse
    {
        $data = !empty($data) ? ['data' => $data] : [];

        return response()->json(['code' => $code, 'message' => $message] + $data, $code);
    }
}
