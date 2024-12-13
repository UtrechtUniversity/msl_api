<?php
namespace App\Mappers\Helpers;

use App\Datasets\BaseDataset;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;

class GfzDownloadHelper
{
    private $client;
    
    public function __construct() {
        $this->client = new HttpBrowser();
    }
    
    public function addData(BaseDataset $dataset) {
        if(!$dataset->msl_source) {
            throw new \Exception('msl_source required to extract files from GFZ page(s).');
        }
        
        // Go to downloadpage
        $crawler = $this->client->request('GET', $dataset->msl_source);
        
        try {
            $link = $crawler->selectLink('Download data and description')->link();
        } catch (\Exception $e) {
            try {
                $link = $crawler->selectLink('Download data')->link();
            } catch (\Exception $e) {
                return $dataset;
            }
        }
        $crawler = $this->client->click($link);
        
        // extract link elements, all links are grouped within <pre> element. First 4 <a> elements are UI elements
        $links = $crawler
            ->filter('body > pre > a')
            ->reduce(function (Crawler $node, $i) {
                return ($i >= 5);
        });
            
        //loop over links and add file information to dataset
        $links->each(function ($node) use (&$dataset) {
            $file = [
                'msl_file_name' => $this->extractFileName($node->attr('href')),
                'msl_download_link' => $node->getBaseHref() . $node->attr('href'),
                'msl_extension' => $this->extractFileExtension($node->attr('href')),
                'msl_timestamp' => ''
            ];
            
            $dataset->msl_downloads[] = $file;
        });
        
        return $dataset;
    }
       
    private function extractFileName($filename) {
        $fileInfo = pathinfo($filename);
        if(isset($fileInfo['filename'])) {
            return $fileInfo['filename'];
        }
        
        return '';
    }
    
    private function extractFileExtension($filename) {
        $fileInfo = pathinfo($filename);
        if(isset($fileInfo['extension'])) {
            return $fileInfo['extension'];
        }
        
        return '';
    }
    
    
    
}

