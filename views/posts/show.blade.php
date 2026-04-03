@extends('layouts.app')

@section('title', $post->title)
@section('description', $post->description)

@section('content')
    <h1>{{ $post->title }}</h1>

    <div style="display:flex;align-items:center;gap:12px;margin:16px 0 32px;flex-wrap:wrap;">
        @if($post->author)
            <img src="{{ $post->author->getAvatar() }}" alt="{{ $post->author->name }}" style="width:36px;height:36px;border-radius:var(--radius-sm);image-rendering:pixelated;">
            <div>
                <span style="font-weight:600;font-size:0.9rem;">{{ $post->author->name }}</span>
                @if($post->published_at)
                    <span style="color:var(--text-muted);font-size:0.85rem;margin-left:8px;">{{ format_date($post->published_at) }}</span>
                @endif
            </div>
        @endif
    </div>

    @if($post->hasImage())
        <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" style="width:100%;border-radius:var(--radius-md);margin-bottom:24px;" loading="lazy">
    @endif

    <div class="rc-card">
        <div class="card-body">
            {!! $post->content !!}
        </div>
    </div>
@endsection
