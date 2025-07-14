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

    /**
     * @OA\Get(
     * path="/api/superadmin/institutions",
     * operationId="getInstitutionsList",
     * tags={"SuperAdmin - Instituciones"},
     * summary="Obtener lista de instituciones",
     * description="Retorna una lista de todas las instituciones con sus Puntos GOB asociados.",
     * security={{"sanctum": {}}},
     * @OA\Response(
     * response=200,
     * description="Operación exitosa",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(ref="#/components/schemas/InstitutionResource")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="No autenticado"
     * ),
     * @OA\Response(
     * response=403,
     * description="Acceso denegado (no es SuperAdmin)"
     * )
     * )
     */
    public function index()
    {
       
        $institutions = Institution::with('puntoGobs')->get();

        
        return InstitutionResource::collection($institutions);
    }

    /**
     * @OA\Post(
     * path="/api/superadmin/institutions",
     * operationId="storeInstitution",
     * tags={"SuperAdmin - Instituciones"},
     * summary="Crear una nueva institución",
     * description="Crea una nueva institución y opcionalmente la asocia con Puntos GOB existentes.",
     * security={{"sanctum": {}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"name", "status"},
     * @OA\Property(property="name", type="string", example="Ministerio de Educación", description="Nombre único de la institución"),
     * @OA\Property(property="phone", type="string", nullable=true, example="809-123-4567", description="Número de teléfono de la institución"),
     * @OA\Property(property="institutional_email", type="string", format="email", nullable=true, example="contacto@minedu.gob.do", description="Correo electrónico institucional"),
     * @OA\Property(property="contact_person_name", type="string", nullable=true, example="Ana García", description="Nombre de la persona de contacto"),
     * @OA\Property(property="status", type="string", enum={"Activo", "Inactivo", "Pendiente"}, example="Activo", description="Estado de la institución"),
     * @OA\Property(property="punto_gob_ids", type="array", @OA\Items(type="integer"), description="IDs de los Puntos GOB a asociar (opcional)")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Institución creada exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/InstitutionResource")
     * ),
     * @OA\Response(
     * response=422,
     * description="Errores de validación",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The given data was invalid."),
     * @OA\Property(property="errors", type="object")
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="No autenticado"
     * ),
     * @OA\Response(
     * response=403,
     * description="Acceso denegado (no es SuperAdmin)"
     * )
     * )
     */
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

    /**
     * @OA\Get(
     * path="/api/superadmin/institutions/{id}",
     * operationId="getInstitutionById",
     * tags={"SuperAdmin - Instituciones"},
     * summary="Obtener detalles de una institución",
     * description="Retorna los detalles de una institución específica por su ID.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID de la institución"
     * ),
     * @OA\Response(
     * response=200,
     * description="Operación exitosa",
     * @OA\JsonContent(ref="#/components/schemas/InstitutionResource")
     * ),
     * @OA\Response(
     * response=404,
     * description="Institución no encontrada"
     * ),
     * @OA\Response(
     * response=401,
     * description="No autenticado"
     * ),
     * @OA\Response(
     * response=403,
     * description="Acceso denegado (no es SuperAdmin)"
     * )
     * )
     */
    public function show(Institution $institution)
    {
        
        return new InstitutionResource($institution->load('puntoGobs'));
    }

    /**
     * @OA\Put(
     * path="/api/superadmin/institutions/{id}",
     * operationId="updateInstitution",
     * tags={"SuperAdmin - Instituciones"},
     * summary="Actualizar una institución",
     * description="Actualiza los datos de una institución existente y sus asociaciones con Puntos GOB.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID de la institución a actualizar"
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="name", type="string", example="Ministerio de Educación y Cultura", description="Nombre actualizado de la institución"),
     * @OA\Property(property="phone", type="string", nullable=true, example="809-765-4321", description="Número de teléfono actualizado"),
     * @OA\Property(property="institutional_email", type="string", format="email", nullable=true, example="contacto.nuevo@mineduc.gob.do", description="Correo electrónico actualizado"),
     * @OA\Property(property="contact_person_name", type="string", nullable=true, example="Pedro Martínez", description="Nombre de la persona de contacto actualizado"),
     * @OA\Property(property="status", type="string", enum={"Activo", "Inactivo", "Pendiente"}, example="Inactivo", description="Estado actualizado de la institución"),
     * @OA\Property(property="punto_gob_ids", type="array", @OA\Items(type="integer"), description="IDs de los Puntos GOB a asociar (sobrescribe los existentes)")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Institución actualizada exitosamente",
     * @OA\JsonContent(ref="#/components/schemas/InstitutionResource")
     * ),
     * @OA\Response(
     * response=422,
     * description="Errores de validación",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="The given data was invalid."),
     * @OA\Property(property="errors", type="object")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Institución no encontrada"
     * ),
     * @OA\Response(
     * response=401,
     * description="No autenticado"
     * ),
     * @OA\Response(
     * response=403,
     * description="Acceso denegado (no es SuperAdmin)"
     * )
     * )
     */
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

    /**
     * @OA\Delete(
     * path="/api/superadmin/institutions/{id}",
     * operationId="deleteInstitution",
     * tags={"SuperAdmin - Instituciones"},
     * summary="Eliminar una institución",
     * description="Elimina una institución por su ID. Esto también eliminará sus servicios y desasociará sus Puntos GOB.",
     * security={{"sanctum": {}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="ID de la institución a eliminar"
     * ),
     * @OA\Response(
     * response=200,
     * description="Institución eliminada exitosamente",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Institución eliminada correctamente.")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Institución no encontrada"
     * ),
     * @OA\Response(
     * response=401,
     * description="No autenticado"
     * ),
     * @OA\Response(
     * response=403,
     * description="Acceso denegado (no es SuperAdmin)"
     * )
     * )
     */
    public function destroy(Institution $institution)
    {
     
        $institution->delete();

        return response()->json(['message' => 'Institución eliminada correctamente.'], 200);
    }
}

