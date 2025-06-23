<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\Request;
use App\Models\User;

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
     * Mostrar el formulario para crear un nuevo servicio.
     */
    public function create()
    {
        return view('admin.servicios.create');
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
            'duracion' => 'required|integer|in:30,60,90,120,180',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $servicio = new Servicio();
        $servicio->nombre = $request->nombre;
        $servicio->descripcion = $request->descripcion;
        $servicio->precio = $request->precio;
        $servicio->categoria = $request->categoria;
        $servicio->subcategoria = $request->subcategoria;
        $servicio->duracion = $request->duracion;

        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('servicios', 'public');
            $servicio->imagen = 'storage/' . $rutaImagen;
        }

        $servicio->save();

        return redirect('/servicios')->with('success', 'Servicio guardado exitosamente.');
    }

    /**
     * Vista del admin con la lista de servicios.
     */
    public function adminIndex()
    {
        $servicios = Servicio::orderBy('categoria')->orderBy('subcategoria')->orderBy('nombre')->get();
        return view('admin.servicios.index', compact('servicios'));
    }

    /**
     * Eliminar un servicio.
     */
    public function destroy(Servicio $servicio)
    {
        $servicio->delete();
        return redirect()->route('admin.servicios.index')->with('success', 'Servicio eliminado correctamente.');
    }

    /**
     * Editar un servicio existente.
     */
    public function edit($id)
    {
        $servicio = Servicio::findOrFail($id);
        $profesionales = User::role('profesional')->get();

        return view('admin.servicios.edit', compact('servicio', 'profesionales'));
    }

    /**
     * Actualizar un servicio existente.
     */
    public function update(Request $request, $id)
    {
        $servicio = Servicio::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'subcategoria' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string',
            'duracion' => 'required|integer|in:30,60,90,120,180',
            'imagen' => 'nullable|image|max:2048',
            'profesionales' => 'nullable|array',
            'profesionales.*' => 'exists:users,id',
        ]);

        $servicio->nombre = $request->nombre;
        $servicio->categoria = $request->categoria;
        $servicio->subcategoria = $request->subcategoria;
        $servicio->precio = $request->precio;
        $servicio->descripcion = $request->descripcion;
        $servicio->duracion = $request->duracion;

        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('servicios', 'public');
            $servicio->imagen = 'storage/' . $rutaImagen;
        }

        $servicio->save();

        // Actualiza la relación muchos a muchos
        $servicio->profesionales()->sync($request->input('profesionales', []));

        return redirect()->route('admin.servicios.index')->with('success', 'Servicio actualizado correctamente.');
    }
}
