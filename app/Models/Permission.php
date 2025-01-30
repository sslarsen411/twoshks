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
 * @property string $permission
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @method static Builder<static>|Permission newModelQuery()
 * @method static Builder<static>|Permission newQuery()
 * @method static Builder<static>|Permission query()
 * @method static Builder<static>|Permission whereCreatedAt($value)
 * @method static Builder<static>|Permission whereId($value)
 * @method static Builder<static>|Permission wherePermission($value)
 * @method static Builder<static>|Permission whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Permission extends Model {
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'roles_permissions');
    }
}
