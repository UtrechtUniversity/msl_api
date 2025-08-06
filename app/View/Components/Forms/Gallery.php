<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Gallery extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $sectionName,
        public string $title,
        public array $images,
        public array $descriptions,
        public bool $titleBold,
    )
    {
        
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.gallery');
    }
}
