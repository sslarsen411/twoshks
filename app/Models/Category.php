<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Category extends Model
{
    public $table = 'categories';
    protected $fillable = [
        'category',
        'slug',
    ];

    public function questions(): HasOne
    {
        return $this->hasOne(Question::class);
    }
}
