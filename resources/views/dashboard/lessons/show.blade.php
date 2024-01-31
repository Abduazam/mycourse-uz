<x-app-layout>
    <div class="row w-100 h-100 p-0 m-0">
        <div class="col-lg-5 col-sm-6 ">
            <div class="block block-rounded block-link-shadow text-center overflow-hidden">
                @if(isset($lesson->file))
                    <div>
                        {!! $lesson->getFile() !!}
                    </div>
                @endif
                <div class="block-content block-content-ful pt-5 pb-3 ribbon ribbon-left @if($lesson->active == 1) {{ 'ribbon-success' }} @else {{ 'ribbon-danger' }} @endif">
                    <div class="fw-semibold mb-1"><h5 class="mb-0">{{ $lesson->title }}</h5></div>
                    <div class="fs-sm text-muted">{!! $lesson->shortDescription(40) !!}</div>
                    <div class="ribbon-box fs-sm">
                        @if($lesson->active == 1) {{ 'Active' }} @else {{ 'Inactive' }} @endif
                    </div>
                </div>
                <div class="block-content block-content-full block-content-sm">
                    <div class="fs-sm"><i class="si si-calendar opacity-75"></i> {!! $lesson->createdAt() !!}</div>
                </div>
                <div class="block-content block-content-full block-content-sm bg-body-light d-flex justify-content-center">
                    <a href="{{ route('lessons.index') }}" class="btn btn-sm btn-primary"><i class="fa fa-rotate-left"></i></a>
                    <a href="{{ route('lessons.edit', ['lesson' => $lesson->id]) }}" type="button" class="btn btn-sm btn-secondary rounded mx-1">
                        <i class="fa fa-pencil-alt"></i>
                    </a>
                    <livewire:lesson.delete :lesson="$lesson" :wire:key="'delete-lesson-' . $lesson->id" />
                </div>
                <div class="block-content">
                    <div class="row items-push text-center">
                        <div class="col-4">
                            <div class="mb-1"><i class="si si-badge h2"></i></div>
                            <div><span class="small">Task</span></div>
                            <div class="text-muted">{{ count($lesson->tasks) }}</div>
                        </div>
                        <div class="col-4">
                            <div class="mb-1"><i class="si si-users h2"></i></div>
                            <div><span class="small">Students</span></div>
                            <div class="text-muted">{{ count($lesson->students) }}</div>
                        </div>
                        <div class="col-4">
                            <div class="mb-1"><i class="si si-check h2"></i></div>
                            <div><span class="small">Applied</span></div>
                            <div class="text-muted">{{ count($lesson->applied) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-sm-6">
            @if(count($lesson->files) > 0)
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h6 class="mb-0">Files</h6>
                    </div>
                    <div class="block-content pb-4">
                        <div class="row">
                            @foreach($lesson->files as $file)
                                <div class="col-md-6 animated fadeIn">
                                    {!! $file->getFile() !!}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <livewire:lesson.tasks :lesson="$lesson" />

            <livewire:lesson.students :lesson="$lesson" />

            <livewire:lesson.user-tasks :lesson="$lesson" :wire:key="'user-tasks-lesson-' . $lesson->id" />
        </div>
    </div>
</x-app-layout>
