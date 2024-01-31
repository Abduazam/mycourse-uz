<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h4 class="mb-0">Questions</h4>
        @if(count($questions) > 0)
            <livewire:question.store />
        @endif
    </div>
    <div class="block-content">
        <div class="row w-100 h-100 mx-0 px-0 pb-4 justify-content-between">
            <div class="col-md-2 col-sm-3 col-4 ps-0">
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
        @if(count($questions) > 0)
            <div class="table-responsive overflow-auto ">
                <table class="my-table w-auto">
                    <thead class="col-12 w-100">
                        <tr class="row w-100 h-100 p-0 m-0 flex-nowrap">
                            <th style="width: 50px;"></th>
                            <th style="width: 60px;" wire:click="sortBy('id')">
                                <span>#</span>
                                <i class="fa fa-angle-@if($orderBy == 'id' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th style="width: 400px;">Question</th>
                            <th style="width: 120px; cursor: pointer;" class="text-capitalize" wire:click="sortBy('position')">
                                <span>Position</span>
                                <i class="fa fa-angle-@if($orderBy == 'position' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th class="text-capitalize" style="width: 135px; cursor: pointer;" wire:click="sortBy('keyboard_id')">
                                <span>Keyboard</span>
                                <i class="fa fa-angle-@if($orderBy == 'keyboard_id' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th class="text-capitalize" style="width: 150px; cursor: pointer;" wire:click="sortBy('created_at')">
                                <span>Created at</span>
                                <i class="fa fa-angle-@if($orderBy == 'created_at' and $orderDirection == 'asc'){{ 'down' }}@else{{ 'up' }}@endif small float-end mt-1"></i>
                            </th>
                            <th style="width: 100px;" class="text-center">Status</th>
                            <th style="width: 100px;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody wire:sortable="updateQuestionPosition">
                    @foreach($questions as $question)
                        <tr class="row w-100 p-0 m-0 flex-nowrap border-top" wire:sortable.item="{{ $question->id }}" wire:key="question-{{ $question->id }}">
                            <td style="width: 50px;"><i class="fa fa-arrows-up-down-left-right text-muted px-1" style="cursor: move;" wire:sortable.handle></i></td>
                            <td style="width: 60px;" class="justify-content-center">{{ $loop->index + 1 }}</td>
                            <td style="width: 400px;" class="fw-semibold">{{ $question->question }}</td>
                            <td style="width: 120px;">{{ $question->position }}</td>
                            <td style="width: 135px;">{!! $question->getKeyboard() !!}</td>
                            <td style="width: 150px;">{{ $question->createdAt() }}</td>
                            <td style="width: 100px;" class="justify-content-center">{!! $question->status() !!}</td>
                            <td style="width: 100px;" class="justify-content-center">
                                <div class="btn-group">
                                    <livewire:question.edit :question="$question" :wire:key="'edit-question-' . $question->id" />
                                    <livewire:question.delete :question="$question" :wire:key="'delete-question-' . $question->id" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="pagination-block pt-4">
                {{ $questions->links() }}
            </div>
        @else
            <div class="col-12 text-center pb-4">
                <i class="fa fa-rotate text-primary display-6"></i>
                <h3 class="fw-bold mt-4 mb-4">Oops.. No data found!</h3>
                <livewire:question.store />
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        window.addEventListener('questionChanged', function (e) {
            Swal.fire({
                title: e.detail.title,
                icon: e.detail.icon,
                iconColor: e.detail.iconColor,
                timer: 3000,
                toast: true,
                position: 'top-right',
                timerProgressBar: true,
                showConfirmButton: false,
            });
        });
    </script>
@endpush
