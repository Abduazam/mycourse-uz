<?php

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTaskFiles extends Model
{
    use HasFactory, Common;

    protected $fillable = [
        'task_id',
        'file_id',
        'text',
        'task_type',
        'status',
    ];

    public function getFile($file_id)
    {
        $telegram = new Telegram(config('telegram.tokens.main'));
        $file = $telegram->getFileDash($file_id);

        $file_path = explode("/", $file['result']['file_path']);

        if ($this->task_type == 2) {
            $result = "https://api.telegram.org/file/bot" . config('telegram.tokens.main') . "/photos/" . end($file_path);
            return "<img src='$result' class='w-75' />";
        } else if ($this->task_type == 3) {
            $result = "https://api.telegram.org/file/bot" . config('telegram.tokens.main') . "/voice/" . end($file_path);
            return "<audio controls><source src='$result' type='audio/ogg'></audio>";
        } else if ($this->task_type == 4) {
            $result = "https://api.telegram.org/file/bot" . config('telegram.tokens.main') . "/music/" . end($file_path);
            return "<audio controls><source src='$result' type='audio/mp4'></audio>";
        }
    }
}
