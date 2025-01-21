<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model {
    public $table = 'reviews';
    //public mixed $id;
    //  public mixed $id = null;
    protected $fillable = [
        'users_id',
        'customer_id',
        'location_id',
        'rate',
        'answers',
        'review',
        'status'
    ];

    public function users(): belongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function locations(): belongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function customers(): belongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
