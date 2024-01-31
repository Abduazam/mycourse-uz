<?php

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Task extends Model
{
    use HasFactory, Common;

    protected $fillable = [
        'lesson_id',
        'file',
        'description',
        'active',
    ];

    public static function search(string $search)
    {
        return empty($search)
            ? static::query()
            : static::query()->where('tasks.description', 'like', '%' . $search . '%');
    }

    public function lesson(): HasOne
    {
        return $this->hasOne(Lesson::class, 'id', 'lesson_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(TaskFiles::class, 'task_id', 'id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(UserCourse::class, 'lesson_id', 'lesson_id');
    }

    public function unchecked(): HasMany
    {
        return $this->hasMany(UserTask::class, 'task_id', 'id')->where('status', '!=', 2);
    }

    public function completed(): HasMany
    {
        return $this->hasMany(UserTask::class, 'task_id', 'id')->where('status', '=', 2);
    }
}
