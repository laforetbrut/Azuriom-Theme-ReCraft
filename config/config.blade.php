@extends('admin.layouts.admin')

@section('title', 'ReCraft - Configuration')

@push('footer-scripts')
    <script>
        function addLinkListener(el) {
            el.addEventListener('click', function () {
                const group = el.closest('.input-group');
                if (group) group.remove();
            });
        }
        document.querySelectorAll('.link-remove').forEach(function (el) { addLinkListener(el); });

        document.getElementById('addLinkButton').addEventListener('click', function () {
            let input = '<div class="input-group mb-2">';
            input += '<input type="text" class="form-control" name="footer_links[{index}][name]" placeholder="Nom">';
            input += '<input type="url" class="form-control" name="footer_links[{index}][value]" placeholder="URL">';
            input += '<button class="btn btn-outline-danger btn-sm link-remove" type="button"><i class="bi bi-x-lg"></i></button>';
            input += '</div>';
            const newEl = document.createElement('div');
            newEl.innerHTML = input;
            addLinkListener(newEl.querySelector('.link-remove'));
            document.getElementById('footerLinks').appendChild(newEl);
        });

        document.getElementById('configForm').addEventListener('submit', function () {
            let i = 0;
            document.getElementById('footerLinks').querySelectorAll('.input-group').forEach(function (el) {
                el.querySelectorAll('input').forEach(function (input) {
                    input.name = input.name.replace('{index}', i.toString());
                });
                i++;
            });
        });

        // Color preview
        document.querySelectorAll('.color-input').forEach(function(input) {
            const preview = input.nextElementSibling;
            if (preview && preview.classList.contains('color-preview')) {
                input.addEventListener('input', function() { preview.style.background = this.value; });
            }
        });
    </script>
@endpush

@push('styles')
<style>
    .config-section { border-left: 3px solid #8b5cf6; padding-left: 16px; margin-bottom: 8px; scroll-margin-top: 60px; }
    .config-section h4 { color: #8b5cf6; margin-bottom: 4px; font-size: 1.1rem; }
    .config-section small { color: #999; }
    .color-preview { display: inline-block; width: 32px; height: 32px; border-radius: 6px; border: 2px solid rgba(255,255,255,0.15); vertical-align: middle; margin-left: 8px; }
    .feature-block, .staff-block, .stat-block { background: rgba(139,92,246,0.06); border: 1px solid rgba(139,92,246,0.15); border-radius: 8px; padding: 16px; margin-bottom: 12px; }
    .footer-col-config { background: rgba(139,92,246,0.06); border: 1px solid rgba(139,92,246,0.15); border-radius: 8px; padding: 16px; height: 100%; }
    [data-bs-theme="dark"] .feature-block, [data-bs-theme="dark"] .staff-block, [data-bs-theme="dark"] .stat-block { background: rgba(139,92,246,0.08); border-color: rgba(139,92,246,0.2); }
    [data-bs-theme="dark"] .color-preview { border-color: rgba(255,255,255,0.15); }
    .dark .feature-block, .dark .staff-block, .dark .stat-block { background: rgba(139,92,246,0.08); border-color: rgba(139,92,246,0.2); }
    .rc-config-quicknav { position: sticky; top: 0; z-index: 10; background: inherit; padding: 12px 0; }
    .rc-opt-group { background: rgba(139,92,246,0.04); border: 1px solid rgba(139,92,246,0.1); border-radius: 8px; padding: 12px 16px; margin-bottom: 8px; }
</style>
@endpush

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">

            {{-- Navigation rapide --}}
            <div class="d-flex flex-wrap gap-2 mb-4 pb-3 rc-config-quicknav" style="border-bottom: 2px solid rgba(139,92,246,0.15);">
                <a href="#sec-colors" class="btn btn-sm btn-outline-secondary"><i class="bi bi-palette"></i> Couleurs</a>
                <a href="#sec-global" class="btn btn-sm btn-outline-secondary"><i class="bi bi-gear"></i> Global</a>
                <a href="#sec-navbar" class="btn btn-sm btn-outline-secondary"><i class="bi bi-layout-text-sidebar"></i> Navbar</a>
                <a href="#sec-hero" class="btn btn-sm btn-outline-secondary"><i class="bi bi-image"></i> Hero</a>
                <a href="#sec-features" class="btn btn-sm btn-outline-secondary"><i class="bi bi-grid-3x3-gap"></i> Features</a>
                <a href="#sec-stats" class="btn btn-sm btn-outline-secondary"><i class="bi bi-bar-chart"></i> Stats</a>
                <a href="#sec-staff" class="btn btn-sm btn-outline-secondary"><i class="bi bi-people"></i> Staff</a>
                <a href="#sec-news" class="btn btn-sm btn-outline-secondary"><i class="bi bi-newspaper"></i> News</a>
                <a href="#sec-discord" class="btn btn-sm btn-outline-secondary"><i class="bi bi-discord"></i> Discord</a>
                <a href="#sec-cta" class="btn btn-sm btn-outline-secondary"><i class="bi bi-megaphone"></i> CTA</a>
                <a href="#sec-order" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrows-move"></i> Ordre</a>
                <a href="#sec-footer" class="btn btn-sm btn-outline-secondary"><i class="bi bi-layout-three-columns"></i> Footer</a>
                <a href="#sec-custom" class="btn btn-sm btn-outline-secondary"><i class="bi bi-code-slash"></i> CSS/JS</a>
                <a href="#sec-errors" class="btn btn-sm btn-outline-secondary"><i class="bi bi-exclamation-triangle"></i> Erreurs</a>
            </div>

            <form action="{{ route('admin.themes.config', $theme) }}" method="POST" id="configForm">
                @csrf

                {{-- ============ COULEURS ============ --}}
                <div class="config-section mb-4" id="sec-colors">
                    <h4><i class="bi bi-palette"></i> Couleurs du theme</h4>
                    <small>Personnalisez toutes les couleurs. Laissez vide pour les valeurs par defaut.</small>
                </div>

                <div class="row">
                    @php
                        $colors = [
                            ['name' => 'color_primary', 'label' => 'Principale', 'default' => '#6d28d9'],
                            ['name' => 'color_primary_light', 'label' => 'Principale claire', 'default' => '#8b5cf6'],
                            ['name' => 'color_accent', 'label' => 'Accent (jaune)', 'default' => '#fbbf24'],
                            ['name' => 'color_bg', 'label' => 'Fond du site', 'default' => '#0c0c1d'],
                            ['name' => 'color_card', 'label' => 'Fond des cartes', 'default' => '#111128'],
                            ['name' => 'color_border', 'label' => 'Bordures', 'default' => '#1e1e40'],
                            ['name' => 'color_text', 'label' => 'Texte principal', 'default' => '#e8e8f0'],
                            ['name' => 'color_text_secondary', 'label' => 'Texte secondaire', 'default' => '#9d9dba'],
                        ];
                    @endphp
                    @foreach($colors as $c)
                        <div class="col-md-3 mb-3">
                            <label class="form-label">{{ $c['label'] }}</label>
                            <div class="d-flex align-items-center">
                                <input type="color" class="form-control form-control-color color-input" name="{{ $c['name'] }}" value="{{ old($c['name'], theme_config($c['name'])) ?: $c['default'] }}">
                                <span class="color-preview" style="background:{{ theme_config($c['name']) ?: $c['default'] }}"></span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <hr>

                {{-- ============ GLOBAL ============ --}}
                <div class="config-section mb-4" id="sec-global">
                    <h4><i class="bi bi-gear"></i> Apparence globale</h4>
                    <small>Parametres generaux du theme : police, coins, animations, largeur de page.</small>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Police principale</label>
                        <select class="form-select" name="global_font">
                            <option value="inter" {{ (theme_config('global_font') ?: 'inter') === 'inter' ? 'selected' : '' }}>Inter</option>
                            <option value="poppins" {{ theme_config('global_font') === 'poppins' ? 'selected' : '' }}>Poppins</option>
                            <option value="roboto" {{ theme_config('global_font') === 'roboto' ? 'selected' : '' }}>Roboto</option>
                            <option value="nunito" {{ theme_config('global_font') === 'nunito' ? 'selected' : '' }}>Nunito</option>
                            <option value="outfit" {{ theme_config('global_font') === 'outfit' ? 'selected' : '' }}>Outfit</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Police Minecraft titres</label>
                        <select class="form-select" name="global_mc_font_headings">
                            <option value="1" {{ theme_config('global_mc_font_headings') !== '0' ? 'selected' : '' }}>Activee</option>
                            <option value="0" {{ theme_config('global_mc_font_headings') === '0' ? 'selected' : '' }}>Desactivee</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Style des coins</label>
                        <select class="form-select" name="global_border_radius">
                            <option value="none" {{ theme_config('global_border_radius') === 'none' ? 'selected' : '' }}>Pixel (aucun)</option>
                            <option value="small" {{ (theme_config('global_border_radius') ?: 'small') === 'small' ? 'selected' : '' }}>Petit (4-6px)</option>
                            <option value="medium" {{ theme_config('global_border_radius') === 'medium' ? 'selected' : '' }}>Moyen (8-12px)</option>
                            <option value="large" {{ theme_config('global_border_radius') === 'large' ? 'selected' : '' }}>Grand (16px)</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Largeur max (px)</label>
                        <input type="number" class="form-control" name="global_page_width" value="{{ old('global_page_width', theme_config('global_page_width')) ?: '1200' }}" min="900" max="1600" step="50">
                    </div>
                </div>

                <h5 class="mt-2 mb-2"><i class="bi bi-fonts"></i> Polices par section</h5>
                <small class="text-muted d-block mb-2">Laissez sur « Police globale » pour utiliser la police principale partout. Choisissez une police specifique pour personnaliser chaque zone.</small>
                @php
                    $fontOptions = [
                        '' => 'Police globale (defaut)',
                        'inter' => 'Inter',
                        'poppins' => 'Poppins',
                        'roboto' => 'Roboto',
                        'montserrat' => 'Montserrat',
                        'nunito' => 'Nunito',
                        'raleway' => 'Raleway',
                        'ubuntu' => 'Ubuntu',
                        'lato' => 'Lato',
                        'open-sans' => 'Open Sans',
                        'outfit' => 'Outfit',
                    ];
                    $sectionFonts = [
                        ['key' => 'font_navbar', 'label' => 'Navbar'],
                        ['key' => 'font_hero_title', 'label' => 'Hero — Titre'],
                        ['key' => 'font_hero_subtitle', 'label' => 'Hero — Sous-titre'],
                        ['key' => 'font_headings', 'label' => 'Titres de sections'],
                        ['key' => 'font_footer', 'label' => 'Footer'],
                        ['key' => 'font_stats', 'label' => 'Stats (chiffres)'],
                    ];
                @endphp
                <div class="row">
                    @foreach($sectionFonts as $sf)
                        <div class="col-md-2 mb-3">
                            <label class="form-label">{{ $sf['label'] }}</label>
                            <select class="form-select form-select-sm" name="{{ $sf['key'] }}">
                                @foreach($fontOptions as $val => $lbl)
                                    <option value="{{ $val }}" {{ theme_config($sf['key']) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Animations</label>
                        <select class="form-select" name="global_animations">
                            <option value="1" {{ theme_config('global_animations') !== '0' ? 'selected' : '' }}>Activees</option>
                            <option value="0" {{ theme_config('global_animations') === '0' ? 'selected' : '' }}>Desactivees</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Vitesse animations</label>
                        <select class="form-select" name="global_animation_speed">
                            <option value="slow" {{ theme_config('global_animation_speed') === 'slow' ? 'selected' : '' }}>Lente</option>
                            <option value="normal" {{ (theme_config('global_animation_speed') ?: 'normal') === 'normal' ? 'selected' : '' }}>Normale</option>
                            <option value="fast" {{ theme_config('global_animation_speed') === 'fast' ? 'selected' : '' }}>Rapide</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Effets glow</label>
                        <select class="form-select" name="global_glow_effects">
                            <option value="1" {{ theme_config('global_glow_effects') !== '0' ? 'selected' : '' }}>Actives</option>
                            <option value="0" {{ theme_config('global_glow_effects') === '0' ? 'selected' : '' }}>Desactives</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Barre de chargement</label>
                        <select class="form-select" name="global_loading_bar">
                            <option value="1" {{ theme_config('global_loading_bar') !== '0' ? 'selected' : '' }}>Activee</option>
                            <option value="0" {{ theme_config('global_loading_bar') === '0' ? 'selected' : '' }}>Desactivee</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Style scrollbar</label>
                        <select class="form-select" name="global_scrollbar_style">
                            <option value="default" {{ (theme_config('global_scrollbar_style') ?: 'default') === 'default' ? 'selected' : '' }}>MC (violet)</option>
                            <option value="thin" {{ theme_config('global_scrollbar_style') === 'thin' ? 'selected' : '' }}>Fine discrete</option>
                            <option value="rounded" {{ theme_config('global_scrollbar_style') === 'rounded' ? 'selected' : '' }}>Arrondie</option>
                            <option value="accent" {{ theme_config('global_scrollbar_style') === 'accent' ? 'selected' : '' }}>Accent (jaune)</option>
                            <option value="hidden" {{ theme_config('global_scrollbar_style') === 'hidden' ? 'selected' : '' }}>Cachee</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Animation au scroll</label>
                        <select class="form-select" name="global_scroll_animation">
                            <option value="fade-up" {{ (theme_config('global_scroll_animation') ?: 'fade-up') === 'fade-up' ? 'selected' : '' }}>Fondu + montee</option>
                            <option value="fade" {{ theme_config('global_scroll_animation') === 'fade' ? 'selected' : '' }}>Fondu simple</option>
                            <option value="slide-left" {{ theme_config('global_scroll_animation') === 'slide-left' ? 'selected' : '' }}>Glissement gauche</option>
                            <option value="slide-right" {{ theme_config('global_scroll_animation') === 'slide-right' ? 'selected' : '' }}>Glissement droite</option>
                            <option value="zoom" {{ theme_config('global_scroll_animation') === 'zoom' ? 'selected' : '' }}>Zoom</option>
                            <option value="none" {{ theme_config('global_scroll_animation') === 'none' ? 'selected' : '' }}>Aucune</option>
                        </select>
                    </div>
                </div>

                <hr>

                {{-- ============ NAVBAR ============ --}}
                <div class="config-section mb-4" id="sec-navbar">
                    <h4><i class="bi bi-layout-text-sidebar"></i> Barre de navigation</h4>
                    <small>Style, hauteur, et comportement de la navbar.</small>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Style</label>
                        <select class="form-select" name="navbar_style">
                            <option value="glass" {{ (theme_config('navbar_style') ?: 'glass') === 'glass' ? 'selected' : '' }}>Glass (flou)</option>
                            <option value="solid" {{ theme_config('navbar_style') === 'solid' ? 'selected' : '' }}>Solide</option>
                            <option value="transparent" {{ theme_config('navbar_style') === 'transparent' ? 'selected' : '' }}>Transparent</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Hauteur (px)</label>
                        <input type="number" class="form-control" name="navbar_height" value="{{ old('navbar_height', theme_config('navbar_height')) ?: '72' }}" min="50" max="100">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Logo (px)</label>
                        <input type="number" class="form-control" name="navbar_logo_size" value="{{ old('navbar_logo_size', theme_config('navbar_logo_size')) ?: '40' }}" min="24" max="64">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Nom du site</label>
                        <select class="form-select" name="navbar_show_name">
                            <option value="1" {{ theme_config('navbar_show_name') !== '0' ? 'selected' : '' }}>Visible</option>
                            <option value="0" {{ theme_config('navbar_show_name') === '0' ? 'selected' : '' }}>Cache</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Sticky (fixee)</label>
                        <select class="form-select" name="navbar_sticky">
                            <option value="1" {{ theme_config('navbar_sticky') !== '0' ? 'selected' : '' }}>Oui</option>
                            <option value="0" {{ theme_config('navbar_sticky') === '0' ? 'selected' : '' }}>Non</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Ombre</label>
                        <select class="form-select" name="navbar_shadow">
                            <option value="1" {{ theme_config('navbar_shadow') !== '0' ? 'selected' : '' }}>Oui</option>
                            <option value="0" {{ theme_config('navbar_shadow') === '0' ? 'selected' : '' }}>Non</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Couleur bordure basse</label>
                        <input type="text" class="form-control" name="navbar_border_color" value="{{ old('navbar_border_color', theme_config('navbar_border_color')) }}" placeholder="Defaut : violet">
                    </div>
                </div>

                <hr>

                {{-- ============ HERO ============ --}}
                <div class="config-section mb-4" id="sec-hero">
                    <h4><i class="bi bi-image"></i> Section Hero</h4>
                    <small>La banniere principale en haut de la page d'accueil.</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">URL image de fond</label>
                        <input type="text" class="form-control" name="hero_background" value="{{ old('hero_background', theme_config('hero_background')) }}" placeholder="https://... ou /storage/img/bg.png">
                        <small class="text-muted">Uploadez via Admin > Images puis collez l'URL.</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">URL logo dans le Hero</label>
                        <input type="text" class="form-control" name="hero_logo" value="{{ old('hero_logo', theme_config('hero_logo')) }}" placeholder="Logo flottant au-dessus du titre">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" class="form-control" name="hero_title" value="{{ old('hero_title', theme_config('hero_title')) }}" placeholder="Ex: Bienvenue sur MonServeur">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sous-titre</label>
                        <input type="text" class="form-control" name="hero_subtitle" value="{{ old('hero_subtitle', theme_config('hero_subtitle')) }}">
                    </div>
                </div>

                <div class="rc-opt-group">
                    <strong class="d-block mb-2">Apparence du Hero</strong>
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Hauteur (vh)</label>
                            <input type="number" class="form-control" name="hero_height" value="{{ old('hero_height', theme_config('hero_height')) ?: '92' }}" min="40" max="100">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Taille titre</label>
                            <select class="form-select" name="hero_title_size">
                                <option value="auto" {{ (theme_config('hero_title_size') ?: 'auto') === 'auto' ? 'selected' : '' }}>Auto</option>
                                <option value="small" {{ theme_config('hero_title_size') === 'small' ? 'selected' : '' }}>Petit</option>
                                <option value="large" {{ theme_config('hero_title_size') === 'large' ? 'selected' : '' }}>Grand</option>
                                <option value="xlarge" {{ theme_config('hero_title_size') === 'xlarge' ? 'selected' : '' }}>Tres grand</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Overlay</label>
                            <select class="form-select" name="hero_overlay_style">
                                <option value="gradient" {{ (theme_config('hero_overlay_style') ?: 'gradient') === 'gradient' ? 'selected' : '' }}>Degrade</option>
                                <option value="solid" {{ theme_config('hero_overlay_style') === 'solid' ? 'selected' : '' }}>Uni</option>
                                <option value="none" {{ theme_config('hero_overlay_style') === 'none' ? 'selected' : '' }}>Aucun</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Opacite (%)</label>
                            <input type="number" class="form-control" name="hero_overlay_opacity" value="{{ old('hero_overlay_opacity', theme_config('hero_overlay_opacity')) ?: '60' }}" min="0" max="100" step="5">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Animation</label>
                            <select class="form-select" name="hero_animation">
                                <option value="fade" {{ (theme_config('hero_animation') ?: 'fade') === 'fade' ? 'selected' : '' }}>Fondu</option>
                                <option value="slide" {{ theme_config('hero_animation') === 'slide' ? 'selected' : '' }}>Glissement</option>
                                <option value="none" {{ theme_config('hero_animation') === 'none' ? 'selected' : '' }}>Aucune</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Particules</label>
                            <select class="form-select" name="hero_particles">
                                <option value="1" {{ theme_config('hero_particles') !== '0' ? 'selected' : '' }}>Oui</option>
                                <option value="0" {{ theme_config('hero_particles') === '0' ? 'selected' : '' }}>Non</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Couleur particules</label>
                            <select class="form-select" name="hero_particles_color">
                                <option value="mixed" {{ (theme_config('hero_particles_color') ?: 'mixed') === 'mixed' ? 'selected' : '' }}>Mixte (violet + jaune)</option>
                                <option value="primary" {{ theme_config('hero_particles_color') === 'primary' ? 'selected' : '' }}>Violet uniquement</option>
                                <option value="accent" {{ theme_config('hero_particles_color') === 'accent' ? 'selected' : '' }}>Jaune uniquement</option>
                                <option value="white" {{ theme_config('hero_particles_color') === 'white' ? 'selected' : '' }}>Blanc</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">IP du serveur</label>
                            <input type="text" class="form-control" name="server_ip" value="{{ old('server_ip', theme_config('server_ip')) }}" placeholder="play.monserveur.fr">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Copier IP au clic</label>
                            <select class="form-select" name="hero_copy_ip">
                                <option value="1" {{ theme_config('hero_copy_ip') !== '0' ? 'selected' : '' }}>Oui</option>
                                <option value="0" {{ theme_config('hero_copy_ip') === '0' ? 'selected' : '' }}>Non</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Style du badge IP</label>
                            <select class="form-select" name="hero_ip_style">
                                <option value="badge" {{ (theme_config('hero_ip_style') ?: 'badge') === 'badge' ? 'selected' : '' }}>Badge (defaut)</option>
                                <option value="pill" {{ theme_config('hero_ip_style') === 'pill' ? 'selected' : '' }}>Pilule arrondie</option>
                                <option value="outline" {{ theme_config('hero_ip_style') === 'outline' ? 'selected' : '' }}>Contour</option>
                                <option value="glass" {{ theme_config('hero_ip_style') === 'glass' ? 'selected' : '' }}>Glass</option>
                                <option value="mc-sign" {{ theme_config('hero_ip_style') === 'mc-sign' ? 'selected' : '' }}>Panneau MC</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Taille IP</label>
                            <select class="form-select" name="hero_ip_size">
                                <option value="small" {{ theme_config('hero_ip_size') === 'small' ? 'selected' : '' }}>Petite</option>
                                <option value="normal" {{ (theme_config('hero_ip_size') ?: 'normal') === 'normal' ? 'selected' : '' }}>Normale</option>
                                <option value="large" {{ theme_config('hero_ip_size') === 'large' ? 'selected' : '' }}>Grande</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Couleur texte IP</label>
                            <input type="color" class="form-control form-control-color" name="hero_ip_color" value="{{ old('hero_ip_color', theme_config('hero_ip_color')) ?: '#4ade80' }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Couleur fond IP</label>
                            <input type="color" class="form-control form-control-color" name="hero_ip_bg_color" value="{{ old('hero_ip_bg_color', theme_config('hero_ip_bg_color')) ?: '#0a3d1f' }}">
                        </div>
                    </div>
                </div>

                <div class="rc-opt-group">
                    <strong class="d-block mb-2"><i class="bi bi-hdd-network"></i> Carte serveur (panneau dans le hero)</strong>
                    <small class="text-muted d-block mb-2">Le bloc qui affiche l'IP, le statut et les joueurs en ligne dans le hero.</small>
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Afficher</label>
                            <select class="form-select" name="hero_server_card">
                                <option value="1" {{ theme_config('hero_server_card') !== '0' ? 'selected' : '' }}>Oui</option>
                                <option value="0" {{ theme_config('hero_server_card') === '0' ? 'selected' : '' }}>Non</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Style</label>
                            <select class="form-select" name="hero_server_card_style">
                                <option value="mc-sign" {{ (theme_config('hero_server_card_style') ?: 'mc-sign') === 'mc-sign' ? 'selected' : '' }}>Panneau MC (bois)</option>
                                <option value="glass" {{ theme_config('hero_server_card_style') === 'glass' ? 'selected' : '' }}>Glass (flou)</option>
                                <option value="dark" {{ theme_config('hero_server_card_style') === 'dark' ? 'selected' : '' }}>Sombre</option>
                                <option value="outline" {{ theme_config('hero_server_card_style') === 'outline' ? 'selected' : '' }}>Contour</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Label</label>
                            <input type="text" class="form-control" name="hero_server_card_label" value="{{ old('hero_server_card_label', theme_config('hero_server_card_label')) ?: 'Serveur' }}" placeholder="Serveur">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Texte "en ligne"</label>
                            <input type="text" class="form-control" name="hero_server_card_online_text" value="{{ old('hero_server_card_online_text', theme_config('hero_server_card_online_text')) ?: 'en ligne' }}" placeholder="en ligne">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Copier IP au clic</label>
                            <select class="form-select" name="hero_server_card_copy_ip">
                                <option value="1" {{ theme_config('hero_server_card_copy_ip') !== '0' ? 'selected' : '' }}>Oui</option>
                                <option value="0" {{ theme_config('hero_server_card_copy_ip') === '0' ? 'selected' : '' }}>Non</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="rc-opt-group">
                    <strong class="d-block mb-2">Boutons du Hero</strong>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Bouton 1 - Texte</label>
                            <input type="text" class="form-control" name="hero_button_text" value="{{ old('hero_button_text', theme_config('hero_button_text')) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Bouton 1 - URL</label>
                            <input type="text" class="form-control" name="hero_button_url" value="{{ old('hero_button_url', theme_config('hero_button_url')) }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Icone (bi-*)</label>
                            <input type="text" class="form-control" name="hero_button_icon" value="{{ old('hero_button_icon', theme_config('hero_button_icon')) }}" placeholder="rocket-takeoff">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Style</label>
                            <select class="form-select" name="hero_button_style">
                                <option value="primary" {{ (theme_config('hero_button_style') ?: 'primary') === 'primary' ? 'selected' : '' }}>Violet</option>
                                <option value="accent" {{ theme_config('hero_button_style') === 'accent' ? 'selected' : '' }}>Jaune</option>
                                <option value="outline" {{ theme_config('hero_button_style') === 'outline' ? 'selected' : '' }}>Contour</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <a href="https://icons.getbootstrap.com/" target="_blank" class="btn btn-sm btn-outline-primary d-block">Icones</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Bouton 2 - Texte</label>
                            <input type="text" class="form-control" name="hero_button2_text" value="{{ old('hero_button2_text', theme_config('hero_button2_text')) }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Bouton 2 - URL</label>
                            <input type="text" class="form-control" name="hero_button2_url" value="{{ old('hero_button2_url', theme_config('hero_button2_url')) }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Icone</label>
                            <input type="text" class="form-control" name="hero_button2_icon" value="{{ old('hero_button2_icon', theme_config('hero_button2_icon')) }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Style</label>
                            <select class="form-select" name="hero_button2_style">
                                <option value="outline" {{ (theme_config('hero_button2_style') ?: 'outline') === 'outline' ? 'selected' : '' }}>Contour</option>
                                <option value="primary" {{ theme_config('hero_button2_style') === 'primary' ? 'selected' : '' }}>Violet</option>
                                <option value="accent" {{ theme_config('hero_button2_style') === 'accent' ? 'selected' : '' }}>Jaune</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- ============ FEATURES ============ --}}
                <div class="config-section mb-4" id="sec-features">
                    <h4><i class="bi bi-grid-3x3-gap"></i> Section Features</h4>
                    <small>Jusqu'a 6 cartes. Laissez le titre vide pour masquer une carte.</small>
                </div>

                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Activer</label>
                        <select class="form-select" name="features_enabled">
                            <option value="1" {{ theme_config('features_enabled') !== '0' ? 'selected' : '' }}>Oui</option>
                            <option value="0" {{ theme_config('features_enabled') === '0' ? 'selected' : '' }}>Non</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Colonnes</label>
                        <select class="form-select" name="features_columns">
                            <option value="2" {{ theme_config('features_columns') === '2' ? 'selected' : '' }}>2</option>
                            <option value="3" {{ (theme_config('features_columns') ?: '3') === '3' ? 'selected' : '' }}>3</option>
                            <option value="4" {{ theme_config('features_columns') === '4' ? 'selected' : '' }}>4</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Style cartes</label>
                        <select class="form-select" name="features_card_style">
                            <option value="default" {{ (theme_config('features_card_style') ?: 'default') === 'default' ? 'selected' : '' }}>Defaut (bordure)</option>
                            <option value="glow" {{ theme_config('features_card_style') === 'glow' ? 'selected' : '' }}>Glow permanent</option>
                            <option value="minimal" {{ theme_config('features_card_style') === 'minimal' ? 'selected' : '' }}>Minimal (sans bordure)</option>
                            <option value="glass" {{ theme_config('features_card_style') === 'glass' ? 'selected' : '' }}>Glass</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Style icones</label>
                        <select class="form-select" name="features_icon_style">
                            <option value="box" {{ (theme_config('features_icon_style') ?: 'box') === 'box' ? 'selected' : '' }}>Boite coloree</option>
                            <option value="circle" {{ theme_config('features_icon_style') === 'circle' ? 'selected' : '' }}>Cercle</option>
                            <option value="plain" {{ theme_config('features_icon_style') === 'plain' ? 'selected' : '' }}>Simple (sans fond)</option>
                            <option value="gradient" {{ theme_config('features_icon_style') === 'gradient' ? 'selected' : '' }}>Degrade</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="features_title" value="{{ old('features_title', theme_config('features_title')) }}" placeholder="Titre de la section">
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="features_subtitle" value="{{ old('features_subtitle', theme_config('features_subtitle')) }}" placeholder="Sous-titre">
                    </div>
                </div>

                @for($i = 1; $i <= 6; $i++)
                    <div class="feature-block">
                        <strong>Feature {{ $i }}</strong>
                        <div class="row mt-2">
                            <div class="col-md-2 mb-2">
                                <input type="text" class="form-control" name="feature_{{ $i }}_icon" value="{{ old("feature_{$i}_icon", theme_config("feature_{$i}_icon")) }}" placeholder="Icone bi-*">
                            </div>
                            <div class="col-md-4 mb-2">
                                <input type="text" class="form-control" name="feature_{{ $i }}_title" value="{{ old("feature_{$i}_title", theme_config("feature_{$i}_title")) }}" placeholder="Titre">
                            </div>
                            <div class="col-md-6 mb-2">
                                <input type="text" class="form-control" name="feature_{{ $i }}_desc" value="{{ old("feature_{$i}_desc", theme_config("feature_{$i}_desc")) }}" placeholder="Description">
                            </div>
                        </div>
                    </div>
                @endfor

                <hr>

                {{-- ============ STATS ============ --}}
                <div class="config-section mb-4" id="sec-stats">
                    <h4><i class="bi bi-bar-chart"></i> Section Statistiques</h4>
                    <small>4 blocs de stats avec icones et animations de compteur.</small>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <select class="form-select" name="stats_enabled">
                            <option value="1" {{ theme_config('stats_enabled') !== '0' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ theme_config('stats_enabled') === '0' ? 'selected' : '' }}>Desactive</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Style</label>
                        <select class="form-select" name="stats_style">
                            <option value="cards" {{ (theme_config('stats_style') ?: 'cards') === 'cards' ? 'selected' : '' }}>Cartes</option>
                            <option value="bar" {{ theme_config('stats_style') === 'bar' ? 'selected' : '' }}>Barre horizontale</option>
                            <option value="minimal" {{ theme_config('stats_style') === 'minimal' ? 'selected' : '' }}>Minimal</option>
                        </select>
                    </div>
                </div>

                @for($i = 1; $i <= 4; $i++)
                    @php $src = theme_config("stat_{$i}_source") ?: ($i === 1 ? 'api_online' : 'manual'); @endphp
                    <div class="stat-block">
                        <strong>Stat {{ $i }}</strong>
                        <div class="row mt-2">
                            <div class="col-md-3 mb-2">
                                <label class="form-label" style="font-size:0.8rem;">Source des donnees</label>
                                <select class="form-select form-select-sm" name="stat_{{ $i }}_source" onchange="document.getElementById('statManual{{ $i }}').style.display = this.value === 'manual' ? '' : 'none';">
                                    <option value="manual" {{ $src === 'manual' ? 'selected' : '' }}>Manuel (valeur fixe)</option>
                                    <option value="api_online" {{ $src === 'api_online' ? 'selected' : '' }}>API — Joueurs en ligne</option>
                                    <option value="api_registered" {{ $src === 'api_registered' ? 'selected' : '' }}>API — Inscrits sur le site</option>
                                    <option value="api_votes" {{ $src === 'api_votes' ? 'selected' : '' }}>API — Total des votes</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label class="form-label" style="font-size:0.8rem;">Icone (bi-*)</label>
                                <input type="text" class="form-control form-control-sm" name="stat_{{ $i }}_icon" value="{{ old("stat_{$i}_icon", theme_config("stat_{$i}_icon")) }}" placeholder="person-check">
                            </div>
                            <div class="col-md-3 mb-2" id="statManual{{ $i }}" @if($src !== 'manual') style="display:none;" @endif>
                                <label class="form-label" style="font-size:0.8rem;">Valeur manuelle</label>
                                <input type="text" class="form-control form-control-sm" name="stat_{{ $i }}_value" value="{{ old("stat_{$i}_value", theme_config("stat_{$i}_value")) }}" placeholder="ex: 1500+">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label" style="font-size:0.8rem;">Label affiche</label>
                                <input type="text" class="form-control form-control-sm" name="stat_{{ $i }}_label" value="{{ old("stat_{$i}_label", theme_config("stat_{$i}_label")) }}" placeholder="Joueurs en ligne">
                            </div>
                        </div>
                    </div>
                @endfor

                <hr>

                {{-- ============ STAFF ============ --}}
                <div class="config-section mb-4" id="sec-staff">
                    <h4><i class="bi bi-people"></i> Section Equipe / Staff</h4>
                    <small>Jusqu'a 12 membres. Avatar auto via pseudo Minecraft.</small>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <select class="form-select" name="staff_enabled">
                            <option value="0" {{ theme_config('staff_enabled') !== '1' ? 'selected' : '' }}>Desactive</option>
                            <option value="1" {{ theme_config('staff_enabled') === '1' ? 'selected' : '' }}>Active</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Colonnes</label>
                        <select class="form-select" name="staff_columns">
                            <option value="2" {{ theme_config('staff_columns') === '2' ? 'selected' : '' }}>2</option>
                            <option value="3" {{ theme_config('staff_columns') === '3' ? 'selected' : '' }}>3</option>
                            <option value="4" {{ (theme_config('staff_columns') ?: '4') === '4' ? 'selected' : '' }}>4</option>
                            <option value="5" {{ theme_config('staff_columns') === '5' ? 'selected' : '' }}>5</option>
                            <option value="6" {{ theme_config('staff_columns') === '6' ? 'selected' : '' }}>6</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Style avatar</label>
                        <select class="form-select" name="staff_avatar_style">
                            <option value="square" {{ (theme_config('staff_avatar_style') ?: 'square') === 'square' ? 'selected' : '' }}>Carre (MC)</option>
                            <option value="round" {{ theme_config('staff_avatar_style') === 'round' ? 'selected' : '' }}>Rond</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="staff_title" value="{{ old('staff_title', theme_config('staff_title')) }}" placeholder="Titre">
                    </div>
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="staff_subtitle" value="{{ old('staff_subtitle', theme_config('staff_subtitle')) }}" placeholder="Sous-titre">
                    </div>
                </div>

                @for($i = 1; $i <= 12; $i++)
                    <div class="staff-block">
                        <strong>Membre {{ $i }}</strong>
                        <div class="row mt-2">
                            <div class="col-md-3 mb-2">
                                <input type="text" class="form-control" name="staff_{{ $i }}_name" value="{{ old("staff_{$i}_name", theme_config("staff_{$i}_name")) }}" placeholder="Pseudo">
                            </div>
                            <div class="col-md-3 mb-2">
                                <input type="text" class="form-control" name="staff_{{ $i }}_role" value="{{ old("staff_{$i}_role", theme_config("staff_{$i}_role")) }}" placeholder="Role">
                            </div>
                            <div class="col-md-6 mb-2">
                                <input type="text" class="form-control" name="staff_{{ $i }}_avatar" value="{{ old("staff_{$i}_avatar", theme_config("staff_{$i}_avatar")) }}" placeholder="URL avatar (vide = skin MC auto)">
                            </div>
                        </div>
                    </div>
                @endfor

                <hr>

                {{-- ============ NEWS ============ --}}
                <div class="config-section mb-4" id="sec-news">
                    <h4><i class="bi bi-newspaper"></i> Section Actualites</h4>
                    <small>Parametres d'affichage des articles sur la page d'accueil.</small>
                </div>

                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Activer</label>
                        <select class="form-select" name="news_enabled">
                            <option value="1" {{ theme_config('news_enabled') !== '0' ? 'selected' : '' }}>Oui</option>
                            <option value="0" {{ theme_config('news_enabled') === '0' ? 'selected' : '' }}>Non</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="number" class="form-control" name="news_count" value="{{ old('news_count', theme_config('news_count')) ?: '4' }}" min="1" max="12">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Colonnes</label>
                        <select class="form-select" name="news_columns">
                            <option value="1" {{ theme_config('news_columns') === '1' ? 'selected' : '' }}>1</option>
                            <option value="2" {{ (theme_config('news_columns') ?: '2') === '2' ? 'selected' : '' }}>2</option>
                            <option value="3" {{ theme_config('news_columns') === '3' ? 'selected' : '' }}>3</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" class="form-control" name="news_title" value="{{ old('news_title', theme_config('news_title')) }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Sous-titre</label>
                        <input type="text" class="form-control" name="news_subtitle" value="{{ old('news_subtitle', theme_config('news_subtitle')) }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Image</label>
                        <select class="form-select" name="news_show_image">
                            <option value="1" {{ theme_config('news_show_image') !== '0' ? 'selected' : '' }}>Oui</option>
                            <option value="0" {{ theme_config('news_show_image') === '0' ? 'selected' : '' }}>Non</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Date</label>
                        <select class="form-select" name="news_show_date">
                            <option value="1" {{ theme_config('news_show_date') !== '0' ? 'selected' : '' }}>Oui</option>
                            <option value="0" {{ theme_config('news_show_date') === '0' ? 'selected' : '' }}>Non</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Extrait</label>
                        <select class="form-select" name="news_show_excerpt">
                            <option value="1" {{ theme_config('news_show_excerpt') !== '0' ? 'selected' : '' }}>Oui</option>
                            <option value="0" {{ theme_config('news_show_excerpt') === '0' ? 'selected' : '' }}>Non</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Long. extrait (car.)</label>
                        <input type="number" class="form-control" name="news_excerpt_length" value="{{ old('news_excerpt_length', theme_config('news_excerpt_length')) ?: '150' }}" min="50" max="500">
                    </div>
                </div>

                <hr>

                {{-- ============ DISCORD ============ --}}
                <div class="config-section mb-4" id="sec-discord">
                    <h4><i class="bi bi-discord"></i> Section Discord</h4>
                    <small>Activez le widget dans les parametres de votre serveur Discord.</small>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">ID du serveur</label>
                        <input type="text" class="form-control" name="discord_id" value="{{ old('discord_id', theme_config('discord_id')) }}" placeholder="123456789012345678">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Lien d'invitation</label>
                        <input type="text" class="form-control" name="discord_invite_url" value="{{ old('discord_invite_url', theme_config('discord_invite_url')) }}" placeholder="https://discord.gg/xxxxx">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Style</label>
                        <select class="form-select" name="discord_style">
                            <option value="split" {{ (theme_config('discord_style') ?: 'split') === 'split' ? 'selected' : '' }}>Split (texte + widget)</option>
                            <option value="widget" {{ theme_config('discord_style') === 'widget' ? 'selected' : '' }}>Widget seul</option>
                            <option value="card" {{ theme_config('discord_style') === 'card' ? 'selected' : '' }}>Carte (sans widget)</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" class="form-control" name="discord_title" value="{{ old('discord_title', theme_config('discord_title')) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="discord_description" rows="2">{{ old('discord_description', theme_config('discord_description')) }}</textarea>
                    </div>
                </div>

                <hr>

                {{-- ============ CTA ============ --}}
                <div class="config-section mb-4" id="sec-cta">
                    <h4><i class="bi bi-megaphone"></i> Section Call To Action</h4>
                    <small>Bandeau d'appel a l'action. Desactive par defaut.</small>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <select class="form-select" name="cta_enabled">
                            <option value="0" {{ theme_config('cta_enabled') !== '1' ? 'selected' : '' }}>Desactive</option>
                            <option value="1" {{ theme_config('cta_enabled') === '1' ? 'selected' : '' }}>Active</option>
                        </select>
                    </div>
                    <div class="col-md-9 mb-3">
                        <input type="text" class="form-control" name="cta_background" value="{{ old('cta_background', theme_config('cta_background')) }}" placeholder="URL image de fond (optionnel)">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <input type="text" class="form-control" name="cta_title" value="{{ old('cta_title', theme_config('cta_title')) }}" placeholder="Titre CTA">
                    </div>
                    <div class="col-md-4 mb-3">
                        <input type="text" class="form-control" name="cta_subtitle" value="{{ old('cta_subtitle', theme_config('cta_subtitle')) }}" placeholder="Sous-titre CTA">
                    </div>
                    <div class="col-md-4 mb-3">
                        <select class="form-select" name="cta_button_style">
                            <option value="accent" {{ (theme_config('cta_button_style') ?: 'accent') === 'accent' ? 'selected' : '' }}>Bouton jaune</option>
                            <option value="primary" {{ theme_config('cta_button_style') === 'primary' ? 'selected' : '' }}>Bouton violet</option>
                            <option value="outline" {{ theme_config('cta_button_style') === 'outline' ? 'selected' : '' }}>Bouton contour</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <input type="text" class="form-control" name="cta_button_text" value="{{ old('cta_button_text', theme_config('cta_button_text')) }}" placeholder="Texte bouton">
                    </div>
                    <div class="col-md-4 mb-3">
                        <input type="text" class="form-control" name="cta_button_url" value="{{ old('cta_button_url', theme_config('cta_button_url')) }}" placeholder="URL bouton">
                    </div>
                    <div class="col-md-4 mb-3">
                        <input type="text" class="form-control" name="cta_button_icon" value="{{ old('cta_button_icon', theme_config('cta_button_icon')) }}" placeholder="Icone (bi-*)">
                    </div>
                </div>

                <hr>

                {{-- ============ ORDRE DES SECTIONS ============ --}}
                <div class="config-section mb-4" id="sec-order">
                    <h4><i class="bi bi-arrows-move"></i> Ordre des sections</h4>
                    <small>Changez l'ordre d'affichage (separe par des virgules).</small>
                </div>

                <div class="mb-3">
                    <input type="text" class="form-control" name="sections_order" value="{{ old('sections_order', theme_config('sections_order')) ?: 'servers,features,stats,news,staff,discord,cta' }}">
                    <small class="text-muted">Valeurs : servers, features, stats, news, staff, discord, cta</small>
                </div>

                <hr>

                {{-- ============ FOOTER ============ --}}
                <div class="config-section mb-4" id="sec-footer">
                    <h4><i class="bi bi-layout-three-columns"></i> Footer</h4>
                </div>

                <div class="row mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Style du footer</label>
                        <select class="form-select" name="footer_style">
                            @php $fStyle = theme_config('footer_style') ?: 'default'; @endphp
                            <option value="default" {{ $fStyle === 'default' ? 'selected' : '' }}>Defaut</option>
                            <option value="glass" {{ $fStyle === 'glass' ? 'selected' : '' }}>Glass</option>
                            <option value="minimal" {{ $fStyle === 'minimal' ? 'selected' : '' }}>Minimal</option>
                            <option value="gradient" {{ $fStyle === 'gradient' ? 'selected' : '' }}>Gradient</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Couleur de fond</label>
                        <input type="color" class="form-control form-control-color" name="footer_bg_color" value="{{ theme_config('footer_bg_color') ?: '#0a0a1a' }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Nb colonnes</label>
                        <select class="form-select" name="footer_columns">
                            @php $fCols = theme_config('footer_columns') ?: '3'; @endphp
                            <option value="2" {{ $fCols === '2' ? 'selected' : '' }}>2</option>
                            <option value="3" {{ $fCols === '3' ? 'selected' : '' }}>3</option>
                            <option value="4" {{ $fCols === '4' ? 'selected' : '' }}>4</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Logo</label>
                        <select class="form-select" name="footer_show_logo">
                            <option value="1" {{ theme_config('footer_show_logo') !== '0' ? 'selected' : '' }}>Oui</option>
                            <option value="0" {{ theme_config('footer_show_logo') === '0' ? 'selected' : '' }}>Non</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Liens nouvel onglet</label>
                        <select class="form-select" name="footer_links_new_tab">
                            <option value="0" {{ theme_config('footer_links_new_tab') !== '1' ? 'selected' : '' }}>Non</option>
                            <option value="1" {{ theme_config('footer_links_new_tab') === '1' ? 'selected' : '' }}>Oui</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-9">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="footer_description" rows="2">{{ old('footer_description', theme_config('footer_description')) }}</textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Copyright</label>
                        <input type="text" class="form-control" name="footer_copyright" value="{{ old('footer_copyright', theme_config('footer_copyright')) }}" placeholder="Laissez vide = defaut">
                    </div>
                </div>

                {{-- Footer columns --}}
                <div class="row">
                    {{-- Colonne 1 --}}
                    <div class="col-md-3 mb-3">
                        <div class="footer-col-config">
                            <label class="form-label fw-bold"><i class="bi bi-1-circle"></i> Colonne 1</label>
                            <input type="text" class="form-control mb-2" name="footer_col1_title" value="{{ old('footer_col1_title', theme_config('footer_col1_title')) }}">
                            <label class="form-label">Contenu</label>
                            <select class="form-select form-select-sm" name="footer_col1_content">
                                @php $col1c = theme_config('footer_col1_content') ?: 'auto'; @endphp
                                <option value="auto" {{ $col1c === 'auto' ? 'selected' : '' }}>Auto (navbar)</option>
                                <option value="description" {{ $col1c === 'description' ? 'selected' : '' }}>Description + logo</option>
                            </select>
                        </div>
                    </div>

                    {{-- Colonne 2 --}}
                    <div class="col-md-3 mb-3">
                        <div class="footer-col-config">
                            <label class="form-label fw-bold"><i class="bi bi-2-circle"></i> Colonne 2</label>
                            <input type="text" class="form-control mb-2" name="footer_col2_title" value="{{ old('footer_col2_title', theme_config('footer_col2_title')) }}">
                            <div id="footerLinks">
                                @foreach(theme_config('footer_links') ?? [] as $link)
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="footer_links[{index}][name]" placeholder="Nom" value="{{ $link['name'] }}">
                                        <input type="url" class="form-control" name="footer_links[{index}][value]" placeholder="URL" value="{{ $link['value'] }}">
                                        <button class="btn btn-outline-danger btn-sm link-remove" type="button"><i class="bi bi-x-lg"></i></button>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" id="addLinkButton" class="btn btn-sm btn-success"><i class="bi bi-plus-lg"></i> Ajouter</button>
                        </div>
                    </div>

                    {{-- Colonne 3 --}}
                    <div class="col-md-3 mb-3">
                        <div class="footer-col-config">
                            <label class="form-label fw-bold"><i class="bi bi-3-circle"></i> Colonne 3</label>
                            <input type="text" class="form-control mb-2" name="footer_col3_title" value="{{ old('footer_col3_title', theme_config('footer_col3_title')) }}">
                            @for($i = 1; $i <= 6; $i++)
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="footer_legal_link{{ $i }}_text" value="{{ old("footer_legal_link{$i}_text", theme_config("footer_legal_link{$i}_text")) }}" placeholder="Lien {{ $i }}">
                                    <input type="text" class="form-control" name="footer_legal_link{{ $i }}_url" value="{{ old("footer_legal_link{$i}_url", theme_config("footer_legal_link{$i}_url")) }}" placeholder="URL">
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- Colonne 4 --}}
                    <div class="col-md-3 mb-3">
                        <div class="footer-col-config">
                            <label class="form-label fw-bold"><i class="bi bi-4-circle"></i> Colonne 4 <small class="text-muted">(si 4 colonnes)</small></label>
                            <input type="text" class="form-control mb-2" name="footer_col4_title" value="{{ old('footer_col4_title', theme_config('footer_col4_title')) }}">
                            @for($i = 1; $i <= 6; $i++)
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="footer_col4_link{{ $i }}_text" value="{{ old("footer_col4_link{$i}_text", theme_config("footer_col4_link{$i}_text")) }}" placeholder="Lien {{ $i }}">
                                    <input type="text" class="form-control" name="footer_col4_link{{ $i }}_url" value="{{ old("footer_col4_link{$i}_url", theme_config("footer_col4_link{$i}_url")) }}" placeholder="URL">
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <h5 class="mt-3">Reseaux sociaux</h5>
                <div class="row">
                    @php
                        $socials = [
                            ['name' => 'footer_social_discord', 'icon' => 'discord', 'label' => 'Discord', 'ph' => 'https://discord.gg/...'],
                            ['name' => 'footer_social_twitter', 'icon' => 'twitter-x', 'label' => 'Twitter / X', 'ph' => 'https://x.com/...'],
                            ['name' => 'footer_social_youtube', 'icon' => 'youtube', 'label' => 'YouTube', 'ph' => 'https://youtube.com/...'],
                            ['name' => 'footer_social_instagram', 'icon' => 'instagram', 'label' => 'Instagram', 'ph' => 'https://instagram.com/...'],
                            ['name' => 'footer_social_tiktok', 'icon' => 'tiktok', 'label' => 'TikTok', 'ph' => 'https://tiktok.com/...'],
                            ['name' => 'footer_social_twitch', 'icon' => 'twitch', 'label' => 'Twitch', 'ph' => 'https://twitch.tv/...'],
                            ['name' => 'footer_social_github', 'icon' => 'github', 'label' => 'GitHub', 'ph' => 'https://github.com/...'],
                            ['name' => 'footer_social_facebook', 'icon' => 'facebook', 'label' => 'Facebook', 'ph' => 'https://facebook.com/...'],
                            ['name' => 'footer_social_telegram', 'icon' => 'telegram', 'label' => 'Telegram', 'ph' => 'https://t.me/...'],
                            ['name' => 'footer_social_linkedin', 'icon' => 'linkedin', 'label' => 'LinkedIn', 'ph' => 'https://linkedin.com/...'],
                            ['name' => 'footer_social_snapchat', 'icon' => 'snapchat', 'label' => 'Snapchat', 'ph' => 'https://snapchat.com/...'],
                            ['name' => 'footer_social_steam', 'icon' => 'steam', 'label' => 'Steam', 'ph' => 'https://steamcommunity.com/...'],
                            ['name' => 'footer_social_reddit', 'icon' => 'reddit', 'label' => 'Reddit', 'ph' => 'https://reddit.com/r/...'],
                        ];
                    @endphp
                    @foreach($socials as $s)
                        <div class="col-md-3 mb-3">
                            <label class="form-label"><i class="bi bi-{{ $s['icon'] }}"></i> {{ $s['label'] }}</label>
                            <input type="text" class="form-control" name="{{ $s['name'] }}" value="{{ old($s['name'], theme_config($s['name'])) }}" placeholder="{{ $s['ph'] }}">
                        </div>
                    @endforeach
                </div>

                <div class="mb-3">
                    <label class="form-label">HTML personnalise (footer)</label>
                    <textarea class="form-control" name="footer_custom_html" rows="3" placeholder="Code HTML libre affiche au-dessus du copyright">{{ old('footer_custom_html', theme_config('footer_custom_html')) }}</textarea>
                    <small class="text-muted">HTML affiche avant la ligne de copyright. Vous pouvez y mettre du texte, des badges, etc.</small>
                </div>

                <hr>

                {{-- ============ CSS / JS CUSTOM ============ --}}
                <div class="config-section mb-4" id="sec-custom">
                    <h4><i class="bi bi-code-slash"></i> Code personnalise</h4>
                    <small>CSS, JS et code HTML dans le &lt;head&gt;.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">CSS personnalise</label>
                    <textarea class="form-control" name="custom_css" rows="4" style="font-family:monospace;font-size:0.85rem;">{{ old('custom_css', theme_config('custom_css')) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">JavaScript personnalise</label>
                    <textarea class="form-control" name="custom_js" rows="4" style="font-family:monospace;font-size:0.85rem;">{{ old('custom_js', theme_config('custom_js')) }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Code HTML dans &lt;head&gt;</label>
                    <textarea class="form-control" name="custom_head_code" rows="3" style="font-family:monospace;font-size:0.85rem;" placeholder="Google Analytics, meta tags, etc.">{{ old('custom_head_code', theme_config('custom_head_code')) }}</textarea>
                </div>

                <hr>

                {{-- ============ PAGE 404 ============ --}}
                <div class="config-section mb-4" id="sec-errors">
                    <h4><i class="bi bi-exclamation-triangle"></i> Page 404</h4>
                    <small>Personnalisez entierement la page d'erreur 404.</small>
                </div>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Style de page</label>
                        <select class="form-select" name="error_404_style">
                            <option value="default" {{ (theme_config('error_404_style') ?: 'default') === 'default' ? 'selected' : '' }}>Classique</option>
                            <option value="fullscreen" {{ theme_config('error_404_style') === 'fullscreen' ? 'selected' : '' }}>Plein ecran</option>
                            <option value="minimal" {{ theme_config('error_404_style') === 'minimal' ? 'selected' : '' }}>Minimal</option>
                            <option value="fun" {{ theme_config('error_404_style') === 'fun' ? 'selected' : '' }}>Fun (MC style)</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Animation du code</label>
                        <select class="form-select" name="error_404_animation">
                            <option value="float" {{ (theme_config('error_404_animation') ?: 'float') === 'float' ? 'selected' : '' }}>Flottant</option>
                            <option value="pulse" {{ theme_config('error_404_animation') === 'pulse' ? 'selected' : '' }}>Pulsation</option>
                            <option value="glitch" {{ theme_config('error_404_animation') === 'glitch' ? 'selected' : '' }}>Glitch</option>
                            <option value="none" {{ theme_config('error_404_animation') === 'none' ? 'selected' : '' }}>Aucune</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Particules</label>
                        <select class="form-select" name="error_404_particles">
                            <option value="1" {{ theme_config('error_404_particles') !== '0' ? 'selected' : '' }}>Oui</option>
                            <option value="0" {{ theme_config('error_404_particles') === '0' ? 'selected' : '' }}>Non</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Barre de recherche</label>
                        <select class="form-select" name="error_404_show_search">
                            <option value="0" {{ theme_config('error_404_show_search') !== '1' ? 'selected' : '' }}>Non</option>
                            <option value="1" {{ theme_config('error_404_show_search') === '1' ? 'selected' : '' }}>Oui</option>
                        </select>
                    </div>
                </div>

                <div class="rc-opt-group">
                    <strong class="d-block mb-2">Textes</strong>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Titre</label>
                            <input type="text" class="form-control" name="error_404_title" value="{{ old('error_404_title', theme_config('error_404_title')) }}" placeholder="Page introuvable">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Sous-titre</label>
                            <input type="text" class="form-control" name="error_404_subtitle" value="{{ old('error_404_subtitle', theme_config('error_404_subtitle')) }}" placeholder="Oups ! On dirait que tu t'es perdu...">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Message</label>
                            <input type="text" class="form-control" name="error_404_message" value="{{ old('error_404_message', theme_config('error_404_message')) }}" placeholder="La page n'existe pas ou a ete deplacee.">
                        </div>
                    </div>
                </div>

                <div class="rc-opt-group">
                    <strong class="d-block mb-2">Apparence du code "404"</strong>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Taille du code</label>
                            <input type="text" class="form-control" name="error_404_code_size" value="{{ old('error_404_code_size', theme_config('error_404_code_size')) ?: '8rem' }}" placeholder="8rem">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Couleur du code</label>
                            <input type="color" class="form-control form-control-color" name="error_404_code_color" value="{{ old('error_404_code_color', theme_config('error_404_code_color')) ?: '#6d28d9' }}">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Image / illustration</label>
                            <input type="text" class="form-control" name="error_404_image" value="{{ old('error_404_image', theme_config('error_404_image')) }}" placeholder="URL image (optionnel)">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Image de fond</label>
                            <input type="text" class="form-control" name="error_404_bg_image" value="{{ old('error_404_bg_image', theme_config('error_404_bg_image')) }}" placeholder="URL background (optionnel)">
                        </div>
                    </div>
                </div>

                <div class="rc-opt-group">
                    <strong class="d-block mb-2">Boutons</strong>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Bouton 1 - Texte</label>
                            <input type="text" class="form-control" name="error_404_button_text" value="{{ old('error_404_button_text', theme_config('error_404_button_text')) ?: 'Retour a l\'accueil' }}">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Icone</label>
                            <input type="text" class="form-control" name="error_404_button_icon" value="{{ old('error_404_button_icon', theme_config('error_404_button_icon')) ?: 'house' }}" placeholder="house">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Style</label>
                            <select class="form-select" name="error_404_button_style">
                                <option value="primary" {{ (theme_config('error_404_button_style') ?: 'primary') === 'primary' ? 'selected' : '' }}>Violet</option>
                                <option value="accent" {{ theme_config('error_404_button_style') === 'accent' ? 'selected' : '' }}>Jaune</option>
                                <option value="outline" {{ theme_config('error_404_button_style') === 'outline' ? 'selected' : '' }}>Contour</option>
                                <option value="glass" {{ theme_config('error_404_button_style') === 'glass' ? 'selected' : '' }}>Glass</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Bouton 2 - Texte</label>
                            <input type="text" class="form-control" name="error_404_button2_text" value="{{ old('error_404_button2_text', theme_config('error_404_button2_text')) }}" placeholder="(optionnel)">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">Bouton 2 - URL</label>
                            <input type="text" class="form-control" name="error_404_button2_url" value="{{ old('error_404_button2_url', theme_config('error_404_button2_url')) }}" placeholder="/discord">
                        </div>
                        <div class="col-md-1 mb-3">
                            <label class="form-label">Icone</label>
                            <input type="text" class="form-control" name="error_404_button2_icon" value="{{ old('error_404_button2_icon', theme_config('error_404_button2_icon')) }}" placeholder="bi-*">
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="form-label">Message personnalise maintenance</label>
                        <input type="text" class="form-control" name="maintenance_custom_message" value="{{ old('maintenance_custom_message', theme_config('maintenance_custom_message')) }}" placeholder="Le serveur est en maintenance, revenez plus tard !">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg mt-3">
                    <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>
@endsection
