@extends('layouts.app')

@section('title', trans('messages.posts.posts'))

@section('content')
    <h1 class="mb-4">{{ trans('messages.posts.posts') }}</h1>

    @forelse($posts as $post)
        <article class="rc-article-card fade-in mb-4">
            <div class="row g-0">
                @if($post->hasImage())
                    <div class="col-md-4">
                        <a href="{{ route('posts.show', $post) }}">
                            <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" style="width:100%;height:100%;min-height:200px;object-fit:cover;border-radius:var(--radius-md) 0 0 var(--radius-md);" loading="lazy">
                        </a>
                    </div>
                @endif
                <div class="{{ $post->hasImage() ? 'col-md-8' : 'col-12' }}">
                    <div class="article-body">
                        <div class="article-meta">
                            @if($post->published_at)
                                <span>{{ format_date($post->published_at) }}</span>
                            @endif
                        </div>
                        <h3><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h3>
                        <p class="article-excerpt">{{ $post->description }}</p>
                        <div class="article-footer">
                            @if($post->author)
                                <div class="article-author">
                                    <img src="{{ $post->author->getAvatar() }}" alt="{{ $post->author->name }}" style="width:28px;height:28px;border-radius:var(--radius-sm);image-rendering:pixelated;" loading="lazy">
                                    <span style="color:var(--text-muted);font-size:0.85rem;">{{ $post->author->name }}</span>
                                </div>
                            @else
                                <div></div>
                            @endif
                            <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline">
                                Lire <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    @empty
        <div class="rc-card text-center" style="padding:60px 20px;">
            <i class="bi bi-newspaper" style="font-size:3rem;color:var(--text-muted);"></i>
            <p class="mt-3" style="color:var(--text-muted);">Aucun article pour le moment.</p>
        </div>
    @endforelse
@endsection
