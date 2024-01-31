<?php

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCourse extends Model
{
    use HasFactory, Common;

    protected $fillable = [
        'user_id',
        'course_id',
        'lesson_id',
        'status',
    ];

    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BotUser::class);
    }

    public function getStatus(): string
    {
        if ($this->status == 1) {
            return "<span class='badge bg-success'>Active</span>";
        }

        return "<span class='badge bg-pulse'>Inactive</span>";
    }
}
