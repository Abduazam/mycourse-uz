<?php

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory, Common;

    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'file',
        'description',
        'active',
    ];

    public static function search(string $search)
    {
        return empty($search)
            ? static::query()
            : static::query()->where('title', 'like', '%'. $search . '%');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(LessonFiles::class, 'lesson_id', 'id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'lesson_id', 'id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(UserCourse::class, 'lesson_id', 'id')->where('status', 1);
    }

    public function applied(): HasMany
    {
        return $this->hasMany(UserTask::class, 'lesson_id', 'id')->where('status', 2);
    }
}
