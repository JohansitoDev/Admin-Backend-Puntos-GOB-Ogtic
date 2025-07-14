<?php namespace App\Models; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Database\Eloquent\Model;
  class SupportTicket extends Model {
     use HasFactory; 
     protected $fillable = ['description', 'user_id', 'assigned_to_user_id', 'priority', 'status', 'closed_at',];
      protected $casts = ['closed_at' => 'datetime',]; 
      public function creator() { 
        return $this->belongsTo(User::class, 'user_id');
     } public function assignee() { 
        return $this->belongsTo(User::class, 'assigned_to_user_id');
     
    }
 }

 