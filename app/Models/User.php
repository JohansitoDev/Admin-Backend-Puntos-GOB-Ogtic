<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; // ¡IMPORTA ESTO!

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles; // ¡AÑADE ESTA LÍNEA!

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'identification_number',
        'sex',
        // 'role', // Puedes mantenerlo o quitarlo si solo usarás Spatie para los roles
        'is_active',
        'institution_id',
        'punto_gob_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relaciones (si no las tienes aún, es buena idea agregarlas para claridad y uso de Eloquent)
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function puntoGob()
    {
        return $this->belongsTo(PuntoGOB::class);
    }
}