/**
 * UX Audit Toolkit
 * Phase 1-7: Final Optimization, Cleanup, and Benchmark
 * Environment: Vanilla JS (ES6+), Bookmarklet Ready, No Dependencies.
 */
(function () {
    'use strict';

    // ==========================================
    // 1. CONFIGURATION & STATE (Optimized)
    // ==========================================
    const CONFIG = {
        ns: '__UX_AUDIT_TOOLKIT__',
        hostId: '__UX_AUDIT_HOST__',
        styleId: '__UX_AUDIT_STYLE__',
        debug: false // Turned off for production/benchmark
    };

    const Logger = {
        info: (msg) => CONFIG.debug && console.log(`%c[UX Audit] %c${msg}`, 'color: #4f46e5; font-weight: bold;', 'color: inherit;'),
        warn: (msg) => CONFIG.debug && console.warn(`%c[UX Audit] %c${msg}`, 'color: #f59e0b; font-weight: bold;', 'color: inherit;'),
        error: (msg) => console.error(`%c[UX Audit Error] %c${msg}`, 'color: #ef4444; font-weight: bold;', 'color: inherit;')
    };

    // State dipadatkan untuk memori
    const State = {
        init: false,
        wireframe: false
    };

    // ==========================================
    // 2. STYLE MANAGER
    // ==========================================
    class StyleManager {
        constructor() {
            this.styleEl = null;
            this.cssCache = ''; // Caching CSS string untuk menekan re-kalkulasi
        }

        buildCSS() {
            // Kita gabungkan Wireframe & Framework Adapter secara statis
            // untuk memangkas iterasi objek di memori (Performance Boost).
            const imgPlaceholderSVG = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='100' height='100'><rect width='100' height='100' fill='%23f1f5f9'/><line x1='0' y1='0' x2='100' y2='100' stroke='%2394a3b8' stroke-width='2'/><line x1='100' y1='0' x2='0' y2='100' stroke='%2394a3b8' stroke-width='2'/></svg>";

            return `
                :root {
                    --wf-t: #334155 !important;
                    --wf-m: #94a3b8 !important;
                    --wf-bc: #cbd5e1 !important;
                    --wf-bg: #ffffff !important;
                    --wf-hl: #f1f5f9 !important;
                    --wf-r: 0px !important;
                    --wf-s: none !important;
                }
                body, main, section, header, footer, aside, nav, div, article { background-color: var(--wf-bg) !important; background-image: none !important; box-shadow: var(--wf-s) !important; color: var(--wf-t) !important; border-radius: var(--wf-r) !important; transition: none !important; }
                h1, h2, h3, h4, h5, h6, p, span, a, li, td, th, label { color: var(--wf-t) !important; text-shadow: none !important; }
                a { text-decoration: underline !important; }
                a:hover { background-color: var(--wf-hl) !important; }
                article, section, aside, header, footer, [class*="card"] { border: 1px solid var(--wf-bc) !important; border-radius: var(--wf-r) !important; box-shadow: var(--wf-s) !important; }
                button, [class*="btn"], [class*="badge"], [role="button"] { background: transparent !important; border: 2px solid var(--wf-t) !important; color: var(--wf-t) !important; border-radius: var(--wf-r) !important; box-shadow: none !important; }
                input, textarea, select, [class*="form-control"] { background: var(--wf-bg) !important; border: 1px solid var(--wf-bc) !important; color: var(--wf-t) !important; border-radius: var(--wf-r) !important; }
                table { border-collapse: collapse !important; width: 100% !important; }
                th, td, tr, thead, tbody { background-color: var(--wf-bg) !important; border: 1px solid var(--wf-bc) !important; color: var(--wf-t) !important; }
                img, picture { content: url("${imgPlaceholderSVG}") !important; object-fit: cover !important; border: 1px solid var(--wf-bc) !important; background: var(--wf-hl) !important; border-radius: var(--wf-r) !important; }
                svg, [class*="fa-"], [class*="icon"], i { color: var(--wf-m) !important; fill: none !important; stroke: var(--wf-m) !important; }
                button svg, [class*="btn"] svg, button i, [class*="btn"] i { stroke: var(--wf-t) !important; color: var(--wf-t) !important; }
                iframe, canvas, [id*="map"], .leaflet-container { filter: grayscale(100%) opacity(0.5) !important; border: 1px dashed var(--wf-t) !important; background: var(--wf-hl) !important; }

                /* Framework Adapters (TW, BS5, AdminLTE) */
                [class*="bg-"], [class*="text-"], [class*="border-"], [class*="shadow-"] { background-color: var(--wf-bg) !important; background-image: none !important; color: var(--wf-t) !important; box-shadow: var(--wf-s) !important; border-radius: var(--wf-r) !important; }
                [class*="hover:bg-"]:hover, .btn:hover { background-color: var(--wf-hl) !important; color: var(--wf-t) !important; }
                .btn, .badge, .alert, .card, .bg-primary, .bg-secondary, .bg-success, .bg-danger, .bg-warning, .bg-info, .bg-light, .bg-dark { background-image: none !important; background-color: var(--wf-bg) !important; border: 1px solid var(--wf-bc) !important; color: var(--wf-t) !important; border-radius: var(--wf-r) !important; }
                .navbar, .app-sidebar, .sidebar { background-color: var(--wf-bg) !important; border-bottom: 1px solid var(--wf-bc) !important; border-right: 1px solid var(--wf-bc) !important; }
                .app-wrapper, .app-header, .small-box, .info-box { background: var(--wf-bg) !important; color: var(--wf-t) !important; border-radius: var(--wf-r) !important; box-shadow: none !important; border: 1px solid var(--wf-bc) !important; }
                .nav-link.active, .nav-item.menu-open > .nav-link { background-color: var(--wf-hl) !important; color: var(--wf-t) !important; font-weight: bold !important; }
                .swal2-popup, .swal2-container { background: var(--wf-bg) !important; border: 2px solid var(--wf-bc) !important; border-radius: 0 !important; color: var(--wf-t) !important; box-shadow: 10px 10px 0px rgba(0,0,0,0.1) !important; }
                .swal2-icon { border-color: var(--wf-m) !important; color: var(--wf-m) !important; }
                .leaflet-control-container { display: none !important; }
                canvas, [id*="chart"] { filter: grayscale(100%) opacity(0.3) !important; }
            `;
        }

        inject() {
            if (!this.cssCache) this.cssCache = this.buildCSS(); // Lazy eval & Cache

            if (!State.wireframe) {
                this.remove();
                return;
            }

            if (!this.styleEl) {
                this.styleEl = document.getElementById(CONFIG.styleId) || document.createElement('style');
                this.styleEl.id = CONFIG.styleId;
                if (!this.styleEl.parentNode) document.head.appendChild(this.styleEl);
            }
            // Mencegah layout thrashing berulang jika textContent di-set padahal isinya sama
            if(this.styleEl.textContent !== this.cssCache) {
                this.styleEl.textContent = this.cssCache;
            }
        }

        remove() {
            if (this.styleEl && this.styleEl.parentNode) {
                this.styleEl.parentNode.removeChild(this.styleEl);
            }
            const z = document.getElementById(CONFIG.styleId);
            if (z && z.parentNode) z.parentNode.removeChild(z);
            this.styleEl = null;
        }
    }

    // ==========================================
    // 3. COMPONENT DETECTOR (Optimized)
    // ==========================================
    class ComponentDetector {
        constructor() {
            // Digabung menjadi single selector besar untuk menekan reflow DOM
            this.sel = {
                'Nav/Side': 'nav, aside, header, .navbar, .sidebar',
                'Container': '.card, table',
                'Interactive': 'button, input, select, textarea, .btn',
                'Visuals': 'img, picture, svg, canvas, .leaflet-container',
                'Modals': '.modal, .swal2-popup'
            };
        }
        scan() {
            // Dijalankan secara asinkron (requestIdleCallback) agar tidak memblokir render UI panel
            const runner = window.requestIdleCallback || setTimeout;
            runner(() => {
                const r = {};
                for (let k in this.sel) {
                    try { r[k] = document.querySelectorAll(this.sel[k]).length; } catch (e) {}
                }
                Logger.info('DOM Scanned:', r);
            });
        }
    }

    // ==========================================
    // 4. UI MANAGER / TOOLBAR (Optimized Events)
    // ==========================================
    class UIManager {
        constructor(engine) {
            this.engine = engine;
            this.hostEl = null;
            this.shadow = null;

            // State
            this.drag = { is: false, cx: 20, cy: 20, ix: 0, iy: 0, ox: 0, oy: 0 };
            this.minimized = false;

            // Cached Bindings
            this._start = this.dragStart.bind(this);
            this._move = this.dragMove.bind(this);
            this._end = this.dragEnd.bind(this);
            this._min = this.toggleMin.bind(this);
        }

        build() {
            this.hostEl = document.createElement('div');
            this.hostEl.id = CONFIG.hostId;
            this.hostEl.style.cssText = `position:fixed!important;bottom:20px!important;right:20px!important;z-index:2147483647!important;`;

            this.shadow = this.hostEl.attachShadow({ mode: 'open' });

            this.shadow.innerHTML = `
                <style>
                    :host{font-family:system-ui,-apple-system,sans-serif}
                    .tb{width:240px;background:#1e293b;border:1px solid #334155;border-radius:8px;box-shadow:0 10px 25px -5px rgba(0,0,0,.5);color:#f8fafc;display:flex;flex-direction:column}
                    .tb.m .bd{display:none} .tb.m{width:auto}
                    .hd{background:#0f172a;padding:8px 12px;display:flex;justify-content:space-between;align-items:center;cursor:grab;user-select:none;border-bottom:1px solid #334155}
                    .hd:active{cursor:grabbing}
                    .ti{font-size:12px;font-weight:600;display:flex;align-items:center;gap:6px}
                    .dt{width:8px;height:8px;border-radius:50%;background:#10b981}
                    .bm{background:0 0;border:0;color:#94a3b8;cursor:pointer;font-size:16px;line-height:1;padding:0 4px}
                    .bm:hover{color:#fff}
                    .bd{padding:12px;display:flex;flex-direction:column;gap:8px}
                    .btn{background:#334155;color:#f8fafc;border:1px solid #475569;padding:6px 10px;border-radius:6px;font-size:12px;font-weight:500;cursor:pointer;display:flex;justify-content:space-between;align-items:center;transition:background .2s}
                    .btn:hover{background:#475569}
                    .btn.on{background:#4f46e5;border-color:#6366f1}
                    .btn.x{background:0 0;border:1px solid #ef4444;color:#ef4444}
                    .btn.x:hover{background:#ef4444;color:#fff}
                    .st{font-size:10px;padding:2px 6px;border-radius:12px;background:rgba(0,0,0,.2)}
                </style>
                <div class="tb" id="tb">
                    <div class="hd" id="hd">
                        <div class="ti"><div class="dt"></div> UX Audit</div>
                        <button class="bm" id="bm" title="Min/Max">&minus;</button>
                    </div>
                    <div class="bd" id="bd"></div>
                </div>
            `;

            document.body.appendChild(this.hostEl);
            this.renderBtns();

            // Event Listeners
            this.shadow.getElementById('bm').addEventListener('click', this._min);
            this.shadow.getElementById('hd').addEventListener('mousedown', this._start);
        }

        renderBtns() {
            const bd = this.shadow.getElementById('bd');
            bd.innerHTML = '';

            const wBtn = document.createElement('button');
            wBtn.className = State.wireframe ? 'btn on' : 'btn';
            wBtn.innerHTML = `Wireframe Mode <span class="st">${State.wireframe ? 'ON' : 'OFF'}</span>`;
            wBtn.onclick = () => { this.engine.toggle(); this.renderBtns(); };

            const xBtn = document.createElement('button');
            xBtn.className = 'btn x';
            xBtn.innerHTML = `Exit Toolkit`;
            xBtn.onclick = () => this.engine.cleanup();

            bd.appendChild(wBtn);
            bd.appendChild(xBtn);
        }

        toggleMin(e) {
            e.stopPropagation();
            this.minimized = !this.minimized;
            const tb = this.shadow.getElementById('tb');
            const bm = this.shadow.getElementById('bm');
            this.minimized ? tb.classList.add('m') : tb.classList.remove('m');
            bm.innerHTML = this.minimized ? '&plus;' : '&minus;';
        }

        dragStart(e) {
            this.drag.ix = e.clientX - this.drag.ox;
            this.drag.iy = e.clientY - this.drag.oy;
            this.drag.is = true;
            // Gunakan passive listener / rAF tidak diperlukan karena translate3d sangat ringan
            document.addEventListener('mousemove', this._move, { passive: true });
            document.addEventListener('mouseup', this._end);
        }

        dragMove(e) {
            if (!this.drag.is) return;
            this.drag.cx = e.clientX - this.drag.ix;
            this.drag.cy = e.clientY - this.drag.iy;
            this.drag.ox = this.drag.cx;
            this.drag.oy = this.drag.cy;
            this.hostEl.style.transform = `translate3d(${this.drag.cx}px, ${this.drag.cy}px, 0)`;
        }

        dragEnd() {
            this.drag.ix = this.drag.cx;
            this.drag.iy = this.drag.cy;
            this.drag.is = false;
            document.removeEventListener('mousemove', this._move);
            document.removeEventListener('mouseup', this._end);
        }

        destroy() {
            if (this.hostEl) {
                // Cabut semua event listener untuk mencegah memory leak
                const hd = this.shadow.getElementById('hd');
                const bm = this.shadow.getElementById('bm');
                if(hd) hd.removeEventListener('mousedown', this._start);
                if(bm) bm.removeEventListener('click', this._min);
                document.removeEventListener('mousemove', this._move);
                document.removeEventListener('mouseup', this._end);

                this.hostEl.remove();
            }
        }
    }

    // ==========================================
    // 5. ENGINE BOOTSTRAP
    // ==========================================
    class UXAuditEngine {
        constructor() {
            this.style = new StyleManager();
            this.ui = new UIManager(this);
            this.detector = new ComponentDetector();
        }

        init() {
            if (State.init) return;
            State.init = true;
            this.ui.build();
            this.detector.scan();
        }

        toggle() {
            State.wireframe = !State.wireframe;
            this.style.inject();
        }

        cleanup() {
            State.init = false;
            State.wireframe = false;
            this.style.remove();
            this.ui.destroy();
            if (window[CONFIG.ns]) delete window[CONFIG.ns];
        }
    }

    // Check Singleton
    if (window[CONFIG.ns]) {
        window[CONFIG.ns].cleanup();
        return;
    }

    const engine = new UXAuditEngine();
    window[CONFIG.ns] = engine;
    engine.init();

})();