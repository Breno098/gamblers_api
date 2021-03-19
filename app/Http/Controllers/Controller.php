<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function send(array $send, $code = 200)
    {
        return response(new ApiResource($send), $code);
    }

    public function sendError($sendError, $code = 400)
    {
        $send['error'] = $sendError;
        return response(new ApiResource($send), $code);
    }

    public function sendErrorValidation($sendError)
    {
        $send['error'] = $sendError;
        $send['message'] = 'Validation error.';
        return response(new ApiResource($send), 400);
    }
}
