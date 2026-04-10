<?php

namespace Tests\Feature;

use App\Models\Ckan\Affiliation;
use Tests\TestCase;

class AffiliationTest extends TestCase
{
    public function test_cleaned_name_identifiers(): void
    {
        $aff_1 = new Affiliation(
            name: 'aff_1',
            identifier: '123345',
            identifierScheme: 'Ror',
            schemeUri: ''
        );
        $aff_2 = new Affiliation(
            name: 'aff_2',
            identifier: 'http://123345',
            identifierScheme: 'ror',
            schemeUri: ''
        );
        $aff_3 = new Affiliation(
            name: 'aff_3',
            identifier: '46',
            identifierScheme: 'ROR',
            schemeUri: ''
        );

        $this->assertEquals($aff_1->getAffilitiationIdentifierWithMetadata(), ['isURL' => true, 'value' => 'https://ror.org/123345']);
        $this->assertEquals($aff_2->getAffilitiationIdentifierWithMetadata(), ['isURL' => true, 'value' => 'http://123345']);
        $this->assertEquals($aff_3->getAffilitiationIdentifierWithMetadata(), ['isURL' => true, 'value' => 'https://ror.org/46']);
    }
}
