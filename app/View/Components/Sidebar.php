<?php
// app/View/Components/Sidebar.php

namespace App\View\Components;

use Illuminate\View\Component;

class Sidebar extends Component
{
    public $nombreUsuario;
    public $modulosPrincipales;
    public $modulosSecundarios;

    public function __construct($nombreUsuario, $modulosPrincipales, $modulosSecundarios)
    {
        $this->nombreUsuario = $nombreUsuario;
        $this->modulosPrincipales = $modulosPrincipales;
        $this->modulosSecundarios = $modulosSecundarios;
    }

    public function render()
    {
        return view('components.sidebar');
    }
}