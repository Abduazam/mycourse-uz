<?php

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTask extends Model
{
    use HasFactory, Common;

    protected $fillable = [
        'user_id',
        'course_id',
        'lesson_id',
        'task_id',
        'status',
    ];

    public static function search(string $search)
    {
        return empty($search)
            ? static::query()
            : static::query()->where('first_name', 'like', '%'. $search . '%');
    }

    public function taskStatus(): string
    {
        if ($this->status == 1) {
            return "<span class='badge bg-warning'>Submitted</span>";
        } else if ($this->status == 0) {
            return "<span class='badge bg-danger'>Assigned</span>";
        } else {
            return "<span class='badge bg-success'>Checked</span>";
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(BotUser::class, 'user_id', 'id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id', 'id');
    }

    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserTaskFiles::class, 'task_id', 'id');
    }
}
