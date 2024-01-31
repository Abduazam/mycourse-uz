<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h4 class="mb-0">Courses</h4>
        @if(count($courses) > 0)
            <a href="{{ route('courses.create') }}" class="btn btn-primary"><i class="fa fa-plus me-1"></i> Create course</a>
        @endif
    </div>
    <div class="block-content">
        <div class="row w-100 h-100 mx-0 px-0 pb-4 justify-content-between">
            <div class="col-sm-2 col-4 ps-0">
                <div class="col-sm-7 col-9">
                    <label for="perPage" hidden=""></label>
                    <select wire:model="perPage" class="form-select" id="perPage">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="0">All</option>
                    </select>
                </div>
            </div>
            <div class="col-lg-5 col-sm-7 col-8 pe-0">
                <label class="w-100">
                    <input wire:model.debounce.100ms="search" type="text" class="form-control w-100" placeholder="Search..">
                </label>
            </div>
        </div>
        @if(count($courses) > 0)
            <div class="table-responsive overflow-auto ">
                <table class="my-table w-auto">
                    <thead class="col-12 w-100">
                        <tr class="row w-100 h-100 p-0 m-0 flex-nowrap">
                            <th class="text-capitalize" style="width: 60px; cursor: pointer;" wire:click="sortBy('id')">
                                <span>#</span>
                                <i class="fa fa-angle-@if($orderBy == 'id' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th class="text-capitalize" style="width: 270px; cursor: pointer;" wire:click="sortBy('title')">
                                <span>Course title</span>
                                <i class="fa fa-angle-@if($orderBy == 'title' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th class="text-capitalize" style="width: 120px; cursor: pointer;" wire:click="sortBy('lesson_count')">
                                <span>Lessons</span>
                                <i class="fa fa-angle-@if($orderBy == 'lesson_count' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th class="text-capitalize" style="width: 125px; cursor: pointer;" wire:click="sortBy('student_count')">
                                <span>Students</span>
                                <i class="fa fa-angle-@if($orderBy == 'student_count' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th class="text-capitalize" style="width: 150px; cursor: pointer;" wire:click="sortBy('application_count')">
                                <span>Application</span>
                                <i class="fa fa-angle-@if($orderBy == 'student_count' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th class="text-capitalize" style="width: 150px; cursor: pointer;" wire:click="sortBy('created_at')">
                                <span>Created at</span>
                                <i class="fa fa-angle-@if($orderBy == 'created_at' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th style="width: 100px;" class="text-center">Status</th>
                            <th style="width: 100px;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($courses as $course)
                        <tr class="row w-100 p-0 m-0 flex-nowrap border-top">
                            <td style="width: 60px;" class="justify-content-center">{{ $loop->index + 1 }}</td>
                            <td style="width: 270px;" class="fw-semibold">
                                <a class="text-dark text-decoration-underline" href="{{ route('courses.show', ['course' => $course->id]) }}">{{ $course->title }}</a>
                            </td>
                            <td style="width: 120px;">{{ $course->lesson_count }}</td>
                            <td style="width: 125px;">{{ $course->student_count }}</td>
                            <td style="width: 150px;">{{ $course->application_count }}</td>
                            <td style="width: 150px;">{{ $course->createdAt() }}</td>
                            <td style="width: 100px;" class="justify-content-center">{!! $course->status() !!}</td>
                            <td style="width: 100px;" class="justify-content-center">
                                <div class="btn-group">
                                    <a href="{{ route('courses.edit', ['course' => $course->id]) }}" type="button" class="btn btn-sm btn-secondary rounded me-1">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <livewire:course.delete :course="$course" :wire:key="'delete-course-' . $course->id" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-block pt-4">
                {{ $courses->links() }}
            </div>
        @else
            <div class="col-12 text-center pb-4">
                <i class="fa fa-rotate text-primary display-6"></i>
                <h3 class="fw-bold mt-4 mb-4">Oops.. No data found!</h3>
                <a class="btn btn-primary" href="{{ route('courses.create') }}">
                    <i class="fa fa-plus me-1"></i> Create course
                </a>
            </div>
        @endif
    </div>
</div>
