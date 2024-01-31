<div>
    <button type="button" class="btn btn-sm bg-success text-white rounded" data-bs-toggle="modal" data-bs-target="#modal-accept-{{ $this?->user_task?->id }}">
        <i class="far fa-circle-check fs-sm text-center"></i>
    </button>

    <!-- Accept Modal -->
    <div class="modal fade" id="modal-accept-{{ $this?->user_task?->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-accept-{{ $this?->user_task?->id }}" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg" style="top: 10%;" role="document">
            <div class="modal-content">
                <div class="block block-rounded shadow-none mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title"></h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content tab-content text-center pb-4">
                        <form wire:submit.prevent="accept" class="text-start">
                            <div class="row m-0 w-100 h-100">
                                <div class="col-md-6 ps-0">
                                    <div class="mb-4 text-start">
                                        <label class="form-label fs-base" for="message">Message</label>
                                        <textarea wire:model.defer="message" class="form-control" id="message" name="message" rows="8" placeholder="Your message.."></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 pe-0">
                                    <div class="additional-files">
                                        <p class="form-label mb-2">Files</p>
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
                        </form>
                    </div>
                    <div class="block-content block-content-full block-content-sm text-center border-top">
                        <button type="button" class="btn border" data-bs-dismiss="modal">
                            <small>Close</small>
                        </button>
                        <button type="button" class="btn bg-success text-white" wire:click="accept" wire:loading.attr="disabled">
                            <small>Yes, accept!</small>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('taskAccepted', function (e) {
            let modal = document.querySelector('.modal-backdrop');
            modal.remove();

            $("#modal-accept-{{ $this?->user_task?->id }}").modal('hide');

            document.body.style.overflow = '';
            document.body.style.padding = '';

            Swal.fire({
                title: e.detail.title,
                icon: e.detail.icon,
                iconColor: e.detail.iconColor,
                timer: 3000,
                toast: true,
                position: 'top-right',
                timerProgressBar: true,
                showConfirmButton: false,
            });

            if (window.location.pathname.startsWith('/user-tasks/') && window.location.pathname.substring('/user-tasks/'.length)) {
                window.location.href = '/user-tasks';
            }
        });
    </script>
@endpush
