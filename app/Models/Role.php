<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property string $role
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Permission> $permissions
 * @property-read int|null $permissions_count
 * @method static Builder<static>|Role newModelQuery()
 * @method static Builder<static>|Role newQuery()
 * @method static Builder<static>|Role query()
 * @method static Builder<static>|Role whereCreatedAt($value)
 * @method static Builder<static>|Role whereId($value)
 * @method static Builder<static>|Role whereRole($value)
 * @method static Builder<static>|Role whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Role extends Model {
    protected $fillable = ['role'];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'roles_permissions');
    }
}
