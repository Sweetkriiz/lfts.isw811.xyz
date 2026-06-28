<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    protected $guarded = [];

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }
}
