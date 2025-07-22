<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePuntoGOBRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-all') || $this->user()->can('manage-punto-gobs');
    }

    public function rules(): array
    {
        $puntoGobId = $this->route('punto_gob') ? $this->route('punto_gob')->id : null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('punto_gobs')->ignore($puntoGobId),
            ],
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('punto_gobs')->ignore($puntoGobId),
            ],
            'is_active' => 'boolean',
     
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Ya existe un Punto GOB con este nombre.',
            'name.required' => 'El nombre del Punto GOB es obligatorio.',
            'email.unique' => 'Este correo electrónico ya está en uso por otro Punto GOB.',
 
        ];
    }
}