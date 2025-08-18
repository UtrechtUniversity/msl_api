<?php

namespace App\Models\Surveys\QuestionTypes;

class SelectQuestion
{
    public $title = '';

    public $options = [];

    public $validation = [];

    public $sectionName = '';

    public $placeholder = '';

    public $titleBold = '';

    public function __construct(array $config)
    {
        $this->title = $config['title'];
        $this->titleBold = $config['titleBold'];
        $this->options = $config['options'];
        $this->validation = $config['validation'];
        $this->sectionName = $config['sectionName'];
        $this->placeholder = $config['placeholder'];
    }
}
