<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h6 class="mb-0">Students</h6>
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
            <div class="col-lg-8 col-sm-7 col-8 pe-0">
                <label class="w-100">
                    <input wire:model.debounce.100ms="search" type="text" class="form-control form-control-sm w-100" placeholder="Search..">
                </label>
            </div>
        </div>
        @if(count($students) > 0)
            <div class="table-responsive overflow-auto">
                <table class="my-table w-auto fs-sm">
                    <thead class="col-12 w-100">
                        <tr class="row w-100 h-100 p-0 m-0 flex-nowrap">
                            <th style="width: 60px; cursor: pointer;" wire:click="sortBy('id')">
                                <span>#</span>
                                <i class="fa fa-angle-@if($orderBy == 'id' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th style="width: 200px;">
                                <span>First name</span>
                            </th>
                            <th style="width: 200px;">
                                <span>Username</span>
                            </th>
                            <th style="width: 300px;">
                                <span>Lesson</span>
                            </th>
                            <th style="width: 130px; cursor: pointer;" wire:click="sortBy('created_at')">
                                <span>Created at</span>
                                <i class="fa fa-angle-@if($orderBy == 'created_at' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th class="text-center" style="width: 80px;">
                                <span>Active</span>
                            </th>
                            <th class="text-center" style="width: 80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $student)
                        <tr class="row w-100 h-100 p-0 m-0 flex-nowrap border-top">
                            <td style="width: 60px;" class="justify-content-center">{{ $loop->index + 1 }}</td>
                            <td style="width: 200px;">{{ ucfirst($student->first_name) }}</td>
                            <td style="width: 200px;">{!! $student->getUsername() !!}</td>
                            <td style="width: 300px;">
                                <a class="text-dark text-decoration-underline" href="{{ route('lessons.show', ['lesson' => $student->lesson_id]) }}">{{ $student->title }}</a>
                            </td>
                            <td style="width: 130px;">{{ $student->createdAt() }}</td>
                            <td style="width: 80px;">{!! $student->getStatus($student->getSteps()->step_1, $student->getSteps()->step_2) !!}</td>
                            <td style="width: 80px;" class="justify-content-center">
                                <div class="btn-group">
                                    <a href="{{ route('users.show', ['user' => $student->id]) }}" type="button" class="btn btn-sm btn-secondary rounded me-1">
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
        @endif
    </div>
</div>
