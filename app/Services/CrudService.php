<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * CrudService
 * app/Services/CrudService.php
 *
 * Servicio genérico reutilizable para store / update / destroy.
 * Los controllers inyectan este servicio y quedan en 1-3 líneas por método.
 *
 * ── Uso básico ──────────────────────────────────────────────────
 *
 *   return $this->crud->store(
 *       model      : new Modulo,
 *       data       : $request->validated(),
 *       redirectTo : route('admin.root.index', ['tab' => 'modulos']),
 *       label      : 'módulo',
 *   );
 *
 * ── Uso con hooks (lógica extra antes/después de guardar) ────────
 *
 *   return $this->crud->store(
 *       model      : new Noticia,
 *       data       : $request->validated(),
 *       redirectTo : route('admin.noticias.index'),
 *       label      : 'noticia',
 *       beforeSave : fn(Noticia $m) => $m->slug = Str::slug($m->titulo),
 *       afterSave  : fn(Noticia $m) => NoticiaCreadaJob::dispatch($m),
 *   );
 */
class CrudService
{
    public function store(
        Model     $model,
        array     $data,
        string    $redirectTo,
        string    $label      = 'registro',
        ?callable $beforeSave = null,
        ?callable $afterSave  = null,
    ): RedirectResponse {
        return $this->persist('crear', $label, $redirectTo, function () use ($model, $data, $beforeSave, $afterSave) {
            $model->fill($data);
            if ($beforeSave) $beforeSave($model);
            $model->save();
            if ($afterSave)  $afterSave($model);
        });
    }

    public function update(
        Model     $model,
        array     $data,
        string    $redirectTo,
        string    $label      = 'registro',
        ?callable $beforeSave = null,
        ?callable $afterSave  = null,
    ): RedirectResponse {
        return $this->persist('actualizar', $label, $redirectTo, function () use ($model, $data, $beforeSave, $afterSave) {
            $model->fill($data);
            if ($beforeSave) $beforeSave($model);
            $model->save();
            if ($afterSave)  $afterSave($model);
        });
    }

    public function destroy(
        Model     $model,
        string    $redirectTo,
        string    $label        = 'registro',
        ?callable $beforeDelete = null,
    ): RedirectResponse {
        return $this->persist('eliminar', $label, $redirectTo, function () use ($model, $beforeDelete) {
            if ($beforeDelete) $beforeDelete($model);
            $model->delete();
        });
    }

    // ── Núcleo privado: transacción + mensajes flash uniformes ───────────────

    private function persist(string $action, string $label, string $redirectTo, callable $callback): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $callback();
            DB::commit();

            $msg = match ($action) {
                'crear'      => ucfirst($label) . ' creado correctamente.',
                'actualizar' => ucfirst($label) . ' actualizado correctamente.',
                'eliminar'   => ucfirst($label) . ' eliminado correctamente.',
                default      => 'Operación realizada.',
            };

            return redirect($redirectTo)->with('success', $msg);

        } catch (Throwable $e) {
            DB::rollBack();
            report($e);

            $msg = $action === 'eliminar'
                ? 'No se pudo eliminar el ' . $label . '. Puede tener registros relacionados.'
                : 'No se pudo ' . $action . ' el ' . $label . '. Intente nuevamente.';

            return redirect($redirectTo)->withInput()->with('error', $msg);
        }
    }
}