<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    public $table = 'customers';
    protected $fillable = [
        'users_id',
        'location_id',
        'oauth_provider',
        'oauth_uid',
        'first_name',
        'last_name',
        'email',
        'phone',
        'purchase', 
        'state', 
    ];
    public function users()    {
        return $this->belongsTo(User::class);
    }
    public function locations()    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function reviews()    {
        return $this->hasMany(Review::class, 'customer_id');
    } 
    public function getFullnameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }
}
