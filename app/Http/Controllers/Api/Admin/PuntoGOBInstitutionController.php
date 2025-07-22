<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\PuntoGOB;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PuntoGOBInstitutionController extends Controller
{
    public function __construct()
    {
       
        $this->middleware('can:is-admin');
    }

    /**
     * @param  \App\Models\PuntoGOB  $puntoGob
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(PuntoGOB $puntoGob)
    {
        $user = Auth::user();

   
        if ($user->punto_gob_id !== $puntoGob->id && $user->cannot('manage-all')) {
            return response()->json(['message' => 'No autorizado para ver las instituciones de este Punto GOB.'], 403);
        }

        return response()->json([
            'message' => 'Instituciones asociadas al Punto GOB.',
            'institutions' => $puntoGob->institutions,
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PuntoGOB  
     * @return \Illuminate\Http\JsonResponse
     */
    public function attach(Request $request, PuntoGOB $puntoGob)
    {
        $user = Auth::user();


        if ($user->punto_gob_id !== $puntoGob->id && $user->cannot('manage-all')) {
            return response()->json(['message' => 'No autorizado para asociar instituciones a este Punto GOB.'], 403);
        }

        $request->validate([
            'institution_id' => [
                'required',
                'exists:institutions,id',
           
                Rule::unique('institution_punto_gob')->where(function ($query) use ($puntoGob) {
                    return $query->where('punto_gob_id', $puntoGob->id);
                }),
            ],
        ], [
            'institution_id.unique' => 'Esta institución ya está asociada a este Punto GOB.',
        ]);

        $puntoGob->institutions()->attach($request->institution_id);

        return response()->json(['message' => 'Institución asociada exitosamente al Punto GOB.'], 200);
    }

    /**
     * @param  \App\Models\PuntoGOB  
     * @param  \App\Models\Institution  
     * @return \Illuminate\Http\JsonResponse
     */
    public function detach(PuntoGOB $puntoGob, Institution $institution)
    {
        $user = Auth::user();


        if ($user->punto_gob_id !== $puntoGob->id && $user->cannot('manage-all')) {
            return response()->json(['message' => 'No autorizado para desasociar instituciones de este Punto GOB.'], 403);
        }

        if (!$puntoGob->institutions()->where('institution_id', $institution->id)->exists()) {
            return response()->json(['message' => 'La institución no está asociada a este Punto GOB.'], 404);
        }

        $puntoGob->institutions()->detach($institution->id);

        return response()->json(['message' => 'Institución desasociada exitosamente del Punto GOB.'], 200);
    }
}