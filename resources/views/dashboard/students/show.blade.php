<x-app-layout>
    <div class="row w-100 h-100 p-0 m-0">
        <div class="col-md-9 pe-md-4">
            <livewire:student.courses :student="$student" :wire:key="'courses-student-' . $student->id" />
            <livewire:student.tasks :student="$student" :wire:key="'tasks-student-' . $student->id" />
        </div>
        <div class="col-md-3 p-md-0">
            <div class="block block-rounded block-link-shadow text-center">
                <div class="block-content block-content-full">
                    <img class="img-avatar" src="/assets/media/avatars/avatar.jpg" alt="">
                </div>
                <div class="block-content block-content-full block-content-sm bg-body-light">
                    <div class="fw-semibold mb-1 h5"><a href="{{ route('users.show', ['user' => $student->id]) }}" class="text-dark">{{ ucfirst($student->first_name) }}</a></div>
                    <div class="fs-sm text-muted">{!! $student->getUsername() !!}</div>
                </div>
                <div class="block-content">
                    <div class="row items-push">
                        <div class="col-6">
                            <div class="mb-1"><i class="si si-notebook fa-2x"></i></div>
                            <div><span class="small">Courses</span></div>
                            <div class="text-muted">{{ count($student->courses) }}</div>
                        </div>
                        <div class="col-6">
                            <div class="mb-1"><i class="si si-trophy fa-2x"></i></div>
                            <div><span class="small">Completed</span></div>
                            <div class="text-muted">{{ count($student->tasks) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
