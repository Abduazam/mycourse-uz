<?php

namespace App\Models;

use App\Traits\Common;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    use HasFactory, Common;

    protected $fillable = [
        'question',
        'position',
        'keyboard_id',
        'active'
    ];

    public static function search(string $search)
    {
        return empty($search)
            ? static::query()
            : static::query()->where('question', 'like', '%'. $search . '%');
    }

    public function keyboard(): BelongsTo
    {
        return $this->belongsTo(Keyboard::class);
    }

    public function getKeyboard(): string
    {
        if (isset($this->keyboard_id)) {
            $keyboard = Keyboard::query()->where('id', $this->keyboard_id)->first();
            return "<span class='badge bg-info'>$keyboard->title</span>";
        } else {
            return "<span class='badge bg-dark'>No keyboard</span>";
        }
    }
}
