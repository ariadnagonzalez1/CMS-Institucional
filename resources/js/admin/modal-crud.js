/**
 * resources/js/admin/modal-crud.js
 *
 * Patrón Module (IIFE) + Registry + Facade.
 * Toda la lógica de modales en un solo lugar.
 * Los Blade no saben nada de JS; este archivo no sabe nada del DOM específico.
 */

const ModalCrud = (() => {

    // ── Registry: mapeo tipo → config del modal ──────────────────────────────
    // Para agregar un CRUD nuevo: solo registrás acá, sin tocar nada más.
    const _registry = {
        'modulos': {
            modalId   : 'modal-editar-modulo',
            baseRoute : '/admin/root/modulos',
        },
        'modos-texto': {
            modalId   : 'modal-editar-modo-texto',
            baseRoute : '/admin/root/modos-texto',
        },
        'secciones-texto': {
            modalId   : 'modal-editar-seccion',
            baseRoute : '/admin/root/secciones',
        },
    };

    // ── Apertura / Cierre ────────────────────────────────────────────────────

    function open(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.remove('hidden');
        setTimeout(() => {
            const first = modal.querySelector('input:not([type=hidden]), select, textarea');
            if (first) first.focus();
        }, 80);
    }

    function close(id) {
        const modal = document.getElementById(id);
        if (modal) modal.classList.add('hidden');
    }

    function closeOnOverlay(event, id) {
        if (event.target === event.currentTarget) close(id);
    }

    function closeAll() {
        document.querySelectorAll('[data-modal-id]').forEach(m => m.classList.add('hidden'));
    }

    // ── Registrar un nuevo tipo de modal desde el exterior ───────────────────
    // Uso: ModalCrud.register('banners', { modalId: '...', baseRoute: '...' })

    function register(tipo, config) {
        _registry[tipo] = config;
    }

    // ── Abrir modal de edición y rellenar datos ──────────────────────────────

    function openEdit(tipo, datos) {
        const cfg = _registry[tipo];
        if (!cfg) {
            console.warn('ModalCrud: tipo no registrado →', tipo);
            return;
        }

        const modal = document.getElementById(cfg.modalId);
        if (!modal) return;

        const form = modal.querySelector('form');
        if (!form) return;

        // Acción del form con el ID del registro
        form.action = cfg.baseRoute + '/' + datos.id;

        // Rellenar inputs, selects y textareas por nombre
        Object.entries(datos).forEach(([key, value]) => {
            if (key === 'id') return;

            const field = form.querySelector(
                `input[name="${key}"]:not([type=hidden]):not([type=checkbox]),
                 select[name="${key}"],
                 textarea[name="${key}"]`
            );
            if (field) field.value = value ?? '';

            // Toggles / checkboxes
            const checkbox = form.querySelector(`input[type="checkbox"][name="${key}"]`);
            if (checkbox) checkbox.checked = Boolean(value);
        });

        // Sincronizar color pickers con sus text inputs
        _syncColorPairs(form, datos);

        open(cfg.modalId);
    }

    // ── Helpers internos ─────────────────────────────────────────────────────

    function _syncColorPairs(form, datos) {
        form.querySelectorAll('.color-pair').forEach(pair => {
            const picker = pair.querySelector('input[type="color"]');
            const text   = pair.querySelector('.color-text');
            if (!picker) return;

            const value = datos[picker.getAttribute('name')];
            if (value && /^#[0-9A-Fa-f]{6}$/.test(value)) {
                picker.value = value;
                if (text) text.value = value;
            }
        });
    }

    // ── Sync en vivo picker ↔ texto (delegado en document) ──────────────────
    document.addEventListener('input', (e) => {
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

    // ── Cerrar con Escape ─────────────────────────────────────────────────────
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAll();
    });

    // ── API pública ───────────────────────────────────────────────────────────
    return { open, close, closeOnOverlay, closeAll, openEdit, register };

})();


// ── Compatibilidad con las funciones del index.blade.php ─────────────────────
// Estas funciones son las que llama el código existente del Blade.

function abrirModal(id)            { ModalCrud.open(id); }
function cerrarModal(id)           { ModalCrud.close(id); }
function cerrarModalOverlay(e, id) { ModalCrud.closeOnOverlay(e, id); }

function abrirModalCrear() {
    const ids = {
        'modulos':         'modal-crear-modulo',
        'modos-texto':     'modal-crear-modo-texto',
        'secciones-texto': 'modal-crear-seccion',
    };
    // tabActiva viene definida en el index.blade.php
    if (typeof tabActiva !== 'undefined' && ids[tabActiva]) {
        ModalCrud.open(ids[tabActiva]);
    }
}

function abrirModalEditar(tipo, datos) {
    ModalCrud.openEdit(tipo, datos);
}