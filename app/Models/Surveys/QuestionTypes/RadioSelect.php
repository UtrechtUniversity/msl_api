<?php

namespace App\Models\Surveys\QuestionTypes;

class RadioSelect
{
    public $title = '';

    public $options = [];

    public $validation = [];

    public $sectionName = '';

    public $titleBold = '';

    public function __construct(array $config)
    {
        $this->title = $config['title'];
        $this->titleBold = $config['titleBold'];
        $this->options = $config['options'];
        $this->validation = $config['validation'];
        $this->sectionName = $config['sectionName'];
    }
}
