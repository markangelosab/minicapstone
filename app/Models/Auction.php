<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Auction extends Model
{
    protected $fillable = [
        'title',
        'description',
        'start_price',
        'current_bid',
        'start_time',
        'end_time',
        'status',
        'winner_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'start_price' => 'decimal:2',
        'current_bid' => 'decimal:2',
    ];

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_id');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming');
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }
}
