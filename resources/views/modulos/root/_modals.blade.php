{{-- resources/views/modulos/root/_modals.blade.php --}}


{{-- ════════════════════════════════
     1. MÓDULO — CREAR
════════════════════════════════ --}}
<x-modal-crud
    id="modal-crear-modulo"
    title="Agregar Módulo"
    :action="route('admin.root.modulos.store')"
    icon="plus"
    submit-label="Guardar">

    <div>
        <x-form-label for="cm-nombre" required>Nombre</x-form-label>
        <x-form-input id="cm-nombre" name="nombre"
                      placeholder="Nombre del módulo" maxlength="120" required />
    </div>

    <div>
        <x-form-label for="cm-tipo">Tipo</x-form-label>
        <x-form-select id="cm-tipo" name="tipo">
            <option value="">— Sin tipo —</option>
            @foreach(\App\Enums\ModuloTipo::cases() as $t)
                <option value="{{ $t->value }}">{{ $t->label() }}</option>
            @endforeach
        </x-form-select>
    </div>

    <div>
        <x-form-label for="cm-path">Path Home</x-form-label>
        <x-form-input id="cm-path" name="path_home"
                      placeholder="templates/modulos/..." :mono="true" />
    </div>

    <div>
        <x-form-label>Estado</x-form-label>
        <x-form-toggle name="estado" :checked="true" label="Activo" />
    </div>
</x-modal-crud>


{{-- ════════════════════════════════
     2. MÓDULO — EDITAR
════════════════════════════════ --}}
<x-modal-crud
    id="modal-editar-modulo"
    title="Editar Módulo"
    method="PUT"
    icon="edit"
    submit-label="Actualizar">

    <div>
        <x-form-label for="em-nombre" required>Nombre</x-form-label>
        <x-form-input id="em-nombre" name="nombre" maxlength="120" required />
    </div>

    <div>
        <x-form-label for="em-tipo">Tipo</x-form-label>
        <x-form-select id="em-tipo" name="tipo">
            <option value="">— Sin tipo —</option>
            @foreach(\App\Enums\ModuloTipo::cases() as $t)
                <option value="{{ $t->value }}">{{ $t->label() }}</option>
            @endforeach
        </x-form-select>
    </div>

    <div>
        <x-form-label for="em-path">Path Home</x-form-label>
        <x-form-input id="em-path" name="path_home" :mono="true" />
    </div>

    <div>
        <x-form-label>Estado</x-form-label>
        <x-form-toggle name="estado" label="Activo" />
    </div>
</x-modal-crud>


{{-- ════════════════════════════════
     3. MODO DE TEXTO — CREAR
════════════════════════════════ --}}
<x-modal-crud
    id="modal-crear-modo-texto"
    title="Agregar Modo de Texto"
    :action="route('admin.root.modos-texto.store')"
    icon="plus"
    submit-label="Guardar">

    <div>
        <x-form-label for="cmt-nombre" required>Nombre</x-form-label>
        <x-form-input id="cmt-nombre" name="nombre"
                      placeholder="Ej: Estándar, Doble columna..." maxlength="100" required />
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <x-form-label for="cmt-cajas">Cantidad de Cajas</x-form-label>
            <x-form-input id="cmt-cajas" name="cantidad_cajas"
                          type="number" min="1" max="255" placeholder="Ej: 2" />
        </div>
        <div>
            <x-form-label>Estado</x-form-label>
            <x-form-toggle name="estado" :checked="true" label="Activo" />
        </div>
    </div>
</x-modal-crud>


{{-- ════════════════════════════════
     4. MODO DE TEXTO — EDITAR
════════════════════════════════ --}}
<x-modal-crud
    id="modal-editar-modo-texto"
    title="Editar Modo de Texto"
    method="PUT"
    icon="edit"
    submit-label="Actualizar">

    <div>
        <x-form-label for="emt-nombre" required>Nombre</x-form-label>
        <x-form-input id="emt-nombre" name="nombre" maxlength="100" required />
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <x-form-label for="emt-cajas">Cant. Cajas</x-form-label>
            <x-form-input id="emt-cajas" name="cantidad_cajas"
                          type="number" min="1" max="255" />
        </div>
        <div>
            <x-form-label>Estado</x-form-label>
            <x-form-toggle name="estado" label="Activo" />
        </div>
    </div>
</x-modal-crud>


{{-- ════════════════════════════════
     5. SECCIÓN DE TEXTO — CREAR
════════════════════════════════ --}}
<x-modal-crud
    id="modal-crear-seccion"
    title="Agregar Sección"
    :action="route('admin.root.secciones.store')"
    size="lg"
    icon="plus"
    submit-label="Guardar">

    <div>
        <x-form-label for="cs-nombre" required>Nombre</x-form-label>
        <x-form-input id="cs-nombre" name="nombre"
                      placeholder="Nombre de la sección" maxlength="150" required />
    </div>

    <div>
        <x-form-label for="cs-modo" required>Modo de Texto</x-form-label>
        <x-form-select id="cs-modo" name="modo_texto_id" required>
            <option value="">— Seleccionar modo —</option>
            @foreach($modosTexto->where('estado', 1) as $modo)
                <option value="{{ $modo->id }}">{{ $modo->nombre }}</option>
            @endforeach
        </x-form-select>
    </div>

    <x-form-color-group />

    <div>
        <x-form-label>Visible en Sitio</x-form-label>
        <x-form-toggle name="visible_en_sitio" :checked="true" label="Sí" />
    </div>
</x-modal-crud>


{{-- ════════════════════════════════
     6. SECCIÓN DE TEXTO — EDITAR
════════════════════════════════ --}}
<x-modal-crud
    id="modal-editar-seccion"
    title="Editar Sección"
    method="PUT"
    size="lg"
    icon="edit"
    submit-label="Actualizar">

    <div>
        <x-form-label for="es-nombre" required>Nombre</x-form-label>
        <x-form-input id="es-nombre" name="nombre" maxlength="150" required />
    </div>

    <div>
        <x-form-label for="es-modo" required>Modo de Texto</x-form-label>
        <x-form-select id="es-modo" name="modo_texto_id" required>
            <option value="">— Seleccionar modo —</option>
            @foreach($modosTexto as $modo)
                <option value="{{ $modo->id }}">{{ $modo->nombre }}</option>
            @endforeach
        </x-form-select>
    </div>

    <x-form-color-group />

    <div>
        <x-form-label>Visible en Sitio</x-form-label>
        <x-form-toggle name="visible_en_sitio" label="Sí" />
    </div>
</x-modal-crud>


{{-- ════════════════════════════════
     7. SECCIÓN BANNER — CREAR
════════════════════════════════ --}}
<x-modal-crud
    id="modal-crear-seccion-banner"
    title="Agregar Sección de Banner"
    :action="route('admin.root.secciones-banners.store')"
    :multipart="true"
    size="lg"
    icon="plus"
    submit-label="Guardar">

    <div>
        <x-form-label for="csb-nombre" required>Nombre</x-form-label>
        <x-form-input id="csb-nombre" name="nombre"
                      placeholder="Nombre de la sección" maxlength="150" required />
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <x-form-label for="csb-ancho" required>Ancho (px)</x-form-label>
            <x-form-input id="csb-ancho" name="ancho"
                          type="number" min="1" placeholder="Ej: 1200" required />
        </div>
        <div>
            <x-form-label for="csb-alto">Alto (px)</x-form-label>
            <x-form-input id="csb-alto" name="alto"
                          type="number" min="1" placeholder="Ej: 400" />
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <x-form-label for="csb-limite">Cantidad límite de banners</x-form-label>
            <x-form-input id="csb-limite" name="cantidad_limite"
                          type="number" min="1" placeholder="Ej: 5" />
        </div>
        <div>
            <x-form-label>Visible en Sitio</x-form-label>
            <x-form-toggle name="visible_en_sitio" :checked="true" label="Sí" />
        </div>
    </div>

    <div>
        <x-form-label for="csb-comentario">Comentario</x-form-label>
        <x-form-textarea id="csb-comentario" name="comentario"
                         placeholder="Descripción o nota interna..." maxlength="255" />
    </div>

    <div>
        <x-form-label>Imagen de Ayuda</x-form-label>
        <label for="csb-imagen" class="flex items-center gap-3 cursor-pointer"
               style="padding: 0.5rem 0.875rem; border-radius: 0.75rem;
                      border: 1px solid #e5e7eb; background-color: #f9fafb;">
            <span style="background-color: #196B4A; color: white; padding: 0.25rem 0.875rem;
                         border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600; white-space: nowrap;">
                Elegir archivo
            </span>
            <span id="csb-imagen-nombre" class="text-sm text-gray-400 truncate">
                Sin archivo seleccionado
            </span>
        </label>
        <input type="file" id="csb-imagen" name="imagen_ayuda"
               accept="image/*" class="hidden"
               onchange="archivoSeleccionado(this, 'csb-imagen-nombre', 'preview-csb-imagen')" />
        <img id="preview-csb-imagen" src="" alt=""
             class="hidden mt-2 rounded-xl border border-gray-200 max-h-28 object-cover" />
    </div>
</x-modal-crud>


{{-- ════════════════════════════════
     8. SECCIÓN BANNER — EDITAR
════════════════════════════════ --}}
<x-modal-crud
    id="modal-editar-seccion-banner"
    title="Editar Sección de Banner"
    :multipart="true"
    method="POST"
    size="lg"
    icon="edit"
    submit-label="Actualizar">

    <input type="hidden" name="_method" value="PUT">

    <div>
        <x-form-label for="esb-nombre" required>Nombre</x-form-label>
        <x-form-input id="esb-nombre" name="nombre" maxlength="150" required />
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <x-form-label for="esb-ancho" required>Ancho (px)</x-form-label>
            <x-form-input id="esb-ancho" name="ancho" type="number" min="1" required />
        </div>
        <div>
            <x-form-label for="esb-alto">Alto (px)</x-form-label>
            <x-form-input id="esb-alto" name="alto" type="number" min="1" />
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <x-form-label for="esb-limite">Cantidad límite de banners</x-form-label>
            <x-form-input id="esb-limite" name="cantidad_limite" type="number" min="1" />
        </div>
        <div>
            <x-form-label>Visible en Sitio</x-form-label>
            <x-form-toggle name="visible_en_sitio" label="Sí" />
        </div>
    </div>

    <div>
        <x-form-label for="esb-comentario">Comentario</x-form-label>
        <x-form-textarea id="esb-comentario" name="comentario" maxlength="255" />
    </div>

    <div>
        <x-form-label>Imagen de Ayuda</x-form-label>
        <label for="esb-imagen" class="flex items-center gap-3 cursor-pointer"
               style="padding: 0.5rem 0.875rem; border-radius: 0.75rem;
                      border: 1px solid #e5e7eb; background-color: #f9fafb;">
            <span style="background-color: #1a3b2e; color: white; padding: 0.25rem 0.875rem;
                         border-radius: 0.5rem; font-size: 0.75rem; font-weight: 600; white-space: nowrap;">
                Elegir archivo
            </span>
            <span id="esb-imagen-nombre" class="text-sm text-gray-400 truncate">
                Sin archivo seleccionado
            </span>
        </label>
        <input type="file" id="esb-imagen" name="imagen_ayuda"
               accept="image/*" class="hidden"
               onchange="archivoSeleccionado(this, 'esb-imagen-nombre', 'preview-esb-imagen')" />
        <img id="preview-esb-imagen" src="" alt=""
             class="hidden mt-2 rounded-xl border border-gray-200 max-h-28 object-cover" />
        <p id="esb-imagen-actual" class="hidden mt-1 text-xs text-gray-400"></p>
    </div>
</x-modal-crud>