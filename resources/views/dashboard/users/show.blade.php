<x-app-layout>
    <div class="row w-100 h-100 p-0 m-0">
        <div class="col-md-7 col-12">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">User card</h3>
                </div>
                <div class="block-content fs-sm overflow-hidden px-0 pb-0">
                    <div class="user-info px-4 pb-4">
                        <div class="row w-100 h-100 p-0 m-0 align-items-center">
                            <div class="col-sm-2 col-3 ps-0">
                                <img class="img-avatar" src="/assets/media/avatars/avatar.jpg" alt="">
                            </div>
                            <div class="col-sm-7 col-6">
                                <h4 class="mb-2">{{ ucfirst($user?->first_name) }}</h4>
                                <h6 class="mb-0">{!! $user?->getUsername() !!}</h6>
                            </div>
                            <div class="col-sm-3 col-3 d-flex flex-column align-items-center">
                                <div class="status mb-1">
                                    {!! $user?->getStatus($user->getSteps()->step_1, $user->getSteps()->step_2) !!}
                                </div>
                                <div class="d-flex">
                                    @if(($user->getSteps()->step_1 == 0 and $user->getSteps()->step_2 == 1) or ($user->getSteps()->step_1 < 1))
                                        <livewire:user.accept :user="$user" :wire:key="'accept-user-' . $user->id" />
                                    @else
                                        <livewire:user.edit :user="$user" :wire:key="'edit-user-' . $user->id" />
                                        <livewire:user.block :user="$user" :wire:key="'block-user-' . $user->id" />
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block-content block-content-full block-content-sm bg-body-light d-flex justify-content-between align-items-center"></div>
                </div>
            </div>
            <div class="row items-push">
                @foreach($user->courses as $course)
                    <div class="col-6">
                        <div class="block block-rounded d-flex flex-column h-100 mb-0">
                            @php
                                $array = ['#2facb2', '#36b3a0', '#db3f3f', '#32a67f', '#8f55f2'];
                                $random = array_rand($array);
                                $color = $array[$random];

                                $data = explode(".", $course?->course?->file);
                            @endphp
                            <div class="block-content block-content-full bg-image flex-grow-0 p-3" style="height: 150px;  background: @if($course?->course?->file and in_array(end($data), ['jpg', 'png', 'jpeg', 'gif', 'heic', 'svg'])) url('/storage/{{ $course?->course?->file }}') @else {{ $color }} @endif; background-size: cover; background-position: center;">
                                <span class="badge bg-black-50 fw-bold p-2 text-uppercase" style="font-size: 11px!important;">
                                    <a class="text-white" href="{{ route('lessons.show', ['lesson' => $course->lesson_id]) }}">Lesson #{{ $course?->lesson_id }}</a>
                                </span>
                            </div>
                            <div class="block-content flex-grow-1 fs-sm px-4 py-3">
                                <h5 class="mb-1">
                                    <a class="text-dark" href="{{ route('courses.show', ['course' => $course->course_id]) }}">{{ $course->course->title }}</a>
                                </h5>
                                <p class="fw-medium fs-sm text-muted mb-0">
                                    {{ $course->createdAt() }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-5 col-12">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Answers</h3>
                </div>
                <div class="block-content pb-3">
                    @if(!is_null($user?->answers()))
                        <div class="fs-sm" id="accordion" role="tablist" aria-multiselectable="true">
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
            </div>
        </div>
    </div>
</x-app-layout>
