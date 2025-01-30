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
 * @property int $user_id
 * @property string $type
 * @property string $stripe_id
 * @property string $stripe_status
 * @property string|null $stripe_price
 * @property int|null $quantity
 * @property string|null $trial_ends_at
 * @property string|null $ends_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User|null $users
 * @method static Builder<static>|Subscription newModelQuery()
 * @method static Builder<static>|Subscription newQuery()
 * @method static Builder<static>|Subscription query()
 * @method static Builder<static>|Subscription whereCreatedAt($value)
 * @method static Builder<static>|Subscription whereEndsAt($value)
 * @method static Builder<static>|Subscription whereId($value)
 * @method static Builder<static>|Subscription whereQuantity($value)
 * @method static Builder<static>|Subscription whereStripeId($value)
 * @method static Builder<static>|Subscription whereStripePrice($value)
 * @method static Builder<static>|Subscription whereStripeStatus($value)
 * @method static Builder<static>|Subscription whereTrialEndsAt($value)
 * @method static Builder<static>|Subscription whereType($value)
 * @method static Builder<static>|Subscription whereUpdatedAt($value)
 * @method static Builder<static>|Subscription whereUserId($value)
 * @mixin Eloquent
 */
class Subscription extends Model {
    protected $fillable = [
        'users_id',
        'type',
        'stripe_id',
        'stripe_status',
        'stripe_price',
        'trial_ends_at',
        'ends_at',
        'created_at',
        'updated_at',
    ];

    public function users(): belongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

}
