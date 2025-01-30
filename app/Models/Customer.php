<?php

namespace App\Models;

//use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

//use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 
 *
 * @property int $id
 * @property int $users_id
 * @property int $location_id
 * @property string $oauth_provider
 * @property string|null $oauth_uid
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $email
 * @property string|null $phone
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $fullname
 * @property-read \App\Models\Location|null $locations
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \App\Models\User|null $users
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereLocationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereOauthProvider($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereOauthUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Customer whereUsersId($value)
 * @mixin \Eloquent
 */
class Customer extends Model {

    public $table = 'customers';
    protected $fillable = [
        'users_id',
        'location_id',
        'oauth_provider',
        'oauth_uid',
        'first_name',
        'last_name',
        'email',
        'phone',
        'purchase',
        'state',
    ];
    protected mixed $users_id;
    protected mixed $id;
    protected mixed $location_id;

    public function users(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function locations(): belongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function reviews(): hasMany
    {
        return $this->hasMany(Review::class, 'customer_id');
    }

    public function getFullnameAttribute(): string
    {
        return $this->first_name.' '.$this->last_name;
    }
}
