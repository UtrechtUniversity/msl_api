<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RadioSelect extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $sectionName = '',
        public string $title = '',
        public array $options,
        public array $ids = [],
        public bool $titleBold = false,
        public array $infoIconsIds = [],
        public bool $asCol = false
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.radio-select');
    }
}
