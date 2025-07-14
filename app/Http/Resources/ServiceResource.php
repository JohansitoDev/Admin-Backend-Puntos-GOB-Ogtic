<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\InstitutionResource;

/**
 * @OA\Schema(
 * schema="ServiceResource",
 * title="Service Resource",
 * description="Representación de un Servicio en la API",
 * @OA\Property(property="id", type="integer", format="int64", description="ID del servicio"),
 * @OA\Property(property="name", type="string", description="Nombre del servicio"),
 * @OA\Property(property="description", type="string", nullable=true, description="Descripción del servicio"),
 * @OA\Property(property="is_active", type="boolean", description="Indica si el servicio está activo"),
 * @OA\Property(property="institution_id", type="integer", description="ID de la institución a la que pertenece el servicio"), // ¡NUEVO!
 * @OA\Property(property="institution", ref="#/components/schemas/InstitutionResource", description="Información de la institución a la que pertenece el servicio"), // ¡NUEVO!
 * @OA\Property(property="created_at", type="string", format="date-time", description="Fecha y hora de creación"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha y hora de la última actualización")
 * )
 */
class ServiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'institution_id' => $this->institution_id, 
            'institution' => new InstitutionResource($this->whenLoaded('institution')), 
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}