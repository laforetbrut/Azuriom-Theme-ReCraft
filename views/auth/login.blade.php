{{-- Utilise le template par défaut d'Azuriom avec le layout du thème --}}
@extends('layouts.app')

@section('title', trans('auth.login'))

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="rc-card" style="padding:32px;">
                <h2 class="text-center mb-4" style="font-weight:800;">{{ trans('auth.login') }}</h2>

                @include('elements.session-alerts')

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label" for="email">{{ trans('auth.email') }}</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
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

                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">{{ trans('auth.remember') }}</label>
                        </div>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" style="font-size:0.85rem;">{{ trans('auth.forgot_password') }}</a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-100">{{ trans('auth.login') }}</button>
                </form>

                @if(Route::has('register'))
                    <p class="text-center mt-3" style="color:var(--text-muted);font-size:0.9rem;">
                        <a href="{{ route('register') }}">{{ trans('auth.register') }}</a>
                    </p>
                @endif
            </div>
        </div>
    </div>
@endsection
