<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\PuntoGOB; 
use App\Http\Resources\PuntoGOBResource; 
use App\Http\Requests\StorePuntoGOBRequest; 
use App\Http\Requests\UpdatePuntoGOBRequest; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate; 

/**
 * @OA\Tag(
 * name="SuperAdmin - Puntos GOB",
 * description="Gestión de Puntos GOB por el SuperAdministrador"
 * )
 */
class PuntoGOBController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); 
        $this->middleware('can:manage-punto-gobs'); 
    }

    /**
     * @OA\Get(
     * path="/api/superadmin/punto-gobs",
     * operationId="getPuntoGOBsList",
     * tags={"SuperAdmin - Puntos GOB"},
     * summary="Obtener lista de Puntos GOB",
     * description="Retorna una lista de todos los Puntos GOB con sus instituciones asociadas.",
     * security={{"sanctum": {}}},
     * @OA\Response(
     * response=200,
     * description="Operación exitosa",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/PuntoGOBResource")
     * )
     * ),
     * @OA\Response(response=401, description="No autenticado"),
     * @OA\Response(response=403, description="Acceso denegado (el usuario no tiene el permiso 'manage-punto-gobs')")
     * )
     */
    public function index()
    {
        // Recupera todos los Puntos GOB y precarga la relación con su institución
        $puntoGobs = PuntoGOB::with('institution')->get();
        return PuntoGOBResource::collection($puntoGobs);
    }

    /**
     * @OA\Post(
     * path="/api/superadmin/punto-gobs",
     * operationId="storePuntoGOB",
     * tags={"SuperAdmin - Puntos GOB"},
     * summary="Crear un nuevo Punto GOB",
     * description="Crea un nuevo Punto GOB en el sistema y lo asocia a una institución. Requiere permiso 'manage-punto-gobs'.",
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name", "institution_id"},
     * @OA\Property(property="name", type="string", example="Punto GOB Centro", description="Nombre único del Punto GOB"),
     * @OA\Property(property="address", type="string", example="Calle Falsa 123", nullable=true, description="Dirección del Punto GOB"),
     * @OA\Property(property="phone", type="string", example="809-555-1234", nullable=true, description="Teléfono del Punto GOB"),
     * @OA\Property(property="email", type="string", format="email", example="centro@puntogob.gob", nullable=true, description="Correo electrónico del Punto GOB"),
     * @OA\Property(property="is_active", type="boolean", example="true", description="Estado de actividad del Punto GOB"),
     * @OA\Property(property="institution_id", type="integer", example="1", description="ID de la institución a la que pertenece el Punto GOB")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Punto GOB creado exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/PuntoGOBResource")
     * ),
     * @OA\Response(response=422, description="Errores de validación"),
     * @OA\Response(response=401, description="No autenticado"),
     * @OA\Response(response=403, description="Acceso denegado")
     * )
     */
    public function store(StorePuntoGOBRequest $request)
    {
        // Los datos ya están validados por StorePuntoGOBRequest
        $puntoGob = PuntoGOB::create($request->validated());
        // Carga la relación 'institution' para que el Resource la incluya en la respuesta
        return new PuntoGOBResource($puntoGob->load('institution'));
    }

    /**
     * @OA\Get(
     * path="/api/superadmin/punto-gobs/{id}",
     * operationId="getPuntoGOBById",
     * tags={"SuperAdmin - Puntos GOB"},
     * summary="Obtener detalles de un Punto GOB",
     * description="Retorna los detalles de un Punto GOB específico por su ID con su institución asociada. Requiere permiso 'manage-punto-gobs'.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID del Punto GOB"
     * ),
     * @OA\Response(
     * response=200,
     * description="Operación exitosa",
     * @OA\JsonContent(ref="#/components/schemas/PuntoGOBResource")
     * ),
     * @OA\Response(response=404, description="Punto GOB no encontrado"),
     * @OA\Response(response=401, description="No autenticado"),
     * @OA\Response(response=403, description="Acceso denegado")
     * )
     */
    public function show(PuntoGOB $puntoGob) // Route Model Binding inyecta el PuntoGOB
    {
        // Carga la relación 'institution' para que el Resource la incluya en la respuesta
        return new PuntoGOBResource($puntoGob->load('institution'));
    }

    /**
     * @OA\Put(
     * path="/api/superadmin/punto-gobs/{id}",
     * operationId="updatePuntoGOB",
     * tags={"SuperAdmin - Puntos GOB"},
     * summary="Actualizar un Punto GOB",
     * description="Actualiza los datos de un Punto GOB existente y su institución asociada. Requiere permiso 'manage-punto-gobs'.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID del Punto GOB a actualizar"
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="name", type="string", example="Punto GOB Sur", description="Nombre actualizado del Punto GOB"),
     * @OA\Property(property="address", type="string", example="Avenida Siempre Viva 742", nullable=true, description="Dirección actualizada"),
     * @OA\Property(property="phone", type="string", example="809-555-4321", nullable=true, description="Teléfono actualizado"),
     * @OA\Property(property="email", type="string", format="email", example="sur@puntogob.gob", nullable=true, description="Correo electrónico actualizado"),
     * @OA\Property(property="is_active", type="boolean", example="false", description="Estado de actividad actualizado"),
     * @OA\Property(property="institution_id", type="integer", example="2", description="ID de la institución asociada (se puede cambiar)")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Punto GOB actualizado exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/PuntoGOBResource")
     * ),
     * @OA\Response(response=422, description="Errores de validación"),
     * @OA\Response(response=404, description="Punto GOB no encontrado"),
     * @OA\Response(response=401, description="No autenticado"),
     * @OA\Response(response=403, description="Acceso denegado")
     * )
     */
    public function update(UpdatePuntoGOBRequest $request, PuntoGOB $puntoGob) // Route Model Binding
    {
        $puntoGob->update($request->validated());
        return new PuntoGOBResource($puntoGob->load('institution'));
    }

    /**
     * @OA\Delete(
     * path="/api/superadmin/punto-gobs/{id}",
     * operationId="deletePuntoGOB",
     * tags={"SuperAdmin - Puntos GOB"},
     * summary="Eliminar un Punto GOB",
     * description="Elimina un Punto GOB por su ID. Requiere permiso 'manage-punto-gobs'.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID del Punto GOB a eliminar"
     * ),
     * @OA\Response(
     * response=200,
     * description="Punto GOB eliminado exitosamente",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Punto GOB eliminado correctamente.")
     * )
     * ),
     * @OA\Response(response=404, description="Punto GOB no encontrado"),
     * @OA\Response(response=401, description="No autenticado"),
     * @OA\Response(response=403, description="Acceso denegado")
     * )
     */
    public function destroy(PuntoGOB $puntoGob) // Route Model Binding
    {
        $puntoGob->delete();
        return response()->json(['message' => 'Punto GOB eliminado correctamente.'], 200);
    }
}