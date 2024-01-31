<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h6 class="mb-0">Tasks</h6>
        @if(count($tasks) > 0)
            <a href="{{ route('tasks.create') }}" class="btn btn-sm btn-primary"><i class="fa fa-plus me-1"></i> Create task</a>
        @endif
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
        @if(count($tasks) > 0)
            <div class="table-responsive overflow-auto">
                <table class="my-table w-auto fs-sm">
                    <thead class="col-12 w-100">
                    <tr class="row w-100 h-100 p-0 m-0 flex-nowrap">
                        <th style="width: 60px; cursor: pointer;" wire:click="sortBy('id')">
                            <span>#</span>
                            <i class="fa fa-angle-@if($orderBy == 'id' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                        </th>
                        <th style="width: 80px; cursor: pointer;" wire:click="sortBy('file_count')">
                            <span>Files</span>
                            <i class="fa fa-angle-@if($orderBy == 'file_count' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                        </th>
                        <th style="width: 200px;">
                            <span>Task</span>
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
                    @foreach($tasks as $task)
                        <tr class="row w-100 h-100 p-0 m-0 flex-nowrap border-top">
                            <td style="width: 60px; cursor: pointer;" class="justify-content-center">{{ $loop->index + 1 }}</td>
                            <td style="width: 80px;" class="justify-content-center">
                                <a href="{{ route('tasks.show', ['task' => $task->id]) }}">
                                    <span class="badge bg-primary">{{ $task->file_count }}</span>
                                </a>
                            </td>
                            <td style="width: 200px;" class="fw-semibold">
                                <a href="{{ route('tasks.show', ['task' => $task->id]) }}" class="text-dark">
                                    {!! $task->description !!}
                                </a>
                            </td>
                            <td style="width: 130px;">{{ $task->createdAt() }}</td>
                            <td style="width: 80px;" class="justify-content-center">{!! $task->status() !!}</td>
                            <td style="width: 80px;" class="justify-content-center">
                                <div class="btn-group">
                                    <a href="{{ route('tasks.edit', ['task' => $task->id]) }}" type="button" class="btn btn-sm btn-secondary rounded me-1">
                                        <i class="fa fa-pencil-alt"></i>
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
                <i class="fa fa-rotate text-primary h3"></i>
                <h4 class="fw-bold">Oops.. No data found!</h4>
                <a class="btn btn-sm btn-primary" href="{{ route('tasks.create') }}">
                    <i class="fa fa-plus me-1"></i> Create task
                </a>
            </div>
        @endif
    </div>
</div>
