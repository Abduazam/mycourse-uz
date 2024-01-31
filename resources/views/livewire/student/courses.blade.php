<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h6 class="mb-0">Courses</h6>
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
        </div>
        @if(count($courses) > 0)
            <div class="table-responsive overflow-auto ">
                <table class="my-table w-auto fs-sm">
                    <thead class="col-12 w-100">
                        <tr class="row w-100 h-100 p-0 m-0 flex-nowrap">
                            <th style="width: 60px; cursor: pointer;" wire:click="sortBy('id')">
                                <span>#</span>
                                <i class="fa fa-angle-@if($orderBy == 'id' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
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
                            <th class="text-center" style="width: 80px;">
                                <span>Active</span>
                            </th>
                            <th class="text-center" style="width: 90px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($courses as $course)
                        <tr class="row w-100 h-100 p-0 m-0 flex-nowrap border-top">
                            <td class="justify-content-center" style="width: 60px;">{{ $loop->index + 1 }}</td>
                            <td class="fw-semibold" style="width: 250px;">
                                <a class="text-dark text-decoration-underline" href="{{ route('courses.show', ['course' => $course->course_id]) }}">{{ $course->course_title }}</a>
                            </td>
                            <td class="fw-semibold" style="width: 300px;">
                                <a class="text-dark text-decoration-underline" href="{{ route('lessons.show', ['lesson' => $course->lesson_id]) }}">{{ $course->lesson_title }}</a>
                            </td>
                            <td style="width: 130px;">{{ $course->createdAt() }}</td>
                            <td style="width: 80px;" class="justify-content-center">{!! $course->getStatus() !!}</td>
                            <td style="width: 90px;" class="justify-content-between">
                                <livewire:student.change :course="$course" :wire:key="'change-course-' . $course->id" />
                                <livewire:student.delete :course="$course" :wire:key="'delete-course-' . $course->id" />
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
                <i class="fa fa-rotate text-primary h3"></i>
                <h4 class="fw-bold">Oops.. No data found!</h4>
                <a class="btn btn-sm btn-primary" href="{{ route('lessons.create') }}">
                    <i class="fa fa-plus me-1"></i> Create lesson
                </a>
            </div>
        @endif
    </div>
</div>
