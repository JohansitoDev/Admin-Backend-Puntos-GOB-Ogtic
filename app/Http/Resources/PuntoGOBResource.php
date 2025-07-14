<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 * schema="PuntoGOBResource",
 * title="PuntoGOB Resource",
 * description="Representación de un Punto GOB en la API",
 * @OA\Property(property="id", type="integer", format="int64", description="ID del Punto GOB"),
 * @OA\Property(property="name", type="string", description="Nombre del Punto GOB (Ej. Sambil, Megacentro)"),
 * @OA\Property(property="location", type="string", description="Ubicación física del Punto GOB"),
 * @OA\Property(property="capacity", type="string", nullable=true, description="Descripción de la capacidad del Punto GOB"),
 * @OA\Property(property="status", type="string", enum={"Activo", "Inactivo", "Mantenimiento"}, description="Estado actual del Punto GOB"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Fecha y hora de creación"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Fecha y hora de la última actualización")
 * )
 */
class PuntoGOBResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
 public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'capacity' => $this->capacity,
            'status' => $this->status,
            'created_at' => $this->created_at ? $this->created_at->toDateTimeString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toDateTimeString() : null,
        ];
    }
}