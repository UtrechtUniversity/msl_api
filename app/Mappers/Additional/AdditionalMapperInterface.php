<?php
namespace App\Mappers\Additional;

use App\Models\Ckan\DataPublication;

interface AdditionalMapperInterface
{

    /**
     * Maps additional content based upon a provided datapublications values
     * @param DataPublication $dataPublication
     * @return DataPuclication
     */
    public function map(DataPublication $dataPublication): DataPublication;
}