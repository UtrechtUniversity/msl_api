<?php

namespace App\Scout;

interface CkanSearchableInterface
{
    public function getCkanType(): string;

    public function getCkanKeyName(): string;

}
