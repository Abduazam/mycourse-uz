<x-app-layout>
    <div class="row w-100 h-100 p-0 m-0">
        <div class="col-lg-5 col-sm-6 ">
            <div class="block block-rounded block-link-shadow text-center overflow-hidden">
                @if(isset($course->file))
                    <div>
                        {!! $course->getFile() !!}
                    </div>
                @endif
                <div class="block-content block-content-ful pt-5 pb-3 ribbon ribbon-left @if($course->active == 1) {{ 'ribbon-success' }} @else {{ 'ribbon-danger' }} @endif">
                    <div class="fw-semibold mb-1"><h5 class="mb-0">{{ $course->title }}</h5></div>
                    <div class="fs-sm text-muted">{!! $course->description !!}</div>
                    <div class="ribbon-box fs-sm">
                        @if($course->active == 1) {{ 'Active' }} @else {{ 'Inactive' }} @endif
                    </div>
                </div>
                    <div class="block-content block-content-full block-content-sm">
                        <div class="fs-sm"><i class="si si-calendar opacity-75"></i> {!! $course->createdAt() !!}</div>
                    </div>
                <div class="block-content block-content-full block-content-sm bg-body-light d-flex justify-content-center">
                    <a href="{{ route('courses.index') }}" class="btn btn-sm btn-primary"><i class="fa fa-rotate-left"></i></a>
                    <a href="{{ route('courses.edit', ['course' => $course->id]) }}" type="button" class="btn btn-sm btn-secondary rounded mx-1">
                        <i class="fa fa-pencil-alt"></i>
                    </a>
                    <livewire:course.delete :course="$course" :wire:key="'delete-course-' . $course->id" />
                </div>
                <div class="block-content">
                    <div class="row items-push text-center">
                        <div class="col-4">
                            <div class="mb-1"><i class="si si-badge h2"></i></div>
                            <div><span class="small">Lessons</span></div>
                            <div class="text-muted">{{ count($course->lessons) }}</div>
                        </div>
                        <div class="col-4">
                            <div class="mb-1"><i class="si si-users h2"></i></div>
                            <div><span class="small">Students</span></div>
                            <div class="text-muted">{{ count($course->students) }}</div>
                        </div>
                        <div class="col-4">
                            <div class="mb-1"><i class="si si-info h2"></i></div>
                            <div><span class="small">Applications</span></div>
                            <div class="text-muted">{{ count($course->applications) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-7 col-sm-6">
            <livewire:course.lessons :course="$course" />

            <livewire:course.students :course="$course" />
        </div>
    </div>
</x-app-layout>
