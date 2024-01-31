<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'question_id',
        'answer',
    ];

    public function question(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Question::class, 'id', 'question_id');
    }
}
