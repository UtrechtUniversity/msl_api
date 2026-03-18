<?php

namespace Tests\Feature;

use App\Models\Ckan\Affiliation;
use App\Models\Ckan\Contributor;
use App\Models\Ckan\NameIdentifier;
use Tests\TestCase;

class ContributorTest extends TestCase
{
    public function test_name_identifiers(): void
    {
        $contributor = new Contributor(name: 'someName', type: '', givenName: 'someGivenName', familyName: 'someFamilyName', nameType: 'someNameType');
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

        $affiliation = new Affiliation(
            name: 'aff_1',
            identifier: '46sadadadaADd',
            identifierScheme: 'ROR',
            schemeUri: 'http://aksd'
        );
        $contributor->addNameIdentifier($nameIdentifier_1);
        $contributor->addNameIdentifier($nameIdentifier_2);
        $contributor->addNameIdentifier($nameIdentifier_3);
        $contributor->addAffiliation($affiliation);
        $this->assertEquals($contributor->getNameIdentifiersWithMetaData(), [['isURL' => true, 'value' => 'https://www.scopus.com/authid/detail.uri?authorId=123345'],  ['isURL' => true, 'value' => 'http://123345'],  ['isURL' => true, 'value' => 'https://orcid.org/46']]);
        $this->assertEquals($contributor->getContributorInfo(), ['name' => 'someName', 'nameType' => 'someNameType', 'nameIdentifiers' => [['isURL' => true, 'value' => 'https://www.scopus.com/authid/detail.uri?authorId=123345'],  ['isURL' => true, 'value' => 'http://123345'],  ['isURL' => true, 'value' => 'https://orcid.org/46']], 'affiliations' => [['name' => 'aff_1', 'identifier' => ['isURL' => true, 'value' => 'https://ror.org/46sadadadaADd']]]]);
    }
}
