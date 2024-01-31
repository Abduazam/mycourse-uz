<?php

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTaskLog extends Model
{
    use HasFactory, Common;

    protected $fillable = [
        'chat_id',
        'last_id',
    ];
}
