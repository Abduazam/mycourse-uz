<div class="block block-rounded">
    @if (session('errorBag'))
        <div>
            <ul>
                @foreach (session('errorBag')->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="block-header block-header-default">
        <h4 class="mb-0">Lessons</h4>
        @if(count($lessons) > 0)
            <a href="{{ route('lessons.create') }}" class="btn btn-primary"><i class="fa fa-plus me-1"></i> Create lesson</a>
        @endif
    </div>
    <div class="block-content">
        <div class="row w-100 h-100 mx-0 px-0 pb-4 justify-content-between">
            <div class="col-md-2 col-3 ps-0">
                <div class="col-sm-7 col-12 m-0">
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
                    <div class="col-sm-10 col-9 px-0">
                        <label class="w-100">
                            <input wire:model.debounce.100ms="search" type="text" class="form-control w-100" placeholder="Search..">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(count($lessons) > 0)
            <div class="table-responsive overflow-auto ">
                <table class="my-table w-auto">
                    <thead class="col-12 w-100">
                        <tr class="row w-100 h-100 p-0 m-0 flex-nowrap">
                            <th class="text-capitalize" style="width: 60px; cursor: pointer;" wire:click="sortBy('id')">
                                <span>#</span>
                                <i class="fa fa-angle-@if($orderBy == 'id' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th class="text-capitalize" style="width: 350px; cursor: pointer;">
                                <span>Lesson title</span>
                            </th>
                            <th class="text-capitalize" style="width: 200px; cursor: pointer;">
                                <span>Course</span>
                            </th>
                            <th class="text-capitalize" style="width: 100px; cursor: pointer;" wire:click="sortBy('task_count')">
                                <span>Tasks</span>
                                <i class="fa fa-angle-@if($orderBy == 'task_count' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th class="text-capitalize" style="width: 125px; cursor: pointer;" wire:click="sortBy('student_count')">
                                <span>Students</span>
                                <i class="fa fa-angle-@if($orderBy == 'student_count' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th class="text-capitalize" style="width: 150px; cursor: pointer;" wire:click="sortBy('created_at')">
                                <span>Created at</span>
                                <i class="fa fa-angle-@if($orderBy == 'created_at' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th class="text-capitalize text-center" style="width: 100px;">
                                <span>Active</span>
                            </th>
                            <th class="text-capitalize text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($lessons as $lesson)
                        <tr class="row w-100 h-100 p-0 m-0 flex-nowrap border-top">
                            <td class="justify-content-center" style="width: 60px;">{{ $loop->index + 1 }}</td>
                            <td class="fw-semibold" style="width: 350px;">
                                <a class="text-dark text-decoration-underline" href="{{ route('lessons.show', ['lesson' => $lesson->id]) }}">{{ $lesson->title }}</a>
                            </td>
                            <td class="fw-semibold" style="width: 200px;">
                                <a class="text-dark text-decoration-underline" href="{{ route('courses.show', ['course' => $lesson->course->id]) }}">{{ $lesson->course->title }}</a>
                            </td>
                            <td style="width: 100px;">{{ $lesson->task_count }}</td>
                            <td style="width: 125px;">{{ $lesson->student_count }}</td>
                            <td style="width: 150px;">{{ $lesson->createdAt() }}</td>
                            <td style="width: 100px;" class="justify-content-center">{!! $lesson->status() !!}</td>
                            <td style="width: 100px;" class="justify-content-center">
                                <div class="btn-group">
                                    <a href="{{ route('lessons.edit', ['lesson' => $lesson->id]) }}" type="button" class="btn btn-sm btn-secondary rounded me-1">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <livewire:lesson.delete :lesson="$lesson" :wire:key="'delete-lesson-' . $lesson->id" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-block pt-4">
                {{ $lessons->links() }}
            </div>
        @else
            <div class="col-12 text-center pb-4">
                <i class="fa fa-rotate text-primary display-6"></i>
                <h3 class="fw-bold mt-4 mb-4">Oops.. No data found!</h3>
                <a class="btn btn-primary" href="{{ route('lessons.create') }}">
                    <i class="fa fa-plus me-1"></i> Create lesson
                </a>
            </div>
        @endif
    </div>
</div>
