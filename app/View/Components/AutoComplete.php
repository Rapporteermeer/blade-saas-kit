<?php

namespace App\View\Components;

class Autocomplete extends Input
{
    public $src;

    public $id;

    public function __construct($name, $src, $id = null, $value = '', $label = '', $bag = 'default')
    {
        parent::__construct($name, $id, $value, $bag, $label, 'text');
        $this->id = $name ?? $id;
        $this->src = $src;
    }

    public function render()
    {
        return view('components.autocomplete');
    }
}
