<?php

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskResponse extends Model
{
    use HasFactory, Common;

    protected $fillable = [
        'task_id',
        'file_id',
        'text',
        'status',
    ];
}
