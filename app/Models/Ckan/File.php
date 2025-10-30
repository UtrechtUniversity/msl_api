<?php

namespace App\Models\Ckan;

class File implements CkanArrayInterface
{
    public string $msl_file_name;

    public string $msl_download_link;

    public string $msl_extension;

    public bool $msl_is_folder;

    public string $msl_timestamp;

    public function __construct(string $fileName, string $downloadLink, string $extension, bool $isFolder, string $timestamp = '')
    {
        $this->msl_file_name = $fileName;
        $this->msl_download_link = $downloadLink;
        $this->msl_extension = $extension;
        $this->msl_is_folder = $isFolder;
        $this->msl_timestamp = $timestamp;
    }

    public function toCkanArray(): array
    {
        return (array) $this;
    }
}
