@extends('layouts.base')

@section('title', theme_config('error_404_title') ?: 'Page introuvable')

@section('app')
@php
    $style404 = theme_config('error_404_style') ?: 'default';
    $anim404 = theme_config('error_404_animation') ?: 'float';
    $codeColor = theme_config('error_404_code_color') ?: '';
    $codeSize = theme_config('error_404_code_size') ?: '8rem';
    $title404 = theme_config('error_404_title') ?: 'Page introuvable';
    $subtitle404 = theme_config('error_404_subtitle') ?: '';
    $message404 = theme_config('error_404_message') ?: 'La page que tu recherches n\'existe pas ou a été déplacée.';
    $image404 = theme_config('error_404_image') ?: '';
    $bgImage404 = theme_config('error_404_bg_image') ?: '';
    $btnText = theme_config('error_404_button_text') ?: 'Retour à l\'accueil';
    $btnIcon = theme_config('error_404_button_icon') ?: 'house';
    $btnStyleMap = ['primary' => 'btn-primary', 'accent' => 'btn-accent', 'outline' => 'btn-outline', 'glass' => 'btn-glass'];
    $btnClass = $btnStyleMap[theme_config('error_404_button_style') ?: 'primary'] ?? 'btn-primary';
    $btn2Text = theme_config('error_404_button2_text') ?: '';
    $btn2Url = theme_config('error_404_button2_url') ?: '#';
    $btn2Icon = theme_config('error_404_button2_icon') ?: '';
    $showSearch = theme_config('error_404_show_search') === '1';
    $showParticles = theme_config('error_404_particles') !== '0';
@endphp

<div class="rc-error-page rc-error-{{ $style404 }}" @if($bgImage404) style="background-image: url('{{ $bgImage404 }}');" @endif>
    @if($bgImage404)
        <div class="rc-error-overlay"></div>
    @endif

    @if($showParticles)
        <div class="rc-error-particles"></div>
    @endif

    <div class="rc-error-content">
        {{-- Code 404 --}}
        <div class="rc-error-code rc-error-anim-{{ $anim404 }}" style="font-size: {{ $codeSize }};{{ $codeColor ? 'color:' . $codeColor . ';' : '' }}">
            404
        </div>

        {{-- Image optionnelle --}}
        @if($image404)
            <img src="{{ $image404 }}" alt="404" class="rc-error-image">
        @endif

        {{-- Textes --}}
        <h1>{{ $title404 }}</h1>
        @if($subtitle404)
            <h2>{{ $subtitle404 }}</h2>
        @endif
        <p>{{ $message404 }}</p>

        {{-- Barre de recherche --}}
        @if($showSearch)
            <form action="{{ url('/') }}" method="GET" class="rc-error-search">
                <div class="rc-error-search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" name="q" placeholder="Rechercher sur le site..." autocomplete="off">
                    <button type="submit" class="btn btn-primary btn-sm">Rechercher</button>
                </div>
            </form>
        @endif

        {{-- Boutons --}}
        <div class="rc-error-buttons">
            <a href="{{ route('home') }}" class="btn {{ $btnClass }} btn-lg">
                @if($btnIcon)
                    <i class="bi bi-{{ $btnIcon }}"></i>
                @endif
                {{ $btnText }}
            </a>
            @if($btn2Text)
                <a href="{{ $btn2Url }}" class="btn btn-outline btn-lg">
                    @if($btn2Icon)
                        <i class="bi bi-{{ $btn2Icon }}"></i>
                    @endif
                    {{ $btn2Text }}
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
