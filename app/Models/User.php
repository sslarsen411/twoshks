<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $company
 * @property string|null $phone
 * @property string|null $mobile
 * @property string $min_rate
 * @property int $multi_loc
 * @property string $loc_qty
 * @property string|null $support_email
 * @property string|null $stripe_id
 * @property string|null $pm_type
 * @property string|null $pm_last_four
 * @property string|null $trial_ends_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Customer> $customers
 * @property-read int|null $customers_count
 * @property-read Collection<int, LocationLink> $links
 * @property-read int|null $links_count
 * @property-read Collection<int, Location> $locations
 * @property-read int|null $locations_count
 * @property-read DatabaseNotificationCollection<int, DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read Collection<int, Review> $reviews
 * @property-read int|null $reviews_count
 * @method static UserFactory factory($count = null, $state = [])
 * @method static Builder<static>|User newModelQuery()
 * @method static Builder<static>|User newQuery()
 * @method static Builder<static>|User query()
 * @method static Builder<static>|User whereCompany($value)
 * @method static Builder<static>|User whereCreatedAt($value)
 * @method static Builder<static>|User whereEmail($value)
 * @method static Builder<static>|User whereId($value)
 * @method static Builder<static>|User whereLocQty($value)
 * @method static Builder<static>|User whereMinRate($value)
 * @method static Builder<static>|User whereMobile($value)
 * @method static Builder<static>|User whereMultiLoc($value)
 * @method static Builder<static>|User whereName($value)
 * @method static Builder<static>|User wherePassword($value)
 * @method static Builder<static>|User wherePhone($value)
 * @method static Builder<static>|User wherePmLastFour($value)
 * @method static Builder<static>|User wherePmType($value)
 * @method static Builder<static>|User whereRememberToken($value)
 * @method static Builder<static>|User whereStripeId($value)
 * @method static Builder<static>|User whereSupportEmail($value)
 * @method static Builder<static>|User whereTrialEndsAt($value)
 * @method static Builder<static>|User whereUpdatedAt($value)
 * @mixin Eloquent
 */
class User extends Authenticatable {

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'company',
        'phone',
        'mobile',
        'min_rate',
        'multi_loc',
        'loc_qty',
        'support_email',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function locations(): hasMany
    {
        return $this->hasMany(Location::class, 'users_id');
    }

    public function links(): hasMany
    {
        return $this->hasMany(LocationLink::class, 'users_id');
    }

    public function customers(): hasMany
    {
        return $this->hasMany(Customer::class, 'users_id');
    }

    public function reviews(): hasMany
    {
        return $this->hasMany(Review::class, 'users_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


}
