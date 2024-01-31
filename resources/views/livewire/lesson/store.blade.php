@php
    $disabled = $errors->any() || empty($this->title) || empty($this->description) || empty($this->course_id) ? true : false;
@endphp

<form wire:submit.prevent="store">
    @csrf
    <div class="row w-100 h-100 p-0 mx-0">
        <div class="col-md-8">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Info</h3>
                </div>
                <div class="block-content">
                    <div class="col-12">
                        <div class="row w-100 h-100 p-0 m-0">
                            <div class="col-md-7 ps-0">
                                <div class="mb-4">
                                    <label class="form-label" for="title">Lesson title</label>
                                    <input wire:model.debounce.500ms="title" type="text" class="form-control @error('title'){{ 'is-invalid' }}@enderror" id="title" name="title" placeholder="Lesson title">
                                    @error('title')
                                    <span class="text-danger pt-2 ps-1 small">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-5 pe-0">
                                <div class="mb-4">
                                    <label class="form-label" for="course_id">Course</label>
                                    <select class="form-select" id="course_id" name="course_id" wire:model="course_id">
                                        <option value="null" selected disabled>Choose course</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4" wire:ignore>
                            <label class="form-label" for="description">Lesson description</label>
                            <input id="description" value="Editor content goes here" type="hidden" name="content">
                            <trix-editor input="description" wire:model.debounce.500ms="description"></trix-editor>
                            @error('description')
                            <span class="text-danger pt-2 ps-1 small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-4 d-md-block d-none">
                <button wire:target="store" wire:loading.attr="disabled" @if($disabled){{ 'disabled' }}@endif type="submit" class="btn bg-primary text-white px-4">Save</button>
                <a href="{{ route('lessons.index') }}" class="btn border bg-white px-4">Cancel</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Files</h3>
                </div>
                <div class="block-content">
                    <div class="col-12">
                        <div class="mb-4">
                            <div x-data="{ isUploading: false, progress: 0 }"
                                 x-on:livewire-upload-start="isUploading = true"
                                 x-on:livewire-upload-finish="isUploading = false, progress = 0"
                                 x-on:livewire-upload-error="isUploading = false"
                                 x-on:livewire-upload-progress="progress = $event.detail.progress">
                                <label class="form-label" for="file">Lesson media</label>
                                <input wire:model="file" class="form-control @error('file'){{ 'is-invalid' }}@enderror" type="file" id="file" accept="image/jpg, image/jpeg, image/png, video/mp4">
                                <div x-show.transition="isUploading" class="progress push mt-2" style="height: 15px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" x-bind:style="`width: ${progress}%;`" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                            @error('file')
                            <span class="text-danger pt-2 ps-1 small">{{ $message }}</span>
                            @enderror
                            @if($file instanceof Illuminate\Http\UploadedFile)
                                @php
                                    $extension = $file->guessExtension();
                                @endphp

                                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ $file->temporaryUrl() }}" alt="" class="w-100 pt-3">
                                @else
                                    <video class="w-100 pt-3" controls>
                                        <source src="{{ $file->temporaryUrl() }}" type="video/mp4">
                                        <source src="{{ $file->temporaryUrl() }}" type="video/ogg">
                                    </video>
                                @endif
                            @endif
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Lesson status</label>
                            <div class="space-x-2">
                                <div class="form-check form-switch form-check-inline">
                                    <input  wire:model="active" class="form-check-input @error('active'){{ 'is-invalid' }}@enderror" type="checkbox" value="1" id="active" name="active" checked="">
                                    <label class="form-check-label" for="active">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="additional-files">
                            <p class="form-label mb-2">Additional files</p>
                            @if($additional_files > 0)
                                @for($i = 0; $i < $additional_files; $i++)
                                    <div class="mb-4">
                                        <div x-data="{ isUploading: false, progress: 0 }"
                                             x-on:livewire-upload-start="isUploading = true"
                                             x-on:livewire-upload-finish="isUploading = false, progress = 0"
                                             x-on:livewire-upload-error="isUploading = false"
                                             x-on:livewire-upload-progress="progress = $event.detail.progress">
                                            <input @if(isset($files[$i])) wire:model="files.{{ $i }}" @else wire:model="files" @endif class="form-control @error('files'){{ 'is-invalid' }}@enderror" type="file" id="files" accept="image/jpg, image/jpeg, image/png, video/mp4">
                                            <div x-show.transition="isUploading" class="progress push mt-2" style="height: 15px;">
                                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" x-bind:style="`width: ${progress}%;`" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                        @error('files')
                                        <span class="text-danger pt-2 ps-1 small">{{ $message }}</span>
                                        @enderror
                                        @if(isset($files[$i]))
                                            @php
                                                $extension = $files[$i]->guessExtension();
                                            @endphp

                                            @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                <img src="{{ $files[$i]->temporaryUrl() }}" alt="" class="w-100 pt-3">
                                            @else
                                                <video class="w-100 pt-3" controls>
                                                    <source src="{{ $files[$i]->temporaryUrl() }}" type="video/mp4">
                                                    <source src="{{ $files[$i]->temporaryUrl() }}" type="video/ogg">
                                                </video>
                                            @endif
                                        @endif
                                    </div>
                                @endfor
                            @endif
                            <a class="btn btn-primary mb-4 w-100" wire:click="addFile"><i class="fa fa-plus"></i> Add file</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mb-4 d-md-none d-block">
            <button wire:target="store" wire:loading.attr="disabled" @if($disabled){{ 'disabled' }}@endif type="submit" class="btn bg-primary text-white px-4">Save</button>
            <a href="{{ route('lessons.index') }}" class="btn border bg-white px-4">Cancel</a>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        let trixEditor = document.getElementById("description")

        addEventListener("trix-blur", function(event) {
            @this.set('description', trixEditor.getAttribute('value'))
        })
    </script>
@endpush
