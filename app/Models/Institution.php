<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 

class Institution extends Model 
{ 
    use HasFactory; 

    protected $fillable = [
        'name', 
        'slug',
        'phone', 
        'institutional_email', 
        'contact_person_name', 
        'status',
        'is_active', 
        'description',
         'is_active',
    ];

    public function users() 
    { 
        return $this->hasMany(User::class); 
    } 

    public function services() 
    { 
        return $this->hasMany(Service::class); 
    } 
    
    public function puntoGobs() 
    { 
        
        return $this->belongsToMany(PuntoGOB::class, 'institution_punto_gob', 'institution_id', 'punto_gob_id'); 
    } 
}


