<?php

namespace App\Http\Resources\V2\Errors;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CkanErrorResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => false,
            'messages' => ['Error received from CKAN api.'],
        ];
    }

    public function withResponse(Request $request, JsonResponse $response)
    {
        $response         // convert resource to HTTP response
            ->setStatusCode(500);
    }
}
