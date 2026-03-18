<?php

namespace Tests\Feature;

use App\Models\Ckan\NameIdentifier;
use Tests\TestCase;

class NameIdentifierTest extends TestCase
{
    public function test_cleaned_name_identifiers(): void
    {
        $nameIdentifier_1 = new NameIdentifier(
            nameIdentifier: '123345',
            nameIdentifierScheme: 'ScopusId',
            nameIdentifierUri: ''
        );
        $nameIdentifier_2 = new NameIdentifier(
            nameIdentifier: 'http://123345',
            nameIdentifierScheme: 'Isni',
            nameIdentifierUri: ''
        );
        $nameIdentifier_3 = new NameIdentifier(
            nameIdentifier: '46',
            nameIdentifierScheme: 'ORCID',
            nameIdentifierUri: ''
        );

        $this->assertEquals($nameIdentifier_1->getCreatorIdentifierWithMetadata(), ['isURL' => true, 'value' => 'https://www.scopus.com/authid/detail.uri?authorId=123345']);
        $this->assertEquals($nameIdentifier_2->getCreatorIdentifierWithMetadata(), ['isURL' => true, 'value' => 'http://123345']);
        $this->assertEquals($nameIdentifier_3->getCreatorIdentifierWithMetadata(), ['isURL' => true, 'value' => 'https://orcid.org/46']);
    }
}
