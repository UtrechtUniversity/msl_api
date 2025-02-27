<?php
namespace App\Exports\Vocabs;

use App\Models\Laboratory;

class LaboratoriesJsonExport
{
    public function export()
    {
        $laboratories = Laboratory::all();

        $output = [];

        foreach($laboratories as $laboratory) {
            $organization = $laboratory->laboratoryOrganization;

            $element = [
                'identifier' => $laboratory->msl_identifier,
                'name' => mb_convert_encoding($laboratory->name, 'UTF-8'),
                'affiliation_name' => $organization->name,
                'affiliation_ror' => $organization->external_identifier
            ];

            $output[] = $element;            
        }

        return (json_encode($output, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT));
    }
}