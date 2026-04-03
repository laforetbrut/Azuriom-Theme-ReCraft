@extends('layouts.base')

@section('app')
    <main class="container content" style="padding-top: 100px; padding-bottom: 60px;">
        @include('elements.session-alerts')

        @yield('content')
    </main>
@endsection
