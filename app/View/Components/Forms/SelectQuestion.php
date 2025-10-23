<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectQuestion extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $sectionName = '',
        public string $placeholder = '',
        public string $title = '',
        public array $options,
        public bool $titleBold = false,
        public string $id = '',
        public string $selected = '',
        public string $onChange = '',
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.select-question');
    }
}
