<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property int $users_id
 * @property int $location_id
 * @property string $link
 * @property int $clicks
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Location $locations
 * @property-read User $users
 * @method static Builder<static>|LocationLink newModelQuery()
 * @method static Builder<static>|LocationLink newQuery()
 * @method static Builder<static>|LocationLink query()
 * @method static Builder<static>|LocationLink whereClicks($value)
 * @method static Builder<static>|LocationLink whereCreatedAt($value)
 * @method static Builder<static>|LocationLink whereId($value)
 * @method static Builder<static>|LocationLink whereLink($value)
 * @method static Builder<static>|LocationLink whereLocationId($value)
 * @method static Builder<static>|LocationLink whereUpdatedAt($value)
 * @method static Builder<static>|LocationLink whereUsersId($value)
 * @mixin Eloquent
 */
class LocationLink extends Model {
    protected $fillable = [
        'users_id',
        'location_id',
        'link',
    ];

    public function users(): belongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function locations(): belongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }


}
