<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'statement',
        'image_path',
        'source',
        'year'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'question_topic');
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }
}
