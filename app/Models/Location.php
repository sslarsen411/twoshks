<?php

namespace App\Models;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property int $id
 * @property int $users_id
 * @property string|null $addr
 * @property string|null $city
 * @property string|null $state
 * @property string|null $zip
 * @property string|null $loc_phone
 * @property string|null $CID
 * @property string|null $PID
 * @property string $status
 * @property string|null $init_rate
 * @property string|null $init_rct
 * @property Carbon $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Customer> $customers
 * @property-read int|null $customers_count
 * @property-read Factory|null $use_factory
 * @property-read LocationLink|null $links
 * @property-read Collection<int, Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read User $users
 * @method static Builder<static>|Location newModelQuery()
 * @method static Builder<static>|Location newQuery()
 * @method static Builder<static>|Location query()
 * @method static Builder<static>|Location whereAddr($value)
 * @method static Builder<static>|Location whereCID($value)
 * @method static Builder<static>|Location whereCity($value)
 * @method static Builder<static>|Location whereCreatedAt($value)
 * @method static Builder<static>|Location whereId($value)
 * @method static Builder<static>|Location whereInitRate($value)
 * @method static Builder<static>|Location whereInitRct($value)
 * @method static Builder<static>|Location whereLocPhone($value)
 * @method static Builder<static>|Location wherePID($value)
 * @method static Builder<static>|Location whereState($value)
 * @method static Builder<static>|Location whereStatus($value)
 * @method static Builder<static>|Location whereUpdatedAt($value)
 * @method static Builder<static>|Location whereUsersId($value)
 * @method static Builder<static>|Location whereZip($value)
 * @mixin Eloquent
 */
class Location extends Model {
    const string STATUS_ACTIVE = 'active';
    const string STATUS_INACTIVE = 'inactive';

    public $table = 'locations';
    protected $fillable = [
        'users_id',
        'addr',
        'city',
        'state',
        'zip',
        'loc_phone',
        'loc_email',
        'CID',
        'PID',
        'init_rate',
        'init_rct',
    ];

    public function users(): belongsTo
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function links(): hasOne
    {
        return $this->hasOne(LocationLink::class);
    }

    public function customers(): hasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function reviews(): hasMany
    {
        return $this->hasMany(Review::class);
    }
}
