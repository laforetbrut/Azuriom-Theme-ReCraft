@extends('layouts.base')

@section('title', trans('messages.home'))

@section('app')

    {{-- ===== HERO SECTION ===== --}}
    @php
        $heroHeight = theme_config('hero_height') ?: '92';
        $heroOverlay = theme_config('hero_overlay_style') ?: 'gradient';
        $heroOpacity = theme_config('hero_overlay_opacity') ?: '60';
        $heroAnim = theme_config('hero_animation') ?: 'fade';
        $heroTitleSize = theme_config('hero_title_size') ?: 'auto';
    @endphp
    <section class="rc-hero rc-hero-anim-{{ $heroAnim }}" style="min-height: {{ $heroHeight }}vh;@if(theme_config('hero_background')) background-image: url('{{ theme_config('hero_background') }}');@endif">
        <div class="rc-hero-overlay rc-overlay-{{ $heroOverlay }}" style="opacity: {{ $heroOpacity / 100 }};"></div>
        @if(theme_config('hero_particles') !== '0')
            <div class="rc-hero-particles" @if(theme_config('hero_particles_color') && theme_config('hero_particles_color') !== 'mixed') style="--particles-color: {{ theme_config('hero_particles_color') }};" @endif></div>
        @endif

        <div class="rc-hero-content fade-in">
            @if(theme_config('hero_logo'))
                <img src="{{ theme_config('hero_logo') }}" alt="{{ site_name() }}" class="rc-hero-logo">
            @endif

            @if(theme_config('server_ip'))
                @php
                    $ipStyle = theme_config('hero_ip_style') ?: 'badge';
                    $ipSize = theme_config('hero_ip_size') ?: 'normal';
                    $ipColor = theme_config('hero_ip_color') ?: '';
                    $ipBgColor = theme_config('hero_ip_bg_color') ?: '';
                    $ipInline = '';
                    if ($ipColor) $ipInline .= "color:{$ipColor};";
                    if ($ipBgColor) $ipInline .= "background:{$ipBgColor};border-color:{$ipBgColor};";
                @endphp
                <div class="rc-hero-badge rc-ip-{{ $ipStyle }} rc-ip-{{ $ipSize }}" @if(theme_config('hero_copy_ip') !== '0') onclick="copyIP()" style="cursor:pointer;{{ $ipInline }}" title="Cliquer pour copier l'IP" @else style="{{ $ipInline }}" @endif>
                    <span class="pulse-dot"></span>
                    {{ theme_config('server_ip') }}
                    @if(theme_config('hero_copy_ip') !== '0')
                        <i class="bi bi-clipboard" id="copyIcon" style="font-size:0.85rem;opacity:0.7;"></i>
                    @endif
                </div>
            @endif

            <h1 @if($heroTitleSize !== 'auto') style="font-size: {{ $heroTitleSize }};" @endif>{{ theme_config('hero_title') ?: site_name() }}</h1>

            <p>{{ theme_config('hero_subtitle') ?: setting('description', '') }}</p>

            @php
                $btnStyleMap = [
                    'primary' => 'btn-primary',
                    'accent' => 'btn-accent',
                    'outline' => 'btn-outline',
                    'glass' => 'btn-glass',
                ];
                $btn1Style = $btnStyleMap[theme_config('hero_button_style') ?: 'primary'] ?? 'btn-primary';
                $btn2Style = $btnStyleMap[theme_config('hero_button2_style') ?: 'outline'] ?? 'btn-outline';
            @endphp
            <div class="rc-hero-buttons">
                @if(theme_config('hero_button_text'))
                    <a href="{{ theme_config('hero_button_url') ?: '#' }}" class="btn {{ $btn1Style }} btn-lg">
                        @if(theme_config('hero_button_icon'))
                            <i class="bi bi-{{ theme_config('hero_button_icon') }}"></i>
                        @endif
                        {{ theme_config('hero_button_text') }}
                    </a>
                @endif
                @if(theme_config('hero_button2_text'))
                    <a href="{{ theme_config('hero_button2_url') ?: '#' }}" class="btn {{ $btn2Style }} btn-lg">
                        @if(theme_config('hero_button2_icon'))
                            <i class="bi bi-{{ theme_config('hero_button2_icon') }}"></i>
                        @endif
                        {{ theme_config('hero_button2_text') }}
                    </a>
                @endif
            </div>

            {{-- Server status in hero --}}
            @if(theme_config('hero_server_card') !== '0' && isset($server) && $server)
                @php
                    $cardStyle = theme_config('hero_server_card_style') ?: 'mc-sign';
                    $cardLabel = theme_config('hero_server_card_label') ?: 'Serveur';
                    $cardOnlineText = theme_config('hero_server_card_online_text') ?: 'en ligne';
                    $cardCopyIp = theme_config('hero_server_card_copy_ip') !== '0';
                @endphp
                <div class="rc-server-status rc-server-{{ $cardStyle }}" @if($cardCopyIp) onclick="copyIP()" style="cursor:pointer;" title="Cliquer pour copier l'IP" @endif>
                    <div class="status-indicator {{ $server->isOnline() ? '' : 'offline' }}"></div>
                    <div class="status-info">
                        <div class="status-label">{{ $cardLabel }}</div>
                        <div class="status-value">{{ $server->fullAddress() }}</div>
                    </div>
                    @if($server->isOnline())
                        <div class="status-players">
                            <div class="count">{{ $server->getOnlinePlayers() }}<span style="font-size:0.8rem;color:var(--text-muted);">/{{ $server->getMaxPlayers() }}</span></div>
                            <div class="label">{{ $cardOnlineText }}</div>
                        </div>
                    @endif
                    @if($cardCopyIp)
                        <i class="bi bi-clipboard" style="font-size:0.85rem;opacity:0.5;margin-left:4px;"></i>
                    @endif
                </div>
            @endif
        </div>
    </section>

    <div class="container">
        @include('elements.session-alerts')

        @php
            $sectionsOrder = explode(',', theme_config('sections_order') ?: 'servers,features,stats,news,staff,discord,cta');
            $sectionsOrder = array_map('trim', $sectionsOrder);
        @endphp

        @foreach($sectionsOrder as $section)

            {{-- ===== SERVERS ===== --}}
            @if($section === 'servers' && ! ($servers ?? collect())->isEmpty())
                <h2 class="text-center mb-3">{{ trans('messages.servers') }}</h2>

                <div class="rc-stats mb-4">
                    @foreach($servers as $srv)
                        <div class="rc-stat-item fade-in">
                            <div class="rc-stat-number">
                                @if($srv->isOnline())
                                    {{ $srv->getOnlinePlayers() }}<span style="color:var(--text-muted);font-size:0.9rem;">/{{ $srv->getMaxPlayers() }}</span>
                                @else
                                    <span style="color:var(--danger);">Offline</span>
                                @endif
                            </div>
                            <div class="rc-stat-label">{{ $srv->name }}</div>
                            @if($srv->joinUrl())
                                <a href="{{ $srv->joinUrl() }}" class="btn btn-primary btn-sm mt-2">{{ trans('messages.server.join') }}</a>
                            @else
                                <p style="color:var(--text-muted);font-size:0.85rem;margin-top:8px;">{{ $srv->fullAddress() }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- ===== FEATURES ===== --}}
            @if($section === 'features' && theme_config('features_enabled') !== '0')
                <div class="rc-section">
                    <div class="rc-section-header fade-in">
                        <h2>{{ theme_config('features_title') ?: 'Pourquoi nous choisir ?' }}</h2>
                        @if(theme_config('features_subtitle'))
                            <p>{{ theme_config('features_subtitle') }}</p>
                        @endif
                        <div class="section-line"></div>
                    </div>

                    @php
                        $featureCols = theme_config('features_columns') ?: '3';
                        $featureCardStyle = theme_config('features_card_style') ?: 'default';
                        $featureIconStyle = theme_config('features_icon_style') ?: 'box';
                    @endphp
                    <div class="rc-features-grid" style="grid-template-columns: repeat({{ $featureCols }}, 1fr);">
                        @for($i = 1; $i <= 6; $i++)
                            @if(theme_config("feature_{$i}_title"))
                                <div class="rc-feature-card rc-card-{{ $featureCardStyle }} fade-in">
                                    <div class="rc-feature-icon rc-icon-{{ $featureIconStyle }}">
                                        <i class="bi bi-{{ theme_config("feature_{$i}_icon") ?: 'star' }}"></i>
                                    </div>
                                    <h3>{{ theme_config("feature_{$i}_title") }}</h3>
                                    <p>{{ theme_config("feature_{$i}_desc") }}</p>
                                </div>
                            @endif
                        @endfor
                    </div>
                </div>
            @endif

            {{-- ===== STATS ===== --}}
            @if($section === 'stats' && theme_config('stats_enabled') !== '0')
                <div class="rc-section">
                    @php $statsStyle = theme_config('stats_style') ?: 'cards'; @endphp
                    <div class="rc-stats-bar rc-stats-{{ $statsStyle }}">
                        @for($i = 1; $i <= 4; $i++)
                            @if(theme_config("stat_{$i}_label"))
                                @php
                                    $statSource = theme_config("stat_{$i}_source") ?: ($i === 1 ? 'api_online' : 'manual');
                                    $displayVal = theme_config("stat_{$i}_value") ?: '0';

                                    if ($statSource === 'api_online') {
                                        // Joueurs en ligne
                                        try {
                                            if (isset($server) && $server && $server->isOnline()) {
                                                $displayVal = $server->getOnlinePlayers();
                                            } elseif (isset($servers)) {
                                                $total = 0;
                                                foreach ($servers as $srv) {
                                                    if ($srv->isOnline()) $total += $srv->getOnlinePlayers();
                                                }
                                                $displayVal = $total;
                                            } else {
                                                $defSrv = \Azuriom\Models\Server::getDefault();
                                                if ($defSrv && $defSrv->isOnline()) {
                                                    $displayVal = $defSrv->getOnlinePlayers();
                                                }
                                            }
                                        } catch (\Throwable $e) {}

                                    } elseif ($statSource === 'api_registered') {
                                        // Inscrits
                                        try {
                                            $displayVal = \Azuriom\Models\User::count();
                                        } catch (\Throwable $e) {}

                                    } elseif ($statSource === 'api_votes') {
                                        // Total votes (plugin Vote)
                                        try {
                                            if (class_exists(\Azuriom\Plugin\Vote\Models\Vote::class)) {
                                                $displayVal = \Azuriom\Plugin\Vote\Models\Vote::count();
                                            } elseif (\Illuminate\Support\Facades\Schema::hasTable('votes')) {
                                                $displayVal = \Illuminate\Support\Facades\DB::table('votes')->count();
                                            } else {
                                                $displayVal = '—';
                                            }
                                        } catch (\Throwable $e) {
                                            $displayVal = '—';
                                        }
                                    }
                                @endphp
                                <div class="rc-stat-block fade-in">
                                    @if(theme_config("stat_{$i}_icon"))
                                        <div class="rc-stat-block-icon">
                                            <i class="bi bi-{{ theme_config("stat_{$i}_icon") }}"></i>
                                        </div>
                                    @endif
                                    <div class="rc-stat-block-value" data-count="{{ $displayVal }}">
                                        {{ $displayVal }}
                                    </div>
                                    <div class="rc-stat-block-label">{{ theme_config("stat_{$i}_label") }}</div>
                                </div>
                            @endif
                        @endfor
                    </div>
                </div>
            @endif

            {{-- ===== NEWS ===== --}}
            @if($section === 'news' && theme_config('news_enabled') !== '0' && ! ($posts ?? collect())->isEmpty())
                <div class="rc-section">
                    <div class="rc-section-header fade-in">
                        <h2>{{ theme_config('news_title') ?: trans('messages.news') }}</h2>
                        @if(theme_config('news_subtitle'))
                            <p>{{ theme_config('news_subtitle') }}</p>
                        @endif
                        <div class="section-line"></div>
                    </div>

                    @php
                        $newsCols = theme_config('news_columns') ?: '2';
                        $newsColClass = $newsCols == '3' ? 'col-md-4' : ($newsCols == '4' ? 'col-md-3' : ($newsCols == '1' ? 'col-12' : 'col-md-6'));
                        $newsShowImage = theme_config('news_show_image') !== '0';
                        $newsShowDate = theme_config('news_show_date') !== '0';
                        $newsShowExcerpt = theme_config('news_show_excerpt') !== '0';
                        $newsExcerptLen = (int)(theme_config('news_excerpt_length') ?: 150);
                    @endphp
                    <div class="row">
                        @foreach($posts as $post)
                            <div class="{{ $newsColClass }} mb-4">
                                <div class="rc-article-card fade-in">
                                    @if($newsShowImage && $post->hasImage())
                                        <a href="{{ route('posts.show', $post) }}">
                                            <img src="{{ $post->imageUrl() }}" alt="{{ $post->title }}" class="article-image" loading="lazy">
                                        </a>
                                    @endif
                                    <div class="article-body">
                                        @if($newsShowDate)
                                            <div class="article-meta">
                                                @if($post->published_at)
                                                    <span>{{ format_date($post->published_at) }}</span>
                                                @endif
                                            </div>
                                        @endif
                                        <h3><a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a></h3>
                                        @if($newsShowExcerpt)
                                            <p class="article-excerpt">{{ $post->description ?? Str::limit(strip_tags($post->content ?? ''), $newsExcerptLen) }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ===== STAFF ===== --}}
            @if($section === 'staff' && theme_config('staff_enabled') === '1')
                <div class="rc-section">
                    <div class="rc-section-header fade-in">
                        <h2>{{ theme_config('staff_title') ?: 'Notre equipe' }}</h2>
                        @if(theme_config('staff_subtitle'))
                            <p>{{ theme_config('staff_subtitle') }}</p>
                        @endif
                        <div class="section-line"></div>
                    </div>

                    @php
                        $staffCols = theme_config('staff_columns') ?: '4';
                        $staffAvatarStyle = theme_config('staff_avatar_style') ?: 'square';
                    @endphp
                    <div class="rc-staff-grid" style="grid-template-columns: repeat({{ $staffCols }}, 1fr);">
                        @for($i = 1; $i <= 12; $i++)
                            @if(theme_config("staff_{$i}_name"))
                                <div class="rc-staff-card fade-in">
                                    <div class="rc-staff-avatar rc-avatar-{{ $staffAvatarStyle }}">
                                        @if(theme_config("staff_{$i}_avatar"))
                                            <img src="{{ theme_config("staff_{$i}_avatar") }}" alt="{{ theme_config("staff_{$i}_name") }}">
                                        @else
                                            <img src="https://mc-heads.net/avatar/{{ theme_config("staff_{$i}_name") }}/100" alt="{{ theme_config("staff_{$i}_name") }}">
                                        @endif
                                    </div>
                                    <h4>{{ theme_config("staff_{$i}_name") }}</h4>
                                    @if(theme_config("staff_{$i}_role"))
                                        <span class="rc-staff-role">{{ theme_config("staff_{$i}_role") }}</span>
                                    @endif
                                </div>
                            @endif
                        @endfor
                    </div>
                </div>
            @endif

            {{-- ===== DISCORD ===== --}}
            @if($section === 'discord' && theme_config('discord_id'))
                @php $discordStyle = theme_config('discord_style') ?: 'split'; @endphp
                <div class="rc-section rc-discord-section rc-discord-{{ $discordStyle }}" style="border-radius:var(--radius-lg);padding:40px;margin:40px 0;">
                    <div class="rc-discord-grid fade-in">
                        <div class="rc-discord-info">
                            <h2>
                                <i class="bi bi-discord" style="color: #5865F2;"></i>
                                {{ theme_config('discord_title') ?: 'Rejoins notre Discord' }}
                            </h2>
                            <p>{{ theme_config('discord_description') ?: '' }}</p>
                            @if(theme_config('discord_invite_url'))
                                <a href="{{ theme_config('discord_invite_url') }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                                    <i class="bi bi-discord"></i> Rejoindre
                                </a>
                            @endif
                        </div>
                        <div class="rc-discord-embed">
                            <iframe
                                src="https://discord.com/widget?id={{ theme_config('discord_id') }}&theme=dark"
                                allowtransparency="true"
                                sandbox="allow-popups allow-popups-to-escape-sandbox allow-same-origin allow-scripts"
                                loading="lazy">
                            </iframe>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ===== CTA ===== --}}
            @if($section === 'cta' && theme_config('cta_enabled') === '1')
                <div class="rc-cta-section fade-in" @if(theme_config('cta_background')) style="background-image: url('{{ theme_config('cta_background') }}');" @endif>
                    <div class="rc-cta-overlay"></div>
                    <div class="rc-cta-content">
                        @if(theme_config('cta_title'))
                            <h2>{{ theme_config('cta_title') }}</h2>
                        @endif
                        @if(theme_config('cta_subtitle'))
                            <p>{{ theme_config('cta_subtitle') }}</p>
                        @endif
                        @if(theme_config('cta_button_text'))
                            @php
                                $ctaBtnMap = ['primary' => 'btn-primary', 'accent' => 'btn-accent', 'outline' => 'btn-outline', 'glass' => 'btn-glass'];
                                $ctaBtnStyle = $ctaBtnMap[theme_config('cta_button_style') ?: 'accent'] ?? 'btn-accent';
                            @endphp
                            <a href="{{ theme_config('cta_button_url') ?: '#' }}" class="btn {{ $ctaBtnStyle }} btn-lg">
                                @if(theme_config('cta_button_icon'))
                                    <i class="bi bi-{{ theme_config('cta_button_icon') }}"></i>
                                @endif
                                {{ theme_config('cta_button_text') }}
                            </a>
                        @endif
                    </div>
                </div>
            @endif

        @endforeach
    </div>

@endsection
