<div>
    <button type="button" class="btn btn-sm bg-pulse text-white px-2 rounded" data-bs-toggle="modal" data-bs-target="#modal-delete-{{ $this?->question?->id }}">
        <i class="far fa-trash-can"></i>
    </button>

    <!-- Delete Modal -->
    <div class="modal fade" id="modal-delete-{{ $this?->question?->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-delete" aria-hidden="true">
        <div class="modal-dialog" style="top: 25%;" role="document">
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
                        <h3 class="mb-1">Delete <span class="text-warning">{{ $this?->question?->question }}</span> question.</h3>
                        <p style="font-size: 17px;">Are you sure you would like to do this?</p>
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
        window.addEventListener('questionDeleted', function (e) {
            let modal = document.querySelector('.modal-backdrop');
            modal.remove();
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
