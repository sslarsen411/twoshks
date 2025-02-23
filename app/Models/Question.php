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
 * @property string|null $question
 * @property string|null $progress
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder<static>|Question newModelQuery()
 * @method static Builder<static>|Question newQuery()
 * @method static Builder<static>|Question query()
 * @method static Builder<static>|Question whereCreatedAt($value)
 * @method static Builder<static>|Question whereId($value)
 * @method static Builder<static>|Question whereProgress($value)
 * @method static Builder<static>|Question whereQuestion($value)
 * @method static Builder<static>|Question whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Question extends Model
{

    public $table = 'questions';
    protected $fillable = [
        'category_id',
        'questions'
    ];

    public function categories(): belongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
