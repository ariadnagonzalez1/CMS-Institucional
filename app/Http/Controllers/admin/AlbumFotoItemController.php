<?php
// app/Http/Controllers/admin/AlbumFotoItemController.php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AlbumFoto;
use App\Models\AlbumFotoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AlbumFotoItemController extends Controller
{
    /**
     * Store multiple photos in the album.
     */
    public function store(Request $request, AlbumFoto $album)
    {
        $request->validate([
            'fotos' => 'required|array',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'epigrafe_general' => 'nullable|string|max:255',
        ]);
        
        try {
            DB::beginTransaction();
            
            $orden = $album->fotos()->count();
            $epigrafeGeneral = $request->input('epigrafe_general');
            
            foreach ($request->file('fotos') as $file) {
                $extension = $file->getClientOriginalExtension();
                $filename = Str::uuid() . '.' . $extension;
                $path = $file->storeAs('albumes/' . $album->id, $filename, 'public');
                
                // Obtener dimensiones
                $imageInfo = getimagesize($file->getRealPath());
                $ancho = $imageInfo[0] ?? null;
                $alto = $imageInfo[1] ?? null;
                
                // Crear la foto
                $foto = new AlbumFotoItem();
                $foto->album_id = $album->id;
                $foto->archivo = $path;
                $foto->nombre_archivo = $filename;
                $foto->epigrafe = $epigrafeGeneral;
                $foto->orden = $orden++;
                $foto->ancho = $ancho;
                $foto->alto = $alto;
                $foto->save();
                
                // Si es la primera foto del álbum, establecer como portada
                if ($orden === 1) {
                    $foto->es_portada = true;
                    $foto->save();
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.albumes.index')
                ->with('success', count($request->file('fotos')) . ' foto(s) agregada(s) correctamente.');
                
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al subir las fotos: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified photo from storage.
     */
    public function destroy(AlbumFoto $album, AlbumFotoItem $foto)
    {
        try {
            DB::beginTransaction();
            
            // Eliminar archivo físico
            if ($foto->archivo && Storage::disk('public')->exists($foto->archivo)) {
                Storage::disk('public')->delete($foto->archivo);
            }
            
            $foto->delete();
            
            DB::commit();
            
            return redirect()->route('admin.albumes.index')
                ->with('success', 'Foto eliminada correctamente.');
                
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al eliminar la foto.');
        }
    }

    /**
     * Set photo as cover.
     */
    public function setPortada(AlbumFoto $album, AlbumFotoItem $foto)
    {
        try {
            DB::beginTransaction();
            
            $album->fotos()->update(['es_portada' => false]);
            $foto->es_portada = true;
            $foto->save();
            
            DB::commit();
            
            return redirect()->route('admin.albumes.index')
                ->with('success', 'Foto de portada actualizada.');
                
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al establecer la portada.');
        }
    }
}