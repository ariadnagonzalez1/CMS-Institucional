<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\CrudService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(private readonly CrudService $crud) {}

    // ─────────────────────────────────────────
    //  Vista principal
    // ─────────────────────────────────────────
    public function edit(Request $request): View
    {
        $user          = $request->user()->load('salaRedaccion', 'modoGrupo');
        $nombreUsuario = $user->name;

        $modulosPrincipales = collect([
            (object)['nombre' => 'Root',                'path_home' => '/admin/root',       'descripcion' => 'Configuración general del sistema'],
            (object)['nombre' => 'Admin y Usuarios',    'path_home' => '/admin/usuarios',   'descripcion' => 'Gestión de usuarios y permisos'],
            (object)['nombre' => 'Novedades y Noticias','path_home' => '/admin/noticias',   'descripcion' => 'Publicar y administrar noticias'],
            (object)['nombre' => 'Publicidad y Banners','path_home' => '/admin/banners',    'descripcion' => 'Gestionar banners publicitarios'],
            (object)['nombre' => 'Audio/Video',          'path_home' => '/admin/multimedia', 'descripcion' => 'Contenido multimedia'],
        ]);
        
$modulosSecundarios = collect([
    (object)['nombre' => 'Álbum de Fotos',        'path_home' => '/admin/albumes',    'descripcion' => 'Crear y gestionar álbumes'],
    (object)['nombre' => 'Calendario Agenda',     'path_home' => '/admin/agenda',     'descripcion' => 'Eventos y programación'],
    (object)['nombre' => 'Mi Perfil',             'path_home' => '/admin/perfil',     'descripcion' => 'Datos personales y cuenta'], // CAMBIADO A /admin/perfil
    (object)['nombre' => 'Contadores Web',        'path_home' => '/admin/contadores', 'descripcion' => 'Estadísticas y métricas'],
    (object)['nombre' => 'Trámites y Formularios','path_home' => '/admin/tramites',   'descripcion' => 'Documentos descargables'],
]);

        return view('modulos.perfil.index', compact(
            'user',
            'nombreUsuario',
            'modulosPrincipales',
            'modulosSecundarios'
        ));
    }

    // ─────────────────────────────────────────
    //  Actualizar datos personales
    // ─────────────────────────────────────────
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        return $this->crud->update(
            model      : $request->user(),
            data       : $request->validated(),
            redirectTo : route('profile.edit'),
            label      : 'perfil',
            beforeSave : function ($user) use ($request) {
                if ($user->isDirty('email')) {
                    $user->email_verified_at = null;
                }
            },
        );
    }

    // ─────────────────────────────────────────
    //  Cambiar contraseña
    // ─────────────────────────────────────────
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.current_password' => 'La contraseña actual es incorrecta.',
            'password.confirmed'                => 'Las contraseñas nuevas no coinciden.',
        ]);

        return $this->crud->update(
            model      : $request->user(),
            data       : [],
            redirectTo : route('profile.edit'),
            label      : 'contraseña',
            beforeSave : fn($user) => $user->password = Hash::make($request->password),
        );
    }

    // ─────────────────────────────────────────
    //  Cambiar avatar
    // ─────────────────────────────────────────
    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ], [
            'avatar.image' => 'El archivo debe ser una imagen.',
            'avatar.max'   => 'La imagen no puede superar 2MB.',
        ]);

        return $this->crud->update(
            model      : $request->user(),
            data       : [],
            redirectTo : route('profile.edit'),
            label      : 'avatar',
            beforeSave : function ($user) use ($request) {
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $user->avatar = $request->file('avatar')->store('avatars', 'public');
            },
        );
    }

    // ─────────────────────────────────────────
    //  Eliminar cuenta
    // ─────────────────────────────────────────
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        auth()->logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}