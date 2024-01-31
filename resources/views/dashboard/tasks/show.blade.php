<x-app-layout>
    <div class="row w-100 h-100 p-0 m-0">
        <div class="col-lg-5 col-sm-6 ">
            <div class="block block-rounded block-link-shadow text-center overflow-hidden">
                @if(isset($task->file))
                    <div>
                        {!! $task->getFile() !!}
                    </div>
                @endif
                <div class="block-content block-content-ful pt-5 pb-3 ribbon ribbon-left @if($task->active == 1) {{ 'ribbon-success' }} @else {{ 'ribbon-danger' }} @endif">
                    <div class="fs-sm text-muted">{!! $task->description !!}</div>
                    <div class="ribbon-box fs-sm">
                        @if($task->active == 1) {{ 'Active' }} @else {{ 'Inactive' }} @endif
                    </div>
                </div>
                <div class="block-content block-content-full block-content-sm">
                    <div class="fs-sm"><i class="si si-calendar opacity-75"></i> {!! $task->createdAt() !!}</div>
                </div>
                <div class="block-content block-content-full block-content-sm bg-body-light d-flex justify-content-center">
                    <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-primary"><i class="fa fa-rotate-left"></i></a>
                    <a href="{{ route('tasks.edit', ['task' => $task->id]) }}" type="button" class="btn btn-sm btn-secondary rounded mx-1">
                        <i class="fa fa-pencil-alt"></i>
                    </a>
                    <livewire:task.delete :task="$task" :wire:key="'delete-task-' . $lesson->id" />
                </div>
                <div class="block-content">
                    <div class="row items-push text-center">
                        <div class="col-4">
                            <div class="mb-1"><i class="si si-users h2"></i></div>
                            <div><span class="small">Total</span></div>
                            <div class="text-muted">{{ count($task->students) }}</div>
                        </div>
                        <div class="col-4">
                            <div class="mb-1"><i class="si si-clock h2"></i></div>
                            <div><span class="small">Unchecked</span></div>
                            <div class="text-muted">{{ count($task->unchecked) }}</div>
                        </div>
                        <div class="col-4">
                            <div class="mb-1"><i class="si si-check h2"></i></div>
                            <div><span class="small">Completed</span></div>
                            <div class="text-muted">{{ count($task->completed) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-sm-6">
            @if(count($task->files) > 0)
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h6 class="mb-0">Files</h6>
                    </div>
                    <div class="block-content pb-4">
                        <div class="row">
                            @foreach($task->files as $file)
                                <div class="col-md-6 animated fadeIn">
                                    {!! $file->getFile() !!}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

                <livewire:task.user-tasks :task="$task" :wire:key="'user-tasks-task-' . $task->id" />
        </div>
    </div>
</x-app-layout>
