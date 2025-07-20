<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\PuntoGOB; 
use Illuminate\Http\Request;
use App\Http\Resources\InstitutionResource; 
use Illuminate\Support\Facades\Gate; 
use Illuminate\Validation\ValidationException; 

class InstitutionController extends Controller
{
    public function __construct()
    {
        
        $this->middleware('can:manage-all');
    }

    public function index()
    {
       
        $institutions = Institution::with('puntoGobs')->get();

        
        return InstitutionResource::collection($institutions);
    }


    public function store(Request $request)
    {
       
        $request->validate([
            'name' => 'required|string|max:255|unique:institutions,name',
            'phone' => 'nullable|string|max:50',
            'institutional_email' => 'nullable|email|max:255',
            'contact_person_name' => 'nullable|string|max:255',
            'status' => 'required|in:Activo,Inactivo,Pendiente',
            'punto_gob_ids' => 'array', 
            'punto_gob_ids.*' => 'exists:punto_gobs,id', 
        ]);

    
        $institution = Institution::create($request->all());

        
        if ($request->has('punto_gob_ids')) {
            $institution->puntoGobs()->sync($request->punto_gob_ids);
        }

        
        return new InstitutionResource($institution->load('puntoGobs'));
    }


    public function show(Institution $institution)
    {
        
        return new InstitutionResource($institution->load('puntoGobs'));
    }

    
    public function update(Request $request, Institution $institution)
    {
        $request->validate([
            
            'name' => 'required|string|max:255|unique:institutions,name,' . $institution->id,
            'phone' => 'nullable|string|max:50',
            'institutional_email' => 'nullable|email|max:255',
            'contact_person_name' => 'nullable|string|max:255',
            'status' => 'required|in:Activo,Inactivo,Pendiente',
            'punto_gob_ids' => 'array',
            'punto_gob_ids.*' => 'exists:punto_gobs,id',
        ]);

        
        $institution->update($request->all());

       
        if ($request->has('punto_gob_ids')) {
            $institution->puntoGobs()->sync($request->punto_gob_ids);
        } else {
           
            $institution->puntoGobs()->detach();
        }

      
        return new InstitutionResource($institution->load('puntoGobs'));
    }


    public function destroy(Institution $institution)
    {
     
        $institution->delete();

        return response()->json(['message' => 'Institución eliminada correctamente.'], 200);
    }
}

