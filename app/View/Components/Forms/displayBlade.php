<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DisplayBlade extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $bladeName
    ) { 
        dd(); 
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.display-blade');
    }
}
