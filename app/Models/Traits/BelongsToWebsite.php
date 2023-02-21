<?php

namespace App\Models\Traits;

use App\Models\Website;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToWebsite
{
    /**
     * Website
     *
     * @return BelongsTo
     */
    public function website(): BelongsTo
    {
        return $this->belongsTo(Website::class);
    }
}
