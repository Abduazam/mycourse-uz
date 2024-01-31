<?php

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskFiles extends Model
{
    use HasFactory, Common;

    protected $table = 'task_files';

    protected $fillable = [
        'task_id',
        'file',
        'description',
        'active',
    ];

    public function tasks(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
