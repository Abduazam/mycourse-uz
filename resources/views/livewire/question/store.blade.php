@php
    $disabled = $errors->any() || empty($this->question) ? true : false;
@endphp

<div>
    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-create-question">
        <i class="fa fa-plus me-1"></i> Create question
    </a>

    <!-- Create Question Modal -->
    <div class="modal fade" id="modal-create-question" tabindex="-1" role="dialog" aria-labelledby="modal-create" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" style="top: 20%;" role="document">
            <form wire:submit.prevent="store">
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
                        <div class="block-content text-start">
                            <div class="mb-4">
                                <label class="form-label" for="question">Question</label>
                                <input type="text" wire:model.defer="question" class="form-control @error('question'){{ 'is-invalid' }}@enderror" id="question" name="question" placeholder="Type question..">
                                @error('question')
                                <span class="text-danger pt-2 ps-1 small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="keyboard_id">Select</label>
                                <select class="form-select" id="keyboard_id" name="keyboard_id" wire:model.defer="keyboard_id">
                                    <option value="null" selected disabled>Choose keyboard</option>
                                    @foreach($keyboards as $keyboard)
                                        <option value="{{ $keyboard->id }}">{{ $keyboard->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="block-content block-content-full block-content-sm text-center border-top">
                            <button type="button" class="btn border" data-bs-dismiss="modal">
                                <small>Close</small>
                            </button>
                            <button wire:target="store" wire:loading.attr="disabled" type="submit" class="btn bg-success text-white">
                                <small>Save</small>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('questionCreated', function (e) {
            let modal = document.querySelector('.modal-backdrop');
            modal.remove();

            $("#modal-create-question").modal('hide');

            document.body.style.overflow = '';

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
        });
    </script>
@endpush
