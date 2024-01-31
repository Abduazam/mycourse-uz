<?php

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonFiles extends Model
{
    use HasFactory, Common;

    protected $table = 'lesson_files';

    protected $fillable = [
        'lesson_id',
        'file',
        'description',
        'active',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
