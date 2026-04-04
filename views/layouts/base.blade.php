<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', setting('description', ''))">
    <meta name="theme-color" content="{{ theme_config('color_primary') ?: '#6d28d9' }}">

    <meta property="og:title" content="@yield('title')">
    <meta property="og:type" content="@yield('type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ favicon() }}">
    <meta property="og:description" content="@yield('description', setting('description', ''))">
    <meta property="og:site_name" content="{{ site_name() }}">
    @stack('meta')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ site_name() }}</title>

    <link rel="shortcut icon" href="{{ favicon() }}">

    {{-- Azuriom core scripts --}}
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
    <script src="{{ asset('vendor/axios/axios.min.js') }}" defer></script>
    <script src="{{ asset('js/script.js') }}" defer></script>
    <script src="{{ theme_asset('js/script.js') }}" defer></script>
    @stack('scripts')

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    @php
        $fontMap = [
            'inter' => 'Inter:wght@400;500;600;700;800;900',
            'poppins' => 'Poppins:wght@400;500;600;700;800;900',
            'roboto' => 'Roboto:wght@400;500;700;900',
            'montserrat' => 'Montserrat:wght@400;500;600;700;800;900',
            'nunito' => 'Nunito:wght@400;500;600;700;800;900',
            'raleway' => 'Raleway:wght@400;500;600;700;800;900',
            'ubuntu' => 'Ubuntu:wght@400;500;700',
            'lato' => 'Lato:wght@400;700;900',
            'open-sans' => 'Open+Sans:wght@400;500;600;700;800',
            'outfit' => 'Outfit:wght@400;500;600;700;800;900',
        ];
        $selectedFont = theme_config('global_font') ?: 'inter';
        $googleFont = $fontMap[$selectedFont] ?? $fontMap['inter'];
        $fontFamily = [
            'inter' => "'Inter'",
            'poppins' => "'Poppins'",
            'roboto' => "'Roboto'",
            'montserrat' => "'Montserrat'",
            'nunito' => "'Nunito'",
            'raleway' => "'Raleway'",
            'ubuntu' => "'Ubuntu'",
            'lato' => "'Lato'",
            'open-sans' => "'Open Sans'",
            'outfit' => "'Outfit'",
        ];
        $mainFont = ($fontFamily[$selectedFont] ?? "'Inter'") . ", -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif";
    @endphp
    @php
        // Collect all unique fonts needed (global + per-section overrides)
        $sectionFontKeys = ['font_navbar', 'font_hero_title', 'font_hero_subtitle', 'font_headings', 'font_footer', 'font_stats'];
        $fontsToLoad = [$googleFont]; // always load the main font
        $sectionFontFamilies = [];
        foreach ($sectionFontKeys as $sfKey) {
            $sfVal = theme_config($sfKey);
            if ($sfVal && isset($fontMap[$sfVal]) && $sfVal !== $selectedFont) {
                $gf = $fontMap[$sfVal];
                if (!in_array($gf, $fontsToLoad)) {
                    $fontsToLoad[] = $gf;
                }
            }
            $sectionFontFamilies[$sfKey] = ($sfVal && isset($fontFamily[$sfVal]))
                ? $fontFamily[$sfVal] . ", -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif"
                : null;
        }
    @endphp
    <link href="https://fonts.googleapis.com/css2?{{ collect($fontsToLoad)->map(fn($f) => 'family=' . $f)->join('&') }}&display=swap" rel="stylesheet">

    {{-- Azuriom core styles --}}
    <link href="{{ asset('vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/base.css') }}" rel="stylesheet">

    {{-- Theme styles --}}
    <link href="{{ theme_asset('css/style.css') }}" rel="stylesheet">

    {{-- Dynamic CSS Variables from config --}}
    @php
        $primary = theme_config('color_primary') ?: '#6d28d9';
        $primaryLight = theme_config('color_primary_light') ?: '#8b5cf6';
        $accent = theme_config('color_accent') ?: '#fbbf24';
        $bgDark = theme_config('color_bg') ?: '#0c0c1d';
        $bgCard = theme_config('color_card') ?: '#111128';
        $borderColor = theme_config('color_border') ?: '#1e1e40';
        $textPrimary = theme_config('color_text') ?: '#e8e8f0';
        $textSecondary = theme_config('color_text_secondary') ?: '#9d9dba';

        // Compute primary-dark (darken primary by ~30%)
        $primaryDark = $primary;
        if (preg_match('/^#([0-9a-f]{6})$/i', $primary, $m)) {
            $r = max(0, hexdec(substr($m[1],0,2)) - 50);
            $g = max(0, hexdec(substr($m[1],2,2)) - 20);
            $b = max(0, hexdec(substr($m[1],4,2)) - 30);
            $primaryDark = sprintf('#%02x%02x%02x', $r, $g, $b);
        }

        // Border radius mapping
        $radiusMap = [
            'none' => ['0px', '0px', '0px'],
            'small' => ['4px', '6px', '8px'],
            'medium' => ['8px', '12px', '16px'],
            'large' => ['12px', '16px', '24px'],
            'full' => ['20px', '28px', '9999px'],
        ];
        $radiusChoice = theme_config('global_border_radius') ?: 'small';
        $radii = $radiusMap[$radiusChoice] ?? $radiusMap['small'];

        // Animation speed
        $animSpeedMap = [
            'slow' => '0.4s',
            'normal' => '0.2s',
            'fast' => '0.1s',
        ];
        $animSpeed = $animSpeedMap[theme_config('global_animation_speed') ?: 'normal'] ?? '0.2s';

        // Page width
        $pageWidth = theme_config('global_page_width') ?: '1200';
    @endphp
    <style>
        :root {
            --primary: {{ $primary }};
            --primary-light: {{ $primaryLight }};
            --primary-dark: {{ $primaryDark }};
            --primary-glow: {{ $primary }}4d;
            --accent: {{ $accent }};
            --bg-dark: {{ $bgDark }};
            --bg-card: {{ $bgCard }};
            --border-color: {{ $borderColor }};
            --text-primary: {{ $textPrimary }};
            --text-secondary: {{ $textSecondary }};
            --font-main: {{ $mainFont }};
            --radius-sm: {{ $radii[0] }};
            --radius-md: {{ $radii[1] }};
            --radius-lg: {{ $radii[2] }};
            --transition: all {{ $animSpeed }} ease;
            @if($sectionFontFamilies['font_navbar']) --font-navbar: {{ $sectionFontFamilies['font_navbar'] }}; @endif
            @if($sectionFontFamilies['font_hero_title']) --font-hero-title: {{ $sectionFontFamilies['font_hero_title'] }}; @endif
            @if($sectionFontFamilies['font_hero_subtitle']) --font-hero-subtitle: {{ $sectionFontFamilies['font_hero_subtitle'] }}; @endif
            @if($sectionFontFamilies['font_headings']) --font-headings: {{ $sectionFontFamilies['font_headings'] }}; @endif
            @if($sectionFontFamilies['font_footer']) --font-footer: {{ $sectionFontFamilies['font_footer'] }}; @endif
            @if($sectionFontFamilies['font_stats']) --font-stats: {{ $sectionFontFamilies['font_stats'] }}; @endif
        }
        .container { max-width: {{ $pageWidth }}px; }

        /* Scrollbar styles — must be top-level (no descendant selectors) */
        @php $scrollStyle = theme_config('global_scrollbar_style') ?: 'default'; @endphp
        @if($scrollStyle === 'thin')
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: var(--bg-dark); }
            ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 3px; }
            ::-webkit-scrollbar-thumb:hover { background: var(--primary-light); }
            * { scrollbar-width: thin; scrollbar-color: var(--primary) var(--bg-dark); }
        @elseif($scrollStyle === 'hidden')
            ::-webkit-scrollbar { display: none; }
            * { scrollbar-width: none; }
        @elseif($scrollStyle === 'colored')
            ::-webkit-scrollbar { width: 10px; height: 10px; }
            ::-webkit-scrollbar-track { background: var(--bg-card); }
            ::-webkit-scrollbar-thumb { background: linear-gradient(var(--primary), var(--primary-light)); border-radius: 5px; border: 2px solid var(--bg-card); }
            ::-webkit-scrollbar-thumb:hover { background: linear-gradient(var(--primary-light), var(--accent)); }
            * { scrollbar-width: auto; scrollbar-color: var(--primary) var(--bg-card); }
        @elseif($scrollStyle === 'minimal')
            ::-webkit-scrollbar { width: 4px; height: 4px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: var(--text-secondary); border-radius: 2px; }
            ::-webkit-scrollbar-thumb:hover { background: var(--primary); }
            * { scrollbar-width: thin; scrollbar-color: var(--text-secondary) transparent; }
        @endif
    </style>

    @stack('styles')

    {{-- Custom head code from config --}}
    @if(theme_config('custom_head_code'))
        {!! theme_config('custom_head_code') !!}
    @endif

    {{-- Custom CSS from config --}}
    @if(theme_config('custom_css'))
        <style>{!! theme_config('custom_css') !!}</style>
    @endif
</head>

<body class="{{ theme_config('global_animations') !== '0' ? 'rc-animations' : 'rc-no-animations' }} {{ theme_config('global_mc_font_headings') === '0' ? 'rc-no-mc-headings' : '' }} {{ theme_config('global_glow_effects') !== '0' ? 'rc-glow' : 'rc-no-glow' }}" data-scroll-anim="{{ theme_config('global_scroll_animation') ?: 'fade-up' }}">

{{-- Loading bar --}}
@if(theme_config('global_loading_bar') === '1')
    <div class="rc-loading-bar" id="rcLoadingBar"></div>
@endif

<div id="app" style="display:flex;flex-direction:column;min-height:100vh;">
    <header>
        @include('elements.navbar')
    </header>

    <div style="flex:1 0 auto;">
        @yield('app')
    </div>

    <footer style="flex-shrink:0;">
        @include('elements.footer')
    </footer>
</div>

{{-- Scroll to top --}}
<button class="rc-scroll-top" id="scrollTopBtn" aria-label="Retour en haut">
    <i class="bi bi-chevron-up"></i>
</button>

@stack('footer-scripts')

{{-- Custom JS from config --}}
@if(theme_config('custom_js'))
    <script>{!! theme_config('custom_js') !!}</script>
@endif

</body>
</html>
