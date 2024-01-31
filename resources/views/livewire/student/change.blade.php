<div>
    <button type="button" class="btn btn-sm bg-info text-white rounded" data-bs-toggle="modal" data-bs-target="#modal-change-{{ $this?->course?->id }}">
        <i class="far fa-pen-to-square fs-sm text-center"></i>
    </button>

    <!-- Accept Modal -->
    <div class="modal fade" id="modal-change-{{ $this?->course?->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-change-{{ $this?->course?->id }}" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" style="top: 10%;" role="document">
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
                    <div class="block-content tab-content pb-4">
                        <form class="fs-base">
                            <div class="mb-4">
                                <label class="form-label" for="course_id">Course</label>
                                <input type="text" class="form-control" id="course_id" name="course_id" placeholder="Text Input" value="{{ $this->course_id->title }}" disabled>
                            </div>
                            <div class="mb-4">
                                <label class="form-label" for="lesson_id">Lesson</label>
                                <select class="form-select" id="lesson_id" name="lesson_id" wire:model.defer="lesson_id">
                                    @foreach($lessons as $lesson)
                                        <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fs-base" for="message">Message</label>
                                <textarea wire:model.defer="message" class="form-control" id="message" name="message" rows="4" placeholder="Your message.."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="block-content block-content-full block-content-sm text-center border-top">
                        <button type="button" class="btn border" data-bs-dismiss="modal">
                            <small>Close</small>
                        </button>
                        <button type="button" class="btn bg-success text-white" wire:click="change" wire:loading.attr="disabled">
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
        window.addEventListener('userChanged', function (e) {
            let modal = document.querySelector('.modal-backdrop');
            modal.remove();

            $("#modal-change-{{ $this?->course?->id }}").modal('hide');

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
        });
    </script>
@endpush
