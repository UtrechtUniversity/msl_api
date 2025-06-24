<?php

namespace App\Models\Surveys;

class SelectQuestion
{
    public $label = '';

    public $options = [];

    public function __construct(array $config)
    {
        $this->label = $config['label'];
        $this->options = $config['options'];
    }
}