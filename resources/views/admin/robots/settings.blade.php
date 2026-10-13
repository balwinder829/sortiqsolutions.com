@extends('admin.layouts.app')

@section('title', 'Robots.txt Settings')

@section('content')
    @include('admin.partials.page-hero', [
        'kicker' => 'SEO Settings',
        'title' => 'Robots.txt Configuration',
        'description' => 'Manage crawling policies, search engine indexing, block administrative paths, and view live output preview.',
    ])

    @if (session('status'))
        <div class="success-box" style="margin-bottom: 24px; padding: 16px; background: #e6f4ea; color: #137333; border-radius: 8px; font-weight: 600;">
            {{ session('status') }}
        </div>
    @endif

    <form id="robots-settings-form" action="{{ route('admin.robots-settings.update') }}" method="post" class="site-settings-form" novalidate>
        @csrf
        @method('PUT')

        <div class="settings-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; align-items: start; margin-bottom: 30px;">
            
            <!-- Left Side: Controls -->
            <section class="settings-panel" style="background: #fff; padding: 24px; border-radius: 12px; border: 1px solid #e2e8f0;">
                <div class="settings-panel-title" style="margin-bottom: 20px;">
                    <span style="color: #ff6600; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Mode Preset</span>
                    <strong style="display: block; font-size: 20px; color: #001a3d; margin-top: 4px;">Crawling Mode</strong>
                </div>

                <div class="settings-panel-body">
                    <label class="settings-field" style="display: block; margin-bottom: 24px; font-weight: 600; color: #334155;">
                        Select Crawling Mode
                        <select name="crawling_mode" id="crawling_mode" class="@error('crawling_mode') is-invalid @enderror" style="width: 100%; padding: 12px; margin-top: 8px; border-radius: 8px; border: 1px solid #cbd5e1; outline: none;">
                            <option value="production" @selected(old('crawling_mode', $settings->crawling_mode) === 'production')>Production Mode (Recommended)</option>
                            <option value="maintenance" @selected(old('crawling_mode', $settings->crawling_mode) === 'maintenance')>Maintenance Mode (Block All)</option>
                            <option value="development" @selected(old('crawling_mode', $settings->crawling_mode) === 'development')>Development Mode (Block All)</option>
                            <option value="custom" @selected(old('crawling_mode', $settings->crawling_mode) === 'custom')>Custom Mode (Manual Toggles)</option>
                        </select>
                        @error('crawling_mode')
                            <span class="field-error" style="color: #dc2626; font-size: 13px; margin-top: 4px; display: block;">{{ $message }}</span>
                        @enderror
                    </label>

                    <div class="settings-panel-title" style="margin-top: 30px; margin-bottom: 16px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
                        <span style="color: #ff6600; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Granular Rules</span>
                        <strong style="display: block; font-size: 18px; color: #001a3d; margin-top: 4px;">Crawling Policies</strong>
                    </div>

                    <div style="display: grid; gap: 16px;">
                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-weight: 600; color: #334155;">
                            <input type="checkbox" name="allow_frontend" id="allow_frontend" value="1" @checked(old('allow_frontend', $settings->allow_frontend)) style="width: 18px; height: 18px; accent-color: #ff6600;">
                            Allow Frontend Crawling
                        </label>

                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-weight: 600; color: #334155;">
                            <input type="checkbox" name="block_backend" id="block_backend" value="1" @checked(old('block_backend', $settings->block_backend)) style="width: 18px; height: 18px; accent-color: #ff6600;">
                            Block Backend/Admin Crawling
                        </label>

                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-weight: 600; color: #334155;">
                            <input type="checkbox" name="block_api" id="block_api" value="1" @checked(old('block_api', $settings->block_api)) style="width: 18px; height: 18px; accent-color: #ff6600;">
                            Block API Routes
                        </label>

                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-weight: 600; color: #334155;">
                            <input type="checkbox" name="block_login" id="block_login" value="1" @checked(old('block_login', $settings->block_login)) style="width: 18px; height: 18px; accent-color: #ff6600;">
                            Block Login & Auth Routes
                        </label>

                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-weight: 600; color: #334155;">
                            <input type="checkbox" name="include_sitemap" id="include_sitemap" value="1" @checked(old('include_sitemap', $settings->include_sitemap)) style="width: 18px; height: 18px; accent-color: #ff6600;">
                            Include Sitemap link
                        </label>

                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-weight: 600; color: #334155;">
                            <input type="checkbox" name="block_images" id="block_images" value="1" @checked(old('block_images', $settings->block_images)) style="width: 18px; height: 18px; accent-color: #ff6600;">
                            Block Image Assets
                        </label>

                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-weight: 600; color: #334155;">
                            <input type="checkbox" name="block_css" id="block_css" value="1" @checked(old('block_css', $settings->block_css)) style="width: 18px; height: 18px; accent-color: #ff6600;">
                            Block CSS Stylesheets
                        </label>

                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-weight: 600; color: #334155;">
                            <input type="checkbox" name="block_js" id="block_js" value="1" @checked(old('block_js', $settings->block_js)) style="width: 18px; height: 18px; accent-color: #ff6600;">
                            Block JavaScript files
                        </label>
                    </div>

                    <div class="settings-panel-title" style="margin-top: 30px; margin-bottom: 16px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
                        <span style="color: #ff6600; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Custom Rules</span>
                        <strong style="display: block; font-size: 18px; color: #001a3d; margin-top: 4px;">Additional URLs</strong>
                    </div>

                    <label class="settings-field" style="display: block; font-weight: 600; color: #334155;">
                        Custom rules/paths (one per line)
                        <textarea name="custom_rules" id="custom_rules" style="width: 100%; min-height: 120px; padding: 12px; margin-top: 8px; border-radius: 8px; border: 1px solid #cbd5e1; outline: none; font-family: monospace; font-size: 13px; resize: vertical;">{{ old('custom_rules', $settings->custom_rules) }}</textarea>
                        <small style="display: block; font-weight: 400; color: #64748b; margin-top: 6px; line-height: 1.4;">
                            Type paths like `/my-folder/` (automatically blocks it) or write full directives like `Disallow: /secret` or `Allow: /public/css/`.
                        </small>
                    </label>
                </div>
            </section>

            <!-- Right Side: Live Preview -->
            <section class="settings-panel" style="background: #fff; padding: 24px; border-radius: 12px; border: 1px solid #e2e8f0; height: 100%;">
                <div class="settings-panel-title" style="margin-bottom: 20px;">
                    <span style="color: #ff6600; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Live Output</span>
                    <strong style="display: block; font-size: 20px; color: #001a3d; margin-top: 4px;">Robots.txt Preview</strong>
                </div>

                <div class="settings-panel-body">
                    <div style="position: relative;">
                        <span style="position: absolute; right: 12px; top: 12px; font-size: 11px; background: rgba(255,255,255,0.15); color: #fff; padding: 2px 8px; border-radius: 4px; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Preview</span>
                        <pre id="robots_preview" style="background: #0f172a; color: #38bdf8; padding: 24px; border-radius: 8px; font-family: 'Courier New', Courier, monospace; font-size: 14px; white-space: pre-wrap; line-height: 1.6; min-height: 320px; margin: 0; box-shadow: inset 0 2px 4px rgba(0,0,0,0.2); overflow-x: auto;"></pre>
                    </div>
                </div>
            </section>

        </div>

        <div class="settings-save-bar" style="display: flex; gap: 16px; align-items: center; background: #fff; padding: 20px 24px; border-radius: 12px; border: 1px solid #e2e8f0; flex-wrap: wrap;">
            <button type="submit" class="primary-btn" style="background: #ff6600; color: #fff; border: 0; padding: 12px 32px; border-radius: 999px; font-weight: 700; cursor: pointer; transition: background 0.2s;">
                Save Settings
            </button>

            <button type="button" class="btn-reset" onclick="if(confirm('Are you sure you want to reset all settings to recommended defaults?')) { document.getElementById('reset-form').submit(); }" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; padding: 12px 32px; border-radius: 999px; font-weight: 700; cursor: pointer; transition: all 0.2s;">
                Reset to Recommended Settings
            </button>

            <a href="{{ route('robots') }}" target="_blank" class="view-live-link" style="color: #ff6600; font-weight: 700; text-decoration: none; margin-left: auto; display: flex; align-items: center; gap: 6px;">
                View Live Robots.txt <span>&nearr;</span>
            </a>
        </div>
    </form>

    <form id="reset-form" action="{{ route('admin.robots-settings.reset') }}" method="post" style="display: none;">
        @csrf
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modeSelect = document.getElementById('crawling_mode');
            const checkboxes = {
                allow_frontend: document.getElementById('allow_frontend'),
                block_backend: document.getElementById('block_backend'),
                block_api: document.getElementById('block_api'),
                block_login: document.getElementById('block_login'),
                include_sitemap: document.getElementById('include_sitemap'),
                block_images: document.getElementById('block_images'),
                block_css: document.getElementById('block_css'),
                block_js: document.getElementById('block_js')
            };
            const customRulesInput = document.getElementById('custom_rules');
            const previewEl = document.getElementById('robots_preview');
            const siteUrl = "{{ rtrim(url('/'), '/') }}";

            // Standard production defaults
            const productionRules = {
                allow_frontend: true,
                block_backend: true,
                block_api: true,
                block_login: true,
                include_sitemap: true,
                block_images: false,
                block_css: false,
                block_js: false
            };

            // Lock values based on selected mode
            function handleModeChange() {
                const mode = modeSelect.value;

                if (mode === 'production') {
                    Object.keys(checkboxes).forEach(key => {
                        checkboxes[key].checked = productionRules[key];
                        checkboxes[key].disabled = true;
                    });
                } else if (mode === 'maintenance' || mode === 'development') {
                    Object.keys(checkboxes).forEach(key => {
                        checkboxes[key].checked = false;
                        checkboxes[key].disabled = true;
                    });
                } else { // Custom mode
                    Object.keys(checkboxes).forEach(key => {
                        checkboxes[key].disabled = false;
                    });
                }

                updatePreviewText();
            }

            // Generate robots.txt preview contents in frontend
            function updatePreviewText() {
                const mode = modeSelect.value;

                if (mode === 'maintenance' || mode === 'development') {
                    previewEl.textContent = "User-agent: *\nDisallow: /";
                    return;
                }

                const allowFrontend = checkboxes.allow_frontend.checked;

                if (mode === 'custom' && !allowFrontend) {
                    previewEl.textContent = "User-agent: *\nDisallow: /";
                    return;
                }

                let rules = [];
                rules.push("User-agent: *");
                rules.push("Allow: /");

                if (checkboxes.block_backend.checked) {
                    rules.push("Disallow: /admin/");
                    rules.push("Disallow: /sortiqadmin/");
                }

                if (checkboxes.block_api.checked) {
                    rules.push("Disallow: /api/");
                }

                if (checkboxes.block_login.checked) {
                    rules.push("Disallow: /login");
                    rules.push("Disallow: /logout");
                    rules.push("Disallow: /register");
                    rules.push("Disallow: /password/");
                }

                if (checkboxes.block_images.checked) {
                    rules.push("Disallow: /images/");
                    rules.push("Disallow: /storage/");
                }

                if (checkboxes.block_css.checked) {
                    rules.push("Disallow: /css/");
                    rules.push("Disallow: /build/assets/*.css");
                }

                if (checkboxes.block_js.checked) {
                    rules.push("Disallow: /js/");
                    rules.push("Disallow: /build/assets/*.js");
                }

                // Add Custom Rules
                const customRulesVal = customRulesInput.value.trim();
                if (customRulesVal) {
                    customRulesVal.split('\n').forEach(line => {
                        const trimmedLine = line.trim();
                        if (trimmedLine) {
                            if (/^(Disallow|Allow|Sitemap|User-agent):/i.test(trimmedLine)) {
                                rules.push(trimmedLine);
                            } else {
                                rules.push(`Disallow: ${trimmedLine}`);
                            }
                        }
                    });
                }

                if (checkboxes.include_sitemap.checked) {
                    rules.push(`Sitemap: ${siteUrl}/sitemap.xml`);
                }

                previewEl.textContent = rules.join("\n");
            }

            // Listeners
            modeSelect.addEventListener('change', handleModeChange);
            Object.values(checkboxes).forEach(cb => {
                cb.addEventListener('change', updatePreviewText);
            });
            customRulesInput.addEventListener('input', updatePreviewText);

            // Ensure disabled checkboxes are submitted correctly by enabling them right before form submit
            document.getElementById('robots-settings-form').addEventListener('submit', () => {
                Object.values(checkboxes).forEach(cb => {
                    cb.disabled = false;
                });
            });

            // Run on initial load
            handleModeChange();
        });
    </script>
@endsection
