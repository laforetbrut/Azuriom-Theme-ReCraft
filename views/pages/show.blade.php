@extends('layouts.app')

@section('title', $page->title)

@section('content')
    <h1>{{ $page->title }}</h1>

    <div class="rc-card mt-3">
        <div class="card-body">
            {!! $page->content !!}
        </div>
    </div>
@endsection
