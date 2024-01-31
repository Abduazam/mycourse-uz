<x-app-layout>
    <!-- Page Content -->
    <div class="bg-body-dark">
        <div class="row mx-0 justify-content-center">
            <div class="hero-static col-sm-8 col-md-7 col-xl-5">
                <div class="content content-full overflow-hidden pt-5">
                    <!-- Header -->
                    <div class="py-4 text-center">
                        <a class="link-fx fw-bold" href="{{ url('/') }}">
                            <i class="fa fa-fire"></i>
                            <span class="fs-4 text-body-color">code</span><span class="fs-4">base</span>
                        </a>
                        <h1 class="h3 fw-bold mt-4 mb-2">Welcome to Your Dashboard</h1>
                        <h2 class="h5 fw-medium text-muted mb-0">It’s a great day today!</h2>
                    </div>

                    <!-- Sign In Form -->
                    <form class="js-validation-signin" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="block block-themed block-rounded block-fx-shadow">
                            <div class="block-header bg-gd-dusk">
                                <h3 class="block-title">Please Sign In</h3>
                            </div>
                            <div class="block-content">
                                <div class="form-floating mb-4">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autocomplete="email" autofocus>
                                    <label class="form-label" for="email">Email</label>
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-floating mb-4">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                                    <label class="form-label" for="password">Password</label>
                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-12 text-sm-end push">
                                        <button type="submit" class="btn btn-lg btn-alt-primary fw-medium w-100">
                                            Sign In
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
