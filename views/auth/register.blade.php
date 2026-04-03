@extends('layouts.app')

@section('title', trans('auth.register'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="rc-card" style="padding:32px;">
                <h2 class="text-center mb-4" style="font-weight:800;">{{ trans('auth.register') }}</h2>

                @include('elements.session-alerts')

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="name">{{ trans('auth.name') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="email">{{ trans('auth.email') }}</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password">{{ trans('auth.password') }}</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                        @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="password_confirmation">{{ trans('auth.confirm_password') }}</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    @captcha

                    <button type="submit" class="btn btn-primary w-100">{{ trans('auth.register') }}</button>
                </form>

                <p class="text-center mt-3" style="color:var(--text-muted);font-size:0.9rem;">
                    <a href="{{ route('login') }}">{{ trans('auth.login') }}</a>
                </p>
            </div>
        </div>
    </div>
@endsection
