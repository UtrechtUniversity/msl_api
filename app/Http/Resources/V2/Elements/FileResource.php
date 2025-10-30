<?php

namespace App\Http\Resources\V2\Elements;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'fileName' => $this->msl_file_name,
            'downloadLink' => $this->msl_download_link,
            'extension' => $this->msl_extension,
            'isFolder' => $this->msl_is_folder
        ];
    }
}
