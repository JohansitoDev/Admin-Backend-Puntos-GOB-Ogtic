<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // GET /api/services
    public function index()
    {
        return Service::all();
    }

    // POST /api/services
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        return Service::create($validated);
    }

    // GET /api/services/{id}
    public function show($id)
    {
        return Service::findOrFail($id);
    }

    // PUT /api/services/{id}
    public function update(Request $request, $id)
    {
        $service = Service::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string'
        ]);

        $service->update($validated);
        return $service;
    }

    // Eliminar /api/services/{id}
    public function destroy($id)
    {
        Service::findOrFail($id)->delete();
        return response()->json(null, 204);
    }

    // Ejemplo de método personalizado: GET /api/services/search/{name}
    public function search($name)
    {
        return Service::where('name', 'like', '%'.$name.'%')->get();
    }

    // Ejemplo de método personalizado: POST /api/services/{id}/activate
    public function activate($id)
    {
        $service = Service::findOrFail($id);
        $service->update(['active' => true]);
        return $service;
    }
}