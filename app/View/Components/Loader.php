<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Loader extends Component
{
    public $type;
    public $message;
    public $width;
    public $height;
    
    /**
     * Create a new component instance.
     */
    public function __construct($type = 'primary', $message = 'Cargando...', $width = 500, $height = 400)
    {
        $this->type = $type;
        $this->message = $message;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.loader');
    }
}