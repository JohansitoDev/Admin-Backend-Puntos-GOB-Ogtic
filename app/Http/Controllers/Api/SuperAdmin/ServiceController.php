<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Http\Resources\ServiceResource;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:manage-all');
    }

    // ... Anotación @OA\Get para index (actualiza el schema para incluir institution_id e institution) ...
    /**
     * @OA\Get(
     * path="/api/superadmin/services",
     * operationId="getServicesList",
     * tags={"SuperAdmin - Servicios"},
     * summary="Obtener lista de servicios",
     * description="Retorna una lista de todos los servicios con sus instituciones asociadas.",
     * security={{"sanctum": {}}},
     * @OA\Response(
     * response=200,
     * description="Operación exitosa",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/ServiceResource")
     * )
     * ),
     * @OA\Response(response=401, description="No autenticado"),
     * @OA\Response(response=403, description="Acceso denegado (no es SuperAdmin o no tiene permiso)")
     * )
     */
    public function index()
    {
        $services = Service::with('institution')->get(); // ¡NUEVO! Carga la relación 'institution'
        return ServiceResource::collection($services);
    }

    // ... Anotación @OA\Post para store (actualiza el body para incluir institution_id) ...
    /**
     * @OA\Post(
     * path="/api/superadmin/services",
     * operationId="storeService",
     * tags={"SuperAdmin - Servicios"},
     * summary="Crear un nuevo servicio",
     * description="Crea un nuevo servicio en el sistema y lo asocia a una institución.",
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name", "institution_id"},
     * @OA\Property(property="name", type="string", example="Renovación Cédula", description="Nombre único del servicio"),
     * @OA\Property(property="description", type="string", nullable=true, example="Proceso de renovación de documento de identidad.", description="Descripción del servicio"),
     * @OA\Property(property="is_active", type="boolean", example="true", description="Estado de actividad del servicio"),
     * @OA\Property(property="institution_id", type="integer", example="1", description="ID de la institución a la que pertenece el servicio") // ¡NUEVO!
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Servicio creado exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/ServiceResource")
     * ),
     * @OA\Response(response=422, description="Errores de validación"),
     * @OA\Response(response=401, description="No autenticado"),
     * @OA\Response(response=403, description="Acceso denegado")
     * )
     */
    public function store(StoreServiceRequest $request)
    {
        $service = Service::create($request->validated());
        return new ServiceResource($service->load('institution')); 
    }

    // ... Anotación @OA\Get para show (actualiza el schema para incluir institution_id e institution) ...
    /**
     * @OA\Get(
     * path="/api/superadmin/services/{id}",
     * operationId="getServiceById",
     * tags={"SuperAdmin - Servicios"},
     * summary="Obtener detalles de un servicio",
     * description="Retorna los detalles de un servicio específico por su ID con su institución asociada.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID del servicio"
     * ),
     * @OA\Response(
     * response=200,
     * description="Operación exitosa",
     * @OA\JsonContent(ref="#/components/schemas/ServiceResource")
     * ),
     * @OA\Response(response=404, description="Servicio no encontrado"),
     * @OA\Response(response=401, description="No autenticado"),
     * @OA\Response(response=403, description="Acceso denegado")
     * )
     */
    public function show(Service $service)
    {
        return new ServiceResource($service->load('institution')); // ¡NUEVO! Carga la relación
    }

    // ... Anotación @OA\Put para update (actualiza el body para incluir institution_id) ...
    /**
     * @OA\Put(
     * path="/api/superadmin/services/{id}",
     * operationId="updateService",
     * tags={"SuperAdmin - Servicios"},
     * summary="Actualizar un servicio",
     * description="Actualiza los datos de un servicio existente y su institución asociada.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID del servicio a actualizar"
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="name", type="string", example="Renovación Cédula Nueva", description="Nombre actualizado del servicio"),
     * @OA\Property(property="description", type="string", nullable=true, example="Descripción actualizada del servicio.", description="Descripción actualizada"),
     * @OA\Property(property="is_active", type="boolean", example="false", description="Estado de actividad actualizado"),
     * @OA\Property(property="institution_id", type="integer", example="2", description="ID de la institución a la que pertenece el servicio (se puede cambiar)") // ¡NUEVO!
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Servicio actualizado exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/ServiceResource")
     * ),
     * @OA\Response(response=422, description="Errores de validación"),
     * @OA\Response(response=404, description="Servicio no encontrado"),
     * @OA\Response(response=401, description="No autenticado"),
     * @OA\Response(response=403, description="Acceso denegado")
     * )
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $service->update($request->validated());
        return new ServiceResource($service->load('institution')); 
    }

    
}