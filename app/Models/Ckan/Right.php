<?php
namespace App\Models\Ckan;

class Right
{

    public string $right;

    public string $uri;

    public string $identifier;

    public string $identifierScheme;

    public string $schemeUri;

    public function __construct($right, $uri = "", $identifier = "", $identifierScheme = "", $schemeUri = "")
    {
        $this->right = $right;
        $this->uri = $uri;
        $this->identifier = $identifier;
        $this->identifierScheme = $identifierScheme;
        $this->schemeUri = $schemeUri;
    }    
}