{{-- resources/views/modulos/root/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Root')
@section('header-title', 'Root — Configuración del Sistema')

@section('content')

<x-flash-alert />

<div class="flex items-center justify-between mb-6">

    <button onclick="abrirModal('modal-crear-modulo')"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold
                   text-white transition-all duration-150 shadow-sm hover:shadow-md hover:brightness-110"
            style="background-color: #196B4A;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Agregar Módulo
    </button>

    <nav class="flex gap-1 bg-gray-100 p-1 rounded-xl" id="root-tabs">
        @foreach([
            ['id' => 'modulos',           'label' => 'Módulos'],
            ['id' => 'secciones-banners',  'label' => 'Banners'],
            ['id' => 'modos-texto',       'label' => 'Modos de Texto'],
            ['id' => 'secciones-texto',   'label' => 'Secciones de Texto'],
        ] as $tab)
            <button
                onclick="cambiarTab('{{ $tab['id'] }}')"
                id="tab-btn-{{ $tab['id'] }}"
                class="tab-btn px-4 py-2 text-sm font-medium rounded-lg transition-all duration-150">
                {{ $tab['label'] }}
            </button>
        @endforeach
    </nav>

    <button id="btn-agregar"
            onclick="abrirModalCrear()"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold
                   text-white transition-all duration-150 shadow-sm hover:shadow-md hover:brightness-110"
            style="background-color: #196B4A;">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        <span id="btn-agregar-label">Agregar Módulo</span>
    </button>
</div>


{{-- TAB: MÓDULOS --}}
<div id="tab-modulos" class="tab-panel">
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
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


{{-- TAB: SECCIONES DE BANNERS --}}
<div id="tab-secciones-banners" class="tab-panel hidden">
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr style="background-color: #1a3b2e;">
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-left">Nombre</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center">Ancho</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center">Alto</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center">Límite</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center">Visible</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($seccionesBanners as $sb)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-5 py-3.5 font-medium text-gray-800">{{ $sb->nombre }}</td>
                        <td class="px-5 py-3.5 text-center text-gray-600">{{ $sb->ancho ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-center text-gray-600">{{ $sb->alto ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-center text-gray-600">{{ $sb->cantidad_limite ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-center">
                            <x-badge-estado :activo="$sb->visible_en_sitio" label-on="sí" label-off="no" />
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="inline-flex items-center gap-1">
                                <x-btn-edit
                                    onclick="abrirModalEditar('secciones-banners', {{ json_encode([
                                        'id'              => $sb->id,
                                        'nombre'          => $sb->nombre,
                                        'ancho'           => $sb->ancho,
                                        'alto'            => $sb->alto,
                                        'cantidad_limite' => $sb->cantidad_limite,
                                        'comentario'      => $sb->comentario,
                                        'imagen_ayuda'    => $sb->imagen_ayuda,
                                        'visible_en_sitio'=> $sb->visible_en_sitio,
                                    ]) }})"
                                />
                                <x-btn-delete
                                    :action="route('admin.root.secciones-banners.destroy', $sb->id)"
                                    :confirm="'¿Eliminar la sección \'' . $sb->nombre . '\'?'"
                                />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-gray-400 text-sm">
                            No hay secciones de banners registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


{{-- TAB: MODOS DE TEXTO --}}
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


{{-- TAB: SECCIONES DE TEXTO --}}
<div id="tab-secciones-texto" class="tab-panel hidden">
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <table class="w-full text-sm">
            <thead>
                <tr style="background-color: #1a3b2e;">
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-left">Nombre</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-left">Modo Texto</th>
                    <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-wider text-white text-center"></th>
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
                        <td class="px-5 py-3.5">
    @if($seccion->color_fondo || $seccion->color_texto || $seccion->color_borde)
        <span class="inline-block px-4 py-2 rounded text-sm font-bold"
              title="Fondo: {{ $seccion->color_fondo }} | Texto: {{ $seccion->color_texto }} | Borde: {{ $seccion->color_borde }}"
              style="
                  background-color: {{ $seccion->color_fondo ?: '#ffffff' }};
                  color: {{ $seccion->color_texto ?: '#000000' }};
                  border: 2px solid {{ $seccion->color_borde ?: '#cccccc' }};
              ">
            Texto
        </span>
    @else
        <span class="font-medium text-gray-800">{{ $seccion->nombre }}</span>
    @endif
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

@include('modulos.root._modals')

@endsection


@push('scripts')
<script>
const ROUTES = {
    'modulos':           '/admin/root/modulos',
    'secciones-banners': '/admin/root/secciones-banners',
    'modos-texto':       '/admin/root/modos-texto',
    'secciones-texto':   '/admin/root/secciones',
};

const LABELS_AGREGAR = {
    'modulos':           'Agregar Módulo',
    'secciones-banners': 'Agregar Sección de Banner',
    'modos-texto':       'Agregar Modo de Texto',
    'secciones-texto':   'Agregar Sección',
};

let tabActiva = '{{ request("tab", "modulos") }}';

function cambiarTab(tab) {
    tabActiva = tab;
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
    document.getElementById('tab-' + tab).classList.remove('hidden');
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('bg-white', 'text-gray-800', 'shadow-sm');
        btn.classList.add('text-gray-500');
    });
    const activeBtn = document.getElementById('tab-btn-' + tab);
    activeBtn.classList.add('bg-white', 'text-gray-800', 'shadow-sm');
    activeBtn.classList.remove('text-gray-500');
    document.getElementById('btn-agregar-label').textContent = LABELS_AGREGAR[tab];
}

function abrirModal(id) {
    document.getElementById(id).classList.remove('hidden');
}
function cerrarModal(id) {
    document.getElementById(id).classList.add('hidden');
}
function cerrarModalOverlay(event, id) {
    if (event.target === event.currentTarget) cerrarModal(id);
}
function abrirModalCrear() {
    const ids = {
        'modulos':           'modal-crear-modulo',
        'secciones-banners': 'modal-crear-seccion-banner',
        'modos-texto':       'modal-crear-modo-texto',
        'secciones-texto':   'modal-crear-seccion',
    };
    abrirModal(ids[tabActiva]);
}

function abrirModalEditar(tipo, datos) {
    const configs = {
        'modulos': {
            modal:   'modal-editar-modulo',
            campos:  ['nombre', 'path_home'],
            selects: ['tipo'],
            estado:  'estado',
        },
        'secciones-banners': {
            modal:  'modal-editar-seccion-banner',
            campos: ['nombre', 'ancho', 'alto', 'cantidad_limite', 'comentario'],
            estado: 'visible_en_sitio',
            imagen: { campo: 'imagen_ayuda', preview: 'preview-esb-imagen', label: 'esb-imagen-actual' },
        },
        'modos-texto': {
            modal:  'modal-editar-modo-texto',
            campos: ['nombre', 'cantidad_cajas'],
            estado: 'estado',
        },
        'secciones-texto': {
            modal:   'modal-editar-seccion',
            campos:  ['nombre'],
            selects: ['modo_texto_id'],
            colores: ['color_fondo', 'color_texto', 'color_borde'],
            estado:  'visible_en_sitio',
        },
    };

    const cfg   = configs[tipo];
    const modal = document.getElementById(cfg.modal);
    const form  = modal.querySelector('form');

    form.action = ROUTES[tipo] + '/' + datos.id;

    (cfg.campos || []).forEach(campo => {
        const el = form.querySelector(`input[name="${campo}"], textarea[name="${campo}"]`);
        if (el) el.value = datos[campo] ?? '';
    });

    (cfg.selects || []).forEach(campo => {
        const el = form.querySelector(`select[name="${campo}"]`);
        if (el) el.value = datos[campo] ?? '';
    });

    (cfg.colores || []).forEach(campo => {
        const picker = form.querySelector(`input[type="color"][name="${campo}"]`);
        const pair   = picker ? picker.closest('.color-pair') : null;
        const text   = pair ? pair.querySelector('.color-text') : null;
        const val    = datos[campo] || '';
        if (picker) picker.value = val || '#ffffff';
        if (text)   text.value   = val;
    });

    if (cfg.estado) {
        const chk = form.querySelector(`input[type="checkbox"][name="${cfg.estado}"]`);
        if (chk) chk.checked = parseInt(datos[cfg.estado]) === 1;
    }

    // Imagen de ayuda: mostrar nombre del archivo actual
    if (cfg.imagen && datos[cfg.imagen.campo]) {
        const label = document.getElementById(cfg.imagen.label);
        if (label) {
            label.textContent = 'Actual: ' + datos[cfg.imagen.campo];
            label.classList.remove('hidden');
        }
    }

    abrirModal(cfg.modal);
}

// Selección de archivo: muestra nombre + preview
function archivoSeleccionado(input, nombreId, previewId) {
    const nombre  = document.getElementById(nombreId);
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        if (nombre)  nombre.textContent = input.files[0].name;
        if (preview) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
}

// Sync color picker ↔ texto
document.addEventListener('input', function (e) {
    const pair = e.target.closest('.color-pair');
    if (!pair) return;
    if (e.target.classList.contains('color-picker')) {
        const text = pair.querySelector('.color-text');
        if (text) text.value = e.target.value;
    } else if (e.target.classList.contains('color-text')) {
        if (/^#[0-9A-Fa-f]{6}$/.test(e.target.value)) {
            const picker = pair.querySelector('.color-picker');
            if (picker) picker.value = e.target.value;
        }
    }
});

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id^="modal-"]').forEach(m => m.classList.add('hidden'));
    }
});

window.ModalCrud = {
    close: cerrarModal,
    closeOnOverlay: cerrarModalOverlay,
};

cambiarTab(tabActiva);
</script>
@endpush