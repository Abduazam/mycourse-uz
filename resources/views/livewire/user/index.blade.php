<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h4 class="mb-0">Users</h4>
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
                            <div class="dropdown-menu px-4 py-4" aria-labelledby="dropdown-content-rich-primary" wire:ignore.self>
                                <div>
                                    <label class="form-label">Status</label>
                                    <div class="space-y-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="active" name="active" wire:model="active">
                                            <label class="form-check-label" for="active">Active</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="authorized" name="authorized" wire:model="authorized">
                                            <label class="form-check-label" for="authorized">Authorized</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1" id="inactive" name="inactive" wire:model="inactive">
                                            <label class="form-check-label" for="inactive">Inactive</label>
                                        </div>
                                    </div>
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
                                <span>Answer</span>
                            </th>
                            <th class="text-capitalize" style="cursor: pointer;" wire:click="sortBy('created_at')">
                                <span>Joined date</span>
                                <i class="fa fa-angle-@if($orderBy == 'created_at' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th class="text-capitalize text-center">
                                <span>Active</span>
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
                            <td>{!! $user->getAnswersCount() !!}</td>
                            <td>{{ $user->createdAt() }}</td>
                            <td class="text-center">{!! $user->getStatus($user->step_1, $user->step_2) !!}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('users.show', ['user' => $user->id]) }}" type="button" class="btn btn-sm btn-secondary rounded me-1">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    @if(($user->step_1 == 0 and $user->step_2 == 1) or ($user->step_1 < 1))
                                        <livewire:user.accept :user="$user" :wire:key="'accept-user-' . $user->id" />
                                    @else
                                        <livewire:user.edit :user="$user" :wire:key="'edit-user-' . $user->id" />
                                        <livewire:user.block :user="$user" :wire:key="'block-user-' . $user->id" />
                                    @endif
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
