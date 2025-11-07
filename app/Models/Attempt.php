<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'user_id',
        'status',        // ex.: 'in_progress' | 'submitted'
        'score',         // inteiro
        'time_seconds',  // inteiro
    ];

    public function exam()   { return $this->belongsTo(Exam::class); }
    public function user()   { return $this->belongsTo(User::class); }
    public function answers(){ return $this->hasMany(AttemptAnswer::class); }
}
