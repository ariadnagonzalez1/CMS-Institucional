<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;



use App\Models\Cliente;
use Livewire\Component;

class Clientes extends Component
{
    public $clientes;
    public $nombre, $email, $telefono, $domicilio, $ocupacion, $cliente_id;




    public function logout()
{
    Auth::logout();
    return redirect()->route('login');
}
   


public function mount() {
        $this->clientes = Cliente::all();
    }

    public function render() {
        return view('livewire.clientes');
    }

    public function resetInput() {
        $this->nombre = '';
        $this->email = '';
        $this->telefono = '';
        $this->domicilio = '';
        $this->ocupacion = '';
        $this->cliente_id = null;
    }

    public function store() {
        $this->validate([
            'nombre' => 'required',
            'email' => 'required|email',
        ]);

        Cliente::create([
            'nombre' => $this->nombre,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'domicilio' => $this->domicilio,
            'ocupacion' => $this->ocupacion,
        ]);

        session()->flash('message', 'Cliente creado.');
        $this->resetInput();
        $this->clientes = Cliente::all();
    }

    public function edit($id) {
        $cliente = Cliente::findOrFail($id);
        $this->cliente_id = $id;
        $this->nombre = $cliente->nombre;
        $this->email = $cliente->email;
        $this->telefono = $cliente->telefono;
        $this->domicilio = $cliente->domicilio;
        $this->ocupacion = $cliente->ocupacion;
    }

    public function update() {
        if ($this->cliente_id) {
            $cliente = Cliente::find($this->cliente_id);
            $cliente->update([
                'nombre' => $this->nombre,
                'email' => $this->email,
                'telefono' => $this->telefono,
                'domicilio' => $this->domicilio,
                'ocupacion' => $this->ocupacion,
            ]);

            session()->flash('message', 'Cliente actualizado.');
            $this->resetInput();
            $this->clientes = Cliente::all();
        }
    }

    public function destroy($id) {
        Cliente::destroy($id);
        session()->flash('message', 'Cliente eliminado.');
        $this->clientes = Cliente::all();
    }
}