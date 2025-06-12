<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;

class ServiciosController extends Controller
{
    /**
     * Mostrar la vista principal de servicios, agrupados por categoría y subcategoría.
     */
        public function index()
    {
        $serviciosRaw = Servicio::select('categoria', 'subcategoria', 'nombre', 'descripcion', 'precio')
            ->distinct()
            ->get();

        $servicios = $serviciosRaw
            ->groupBy('categoria')
            ->map(function ($grupo) {
                return $grupo->groupBy('subcategoria');
            });

        return view('servicios.index', compact('servicios'));
    }


    /**
     * Mostrar la vista individual de un servicio específico.
     */
    public function show(Servicio $servicio)
    {
        return view('servicios.show', compact('servicio'));
    }

    /**
     * Guardar un nuevo servicio desde un formulario.
     */
    public function guardarServicio(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'nullable|numeric|min:0',
            'categoria' => 'required|string|max:255',
            'subcategoria' => 'required|string|max:255',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $servicio = new Servicio();
        $servicio->nombre = $request->input('nombre');
        $servicio->descripcion = $request->input('descripcion');
        $servicio->precio = $request->input('precio');
        $servicio->categoria = $request->input('categoria');
        $servicio->subcategoria = $request->input('subcategoria');

        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('public/servicios');
            $servicio->imagen = str_replace('public/', 'storage/', $rutaImagen); // acceso público
        }
        
        $servicio->save();

        return redirect('/servicios')->with('success', 'Servicio guardado exitosamente.');
    }

        public function create()
    {
        return view('admin.servicios.create');
    }


    public function adminIndex()
{
    $servicios = Servicio::orderBy('categoria')->orderBy('subcategoria')->orderBy('nombre')->get();
    return view('admin.servicios.index', compact('servicios'));
}

public function destroy(Servicio $servicio)
{
    $servicio->delete();
    return redirect()->route('admin.servicios.index')->with('success', 'Servicio eliminado correctamente.');
}

public function edit($id)
{
    $servicio = Servicio::findOrFail($id);
    return view('admin.servicios.edit', compact('servicio'));
}


public function update(Request $request, $id)
{
    $servicio = Servicio::findOrFail($id);

    $request->validate([
        'nombre' => 'required|string|max:255',
        'categoria' => 'required|string|max:255',
        'subcategoria' => 'required|string|max:255',
        'precio' => 'required|numeric|min:0',
        'descripcion' => 'nullable|string',
        'imagen' => 'nullable|image|max:2048',
    ]);

    $servicio->nombre = $request->nombre;
    $servicio->categoria = $request->categoria;
    $servicio->subcategoria = $request->subcategoria;
    $servicio->precio = $request->precio;
    $servicio->descripcion = $request->descripcion;

    if ($request->hasFile('imagen')) {
        $servicio->imagen = $request->file('imagen')->store('servicios', 'public');
    }

    $servicio->save();

    return redirect()->route('admin.servicios.index')->with('success', 'Servicio actualizado correctamente.');
}


}
