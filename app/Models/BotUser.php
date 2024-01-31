<?php

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BotUser extends Model
{
    use HasFactory, Common;

    protected $fillable = [
        'chat_id',
        'first_name',
        'username',
        'is_view',
    ];

    public static function search(string $search)
    {
        return empty($search)
            ? static::query()
            : static::query()->where('first_name', 'like', '%'. $search . '%');
    }

    public function getUsername(): string
    {
        if (isset($this->username)) {
            return "<a href='https://t.me/$this->username' target='_blank' class='badge bg-primary text-white'>$this->username</a>";
        } else {
            return "<span class='badge bg-dark'>No username</span>";
        }
    }

    public function getAnswersCount(): int
    {
        return UserAnswer::query()->where('chat_id', $this->chat_id)->count();
    }

    public function getStatus($step_1, $step_2): string
    {
        if ($step_1 === 0 and $step_2 === 1) {
            return "<span class='badge bg-warning'>Authorized</span>";
        }

        if ($step_1 > 0) {
            return "<span class='badge bg-success'>Active</span>";
        }

        return "<span class='badge bg-pulse'>Inactive</span>";
    }

    public function answers(): \Illuminate\Database\Eloquent\Collection|array
    {
        return UserAnswer::where('chat_id', $this->chat_id)->get();
    }

    public function getSteps()
    {
        return UserAction::where('chat_id', $this->chat_id)->first();
    }

    public function courses(): HasMany
    {
        return $this->hasMany(UserCourse::class, 'user_id', 'id')->where('status', 1);
    }

    public function course($course_id)
    {
        return Course::where('id', $course_id)->first();
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(UserTask::class, 'user_id', 'id')->where('status', 2);
    }
}
