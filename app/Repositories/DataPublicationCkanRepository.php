<?php

namespace App\Repositories;

use App\Models\Ckan\DataPublication;
use App\ResultSets\ResultSet;

class DataPublicationCkanRepository implements DataPublicationRepositoryInterface
{

    public function getById($id): DataPublication
    {
        // TODO: Implement getById() method.
    }

    public function create(DataPublication $dataPublication): DataPublication
    {
        // TODO: Implement create() method.
    }

    public function update(DataPublication $dataPublication): DataPublication
    {
        // TODO: Implement update() method.
    }

    public function store(DataPublication $dataPublication): DataPublication
    {
        // TODO: Implement store() method.
    }

    public function delete(DataPublication $dataPublication): void
    {
        // TODO: Implement delete() method.
    }

    public function search($count, $searchString): ResultSet
    {
        // TODO: Implement search() method.
    }

    public function searchByRequest(): ResultSet
    {
        // TODO: Implement searchByRequest() method.
    }
}
