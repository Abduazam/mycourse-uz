<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h6 class="mb-0">Tasks</h6>
    </div>
    <div class="block-content">
        <div class="row w-100 h-100 mx-0 px-0 pb-4 justify-content-between">
            <div class="col-xl-2 col-lg-3 col-sm-4 col-3 ps-0">
                <div class="col-12">
                    <label for="perPage" hidden=""></label>
                    <select wire:model="perPage" class="form-select form-select-sm" id="perPage">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="0">All</option>
                    </select>
                </div>
            </div>
            <div class="col-xl-1 col-3 pe-0 d-flex justify-content-end">
                <div class="dropdown push mb-0">
                    <button type="button" class="btn btn-sm bg-white border border-lighter dropdown-toggle" id="dropdown-content-rich-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-filter"></i></button>
                    <div class="dropdown-menu p-3" aria-labelledby="dropdown-content-rich-primary" style="width: 250px;">
                        <div class="mb-0">
                            <label class="form-label fs-sm" for="course_id">By course</label>
                            <select class="form-select form-select-sm" id="course_id" name="course_id" wire:model="course_id">
                                <option value="0" selected>All</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(count($tasks) > 0)
            <div class="table-responsive overflow-auto ">
                <table class="my-table w-auto fs-sm">
                    <thead class="col-12 w-100">
                    <tr class="row w-100 h-100 p-0 m-0 flex-nowrap">
                        <th style="width: 60px; cursor: pointer;" wire:click="sortBy('id')">
                            <span>#</span>
                            <i class="fa fa-angle-@if($orderBy == 'id' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                        </th>
                        <th style="width: 80px; cursor: pointer;" wire:click="sortBy('file_count')">
                            <span>File</span>
                            <i class="fa fa-angle-@if($orderBy == 'file_count' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                        </th>
                        <th style="width: 250px; cursor: pointer;">
                            <span>Course</span>
                        </th>
                        <th style="width: 300px; cursor: pointer;">
                            <span>Lesson</span>
                        </th>
                        <th style="width: 130px; cursor: pointer;" wire:click="sortBy('created_at')">
                            <span>Created at</span>
                            <i class="fa fa-angle-@if($orderBy == 'created_at' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                        </th>
                        <th class="text-center" style="width: 90px;">
                            <span>Status</span>
                        </th>
                        <th class="text-center" style="width: 100px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tasks as $task)
                        <tr class="row w-100 h-100 p-0 m-0 flex-nowrap border-top">
                            <td class="justify-content-center" style="width: 60px;">{{ $loop->index + 1 }}</td>
                            <td style="width: 80px;" class="justify-content-center">
                                <a href="{{ route('user-tasks.show', ['user_task' => $task->id]) }}">
                                    <span class="badge bg-primary">{{ $task->file_count }}</span>
                                </a>
                            </td>
                            <td class="fw-semibold" style="width: 250px;">
                                <a class="text-dark text-decoration-underline" href="{{ route('courses.show', ['course' => $task->course_id]) }}">{{ $task->course_title }}</a>
                            </td>
                            <td class="fw-semibold" style="width: 300px;">
                                <a class="text-dark text-decoration-underline" href="{{ route('lessons.show', ['lesson' => $task->lesson_id]) }}">{{ $task->lesson_title }}</a>
                            </td>
                            <td style="width: 130px;">{{ $task->createdAt() }}</td>
                            <td style="width: 90px;" class="justify-content-center">{!! $task->taskStatus() !!}</td>
                            <td style="width: 100px;" class="justify-content-center">
                                <a href="{{ route('user-tasks.show', ['user_task' => $task->id]) }}" type="button" class="btn btn-sm btn-dark rounded me-1">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @if($task->status == 1)
                                    <livewire:user-task.accept :user_task="$task" :wire:key="'accept-user-task-' . $task->id" />
                                @endif
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
                <i class="fa fa-rotate text-primary h3"></i>
                <h4 class="fw-bold">Oops.. No data found!</h4>
            </div>
        @endif
    </div>
</div>
