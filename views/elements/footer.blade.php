@php $footerNewTab = theme_config('footer_links_new_tab') === '1'; @endphp
<div class="rc-footer">
    <div class="container">
        <div class="rc-footer-grid">
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
                    @if(theme_config('footer_social_discord'))
                        <a href="{{ theme_config('footer_social_discord') }}" target="_blank" rel="noopener" aria-label="Discord"><i class="bi bi-discord"></i></a>
                    @endif
                    @if(theme_config('footer_social_twitter'))
                        <a href="{{ theme_config('footer_social_twitter') }}" target="_blank" rel="noopener" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                    @endif
                    @if(theme_config('footer_social_youtube'))
                        <a href="{{ theme_config('footer_social_youtube') }}" target="_blank" rel="noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                    @endif
                    @if(theme_config('footer_social_instagram'))
                        <a href="{{ theme_config('footer_social_instagram') }}" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    @endif
                    @if(theme_config('footer_social_tiktok'))
                        <a href="{{ theme_config('footer_social_tiktok') }}" target="_blank" rel="noopener" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
                    @endif
                    @if(theme_config('footer_social_github'))
                        <a href="{{ theme_config('footer_social_github') }}" target="_blank" rel="noopener" aria-label="GitHub"><i class="bi bi-github"></i></a>
                    @endif
                    @if(theme_config('footer_social_twitch'))
                        <a href="{{ theme_config('footer_social_twitch') }}" target="_blank" rel="noopener" aria-label="Twitch"><i class="bi bi-twitch"></i></a>
                    @endif
                </div>
            </div>

            <div class="rc-footer-col">
                <h4>{{ theme_config('footer_col1_title') ?: 'Navigation' }}</h4>
                <ul class="rc-footer-links">
                    <li><a href="{{ route('home') }}" @if($footerNewTab) target="_blank" rel="noopener" @endif>{{ trans('messages.home') }}</a></li>
                    @foreach($navbar ?? [] as $element)
                        @if(isset($element['route']))
                            <li><a href="{{ route($element['route']) }}" @if($footerNewTab) target="_blank" rel="noopener" @endif>{{ $element['name'] }}</a></li>
                        @elseif(isset($element['url']))
                            <li><a href="{{ $element['url'] }}" @if($footerNewTab) target="_blank" rel="noopener" @endif>{{ $element['name'] }}</a></li>
                        @endif
                    @endforeach
                </ul>
            </div>

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

            <div class="rc-footer-col">
                <h4>{{ theme_config('footer_col3_title') ?: 'Legal' }}</h4>
                <ul class="rc-footer-links">
                    @for($i = 1; $i <= 3; $i++)
                        @if(theme_config("footer_legal_link{$i}_text"))
                            <li><a href="{{ theme_config("footer_legal_link{$i}_url") ?: '#' }}" @if($footerNewTab) target="_blank" rel="noopener" @endif>{{ theme_config("footer_legal_link{$i}_text") }}</a></li>
                        @endif
                    @endfor
                </ul>
            </div>
        </div>

        <div class="rc-footer-bottom">
            <p>{{ theme_config('footer_copyright') ?: setting('copyright') }}</p>
            <p>@lang('messages.copyright') | Theme <span style="color:var(--primary-light);font-weight:600;">ReCraft</span></p>
        </div>
    </div>
</div>
