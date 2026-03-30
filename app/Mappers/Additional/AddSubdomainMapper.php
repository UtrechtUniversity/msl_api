<?php

namespace App\Mappers\Additional;

use App\Enums\DataPublicationSubDomain;
use App\Models\Ckan\DataPublication;
use App\Models\SourceDataset;
use Exception;

class AddSubdomainMapper extends AdditionalMapper
{
    /**
     * @var array<int, DataPublicationSubDomain>
     */
    public readonly array $subdomains;

    protected function initialize(array $subdomains): void
    {
        $this->subdomains = $subdomains;
    }

    /**
     * @param  array<string, string>  $args
     * @return array<int,DataPublicationSubDomain>
     */
    protected function validateInput(array $args): array
    {
        $subDomainsString = 'subdomains';
        $className = get_class($this);

        if (! array_key_exists($subDomainsString, $args)) {
            throw new Exception("The option for '$className' should have as key '$subDomainsString'. This is a bug.");
        }
        $subdomains = $args[$subDomainsString];
        if (! is_array($subdomains)) {
            throw new Exception("The value of '$subDomainsString' in options for '$className' should have been an array. This is a bug.");
        }

        $validSubdomains = [];
        foreach ($subdomains as $subdomain) {
            $validSubdomain = DataPublicationSubDomain::tryFrom($subdomain);
            if (! $validSubdomain) {
                throw new Exception("Value'$subdomain' is not a valid subdomain. This is a bug.");
            }
            $validSubdomains[] = $validSubdomain;
        }

        return $validSubdomains;
    }

    /**
     * Add original subdomains
     */
    public function map(DataPublication $dataPublication, SourceDataset $sourceDataset): DataPublication
    {
        foreach ($this->subdomains as $subdomain) {

            $dataPublication->addSubDomain($subdomain, true);
        }

        return $dataPublication;
    }
}
