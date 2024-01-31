<x-app-layout>
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Keyboards</h3>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                @if(isset($keyboards) and count($keyboards) > 0)
                <table class="table table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">#</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($keyboards as $keyboard)
                            <tr>
                                <td class="text-center">{{ $loop->index + 1 }}</td>
                                <td>{{ $keyboard->title }}</td>
                                <td>{{ $keyboard->slug }}</td>
                                <td class="text-center">
                                    <i class="fa fa-check-circle text-success"></i>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <div class="text-center">
                        <p class="h3"><i class="far fa-circle-xmark pe-1 text-pulse"></i> No records</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
