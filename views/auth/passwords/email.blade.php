@extends('layouts.app')

@section('title', trans('auth.forgot_password'))

@section('content')
    <section class="rc-section" style="padding-top:120px;min-height:80vh;display:flex;align-items:center;">
        <div class="container" style="max-width:450px;">
            <div class="rc-card" style="padding:40px;">
                <div class="text-center" style="margin-bottom:32px;">
                    <h1 style="font-size:1.8rem;font-weight:800;">{{ trans('auth.forgot_password') }}</h1>
                    <p style="color:var(--text-secondary);margin-top:8px;">Entrez votre email pour recevoir un lien de réinitialisation</p>
                </div>

                @if(session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @foreach($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endforeach

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="email">{{ trans('auth.email') }}</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;">
                        <i class="bi bi-envelope"></i> {{ trans('auth.send_password_reset') }}
                    </button>
                </form>

                <p style="text-align:center;margin-top:24px;color:var(--text-muted);font-size:0.9rem;">
                    <a href="{{ route('login') }}">← Retour à la connexion</a>
                </p>
            </div>
        </div>
    </section>
@endsection
