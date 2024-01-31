<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h4 class="mb-0">Reminders</h4>
        <a href="{{ route('reminders.create') }}" class="btn btn-primary"><i class="fa fa-plus me-1"></i> Create reminder</a>
    </div>
    <div class="block-content pb-4">
        @if(count($reminders) > 0)
            <div class="table-responsive overflow-auto">
                <table class="my-table w-auto">
                    <thead class="col-12 w-100">
                        <tr class="row w-100 h-100 p-0 m-0 flex-nowrap">
                            <th class="text-capitalize text-center" style="width: 60px;">#</th>
                            <th class="text-capitalize text-center" style="width: 100px;">Media</th>
                            <th class="text-capitalize" style="width: 300px;">Text</th>
                            <th class="text-capitalize text-center" style="width: 100px;">Per days</th>
                            <th class="text-capitalize text-center" style="width: 150px;">Next day</th>
                            <th class="text-capitalize text-center" style="width: 150px;">Created at</th>
                            <th style="width: 100px;" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($reminders as $reminder)
                        <tr class="row w-100 p-0 m-0 flex-nowrap border-top">
                            <td style="width: 60px;" class="justify-content-center">{{ $loop->index + 1 }}</td>
                            <td style="width: 100px;" class="fw-semibold">{!! $reminder->getFile() !!}</td>
                            <td style="width: 300px;">{!! $reminder->text !!}</td>
                            <td style="width: 100px;">{{ $reminder->per_day }}</td>
                            <td style="width: 150px;">{{ $reminder->next_day }}</td>
                            <td style="width: 150px;">{{ $reminder->createdAt() }}</td>
                            <td style="width: 100px;" class="justify-content-center">
                                <div class="btn-group">
                                    <a href="{{ route('reminders.edit', ['reminder' => $reminder->id]) }}" type="button" class="btn btn-sm btn-secondary rounded me-1">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <livewire:reminder.delete :reminder="$reminder" :wire:key="'delete-reminder-' . $reminder->id" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
