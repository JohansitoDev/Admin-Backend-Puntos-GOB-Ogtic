<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles; 

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles; 

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'identification_number',
        'sex',
        // 'role', 
        'is_active',
        'institution_id',
        'punto_gob_id',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

 
    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function puntoGob()
    {
        return $this->belongsTo(PuntoGOB::class);
    }
}