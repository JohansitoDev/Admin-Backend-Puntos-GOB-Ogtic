<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 * schema="InstitutionResource",
 * title="Institution Resource",
 * description="Representación de una institución en la API",
 * @OA\Property(property="id", type="integer", format="int64", description="ID de la institución"),
 * @OA\Property(property="name", type="string", description="Nombre de la institución"),
 * @OA\Property(property="phone", type="string", nullable=true, description="Teléfono de contacto de la institución"),
 * @OA\Property(property="institutional_email", type="string", format="email", nullable=true, description="Correo electrónico institucional"),
 * @OA\Property(property="contact_person_name", type="string", nullable=true, description="Nombre de la persona de contacto"),
 * @OA\Property(property="status", type="string", enum={"Activo", "Inactivo", "Pendiente"}, description="Estado actual de la institución"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Fecha y hora de creación"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha y hora de la última actualización"),
 * @OA\Property(
 * property="punto_gobs",
 * type="array",
 * description="Puntos GOB asociados a esta institución (solo cuando se cargan explícitamente)",
 * @OA\Items(ref="#/components/schemas/PuntoGOBResource")
 * )
 * )
 */
class InstitutionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'institutional_email' => $this->institutional_email,
            'contact_person_name' => $this->contact_person_name,
            'status' => $this->status,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
         
            'punto_gobs' => PuntoGOBResource::collection($this->whenLoaded('puntoGobs')),
        ];
    }
}