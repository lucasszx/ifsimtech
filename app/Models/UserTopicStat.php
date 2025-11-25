<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTopicStat extends Model
{
    protected $fillable = [
        'user_id',
        'topic_id',
        'total_attempts',
        'correct_attempts',
    ];

    // Relacionamentos
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    // Accessor para taxa (%)
    public function getRateAttribute(): float
    {
        if ($this->total_attempts == 0) {
            return 0;
        }

        return round(100 * $this->correct_attempts / $this->total_attempts, 1);
    }

    // Accessor para nível (Crítico / Atenção / OK)
    public function getLevelAttribute(): string
    {
        $rate = $this->rate;

        if ($rate < 40) {
            return 'Crítico';
        }

        if ($rate < 70) {
            return 'Atenção';
        }

        return 'OK';
    }
}
