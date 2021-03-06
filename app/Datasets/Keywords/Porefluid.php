<?php
namespace App\Datasets\Keywords;

class Porefluid
{
    public $msl_porefluid_combined = '';
    
    public $msl_porefluid_1 = '';
    
    public $msl_porefluid_2 = '';
    
    public $msl_porefluid_3 = '';

    
    private $levels = [
        1 => 'msl_porefluid_1',
        2 => 'msl_porefluid_2',
        3 => 'msl_porefluid_3'
    ];
            
    
    public function __construct($keyword = null) {
        if($keyword) {                      
            $keywordHierarchy = $keyword->getFullHierarchy();
            
            foreach ($keywordHierarchy as $keywordInHierarchy) {
                $this->{$this->levels[$keywordInHierarchy->level]} = $keywordInHierarchy->getFullPath();
            }
            
            $this->msl_porefluid_combined = $keyword->getFullPath();
        }
    }
    
    public function toArray() {
        return [
            'msl_porefluid_combined' => $this->msl_porefluid_combined,
            'msl_porefluid_1' => $this->msl_porefluid_1,
            'msl_porefluid_2' => $this->msl_porefluid_2,
            'msl_porefluid_3' => $this->msl_porefluid_3
        ];
    }
}

