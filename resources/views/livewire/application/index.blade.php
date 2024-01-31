<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h4 class="mb-0">Applications</h4>
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
        @if(count($users) > 0)
            <div class="table-responsive">
                <table class="table table-striped table-vcenter border-top table-bordered">
                    <thead>
                    <tr>
                        <th class="text-capitalize" style="width: 60px; cursor: pointer;" wire:click="sortBy('id')">
                            <span>#</span>
                            <i class="fa fa-angle-@if($orderBy == 'id' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                        </th>
                        <th class="text-capitalize">
                            <span>First name</span>
                        </th>
                        <th class="text-capitalize">
                            <span>Username</span>
                        </th>
                        <th class="text-capitalize">
                            <span>Course</span>
                        </th>
                        <th class="text-capitalize" style="cursor: pointer;" wire:click="sortBy('created_at')">
                            <span>Joined date</span>
                            <i class="fa fa-angle-@if($orderBy == 'created_at' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                        </th>
                        <th class="text-capitalize text-center">
                            <span>Status</span>
                        </th>
                        <th class="text-capitalize text-center" style="width: 100px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="text-center">{{ $loop->index + 1 }}</td>
                            <td>{{ ucfirst($user->first_name) }}</td>
                            <td>{!! $user->getUsername() !!}</td>
                            <td>
                                <a href="{{ route('courses.show', ['course' => $user->course_id]) }}" class="text-dark fw-medium text-decoration-underline">{{ $user->course($user->course_id)->title }}</a>
                            </td>
                            <td>{{ $user->createdAt() }}</td>
                            <td class="text-center">
                                @if($user->status == 0)
                                    <span class="badge bg-pulse">Request</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <livewire:application.accept :user="$user" :course="$user->course_id" :wire:key="'accept-application-' . $user->id" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-block pt-4">
                {{ $users->links() }}
            </div>
        @else
            <div class="col-12 text-center pb-4">
                <i class="fa fa-rotate text-primary display-6"></i>
                <h3 class="fw-bold mt-4 mb-4">Oops.. No data found!</h3>
            </div>
        @endif
    </div>
</div>
