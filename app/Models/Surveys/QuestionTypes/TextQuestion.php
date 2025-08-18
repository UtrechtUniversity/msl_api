<?php

namespace App\Models\Surveys\QuestionTypes;

class TextQuestion
{
    public $title = '';

    public $titleBold = false;

    public $sectionName = '';

    public $textBlock = false;

    public $placeholder = '';

    public $validation = [];

    public function __construct(array $config)
    {
        $this->title = $config['title'];
        $this->titleBold = $config['titleBold'];
        $this->sectionName = $config['sectionName'];
        $this->textBlock = $config['textBlock'];
        $this->placeholder = $config['placeholder'];
        $this->validation = $config['validation'];
    }
}
