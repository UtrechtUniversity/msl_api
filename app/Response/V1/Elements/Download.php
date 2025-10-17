<?php

namespace App\Response\V1\Elements;

class Download
{
    public $fileName = '';

    public $downloadLink = '';

    public function __construct($data)
    {
        if (isset($data['msl_file_name'])) {
            $this->fileName = $data['msl_file_name'];
        }

        if (isset($data['msl_download_link'])) {
            $this->downloadLink = $data['msl_download_link'];
        }
    }
}
