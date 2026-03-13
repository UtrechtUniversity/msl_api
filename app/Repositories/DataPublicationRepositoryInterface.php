<?php

namespace App\Repositories;

use App\Models\Ckan\DataPublication;
use App\ResultSets\ResultSet;

interface DataPublicationRepositoryInterface
{

    public function getById($id): DataPublication;

    public function create(DataPublication $dataPublication): DataPublication;

    public function update(DataPublication $dataPublication): DataPublication;

    public function store(DataPublication $dataPublication): DataPublication;

    public function delete(DataPublication $dataPublication): void;

    /**
     * should return a resultset object
     */
    public function search($count, $searchString): ResultSet;

    public function searchByRequest(): ResultSet;


}
