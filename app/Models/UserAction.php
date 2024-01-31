<?php

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAction extends Model
{
    use HasFactory, Common;

    protected $fillable = [
        'chat_id',
        'step_1',
        'step_2',
    ];
}
