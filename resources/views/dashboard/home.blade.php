<x-app-layout>
    <div class="row">
        <!-- Row #1 -->
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-link-shadow text-end" href="{{ route('users.index') }}">
                <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                    <div class="d-none d-sm-block">
                        <i class="fa fa-users fa-2x text-info"></i>
                    </div>
                    <div>
                        <div class="fs-3 fw-semibold">{{ $users }}</div>
                        <div class="fs-sm fw-semibold text-uppercase text-muted">Users</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-link-shadow text-end" href="{{ route('students.index') }}">
                <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                    <div class="d-none d-sm-block">
                        <i class="fa fa-user-graduate fa-2x text-success"></i>
                    </div>
                    <div>
                        <div class="fs-3 fw-semibold">{{ count($students) }}</div>
                        <div class="fs-sm fw-semibold text-uppercase text-muted">Students</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-link-shadow text-end" href="{{ route('applications.index') }}">
                <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                    <div class="d-none d-sm-block">
                        <i class="si si-info fa-2x text-pulse"></i>
                    </div>
                    <div>
                        <div class="fs-3 fw-semibold">{{ $applications }}</div>
                        <div class="fs-sm fw-semibold text-uppercase text-muted">Applications</div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-6 col-xl-3">
            <a class="block block-rounded block-link-shadow text-end" href="{{ route('user-tasks.index') }}">
                <div class="block-content block-content-full d-sm-flex justify-content-between align-items-center">
                    <div class="d-none d-sm-block">
                        <i class="si si-note fa-2x text-warning"></i>
                    </div>
                    <div>
                        <div class="fs-3 fw-semibold">{{ $user_tasks }}</div>
                        <div class="fs-sm fw-semibold text-uppercase text-muted">User tasks</div>
                    </div>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
