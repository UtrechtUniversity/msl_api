<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class MappingException extends Exception
{
    public function report()
    {
        Log::channel('mapping')->info($this->message);
    }
}
