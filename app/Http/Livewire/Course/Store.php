<?php

namespace App\Http\Livewire\Course;

use App\Models\Course;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class Store extends Component
{
    use WithFileUploads;

    public ?string $title = null;
    public $file = null;
    public ?string $description = null;
    public ?bool $active = true;

    protected array $rules = [
        'title' => ['required', 'unique:courses,title', 'min:5', 'max:255', 'string'],
        'file' => ['nullable', 'mimes:jpg,jpeg,png,mp4'],
        'description' => ['required', 'min:5', 'string'],
        'active' => ['required', 'bool']
    ];

    /**
     * @throws ValidationException
     */
    public function updated($property): void
    {
        $this->validateOnly($property);
    }

    public function store()
    {
        $file = $this?->file?->store('courses', 'public');

        Course::create([
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'file' => $file,
            'description' => $this->description,
            'active' => $this->active,
        ]);

        session()->flash('success', 'Course created');

        return redirect()->to('courses');
    }

    public function render(): View
    {
        return view('livewire.course.store');
    }
}
