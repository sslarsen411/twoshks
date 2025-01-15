<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company',
        'phone',
        'mobile',
        'min_rate',
        'multi_loc',
        'loc_qty',
        'support_email',
        'role'
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function locations()    {
        return $this->hasMany(Location::class, 'users_id');
    }
    public function links()    {
        return $this->hasMany(LocationLink::class, 'users_id');
    }
    public function customers()    {
        return $this->hasMany(Customer::class, 'users_id');
    }
    public function reviews()    {
        return $this->hasMany(Review::class, 'users_id');
    }

    
}
