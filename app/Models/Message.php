<?php

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Message extends Model
{
    use HasFactory, Common;

    protected $fillable = [
        'file',
        'text',
        'active'
    ];

    public function telegramText(int $size = null): string
    {
        $text = str_replace('&nbsp;', '', $this->text);
        $text = strip_tags($text, '<b><strong><i><em><u><ins><s><strike><del><span><a><code><pre>');
        if ($size != null) {
            return Str::limit($text, $size);
        }

        return $text;
    }
}
