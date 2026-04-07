<?php
// app/Http/Controllers/PublicController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\ConfiguracionSitio;
use App\Models\Banner;
use App\Models\Noticia;
use App\Models\Descargable;
use App\Models\Multimedia;
use App\Models\SolicitudEmpresa;
use App\Models\SeccionBanner;
use App\Models\SolicitudEmpresaIngenieria;
use Carbon\Carbon;

class PublicController extends Controller
{
    private function sharedData(): array
    {
        $config = ConfiguracionSitio::where('activo', 1)->first();
        
        // Si no hay configuración, crear un objeto con valores por defecto
        if (!$config) {
            $config = new ConfiguracionSitio();
            $config->titulo_sitio = 'Colegio Público de Ingenieros de Formosa';
            $config->descripcion_sitio = 'Regulamos y promovemos el ejercicio profesional de la ingeniería en la provincia de Formosa, garantizando calidad, ética y compromiso con la sociedad.';
            $config->whatsapp = '3704 043114';
            $config->telefono = '3704 043114';
            $config->email_principal = 'ingenierosformosa@gmail.com';
            $config->facebook_url = null;
            $config->instagram_url = null;
            $config->youtube_url = null;
            $config->twitter_url = null;
            $config->google_maps_url = null;
            $config->activo = 1;
        }
        
        return compact('config');
    }

    public function inicio(): View
    {
        $shared = $this->sharedData();

        $seccionPortada = SeccionBanner::where('visible_en_sitio', 1)
            ->where(fn($q) => $q->where('nombre', 'like', '%portada%')->orWhere('nombre', 'like', '%principal%')->orWhere('nombre', 'like', '%home%'))
            ->first()
            ?? SeccionBanner::where('visible_en_sitio', 1)->first();

        $hoy = now()->toDateString();
        $banners = $seccionPortada
            ? Banner::where('seccion_banner_id', $seccionPortada->id)
                ->where('estado', 'activo')
                ->where(fn($q) => $q->whereNull('fecha_inicio')->orWhere('fecha_inicio', '<=', $hoy))
                ->where(fn($q) => $q->whereNull('fecha_fin')->orWhere('fecha_fin', '>=', $hoy))
                ->orderBy('orden')
                ->get()
            : collect();

        // Noticias hero: activas + visibles + destacado en portada
        $noticiasHero = Noticia::with(['imagenes', 'seccion'])
            ->where('activa', 1)
            ->where('visible', 1)
            ->where('es_destacado_portada', 1)
            ->orderByDesc('fecha_publicacion')
            ->limit(5)
            ->get();

        // Noticias novedades: activas + visibles + destacado en portada
        $noticias = Noticia::with(['imagenes', 'seccion'])
            ->where('activa', 1)
            ->where('visible', 1)
            ->where('es_destacado_portada', 1)
            ->orderByDesc('fecha_publicacion')
            ->limit(3)
            ->get();

        $consejoDirectivo     = collect();
        $tribunalFiscalizador = collect();
        $tribunalEtica        = collect();

        $descargables = Descargable::where('visible', 1)
            ->where('estado', 1)
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        $videos = Multimedia::with(['seccion', 'tipo'])
            ->where('estado', 1)
            ->where(fn($q) => $q->whereNotNull('codigo_embed')
                                ->orWhereNotNull('url_externa'))
            ->orderBy('orden')
            ->limit(3)
            ->get();

        return view('public.inicio', array_merge($shared, compact(
            'banners', 'noticias', 'noticiasHero',
            'consejoDirectivo', 'tribunalFiscalizador', 'tribunalEtica',
            'descargables', 'videos'
        )));
    }

    public function institucional(): View
    {
        return view('public.institucional', $this->sharedData());
    }

    public function novedades(Request $request): View
    {
        $shared = $this->sharedData();

        $query = Noticia::with(['imagenes', 'seccion'])
            ->where('activa', 1)
            ->where('visible', 1)
            ->orderByDesc('fecha_publicacion');

        if ($request->filled('seccion')) {
            $query->where('seccion_noticia_id', $request->seccion);
        }

        $noticias  = $query->paginate(9)->withQueryString();
        $secciones = \App\Models\SeccionNoticia::where('visible_en_sitio', 1)->orderBy('orden')->get();

        return view('public.novedades', array_merge($shared, compact('noticias', 'secciones')));
    }

    public function noticia(string $slug): View
    {
        $shared = $this->sharedData();

        $noticia = Noticia::with(['imagenes', 'seccion', 'noticiasRelacionadas'])
            ->where('slug', $slug)
            ->where('activa', 1)
            ->where('visible', 1)
            ->firstOrFail();

        $noticia->increment('visitas');

        return view('public.noticia', array_merge($shared, compact('noticia')));
    }

    public function servicios(): View
    {
        $shared = $this->sharedData();

        $descargables = Descargable::with('seccion')
            ->where('visible', 1)
            ->where('estado', 1)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('seccion_descargable_id');

        $videos = Multimedia::with(['seccion', 'tipo'])
            ->where('estado', 1)
            ->where(fn($q) => $q->whereNotNull('codigo_embed')
                                ->orWhereNotNull('url_externa'))
            ->orderBy('orden')
            ->get();

        return view('public.servicios', array_merge($shared, compact('descargables', 'videos')));
    }

    public function contacto(): View
    {
        return view('public.contacto', $this->sharedData());
    }

    public function contactoEnviar(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre'   => 'required|string|max:150',
            'email'    => 'required|email|max:150',
            'telefono' => 'nullable|string|max:30',
            'empresa'  => 'nullable|string|max:150',
            'asunto'   => 'nullable|string|max:255',
            'ubicacion'=> 'nullable|string|max:150',
            'mensaje'  => 'required|string|max:3000',
        ]);

        $solicitud = SolicitudEmpresa::create([
            'nombre'   => $validated['nombre'],
            'email'    => $validated['email'],
            'telefono' => $validated['telefono'] ?? null,
            'empresa'  => $validated['empresa'] ?? null,
            'asunto'   => $validated['asunto'] ?? null,
            'ubicacion'=> $validated['ubicacion'] ?? null,
            'mensaje'  => $validated['mensaje'],
            'estado'   => 'pendiente',
        ]);

        if ($request->filled('ingenierias')) {
            foreach ((array) $request->ingenierias as $ingId) {
                SolicitudEmpresaIngenieria::create([
                    'solicitud_empresa_id' => $solicitud->id,
                    'ingenieria_id'        => $ingId,
                ]);
            }
        }

        return back()->with('success', '¡Tu mensaje fue enviado correctamente! Nos comunicaremos a la brevedad.');
    }

    public function descargableDownload(int $id)
    {
        $descargable = Descargable::where('id', $id)
            ->where('visible', 1)
            ->where('estado', 1)
            ->firstOrFail();

        $descargable->increment('total_descargas');

        $path = storage_path('app/public/' . $descargable->archivo);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->download(
            $path,
            $descargable->nombre_original_archivo ?? basename($descargable->archivo)
        );
    }
}