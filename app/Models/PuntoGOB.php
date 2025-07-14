<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PuntoGOB extends Model
{
    use HasFactory;

    protected $table = 'punto_gobs'; 

    protected $fillable = [
        'name',
        'location',
        'capacity',
        'status',
    ];

    public function institutions()
    {
        
        return $this->belongsToMany(Institution::class, 'institution_punto_gob', 'punto_gob_id', 'institution_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}


