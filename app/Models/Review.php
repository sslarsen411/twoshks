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
 * @property int $customer_id
 * @property int $location_id
 * @property string $rate
 * @property string|null $answers
 * @property string|null $review
 * @property string $status
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property-read Customer $customers
 * @property-read Location $locations
 * @property-read User $users
 * @method static Builder<static>|Review newModelQuery()
 * @method static Builder<static>|Review newQuery()
 * @method static Builder<static>|Review query()
 * @method static Builder<static>|Review whereAnswers($value)
 * @method static Builder<static>|Review whereCreatedAt($value)
 * @method static Builder<static>|Review whereCustomerId($value)
 * @method static Builder<static>|Review whereId($value)
 * @method static Builder<static>|Review whereLocationId($value)
 * @method static Builder<static>|Review whereRate($value)
 * @method static Builder<static>|Review whereReview($value)
 * @method static Builder<static>|Review whereStatus($value)
 * @method static Builder<static>|Review whereUpdatedAt($value)
 * @method static Builder<static>|Review whereUsersId($value)
 * @mixin Eloquent
 */
class Review extends Model {
    public $table = 'reviews';
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
