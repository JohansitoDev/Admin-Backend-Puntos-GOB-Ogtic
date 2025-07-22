<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePuntoGOBRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage-all') || $this->user()->can('manage-punto-gobs');
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:punto_gobs,name',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:255|unique:punto_gobs,email',
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