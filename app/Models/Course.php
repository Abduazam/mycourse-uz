<?php

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory, Common;

    protected $fillable = [
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
            : static::query()->where('courses.title', 'like', '%' . $search . '%');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class, 'course_id', 'id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(UserCourse::class, 'course_id', 'id')->where('status', 1);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(UserCourse::class, 'course_id', 'id')->where('status', 0);
    }
}
