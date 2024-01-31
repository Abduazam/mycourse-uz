<div>
    <button type="button" class="btn btn-sm bg-warning text-white px-2 me-1 rounded" data-bs-toggle="modal" data-bs-target="#modal-edit-{{ $this?->user?->id }}">
        <i class="fa fa-pen"></i>
    </button>

    <!-- Accept Modal -->
    <div class="modal fade" id="modal-edit-{{ $this?->user?->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-edit-{{ $this?->user?->id }}" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" style="top: 10%;" role="document">
            <div class="modal-content">
                <div class="block block-rounded shadow-none mb-0">
                    <div class="block-header block-header-default overflow-hidden p-0 w-100">
                        <ul class="nav nav-tabs nav-tabs-block w-100" role="tablist" wire:ignore>
                            <li class="nav-item">
                                <button class="nav-link active" id="btabs-animated-fade-info-{{ $user?->id }}-tab" data-bs-toggle="tab" data-bs-target="#btabs-animated-fade-info-{{ $user?->id }}" role="tab" aria-controls="btabs-animated-fade-info-{{ $user?->id }}" aria-selected="true">Info</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="btabs-animated-fade-message-{{ $user?->id }}-tab" data-bs-toggle="tab" data-bs-target="#btabs-animated-fade-message-{{ $user?->id }}" role="tab" aria-controls="btabs-animated-fade-message-{{ $user?->id }}" aria-selected="false">Name</button>
                            </li>
                            <li class="nav-item ms-auto">
                                <button class="btn-block-option py-3 px-4" data-bs-dismiss="modal" aria-label="Close">
                                    <i class="fa fa-times"></i>
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="block-content tab-content text-center pb-4">
                        <div class="tab-pane fade show active" wire:ignore id="btabs-animated-fade-info-{{ $user?->id }}" role="tabpanel" aria-labelledby="btabs-animated-fade-info-{{ $user?->id }}-tab" tabindex="0">
                            <h4>{{ ucfirst($user?->first_name) }} <span class="fw-normal small">{!! $user?->getUsername() !!}</span></h4>
                            @if(!is_null($user?->answers()))
                                <div id="accordion" role="tablist" aria-multiselectable="true">
                                    @foreach($user?->answers() as $answer)
                                        <div class="block block-bordered block-rounded mb-2">
                                            <div class="block-header" role="tab" id="accordion_h{{ $loop->index + 1 }}">
                                                <a class="fw-semibold collapsed w-100 text-dark" data-bs-toggle="collapse" data-bs-parent="#accordion" href="#accordion_q{{ $loop->index + 1 }}" aria-expanded="false" aria-controls="accordion_q{{ $loop->index + 1 }}">{{ $loop->index + 1 }}. {{ $answer->question->question }}</a>
                                            </div>
                                            <div id="accordion_q{{ $loop->index + 1 }}" class="collapse" role="tabpanel" aria-labelledby="accordion_h{{ $loop->index + 1 }}" data-bs-parent="#accordion" style="">
                                                <div class="block-content py-2 px-3">
                                                    {{ $answer->answer }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="tab-pane fade" wire:ignore.self id="btabs-animated-fade-message-{{ $user?->id }}" role="tabpanel" aria-labelledby="btabs-animated-fade-message-{{ $user?->id }}-tab" tabindex="0">
                            <form wire:submit.prevent="edit" class="text-start">
                                <div class="mb-4">
                                    <label class="form-label" for="new_name">New name</label>
                                    <input type="text" class="form-control" id="new_name" name="new_name" wire:model="new_name" placeholder="New name">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="block-content block-content-full block-content-sm text-center border-top">
                        <button type="button" class="btn border" data-bs-dismiss="modal">
                            <small>Close</small>
                        </button>
                        <button type="button" class="btn bg-success text-white" wire:click="edit" wire:loading.attr="disabled">
                            <small>Yes, edit!</small>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('userEdited', function (e) {
            let modal = document.querySelector('.modal-backdrop');
            modal.remove();

            $("#modal-create-question").modal('hide');

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

            if (window.location.pathname.startsWith('/users/') && window.location.pathname.substring('/users/'.length)) {
                location.reload();
            }
        });
    </script>
@endpush
