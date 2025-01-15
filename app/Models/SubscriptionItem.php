<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionItem extends Model {
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
