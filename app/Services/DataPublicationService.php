<?php

namespace App\Services;

use App\CkanClient\Client;
use App\CkanClient\Request\PackageShowRequest;
use App\Models\Ckan\DataPublication;

class DataPublicationService
{

    public function getById(string $id): ?DataPublication
    {
        $client = new Client;
        $request = new PackageShowRequest;
        $request->id = $id;

        $result = $client->get($request);

        if (! $result->isSuccess()) {
            return null;
        }

        return DataPublication::fromCkanArray($result->getResult());
    }
}
