<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return Service::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        return Service::create($validated);
    }

    public function show($id)
    {
        return Service::findOrFail($id);
    }

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

    public function destroy($id)
    {
        Service::findOrFail($id)->delete();
        return response()->json(null, 204);
    }

    public function search($name)
    {
        return Service::where('name', 'like', '%'.$name.'%')->get();
    }

    public function activate($id)
    {
        $service = Service::findOrFail($id);
        $service->update(['active' => true]);
        return $service;
    }
}