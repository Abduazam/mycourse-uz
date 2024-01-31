@php
    $disabled = $errors->any() || empty($this->perDay);
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
                        <div class="mb-4">
                            <label class="form-label" for="perDay">Per day</label>
                            <select wire:model.debounce.500ms="perDay" name="perDay" id="perDay"
                                    class="form-select @error('perDay'){{ 'is-invalid' }}@enderror">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                            </select>
                            @error('perDay')
                            <span class="text-danger pt-2 ps-1 small">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4" wire:ignore>
                            <label class="form-label" for="description">Text</label>
                            <input id="text" value="Editor content goes here" type="hidden" name="content">
                            <trix-editor input="text" wire:model.debounce.500ms="text"></trix-editor>
                            @error('text')
                            <span class="text-danger pt-2 ps-1 small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-4 d-md-block d-none">
                <button wire:target="store" wire:loading.attr="disabled" @if($disabled)
                    {{ 'disabled' }}
                @endif type="submit" class="btn bg-primary text-white px-4">Save
                </button>
                <a href="{{ route('reminders.index') }}" class="btn border bg-white px-4">Cancel</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Media</h3>
                </div>
                <div class="block-content">
                    <div class="mb-4">
                        <div x-data="{ isUploading: false, progress: 0 }"
                             x-on:livewire-upload-start="isUploading = true"
                             x-on:livewire-upload-finish="isUploading = false, progress = 0"
                             x-on:livewire-upload-error="isUploading = false"
                             x-on:livewire-upload-progress="progress = $event.detail.progress">
                            <label class="form-label" for="file">File</label>
                            <input wire:model="file" class="form-control @error('file'){{ 'is-invalid' }}@enderror"
                                   type="file" id="file" accept="image/jpg, image/jpeg, image/png, video/mp4">
                            <div x-show.transition="isUploading" class="progress push mt-2" style="height: 15px;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                                     x-bind:style="`width: ${progress}%;`" aria-valuenow="0" aria-valuemin="0"
                                     aria-valuemax="100"></div>
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
                </div>
            </div>
        </div>
        <div class="mb-4 d-md-none d-block">
            <button wire:target="store" wire:loading.attr="disabled" @if($disabled){{ 'disabled' }}@endif type="submit" class="btn bg-primary text-white px-4">Save
            </button>
            <a href="{{ route('reminders.index') }}" class="btn border bg-white px-4">Cancel</a>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        let trixEditor = document.getElementById("text")

        addEventListener("trix-blur", function (event) {
        @this.set('text', trixEditor.getAttribute('value'))
        })
    </script>
@endpush
