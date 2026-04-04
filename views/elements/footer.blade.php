@php
    $footerNewTab = theme_config('footer_links_new_tab') === '1';
    $footerStyle = theme_config('footer_style') ?: 'default';
    $footerCols = theme_config('footer_columns') ?: '3';
    $footerBg = theme_config('footer_bg_color');
    $col1Content = theme_config('footer_col1_content') ?: 'auto';
@endphp
<div class="rc-footer {{ $footerStyle !== 'default' ? 'rc-footer-' . $footerStyle : '' }}" @if($footerBg) style="background: {{ $footerBg }};" @endif>
    <div class="container">
        <div class="rc-footer-grid rc-footer-cols-{{ $footerCols }}">

            {{-- About / Logo column (always first) --}}
            <div class="rc-footer-about">
                @if(theme_config('footer_show_logo') !== '0')
                    <div class="footer-logo">
                        <img src="{{ favicon() }}" alt="{{ site_name() }}">
                        {{ site_name() }}
                    </div>
                @endif
                @if(theme_config('footer_description'))
                    <p>{!! theme_config('footer_description') !!}</p>
                @endif

                <div class="rc-footer-socials">
                    @php
                        $socialList = [
                            ['key' => 'footer_social_discord', 'icon' => 'discord'],
                            ['key' => 'footer_social_twitter', 'icon' => 'twitter-x'],
                            ['key' => 'footer_social_youtube', 'icon' => 'youtube'],
                            ['key' => 'footer_social_instagram', 'icon' => 'instagram'],
                            ['key' => 'footer_social_tiktok', 'icon' => 'tiktok'],
                            ['key' => 'footer_social_github', 'icon' => 'github'],
                            ['key' => 'footer_social_twitch', 'icon' => 'twitch'],
                            ['key' => 'footer_social_facebook', 'icon' => 'facebook'],
                            ['key' => 'footer_social_telegram', 'icon' => 'telegram'],
                            ['key' => 'footer_social_linkedin', 'icon' => 'linkedin'],
                            ['key' => 'footer_social_snapchat', 'icon' => 'snapchat'],
                            ['key' => 'footer_social_steam', 'icon' => 'steam'],
                            ['key' => 'footer_social_reddit', 'icon' => 'reddit'],
                        ];
                    @endphp
                    @foreach($socialList as $social)
                        @if(theme_config($social['key']))
                            <a href="{{ theme_config($social['key']) }}" target="_blank" rel="noopener" aria-label="{{ $social['icon'] }}"><i class="bi bi-{{ $social['icon'] }}"></i></a>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Column 1 --}}
            <div class="rc-footer-col">
                <h4>{{ theme_config('footer_col1_title') ?: 'Navigation' }}</h4>
                <ul class="rc-footer-links">
                    @if($col1Content === 'auto')
                        <li><a href="{{ route('home') }}" @if($footerNewTab) target="_blank" rel="noopener" @endif>{{ trans('messages.home') }}</a></li>
                        @foreach($navbar ?? [] as $element)
                            @if(isset($element['route']))
                                <li><a href="{{ route($element['route']) }}" @if($footerNewTab) target="_blank" rel="noopener" @endif>{{ $element['name'] }}</a></li>
                            @elseif(isset($element['url']))
                                <li><a href="{{ $element['url'] }}" @if($footerNewTab) target="_blank" rel="noopener" @endif>{{ $element['name'] }}</a></li>
                            @endif
                        @endforeach
                    @else
                        {{-- Description mode: logo + description already shown in about column --}}
                        <li><a href="{{ route('home') }}" @if($footerNewTab) target="_blank" rel="noopener" @endif>{{ trans('messages.home') }}</a></li>
                    @endif
                </ul>
            </div>

            {{-- Column 2: Custom links --}}
            <div class="rc-footer-col">
                <h4>{{ theme_config('footer_col2_title') ?: 'Liens utiles' }}</h4>
                <ul class="rc-footer-links">
                    @foreach(theme_config('footer_links') ?? [] as $link)
                        @if(isset($link['name']) && isset($link['value']))
                            <li><a href="{{ $link['value'] }}" @if($footerNewTab) target="_blank" rel="noopener" @endif>{{ $link['name'] }}</a></li>
                        @endif
                    @endforeach
                </ul>
            </div>

            @if((int)$footerCols >= 3)
            {{-- Column 3: Legal / custom links --}}
            <div class="rc-footer-col">
                <h4>{{ theme_config('footer_col3_title') ?: 'Legal' }}</h4>
                <ul class="rc-footer-links">
                    @for($i = 1; $i <= 6; $i++)
                        @if(theme_config("footer_legal_link{$i}_text"))
                            <li><a href="{{ theme_config("footer_legal_link{$i}_url") ?: '#' }}" @if($footerNewTab) target="_blank" rel="noopener" @endif>{{ theme_config("footer_legal_link{$i}_text") }}</a></li>
                        @endif
                    @endfor
                </ul>
            </div>
            @endif

            @if((int)$footerCols >= 4)
            {{-- Column 4: Extra links --}}
            <div class="rc-footer-col">
                <h4>{{ theme_config('footer_col4_title') ?: 'Autre' }}</h4>
                <ul class="rc-footer-links">
                    @for($i = 1; $i <= 6; $i++)
                        @if(theme_config("footer_col4_link{$i}_text"))
                            <li><a href="{{ theme_config("footer_col4_link{$i}_url") ?: '#' }}" @if($footerNewTab) target="_blank" rel="noopener" @endif>{{ theme_config("footer_col4_link{$i}_text") }}</a></li>
                        @endif
                    @endfor
                </ul>
            </div>
            @endif

        </div>

        {{-- Custom HTML --}}
        @if(theme_config('footer_custom_html'))
            <div class="rc-footer-custom-html">
                {!! theme_config('footer_custom_html') !!}
            </div>
        @endif

        <div class="rc-footer-bottom">
            <p>{{ theme_config('footer_copyright') ?: setting('copyright') }}</p>
            <p>@lang('messages.copyright') | Theme <span style="color:var(--primary-light);font-weight:600;">ReCraft</span></p>
        </div>
    </div>
</div>
