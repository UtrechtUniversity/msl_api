<?php

namespace App\Http\Resources\V2\Elements;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    protected string $dataPublicationName;

    public function __construct($resource, string $dataPublicationName)
    {
        parent::__construct($resource);
        $this->dataPublicationName = $dataPublicationName;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'fileName' => $this->msl_file_name,
            'downloadLink' => route('file-download', ['id' => $this->dataPublicationName, 'url' => base64_encode($this->msl_download_link)]),
            'extension' => $this->msl_extension,
            'isFolder' => $this->msl_is_folder,
        ];
    }
}
