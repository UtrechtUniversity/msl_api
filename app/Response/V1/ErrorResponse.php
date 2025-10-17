<?php

namespace App\Response\V1;

class ErrorResponse
{
    public $success = false;

    public $message = '';

    public function getAsLaravelResponse()
    {
        return response()->json([
            'success' => $this->success,
            'message' => $this->message,
        ], 500);
    }
}
