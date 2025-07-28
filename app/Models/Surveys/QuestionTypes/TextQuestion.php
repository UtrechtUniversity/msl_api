<?php

namespace App\Models\Surveys\QuestionTypes;

class TextQuestion
{
    public $label = '';

    public function __construct(array $config)
    {
        $this->label = $config['label'];
    }
}