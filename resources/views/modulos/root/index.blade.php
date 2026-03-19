{{-- resources/views/modulos/root/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Root')
@section('header-title', 'Root — Configuración del Sistema')

@section('content')

<x-flash-alert />

{{-- ══════════════════════════════════════════════════════════
     BARRA SUPERIOR: tabs + botón agregar
══════════════════════════════════════════════════════════ --}}
<div class="flex items-center justify-between mb-6">

    {{-- Tabs --}}
    <nav class="flex gap-1 bg-gray-100 p-1 rounded-xl" id="root-tabs">
        @foreach([
            ['id' => 'modulos',         'label' => 'Módulos'],
            ['id' => 'modos-texto',     'label' => 'Modos de Texto'],
            ['id' => 'secciones-texto', 'label' => 'Secciones de Texto'],
        ] as $tab)
            <button
                onclick="cambiarTab('{{ $tab['id'] }}')"
                id="tab-btn-{{ $tab['id'] }}"
                class="tab-btn px-4 py-2 text-sm font-medium rounded-lg transition-all duration-150">
                {{ $tab['label'] }}
            </button>
        @endforeach
    </nav>

    {{-- Botón agregar (dinámico según tab activa) --}}
    <button id="btn-agregar"
            onclick="abrirModalCrear()"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold
                   text-white transition-all duration-150 shadow-sm hover:shadow-md"
            style="background-color: #196B4A;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        <span id="btn-agregar-label">Agregar Módulo</span>
    </button>
</div>


{{-- ══════════════════════════════════════════════════════════
     TAB: MÓDULOS
══════════════════════════════════════════════════════════ --}}
<div id="tab-modulos" class="tab-panel">
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">

        {{-- tabla --}}
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left" style="background-color: #1a3b2e;">
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white">Nombre</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white">Tipo</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white">Path Home</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center">Estado</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($modulos as $modulo)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5 font-medium text-gray-800">{{ $modulo->nombre }}</td>
                        <td class="px-5 py-3.5"><x-badge-tipo :tipo="$modulo->tipo" /></td>
                        <td class="px-5 py-3.5 text-gray-500 font-mono text-xs">
                            {{ $modulo->path_home ?? '—' }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <x-badge-estado :activo="$modulo->estado" />
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="inline-flex items-center gap-1">
                                <x-btn-edit
                                    onclick="abrirModalEditar('modulos', {{ json_encode([
                                        'id'        => $modulo->id,
                                        'nombre'    => $modulo->nombre,
                                        'tipo'      => $modulo->tipo,
                                        'path_home' => $modulo->path_home,
                                        'icono'     => $modulo->icono,
                                        'orden'     => $modulo->orden,
                                        'estado'    => $modulo->estado,
                                    ]) }})"
                                />
                                <x-btn-delete
                                    :action="route('admin.root.modulos.destroy', $modulo->id)"
                                    :confirm="'¿Eliminar el módulo \'' . $modulo->nombre . '\'? Esta acción no se puede deshacer.'"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-400 text-sm">
                            No hay módulos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════
     TAB: MODOS DE TEXTO
══════════════════════════════════════════════════════════ --}}
<div id="tab-modos-texto" class="tab-panel hidden">
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr style="background-color: #1a3b2e;">
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-left">Nombre</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-left">Descripción</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center">Cajas</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center">Estado</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($modosTexto as $modo)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5 font-medium text-gray-800">{{ $modo->nombre }}</td>
                        <td class="px-5 py-3.5 text-gray-500">{{ $modo->descripcion ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-center text-gray-600">{{ $modo->cantidad_cajas ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-center">
                            <x-badge-estado :activo="$modo->estado" />
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="inline-flex items-center gap-1">
                                <x-btn-edit
                                    onclick="abrirModalEditar('modos-texto', {{ json_encode([
                                        'id'             => $modo->id,
                                        'nombre'         => $modo->nombre,
                                        'descripcion'    => $modo->descripcion,
                                        'cantidad_cajas' => $modo->cantidad_cajas,
                                        'estado'         => $modo->estado,
                                    ]) }})"
                                />
                                <x-btn-delete
                                    :action="route('admin.root.modos-texto.destroy', $modo->id)"
                                    :confirm="'¿Eliminar el modo \'' . $modo->nombre . '\'?'"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-400 text-sm">
                            No hay modos de texto registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════
     TAB: SECCIONES DE TEXTO
══════════════════════════════════════════════════════════ --}}
<div id="tab-secciones-texto" class="tab-panel hidden">
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr style="background-color: #1a3b2e;">
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-left">Nombre</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-left">Modo Texto</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center">Colores</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center">Visible</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($secciones as $seccion)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5 font-medium text-gray-800">{{ $seccion->nombre }}</td>
                        <td class="px-5 py-3.5 text-gray-500">
                            {{ $seccion->modoTexto->nombre ?? '—' }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <div class="inline-flex items-center gap-2">
                                @if($seccion->color_fondo)
                                    <span title="Fondo: {{ $seccion->color_fondo }}"
                                          class="h-5 w-5 rounded border border-gray-200 inline-block"
                                          style="background-color: {{ $seccion->color_fondo }}"></span>
                                @endif
                                @if($seccion->color_texto)
                                    <span title="Texto: {{ $seccion->color_texto }}"
                                          class="h-5 w-5 rounded border border-gray-200 inline-block"
                                          style="background-color: {{ $seccion->color_texto }}"></span>
                                @endif
                                @if($seccion->color_borde)
                                    <span title="Borde: {{ $seccion->color_borde }}"
                                          class="h-5 w-5 rounded border border-gray-200 inline-block"
                                          style="background-color: {{ $seccion->color_borde }}"></span>
                                @endif
                                @if(!$seccion->color_fondo && !$seccion->color_texto && !$seccion->color_borde)
                                    <span class="text-gray-400">—</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <x-badge-estado :activo="$seccion->visible_en_sitio"
                                            label-on="sí" label-off="no" />
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="inline-flex items-center gap-1">
                                <x-btn-edit
                                    onclick="abrirModalEditar('secciones-texto', {{ json_encode([
                                        'id'               => $seccion->id,
                                        'modo_texto_id'    => $seccion->modo_texto_id,
                                        'nombre'           => $seccion->nombre,
                                        'color_fondo'      => $seccion->color_fondo,
                                        'color_texto'      => $seccion->color_texto,
                                        'color_borde'      => $seccion->color_borde,
                                        'visible_en_sitio' => $seccion->visible_en_sitio,
                                        'orden'            => $seccion->orden,
                                    ]) }})"
                                />
                                <x-btn-delete
                                    :action="route('admin.root.secciones.destroy', $seccion->id)"
                                    :confirm="'¿Eliminar la sección \'' . $seccion->nombre . '\'?'"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-400 text-sm">
                            No hay secciones registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════
     MODALES — CREAR
══════════════════════════════════════════════════════════ --}}

{{-- Modal: Crear Módulo --}}
<x-modal id="modal-crear-modulo" title="Agregar Módulo">
    <form action="{{ route('admin.root.modulos.store') }}" method="POST" class="space-y-4">
        @csrf
        @include('modulos.root._form-modulo', ['modulo' => null])
        @include('modulos.root._form-footer', ['modalId' => 'modal-crear-modulo', 'label' => 'Crear módulo'])
    </form>
</x-modal>

{{-- Modal: Crear Modo de Texto --}}
<x-modal id="modal-crear-modo-texto" title="Agregar Modo de Texto">
    <form action="{{ route('admin.root.modos-texto.store') }}" method="POST" class="space-y-4">
        @csrf
        @include('modulos.root._form-modo-texto', ['modo' => null])
        @include('modulos.root._form-footer', ['modalId' => 'modal-crear-modo-texto', 'label' => 'Crear modo'])
    </form>
</x-modal>

{{-- Modal: Crear Sección --}}
<x-modal id="modal-crear-seccion" title="Agregar Sección de Texto">
    <form action="{{ route('admin.root.secciones.store') }}" method="POST" class="space-y-4">
        @csrf
        @include('modulos.root._form-seccion', ['seccion' => null, 'modosTexto' => $modosTexto])
        @include('modulos.root._form-footer', ['modalId' => 'modal-crear-seccion', 'label' => 'Crear sección'])
    </form>
</x-modal>


{{-- ══════════════════════════════════════════════════════════
     MODALES — EDITAR (se rellenan por JS)
══════════════════════════════════════════════════════════ --}}

{{-- Modal: Editar Módulo --}}
<x-modal id="modal-editar-modulo" title="Editar Módulo">
    <form id="form-editar-modulo" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        @include('modulos.root._form-modulo', ['modulo' => null])
        @include('modulos.root._form-footer', ['modalId' => 'modal-editar-modulo', 'label' => 'Guardar cambios'])
    </form>
</x-modal>

{{-- Modal: Editar Modo de Texto --}}
<x-modal id="modal-editar-modo-texto" title="Editar Modo de Texto">
    <form id="form-editar-modo-texto" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        @include('modulos.root._form-modo-texto', ['modo' => null])
        @include('modulos.root._form-footer', ['modalId' => 'modal-editar-modo-texto', 'label' => 'Guardar cambios'])
    </form>
</x-modal>

{{-- Modal: Editar Sección --}}
<x-modal id="modal-editar-seccion" title="Editar Sección de Texto">
    <form id="form-editar-seccion" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        @include('modulos.root._form-seccion', ['seccion' => null, 'modosTexto' => $modosTexto])
        @include('modulos.root._form-footer', ['modalId' => 'modal-editar-seccion', 'label' => 'Guardar cambios'])
    </form>
</x-modal>

@endsection


@push('scripts')
<script>
// ─── Rutas base (para construir URLs de update) ───────────────────────────────
const ROUTES = {
    'modulos':         '/admin/root/modulos',
    'modos-texto':     '/admin/root/modos-texto',
    'secciones-texto': '/admin/root/secciones',
};

const LABELS_AGREGAR = {
    'modulos':         'Agregar Módulo',
    'modos-texto':     'Agregar Modo de Texto',
    'secciones-texto': 'Agregar Sección',
};

// ─── Tab activa ───────────────────────────────────────────────────────────────
let tabActiva = '{{ request("tab", "modulos") }}';

function cambiarTab(tab) {
    tabActiva = tab;

    // Ocultar todos los paneles
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
    document.getElementById('tab-' + tab).classList.remove('hidden');

    // Estilos de tabs
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-white', 'text-gray-800', 'shadow-sm');
        btn.classList.add('text-gray-500');
    });
    const activeBtn = document.getElementById('tab-btn-' + tab);
    activeBtn.classList.add('bg-white', 'text-gray-800', 'shadow-sm');
    activeBtn.classList.remove('text-gray-500');

    // Label del botón agregar
    document.getElementById('btn-agregar-label').textContent = LABELS_AGREGAR[tab];
}

// ─── Modal helpers ─────────────────────────────────────────────────────────────
function abrirModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function cerrarModal(id) {
    document.getElementById(id).classList.add('hidden');
}

// ─── Abrir modal de CREAR según tab activa ────────────────────────────────────
function abrirModalCrear() {
    const ids = {
        'modulos':         'modal-crear-modulo',
        'modos-texto':     'modal-crear-modo-texto',
        'secciones-texto': 'modal-crear-seccion',
    };
    abrirModal(ids[tabActiva]);
}

// ─── Abrir modal de EDITAR y rellenar datos ───────────────────────────────────
function abrirModalEditar(tipo, datos) {
    const configs = {
        'modulos': {
            modal:  'modal-editar-modulo',
            form:   'form-editar-modulo',
            campos: ['nombre', 'path_home', 'icono', 'orden'],
            selects: ['tipo'],
            estado: 'estado',
        },
        'modos-texto': {
            modal:  'modal-editar-modo-texto',
            form:   'form-editar-modo-texto',
            campos: ['nombre', 'descripcion', 'cantidad_cajas'],
            estado: 'estado',
        },
        'secciones-texto': {
            modal:  'modal-editar-seccion',
            form:   'form-editar-seccion',
            campos: ['nombre', 'color_fondo', 'color_texto', 'color_borde', 'orden'],
            selects: ['modo_texto_id'],
            estado: 'visible_en_sitio',
            colores: ['color_fondo', 'color_texto', 'color_borde'],
        },
    };

    const cfg  = configs[tipo];
    const form = document.getElementById(cfg.form);

    // Acción del form
    form.action = ROUTES[tipo] + '/' + datos.id;

    // Rellenar inputs de texto/número
    (cfg.campos || []).forEach(campo => {
        const el = form.querySelector(`input[name="${campo}"], textarea[name="${campo}"]`);
        if (el) el.value = datos[campo] ?? '';
    });

    // Rellenar selects
    (cfg.selects || []).forEach(campo => {
        const el = form.querySelector(`select[name="${campo}"]`);
        if (el) el.value = datos[campo] ?? '';
    });

    // Rellenar color pickers + textos sincronizados
    (cfg.colores || []).forEach(campo => {
        const picker = form.querySelector(`input[type="color"][name="${campo}"]`);
        const text   = form.querySelector(`.color-pair input[type="color"][name="${campo}"]`)
                        ?.closest('.color-pair')
                        ?.querySelector('.color-text');
        const val = datos[campo] || '';
        if (picker) picker.value = val || picker.defaultValue;
        if (text)   text.value   = val;
    });

    // Rellenar checkbox de estado
    if (cfg.estado) {
        const chk = form.querySelector(`input[type="checkbox"][name="${cfg.estado}"]`);
        if (chk) chk.checked = !!datos[cfg.estado];
    }

    abrirModal(cfg.modal);
}

// ─── Sync color picker ↔ texto (delegado, funciona con cualquier modal) ───────
document.addEventListener('input', function (e) {
    const pair = e.target.closest('.color-pair');
    if (!pair) return;

    if (e.target.classList.contains('color-picker')) {
        pair.querySelector('.color-text').value = e.target.value;
    } else if (e.target.classList.contains('color-text')) {
        if (/^#[0-9A-Fa-f]{6}$/.test(e.target.value)) {
            pair.querySelector('.color-picker').value = e.target.value;
        }
    }
});

// ─── Cerrar modal con Escape ──────────────────────────────────────────────────
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id^="modal-"]').forEach(m => m.classList.add('hidden'));
    }
});

// ─── Inicializar tab según query param ───────────────────────────────────────
cambiarTab(tabActiva);
</script>
@endpush