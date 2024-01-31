<div>
    <button type="button" class="btn btn-sm bg-success text-white px-2 rounded" data-bs-toggle="modal" data-bs-target="#modal-accept-{{ $this?->user?->id }}">
        <i class="far fa-circle-check"></i>
    </button>

    <!-- Accept Modal -->
    <div class="modal fade" id="modal-accept-{{ $this?->user?->id }}" tabindex="-1" role="dialog" aria-labelledby="modal-accept-{{ $this?->user?->id }}" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="block block-rounded shadow-none mb-0">
                    <div class="block-header block-header-default overflow-hidden p-0 w-100">
                        <ul class="nav nav-tabs nav-tabs-block w-100" role="tablist" wire:ignore>
                            <li class="nav-item">
                                <button class="nav-link active" id="btabs-animated-fade-info-{{ $user?->id }}-tab" data-bs-toggle="tab" data-bs-target="#btabs-animated-fade-info-{{ $user?->id }}" role="tab" aria-controls="btabs-animated-fade-info-{{ $user?->id }}" aria-selected="true">Info</button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link" id="btabs-animated-fade-message-{{ $user?->id }}-tab" data-bs-toggle="tab" data-bs-target="#btabs-animated-fade-message-{{ $user?->id }}" role="tab" aria-controls="btabs-animated-fade-message-{{ $user?->id }}" aria-selected="false">Profile</button>
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
                            <form wire:submit.prevent="accept" class="text-start">
                                <div class="mb-4">
                                    <label class="form-label" for="new_name">New name</label>
                                    <input type="text" class="form-control" id="new_name" name="new_name" wire:model="new_name" placeholder="New name">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="course_id">Course</label>
                                    <select class="form-select" id="course_id" name="course_id" wire:model="course_id">
                                        <option disabled selected value="null">Select course</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="lesson_id">Lesson</label>
                                    <select class="form-select" id="lesson_id" name="lesson_id" wire:model="lesson_id">
                                        <option disabled selected value="null">Select lesson</option>
                                        @if(!is_null($lessons))
                                            @foreach($lessons as $lesson)
                                                <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="mb-4 text-start">
                                    <label class="form-label fs-base" for="message">Message</label>
                                    <textarea wire:model.defer="message" class="form-control" id="message" name="message" rows="4" placeholder="Your message.."></textarea>
                                </div>
                            </form>
                        </div>
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
        window.addEventListener('userAccepted', function (e) {
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
        });
    </script>
@endpush
