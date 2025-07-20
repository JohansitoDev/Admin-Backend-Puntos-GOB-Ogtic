<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PuntoGOB; 
use App\Http\Resources\PuntoGOBResource; 
use App\Http\Requests\StorePuntoGOBRequest; 
use App\Http\Requests\UpdatePuntoGOBRequest; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate; 


class PuntoGOBController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); 
        $this->middleware('can:manage-punto-gobs'); 
    }


    public function index()
    {
       
        $puntoGobs = PuntoGOB::with('institution')->get();
        return PuntoGOBResource::collection($puntoGobs);
    }


    public function store(StorePuntoGOBRequest $request)
    {
       
        $puntoGob = PuntoGOB::create($request->validated());
     
        return new PuntoGOBResource($puntoGob->load('institution'));
    }

  
    public function show(PuntoGOB $puntoGob) 
    {
       
        return new PuntoGOBResource($puntoGob->load('institution'));
    }

    public function update(UpdatePuntoGOBRequest $request, PuntoGOB $puntoGob) 
    {
        $puntoGob->update($request->validated());
        return new PuntoGOBResource($puntoGob->load('institution'));
    }

   
    public function destroy(PuntoGOB $puntoGob) 
    {
        $puntoGob->delete();
        return response()->json(['message' => 'Punto GOB eliminado correctamente.'], 200);
    }
}