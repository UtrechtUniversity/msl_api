<?php

namespace App\Mappers\Fast;

use App\Models\Laboratory\Laboratory;
use App\Models\Laboratory\LaboratoryContactPerson;
use App\Models\Laboratory\LaboratoryManager;
use App\Models\Laboratory\LaboratoryOrganization;

class FacilityResponseMapper
{

    public static function mapToLaboratory(array $data): Laboratory
    {
        $laboratory = new Laboratory();

        $laboratory->fast_id = $data['id'];
        $laboratory->name = $data['name'];
        $laboratory->description = $data['description'];
        $laboratory->description_html = $data['description_html'] ?? '';
        $laboratory->website = $data['website'];
        $laboratory->address_street_1 = $data['address_street_1'];
        $laboratory->address_street_2 = $data['address_street_2'];
        $laboratory->address_postalcode = $data['address_postcode'];
        $laboratory->address_city = $data['address_city'];
        $laboratory->address_country_code = $data['address_country_code'];
        $laboratory->address_country_name = $data['address_country']['name'];
        $laboratory->latitude = $data['gps_latitude'];
        $laboratory->longitude = $data['gps_longitude'];
        $laboratory->altitude = $data['gps_altitude'];
        $laboratory->external_identifier = $data['external_identifier'];
        $laboratory->msl_identifier = md5($laboratory->fast_id);
        $laboratory->lab_portal_name = '';
        $laboratory->lab_editor_name = '';
        $laboratory->msl_identifier_inputstring = '';
        $laboratory->original_domain = '';
        $laboratory->fast_domain_id = $data['domain']['id'];
        $laboratory->fast_domain_name = $data['domain']['name'];

        return $laboratory;
    }

    public static function mapToOrganization(array $data): LaboratoryOrganization
    {
        $organization = new LaboratoryOrganization();

        $organization->fast_id = $data['id'];
        $organization->name = $data['name'];
        $organization->external_identifier = $data['affiliation']['external_identifier'] ?? '';

        return $organization;
    }

    public static function mapToContactPerson(string $contactPersonEmail): LaboratoryContactPerson
    {
        $contactPerson = new LaboratoryContactPerson();
        $contactPerson->email = $contactPersonEmail;

        return $contactPerson;
    }

    public static function mapToManager(array $data): LaboratoryManager
    {
        $manager = new LaboratoryManager();

        $manager->fast_id = $data['id'];
        $manager->email = $data['email'];
        $manager->first_name = $data['first_name'];
        $manager->last_name = $data['last_name'];
        $manager->orcid = $data['orcid'];
        $manager->address_street_1 = $data['address_street_1'];
        $manager->address_street_2 = $data['address_street_2'];
        $manager->address_postalcode = $data['address_postcode'];
        $manager->address_city = $data['address_city'];
        $manager->address_country_code = $data['address_country']['code'];
        $manager->address_country_name = $data['address_country']['name'];
        $manager->affiliation_fast_id = $data['affiliation_id'];
        $manager->nationality_code = $data['nationality']['code'];
        $manager->nationality_name = $data['nationality']['name'];

        return $manager;
    }
}
