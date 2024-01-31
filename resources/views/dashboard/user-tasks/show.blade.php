<x-app-layout>
    <div class="row w-100 h-100 p-0 m-0">
        <div class="col-md-4">
            <div class="block block-rounded block-link-shadow text-center">
                <div class="block-content block-content-full">
                    <img class="img-avatar" src="/assets/media/avatars/avatar.jpg" alt="">
                </div>
                <div class="block-content block-content-full block-content-sm bg-body-light">
                    <div class="fw-semibold mb-1">{{ ucfirst($userTask->user->first_name) }}</div>
                    <div class="fs-sm text-muted">{!! $userTask->user->getUsername() !!}</div>
                </div>
                <div class="block-content text-start">
                    <h6 class="mb-2">Course: <a href="{{ route('courses.show', ['course' => $userTask->course_id]) }}" class="text-dark fw-normal">{{ $userTask->course->title }}</a></h6>
                    <h6>Lesson: <a href="{{ route('lessons.show', ['lesson' => $userTask->lesson_id]) }}" class="text-dark fw-normal">{{ $userTask->lesson->title }}</a></h6>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="block block-rounded row g-0 overflow-hidden">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Files</h3>
                    @if($userTask->status == 1)
                    <livewire:user-task.accept :user_task="$userTask" :wire:key="'accept-user-task-' . $userTask->id" />
                    @endif
                </div>
                <ul class="nav nav-tabs nav-tabs-block flex-md-column col-md-4 rounded-0" role="tablist">
                    @foreach($userTask->files as $file)
                        <li class="nav-item d-md-flex flex-md-column" role="presentation">
                            <button class="nav-link fs-sm text-md-start rounded-0 @if($loop->index == 0) {{ 'active' }} @endif" id="btabs-vertical-{{ $loop->index + 1 }}-tab" data-bs-toggle="tab" data-bs-target="#btabs-vertical-{{ $loop->index + 1 }}" role="tab" aria-controls="btabs-vertical-{{ $loop->index + 1 }}" aria-selected="false" tabindex="-1">
                                <i class="fa fa-{{ $loop->index + 1 }} opacity-50 me-1 d-none d-sm-inline-block"></i> Task
                            </button>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content col-md-8">
                    @foreach($userTask->files as $file)
                        <div class="block-content tab-pane @if($loop->index == 0) {{ 'active' }} @endif" id="btabs-vertical-{{ $loop->index + 1 }}" role="tabpanel" aria-labelledby="btabs-vertical-{{ $loop->index + 1 }}-tab" tabindex="0">
                            <div class="w-100">
                                @if(isset($file->file_id))
                                    <div class="file-block mb-3">
                                        {!! $file->getFile($file->file_id) !!}
                                    </div>
                                @endif

                                @if(isset($file->text))
                                    <div class="text-block mb-4">
                                        {{ $file->text }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
