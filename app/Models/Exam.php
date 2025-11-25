<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
    'user_id','title','questions_count','status','filters'
    ];
    protected $casts = [
    'filters' => 'array', // se você guarda os filtros em JSON
    ];
    public function questions(){ return $this->belongsToMany(Question::class)->withPivot('order'); }


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
