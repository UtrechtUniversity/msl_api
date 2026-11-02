<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Str;

class YodaService
{

    public function getFileList(string $landingPageUrl)
    {
        $landingPageContent = $this->getPage($landingPageUrl);

        $contentUrl = $this->getContentUrl($landingPageContent);

        $manifestUrl = $this->getManifestUrl($landingPageUrl);

        $manifest = $this->getManifest($manifestUrl);

        $files = $this->getFiles($manifest, $landingPageUrl);


        dd($files);

        return [];
    }

    private function getPage(string $url): string
    {
        $response = Http::withUserAgent("F-UJI/4.0 (+https://github.com/pangaea-data-publisher/fuji)")
            ->get($url);

        return (string) $response->getBody();
    }

    private function getContentUrl(string $landingPageSource): string
    {
        $domDocument = new DOMDocument;
        $domDocument->loadHTML($landingPageSource, LIBXML_NOERROR);

        $xpath = new DOMXPath($domDocument);
        $query = '//*[contains(text(), "View contents")]';

        $matches = $xpath->query($query);
        if ($matches->length > 0) {
            $resultNode = $matches->item(0);

            return $resultNode->getAttribute('href');
        }

        throw new Exception('content url could not be extracted');
    }

    /**
     * Create url to manifest file based on landing page url
     * For instance https://public.yoda.uu.nl/geo/UU01/575EWU.html -> https://public.yoda.uu.nl/geo/UU01/575EWU-manifest.json
     */
    private function getManifestUrl(string $landingPageUrl): string
    {
        return Str::replaceEnd('.html', '-manifest.json', $landingPageUrl);
    }

    private function getManifest(string $manifestUrl): array
    {
        $response = Http::withUserAgent("F-UJI/4.0 (+https://github.com/pangaea-data-publisher/fuji)")
            ->get($manifestUrl);

        $manifest = json_decode($response->getBody(), true);

        if(!$manifest) {
            throw new Exception('manifest file could not be decoded');
        }

        return $manifest;
    }

    private function getFiles(array $manifest, string $contentUrl): array
    {
        $files = [];

        foreach ($manifest as $manifestFile)
        {
            // for the moment we are only interested in the files and folders that are part of the /original folder
            $folders = explode('/', $manifestFile['name']);

            // files in original folder
            if(count($folders) === 2 && $folders[0] == 'original') {
                $file = [];
                $file['fileName'] = $folders[count($folders) - 1];
                $file['downloadLink'] = $contentUrl . '/' . urlencode($manifestFile['name']);
                $file['extension'] = '';
                $file['isFolder'] = false;

                $files[] = $file;
            }
        }


        return $files;
    }
}
