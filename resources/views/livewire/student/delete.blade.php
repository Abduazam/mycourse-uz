<div>
    <button type="button" class="btn btn-sm bg-pulse text-white px-2 rounded" data-bs-toggle="modal" data-bs-target="#modal-delete-{{ $this?->course?->id }}">
        <i class="far fa-trash-can"></i>
    </button>

    <!-- Delete Modal -->
    <div class="modal fade" id="modal-delete-{{ $this?->course?->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-delete" aria-hidden="true">
        <div class="modal-dialog" style="top: 15%;" role="document">
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
                    <div class="block-content fs-sm text-center">
                        <h3 class="mb-1">Delete <span class="text-warning">{{ ucfirst($this?->user?->first_name) }}</span> student from course <span class="text-info">{{ $this?->course_id?->title }}</span>.</h3>
                        <p style="font-size: 17px;">Are you sure you would like to do this?</p>
                        <form class="px-4">
                            <div class="mb-4 text-start">
                                <label class="form-label fs-base" for="message">Message</label>
                                <textarea wire:model.defer="message" class="form-control" id="message" name="message" rows="4" placeholder="Your message.."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="block-content block-content-full block-content-sm text-center border-top">
                        <button type="button" class="btn border" data-bs-dismiss="modal">
                            <small>Close</small>
                        </button>
                        <button type="button" class="btn bg-pulse text-white" wire:click="delete" wire:loading.attr="disabled">
                            <small>Yes, delete!</small>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('studentDeleted', function (e) {
            let modal = document.querySelector('.modal-backdrop');
            modal.remove();

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

            if (window.location.pathname.startsWith('/tasks/') && window.location.pathname.substring('/tasks/'.length)) {
                window.location.href = '/tasks';
            }
        });
    </script>
@endpush
