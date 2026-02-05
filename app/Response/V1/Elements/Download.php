<?php

namespace App\Response\V1\Elements;

class Download
{
    public $fileName = '';

    public $downloadLink = '';

    public function __construct($fileData, $dataPublication)
    {
        if (isset($fileData['msl_file_name'])) {
            $this->fileName = $fileData['msl_file_name'];
        }

        if (isset($fileData['msl_download_link'])) {
            $this->downloadLink = route('file-download', ['id' => $dataPublication['name'], 'url' => base64_encode($fileData['msl_download_link'])]);
        }
    }
}
