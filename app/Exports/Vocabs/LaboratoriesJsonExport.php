<?php

namespace App\Exports\Vocabs;

use App\Models\Laboratory\Laboratory;

class LaboratoriesJsonExport
{

    public function export11(): false|string
    {
        $laboratories = Laboratory::where('external_identifier', '<>', '')->get();

        $output = [];

        foreach ($laboratories as $laboratory) {
            $organization = $laboratory->laboratoryOrganization;

            $element = [
                'identifier' => $laboratory->external_identifier,
                'name' => mb_convert_encoding($laboratory->name, 'UTF-8'),
                'display_name' => mb_convert_encoding($laboratory->name, 'UTF-8') . ' - ' . $organization->name,
                'affiliation_name' => $organization->name,
                'affiliation_ror' => $organization->external_identifier,
                'scientific_domain' => $laboratory->fast_domain_name,
                'country' => $laboratory->address_country_name,
            ];

            $output[] = $element;
        }

        return json_encode($output, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    }
    public function export10(): false|string
    {
        $laboratories = Laboratory::where('external_identifier', '<>', '')->get();

        $output = [];

        foreach ($laboratories as $laboratory) {
            $organization = $laboratory->laboratoryOrganization;

            $element = [
                'identifier' => $laboratory->external_identifier,
                'name' => mb_convert_encoding($laboratory->name, 'UTF-8'),
                'affiliation_name' => $organization->name,
                'affiliation_ror' => $organization->external_identifier,
            ];

            $output[] = $element;
        }

        return json_encode($output, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
