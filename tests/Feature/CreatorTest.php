<?php

namespace Tests\Feature;

use App\Models\Ckan\Affiliation;
use App\Models\Ckan\Creator;
use App\Models\Ckan\NameIdentifier;
use Tests\TestCase;

class CreatorTest extends TestCase
{
    public function test_name_identifiers(): void
    {
        $creator = new Creator(
            name: 'someName',
            givenName: 'someGivenName',
            familyName: 'someFamilyName',
            nameType: 'someNameType'
        );
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
        $affiliation = new Affiliation(
            name: 'aff_1',
            identifier: '46sadadadaADd',
            identifierScheme: 'ROR',
            schemeUri: 'http://aksd'
        );
        $creator->addNameIdentifier($nameIdentifier_1);
        $creator->addNameIdentifier($nameIdentifier_2);
        $creator->addAffiliation($affiliation);

        $this->assertEquals($creator->getNameIdentifiersWithMetaData(), [['isURL' => true, 'value' => 'https://www.scopus.com/authid/detail.uri?authorId=123345'], ['isURL' => true, 'value' => 'http://123345']]);
        $this->assertEquals($creator->getCreatorInfo(), ['name' => 'someName', 'nameType' => 'someNameType', 'nameIdentifiers' => [['isURL' => true, 'value' => 'https://www.scopus.com/authid/detail.uri?authorId=123345'],  ['isURL' => true, 'value' => 'http://123345']], 'affiliations' => [['name' => 'aff_1', 'identifier' => ['isURL' => true, 'value' => 'https://ror.org/46sadadadaADd']]]]);
    }
}
