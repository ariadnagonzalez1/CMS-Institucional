<?php
// app/Exports/AdministradoresExport.php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdministradoresExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $request;

    public function __construct($request = null)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = User::with(['salaRedaccion', 'modoGrupo', 'privilegios']);

        // Aplicar los mismos filtros que en el index
        if ($this->request && $this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('apellido', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('dni', 'LIKE', "%{$search}%");
            });
        }

        if ($this->request && $this->request->filled('privilegio_id')) {
            $query->whereHas('privilegios', function($q) use ($request) {
                $q->where('privilegio_id', $this->request->privilegio_id);
            });
        }

        return $query->orderBy('name')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'DNI',
            'Nombre',
            'Apellido',
            'Email',
            'Celular',
            'Teléfono Fijo',
            'Sala Redacción',
            'Modo Grupo',
            'Privilegios',
            'Estado',
            'Último Login',
            'Creado',
        ];
    }

    public function map($admin): array
    {
        // Obtener nombres de privilegios
        $privilegios = $admin->privilegios->pluck('nombre')->implode(', ');
        
        return [
            $admin->id,
            $admin->dni,
            $admin->name,
            $admin->apellido ?? '',
            $admin->email,
            $admin->celular ?? '',
            $admin->telefono_fijo ?? '',
            $admin->salaRedaccion ? $admin->salaRedaccion->nombre : '',
            $admin->modoGrupo ? $admin->modoGrupo->nombre : '',
            $privilegios,
            $admin->activo ? 'Activo' : 'Inactivo',
            $admin->ultimo_login ? $admin->ultimo_login->format('d/m/Y H:i') : '',
            $admin->created_at ? $admin->created_at->format('d/m/Y H:i') : '',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
            'A' => ['font' => ['size' => 10]],
            'B' => ['font' => ['size' => 10]],
            'C' => ['font' => ['size' => 10]],
            'D' => ['font' => ['size' => 10]],
            'E' => ['font' => ['size' => 10]],
            'F' => ['font' => ['size' => 10]],
            'G' => ['font' => ['size' => 10]],
            'H' => ['font' => ['size' => 10]],
            'I' => ['font' => ['size' => 10]],
            'J' => ['font' => ['size' => 10]],
            'K' => ['font' => ['size' => 10]],
            'L' => ['font' => ['size' => 10]],
            'M' => ['font' => ['size' => 10]],
        ];
    }
}