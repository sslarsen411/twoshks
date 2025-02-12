<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BusinessCategory extends Model
{
    use Sluggable;
    protected static $model = BusinessCategory::class;
    protected $fillable = [
        'category',
        'slug',
    ];

    public function users(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'category'
            ]
        ];
    }
}
