<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Common
{
    public function getFile(): string|null
    {
        if (isset($this->file) and !empty($this->file)) {
            $files = explode('.', $this->file);
            if (in_array(end($files), ['jpg', 'jpeg', 'png', 'gif'])) {
                return '<img src="/storage/' . $this->file . '" alt="' . $this->title . '" class="w-100">';
            }

            if (in_array(end($files), ["mp3", "wav", "aac"])) {
                return '<audio class="w-100 pt-3" controls><source src="/storage/' . $this->file . '" type="audio/mpeg"><source src="/storage/'. $this->file .'" type="audio/ogg"></audio>';
            }

            return '<video class="w-100" controls><source src="/storage/' . $this->file . '" type="video/mp4"><source src="/storage/' . $this->file . '" type="video/ogg"></video>';
        } else {
            return null;
        }
    }

    public function shortDescription(int $wordCount = 7): string
    {
        return Str::words(strip_tags($this->description), $wordCount);
    }

    public function createdAt()
    {
        return $this->created_at->format('d F, Y');
    }

    public function status(): string
    {
        if ($this->active == 1) {
            return "<span class='badge bg-success'>Active</span>";
        }

        return "<span class='badge bg-pulse'>Inactive</span>";
    }

    public function telegramDescription(int $size = null): string
    {
        $text = str_replace('&nbsp;', '', $this->description);
        $text = strip_tags($text, '<b><strong><i><em><u><ins><s><strike><del><span><a><code><pre>');
        if ($size != null) {
            return Str::limit($text, $size);
        }

        return $text;
    }
}
