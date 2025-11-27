<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Laravel\Scout\Searchable;

class Review extends Model
{
    use HasFactory;
    use Searchable;

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function reviewer(): MorphTo
    {
        return $this->morphTo();
    }

    protected function makeAllSearchableUsing(Builder $query)
    {
        return $query->with('reviewable');
    }

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }
}
