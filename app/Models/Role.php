<?php

namespace App\Models;

use App\Models\Permissions;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
   protected $fillable = ['role'];
   public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }
}
