<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class BaseApiController extends Controller
{
    public function sendResponse($data = [], $status = Response::HTTP_OK)
    {
        return $this->response($data,$status);
    }

    public function sendError($error = [], $status = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return $this->response($error,$status);
    }

    private function response($data = [], $status)
    {
        return response()->json($data,$status);
    }
}
