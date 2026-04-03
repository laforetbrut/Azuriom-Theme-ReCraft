@extends('layouts.app')

@section('title', @yield('code', 'Erreur'))

@section('content')
    <div class="rc-error-page">
        <div>
            <h1>@yield('code', '?')</h1>
            <h2>@yield('title', 'Erreur')</h2>
            <p>@yield('message', 'Une erreur est survenue.')</p>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="bi bi-house"></i> Retour
            </a>
        </div>
    </div>
@endsection
