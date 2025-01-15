<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    public $table = 'reviews';
    protected $fillable = [
        'users_id',
        'customer_id',
        'location_id',
        'rate',
        'answers',
        'review',
        'status'       
    ];    
    public function users()    {
        return $this->belongsTo(User::class, 'users_id');
    }
    public function locations()    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function customers()    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
