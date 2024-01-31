<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h4 class="mb-0">Tasks</h4>
        @if(count($tasks) > 0)
            <a href="{{ route('tasks.create') }}" class="btn btn-primary"><i class="fa fa-plus me-1"></i> Create task</a>
        @endif
    </div>
    <div class="block-content">
        <div class="row w-100 h-100 mx-0 px-0 pb-4 justify-content-between">
            <div class="col-md-2 col-3 ps-0">
                <div class="col-sm-7 col-9 m-0">
                    <label for="perPage" hidden=""></label>
                    <select wire:model="perPage" class="form-select" id="perPage">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="0">All</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6 col-9 pe-0">
                <div class="row w-100 m-0">
                    <div class="col-10 px-0">
                        <label class="w-100">
                            <input wire:model.debounce.100ms="search" type="text" class="form-control w-100" placeholder="Search by description..">
                        </label>
                    </div>
                    <div class="col-2 pe-0">
                        <div class="dropdown push mb-0">
                            <button type="button" class="btn bg-white border border-lighter dropdown-toggle" id="dropdown-content-rich-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-filter"></i></button>
                            <div class="dropdown-menu p-3" aria-labelledby="dropdown-content-rich-primary" style="width: 250px;">
                                <div class="mb-0">
                                    <label class="form-label" for="course_id">By course</label>
                                    <select class="form-select" id="course_id" name="course_id" wire:model="course_id">
                                        <option value="0" selected>All</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if(!is_null($lessons))
                                    <div class="mt-4">
                                        <label class="form-label" for="lesson_id">By lesson</label>
                                        <select class="form-select" id="lesson_id" name="lesson_id" wire:model="lesson_id">
                                            <option value="0" selected>All</option>
                                            @foreach($lessons as $lesson)
                                                <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(count($tasks) > 0)
            <div class="table-responsive overflow-auto">
                <table class="my-table w-auto">
                    <thead class="col-12 w-100">
                        <tr class="row w-100 h-100 p-0 m-0 flex-nowrap">
                            <th style="width: 60px; cursor: pointer;" wire:click="sortBy('id')">
                                <span>#</span>
                                <i class="fa fa-angle-@if($orderBy == 'id' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th style="width: 100px; cursor: pointer;" wire:click="sortBy('file_count')">
                                <span>Media</span>
                                <i class="fa fa-angle-@if($orderBy == 'file_count' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th style="width: 150px;">Course</th>
                            <th style="width: 300px;">Lesson</th>
                            <th style="width: 350px;">Description</th>
                            <th style="width: 140px;" wire:click="sortBy('created_at')">
                                <span>Created at</span>
                                <i class="fa fa-angle-@if($orderBy == 'created_at' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th style="width: 80px;" class="text-center">Status</th>
                            <th style="width: 130px;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($tasks as $task)
                        <tr class="row w-100 h-100 p-0 m-0 flex-nowrap border-top" >
                            <td style="width: 60px;" class="justify-content-center">{{ $loop->index + 1 }}</td>
                            <td style="width: 100px;" class="justify-content-center">
                                <a href="{{ route('tasks.show', ['task' => $task->id]) }}">
                                    <span class="badge bg-primary">{{ $task->file_count }}</span>
                                </a>
                            </td>
                            <td style="width: 150px;">
                                <a href="{{ route('courses.show', ['course' => $task->lesson->course->id]) }}" class="fw-bold text-gray-darker">{{ $task->lesson->course->title }}</a>
                            </td>
                            <td style="width: 300px;">
                                <a href="{{ route('lessons.show', ['lesson' => $task->lesson->id]) }}" class="fw-bold text-gray-darker">{{ $task->lesson->title }}</a>
                            </td>
                            <td style="width: 350px;">{{ $task->shortDescription(4) }}</td>
                            <td style="width: 140px;">{{ $task->createdAt() }}</td>
                            <td style="width: 80px;" class="justify-content-center">{!! $task->status() !!}</td>
                            <td style="width: 130px;" class="justify-content-center">
                                <div class="btn-group">
                                    <a href="{{ route('tasks.edit', ['task' => $task->id]) }}" type="button" class="btn btn-sm btn-secondary rounded me-1">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <a href="{{ route('tasks.show', ['task' => $task->id]) }}" type="button" class="btn btn-sm btn-warning rounded me-1">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <livewire:task.delete :task="$task" :wire:key="'delete-task-' . $task->id" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-block pt-4">
                {{ $tasks->links() }}
            </div>
        @else
            <div class="col-12 text-center pb-4">
                <i class="fa fa-rotate text-primary display-6"></i>
                <h3 class="fw-bold mt-4 mb-4">Oops.. No data found!</h3>
                <a class="btn btn-primary" href="{{ route('tasks.create') }}">
                    <i class="fa fa-plus me-1"></i> Create task
                </a>
            </div>
        @endif
    </div>
</div>
