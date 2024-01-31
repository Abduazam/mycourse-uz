<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h4 class="mb-0">Students</h4>
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
                    <div class="col-12 px-0">
                        <label class="w-100">
                            <input wire:model.debounce.100ms="search" type="text" class="form-control w-100" placeholder="Search by name..">
                        </label>
                    </div>
                </div>
            </div>
        </div>
        @if(count($students) > 0)
            <div class="table-responsive overflow-auto">
                <table class="my-table w-auto">
                    <thead class="col-12 w-100">
                    <tr class="row w-100 h-100 p-0 m-0 flex-nowrap">
                        <th style="width: 60px; cursor: pointer;" wire:click="sortBy('id')">
                            <span>#</span>
                            <i class="fa fa-angle-@if($orderBy == 'id' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                        </th>
                        <th style="width: 250px;">First name</th>
                        <th style="width: 200px;">Username</th>
                        <th style="width: 120px; cursor: pointer;" wire:click="sortBy('course_count')">
                            <span>Courses</span>
                            <i class="fa fa-angle-@if($orderBy == 'course_count' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                        </th>
                        <th style="width: 140px; cursor: pointer;" wire:click="sortBy('created_at')">
                            <span>Created at</span>
                            <i class="fa fa-angle-@if($orderBy == 'created_at' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                        </th>
                        <th style="width: 80px;" class="text-center">Status</th>
                        <th style="width: 90px;" class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $student)
                        <tr class="row w-100 h-100 p-0 m-0 flex-nowrap border-top" >
                            <td style="width: 60px;" class="justify-content-center">{{ $loop->index + 1 }}</td>
                            <td style="width: 250px;">
                                <a href="{{ route('users.show', ['user' => $student->id]) }}" class="fw-bold text-dark">{{ ucfirst($student->first_name) }}</a>
                            </td>
                            <td style="width: 200px;">{!! $student->getUsername() !!}</td>
                            <td style="width: 120px;">{{ $student->course_count }}</td>
                            <td style="width: 140px;">{{ $student->createdAt() }}</td>
                            <td style="width: 80px;" class="justify-content-center">{!! $student->getStatus($student->getSteps()->step_1, $student->getSteps()->step_2) !!}</td>
                            <td style="width: 90px;" class="justify-content-center">
                                <div class="btn-group">
                                    <a href="{{ route('students.show', ['student' => $student->id]) }}" type="button" class="btn btn-sm btn-dark rounded me-1">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-block pt-4">
                {{ $students->links() }}
            </div>
        @else
            <div class="col-12 text-center pb-4">
                <i class="fa fa-rotate text-primary display-6"></i>
                <h3 class="fw-bold mt-4 mb-4">Oops.. No data found!</h3>
            </div>
        @endif
    </div>
</div>
