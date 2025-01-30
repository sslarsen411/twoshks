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
 * @property int $subscription_id
 * @property string $stripe_id
 * @property string $stripe_product
 * @property string $stripe_price
 * @property int|null $quantity
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Subscription|null $subscription
 * @method static Builder<static>|SubscriptionItem newModelQuery()
 * @method static Builder<static>|SubscriptionItem newQuery()
 * @method static Builder<static>|SubscriptionItem query()
 * @method static Builder<static>|SubscriptionItem whereCreatedAt($value)
 * @method static Builder<static>|SubscriptionItem whereId($value)
 * @method static Builder<static>|SubscriptionItem whereQuantity($value)
 * @method static Builder<static>|SubscriptionItem whereStripeId($value)
 * @method static Builder<static>|SubscriptionItem whereStripePrice($value)
 * @method static Builder<static>|SubscriptionItem whereStripeProduct($value)
 * @method static Builder<static>|SubscriptionItem whereSubscriptionId($value)
 * @method static Builder<static>|SubscriptionItem whereUpdatedAt($value)
 * @mixin Eloquent
 */
class SubscriptionItem extends Model {
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
