@extends('layouts.app')

@section('title', trans('auth.reset_password'))

@section('content')
    <section class="rc-section" style="padding-top:120px;min-height:80vh;display:flex;align-items:center;">
        <div class="container" style="max-width:450px;">
            <div class="rc-card" style="padding:40px;">
                <div class="text-center" style="margin-bottom:32px;">
                    <h1 style="font-size:1.8rem;font-weight:800;">{{ trans('auth.reset_password') }}</h1>
                </div>

                @foreach($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endforeach

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group">
                        <label class="form-label" for="email">{{ trans('auth.email') }}</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ $email ?? old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">{{ trans('auth.password') }}</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">{{ trans('auth.confirm_password') }}</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;">
                        {{ trans('auth.reset_password') }}
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
