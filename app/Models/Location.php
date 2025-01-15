<?php

namespace App\Models;


use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;
    
    public $table = 'locations';
    protected $fillable = [
        'users_id', 
        'addr',
        'city',
        'state',
        'zip',
        'loc_phone',
        'loc_email',
        'CID',
        'PID',
        'init_rate',
        'init_rct',
    ];

    public function users(){
        return $this->belongsTo(User::class, 'users_id');
    }    
    public function links(){
        return $this->hasOne(LocationLink::class);
    }
    public function customers(){
        return $this->hasMany(Customer::class);
    }
    public function reviews(){
        return $this->hasMany(Review::class);
    }
}
