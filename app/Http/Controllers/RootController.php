<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\ModoTexto;
use App\Models\SeccionNoticia;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RootController extends Controller
{
    // ─────────────────────────────────────────
    //  Variables compartidas del layout
    // ─────────────────────────────────────────
    private function layoutData(): array
    {
        $user          = Auth::user();
        $nombreUsuario = $user ? $user->name : 'Usuario';

        $modulosPrincipales = collect([
            (object)['nombre' => 'Root',               'path_home' => '/admin/root',       'descripcion' => 'Configuración general del sistema'],
            (object)['nombre' => 'Admin y Usuarios',   'path_home' => '/admin/usuarios',   'descripcion' => 'Gestión de usuarios y permisos'],
            (object)['nombre' => 'Novedades y Noticias','path_home' => '/admin/noticias',  'descripcion' => 'Publicar y administrar noticias'],
            (object)['nombre' => 'Publicidad y Banners','path_home' => '/admin/banners',   'descripcion' => 'Gestionar banners publicitarios'],
            (object)['nombre' => 'Audio/Video',         'path_home' => '/admin/multimedia','descripcion' => 'Contenido multimedia'],
        ]);

        $modulosSecundarios = collect([
            (object)['nombre' => 'Álbum de Fotos',       'path_home' => '/admin/albumes',   'descripcion' => 'Crear y gestionar álbumes'],
            (object)['nombre' => 'Calendario Agenda',    'path_home' => '/admin/agenda',    'descripcion' => 'Eventos y programación'],
            (object)['nombre' => 'Mi Perfil',            'path_home' => '/admin/perfil',    'descripcion' => 'Datos personales y cuenta'],
            (object)['nombre' => 'Contadores Web',       'path_home' => '/admin/contadores','descripcion' => 'Estadísticas y métricas'],
            (object)['nombre' => 'Trámites y Formularios','path_home' => '/admin/tramites', 'descripcion' => 'Documentos descargables'],
        ]);

        return compact('nombreUsuario', 'modulosPrincipales', 'modulosSecundarios');
    }

    // ─────────────────────────────────────────
    //  INDEX — vista principal con las 3 tabs
    // ─────────────────────────────────────────
    public function index(): View
    {
        $modulos    = Modulo::orderBy('orden')->get();
        $modosTexto = ModoTexto::orderBy('id')->get();
        $secciones  = SeccionNoticia::with('modoTexto')->orderBy('orden')->get();

        return view('modulos.root.index', array_merge(
            $this->layoutData(),
            compact('modulos', 'modosTexto', 'secciones')
        ));
    }

    // ─────────────────────────────────────────
    //  MÓDULOS
    // ─────────────────────────────────────────
    public function moduloStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:120',
            'tipo'      => 'nullable|string|max:50',
            'path_home' => 'nullable|string|max:255',
            'icono'     => 'nullable|string|max:100',
            'orden'     => 'nullable|integer|min:0',
            'estado'    => 'boolean',
        ]);

        $data['estado'] = $request->boolean('estado', true);
        $data['orden']  = $data['orden'] ?? 0;

        Modulo::create($data);

        return redirect()->route('admin.root.index', ['tab' => 'modulos'])
            ->with('success', 'Módulo creado correctamente.');
    }

    public function moduloUpdate(Request $request, Modulo $modulo): RedirectResponse
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:120',
            'tipo'      => 'nullable|string|max:50',
            'path_home' => 'nullable|string|max:255',
            'icono'     => 'nullable|string|max:100',
            'orden'     => 'nullable|integer|min:0',
            'estado'    => 'boolean',
        ]);

        $data['estado'] = $request->boolean('estado');

        $modulo->update($data);

        return redirect()->route('admin.root.index', ['tab' => 'modulos'])
            ->with('success', 'Módulo actualizado correctamente.');
    }

    public function moduloDestroy(Modulo $modulo): RedirectResponse
    {
        $modulo->delete();

        return redirect()->route('admin.root.index', ['tab' => 'modulos'])
            ->with('success', 'Módulo eliminado.');
    }

    // ─────────────────────────────────────────
    //  MODOS DE TEXTO
    // ─────────────────────────────────────────
    public function modoTextoStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre'         => 'required|string|max:100',
            'descripcion'    => 'nullable|string|max:255',
            'cantidad_cajas' => 'nullable|integer|min:1|max:255',
            'estado'         => 'boolean',
        ]);

        $data['estado'] = $request->boolean('estado', true);

        ModoTexto::create($data);

        return redirect()->route('admin.root.index', ['tab' => 'modos-texto'])
            ->with('success', 'Modo de texto creado correctamente.');
    }

    public function modoTextoUpdate(Request $request, ModoTexto $modoTexto): RedirectResponse
    {
        $data = $request->validate([
            'nombre'         => 'required|string|max:100',
            'descripcion'    => 'nullable|string|max:255',
            'cantidad_cajas' => 'nullable|integer|min:1|max:255',
            'estado'         => 'boolean',
        ]);

        $data['estado'] = $request->boolean('estado');

        $modoTexto->update($data);

        return redirect()->route('admin.root.index', ['tab' => 'modos-texto'])
            ->with('success', 'Modo de texto actualizado correctamente.');
    }

    public function modoTextoDestroy(ModoTexto $modoTexto): RedirectResponse
    {
        $modoTexto->delete();

        return redirect()->route('admin.root.index', ['tab' => 'modos-texto'])
            ->with('success', 'Modo de texto eliminado.');
    }

    // ─────────────────────────────────────────
    //  SECCIONES DE TEXTO (secciones_noticias)
    // ─────────────────────────────────────────
    public function seccionStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'modo_texto_id'    => 'required|exists:modos_texto,id',
            'nombre'           => 'required|string|max:150',
            'color_fondo'      => 'nullable|string|max:20',
            'color_texto'      => 'nullable|string|max:20',
            'color_borde'      => 'nullable|string|max:20',
            'visible_en_sitio' => 'boolean',
            'orden'            => 'nullable|integer|min:0',
        ]);

        $data['visible_en_sitio'] = $request->boolean('visible_en_sitio', true);
        $data['orden']            = $data['orden'] ?? 0;

        SeccionNoticia::create($data);

        return redirect()->route('admin.root.index', ['tab' => 'secciones-texto'])
            ->with('success', 'Sección creada correctamente.');
    }

    public function seccionUpdate(Request $request, SeccionNoticia $seccionNoticia): RedirectResponse
    {
        $data = $request->validate([
            'modo_texto_id'    => 'required|exists:modos_texto,id',
            'nombre'           => 'required|string|max:150',
            'color_fondo'      => 'nullable|string|max:20',
            'color_texto'      => 'nullable|string|max:20',
            'color_borde'      => 'nullable|string|max:20',
            'visible_en_sitio' => 'boolean',
            'orden'            => 'nullable|integer|min:0',
        ]);

        $data['visible_en_sitio'] = $request->boolean('visible_en_sitio');

        $seccionNoticia->update($data);

        return redirect()->route('admin.root.index', ['tab' => 'secciones-texto'])
            ->with('success', 'Sección actualizada correctamente.');
    }

    public function seccionDestroy(SeccionNoticia $seccionNoticia): RedirectResponse
    {
        $seccionNoticia->delete();

        return redirect()->route('admin.root.index', ['tab' => 'secciones-texto'])
            ->with('success', 'Sección eliminada.');
    }
}