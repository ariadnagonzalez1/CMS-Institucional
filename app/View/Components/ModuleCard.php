<?php
// app/View/Components/ModuleCard.php

namespace App\View\Components;

use Illuminate\View\Component;

class ModuleCard extends Component
{
    public $module;

    public function __construct($module)
    {
        $this->module = $module;
    }

    public function render()
    {
        return view('components.module-card');
    }
}