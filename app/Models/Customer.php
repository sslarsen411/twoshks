<?php

namespace App\Models;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

//use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model {

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

    public function users(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function locations(): belongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function reviews(): hasMany
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    public function getFullnameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }
}
