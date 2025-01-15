<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationLink extends Model
{
    use HasFactory;
    protected $fillable = [
        'users_id', 
        'location_id',
        'link', 
    ];

    public function users(){
        return $this->belongsTo(User::class, 'users_id');
    }    
    public function locations(){
        return $this->belongsTo(Location::class, 'location_id');
    }    


}
