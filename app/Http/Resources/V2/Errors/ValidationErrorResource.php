<?php

namespace App\Http\Resources\V2\Errors;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ValidationErrorResource extends JsonResource
{

    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ['success' => false, 'messages' => collect($this->errors())->flatten()];
    }
    public function withResponse(Request $request, JsonResponse $response)
    {
        $response
            ->setStatusCode($this->status);
    }
}
