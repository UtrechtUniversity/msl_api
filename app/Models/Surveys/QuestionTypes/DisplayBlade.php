<?php

namespace App\Models\Surveys\QuestionTypes;

class DisplayBlade
{
    public $bladeName = '';
    public $bladeVars = [];

    public function __construct(array $config)
    {
        $this->bladeName = $config['bladeName'];
        $this->bladeVars = $config['bladeVars'];
    }
}
