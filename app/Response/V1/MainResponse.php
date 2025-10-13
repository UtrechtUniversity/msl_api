<?php

namespace App\Response\V1;

class MainResponse
{
    public $success = true;

    public $message = '';

    public $result;

    public function __construct() {
        $this->result = new ResultBlock();
    }

    public function setByCkanResponse($response, $context) {
        $this->success = $response->isSuccess();
        $this->result->setByCkanResponse($response, $context);
    }

    public function getAsLaravelResponse() {
        return response()->json([
            'success' => $this->success,
            'message' => $this->message,
            'result' => $this->result->getAsArray()
        ], 200);
    }


}
