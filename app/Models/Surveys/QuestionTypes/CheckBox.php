<?php

namespace App\Models\Surveys\QuestionTypes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckBox
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
