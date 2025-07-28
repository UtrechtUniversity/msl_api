<?php

namespace App\Models\Surveys\QuestionTypes;

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