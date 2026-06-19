<script setup>
import { onMounted, onUnmounted } from 'vue'
import { Head } from '@inertiajs/vue3'

const services = [
    { n: '01', title: 'Développement Web Custom', desc: 'Applications web performantes et scalables, conçues sur mesure pour votre métier.' },
    { n: '02', title: 'Applications SaaS', desc: 'Conception et déploiement de plateformes SaaS multi-tenant, de la maquette au Go-Live.' },
    { n: '03', title: 'Microservices & API', desc: 'Architecture microservices, API Gateway, intégration de services tiers et ETL.' },
    { n: '04', title: 'DevOps & CI/CD', desc: 'Pipelines CI/CD, containerisation Docker, déploiement continu sur VPS ou cloud.' },
    { n: '05', title: 'Transformation Digitale', desc: 'Audit, conseil stratégique et accompagnement pour digitaliser vos processus métier.' },
]

let cleanupResize = null

onMounted(() => {
    const btn = document.getElementById('gridToggle')
    function setGrid(on) {
        document.body.classList.toggle('grid-on', on)
        if (btn) {
            btn.setAttribute('aria-pressed', on ? 'true' : 'false')
            const l = btn.querySelector('.lbl')
            if (l) l.textContent = on ? 'Hide grid' : 'Show grid'
        }
    }
    function onClick() { setGrid(!document.body.classList.contains('grid-on')) }
    function onKey(e) {
        if ((e.key === 'g' || e.key === 'G') && !e.metaKey && !e.ctrlKey && !e.altKey) {
            setGrid(!document.body.classList.contains('grid-on'))
        }
    }
    btn?.addEventListener('click', onClick)
    document.addEventListener('keydown', onKey)

    document.querySelectorAll('.guides .cols').forEach((h) => {
        const n = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--cols').trim() || '12', 10)
        for (let i = 1; i <= n; i++) {
            const c = document.createElement('div')
            c.className = 'col'
            const s = document.createElement('span')
            s.textContent = i
            c.appendChild(s)
            h.appendChild(c)
        }
    })

    const cvs = document.createElement('canvas')
    const ctx = cvs.getContext('2d')
    const sel = '.masthead, .numeral'
    function alignOptical() {
        document.querySelectorAll(sel).forEach((el) => {
            el.style.marginLeft = '0px'
            const cs = getComputedStyle(el)
            let ch = (el.textContent || '').trim().charAt(0)
            if (!ch) return
            if (cs.textTransform === 'uppercase') ch = ch.toUpperCase()
            ctx.font = `${cs.fontStyle} ${cs.fontWeight} ${cs.fontSize} ${cs.fontFamily}`
            ctx.textAlign = 'left'
            const abl = ctx.measureText(ch).actualBoundingBoxLeft
            if (isFinite(abl)) el.style.marginLeft = `${abl.toFixed(2)}px`
        })
    }
    if (document.fonts?.ready) document.fonts.ready.then(alignOptical)
    alignOptical()
    let t
    function onResize() { clearTimeout(t); t = setTimeout(alignOptical, 120) }
    window.addEventListener('resize', onResize)

    cleanupResize = () => {
        btn?.removeEventListener('click', onClick)
        document.removeEventListener('keydown', onKey)
        window.removeEventListener('resize', onResize)
    }
})

onUnmounted(() => cleanupResize?.())
</script>

<template>
    <Head>
        <title>Grid Lab — Müller-Brockmann | Diginova</title>
        <meta name="robots" content="noindex, nofollow" />
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900|space-mono:400,700" rel="stylesheet" />
    </Head>

    <button id="gridToggle" class="toggle" aria-pressed="false">
        <span class="dot"></span><span class="lbl">Show grid</span> <span style="opacity:.5">— press G</span>
    </button>

    <!-- ═══════════ SPREAD 1 — MASTHEAD ═══════════ -->
    <section class="spread" style="padding-top: var(--pad); padding-bottom: var(--lh);">
        <div class="wrap">
            <div class="grid">
                <div class="band">
                    <p class="kicker" style="grid-column: 1 / 13;">Rapport — Édition 01 / Grid Lab</p>
                </div>
                <div class="band" style="margin-top: var(--bl);">
                    <h1 class="masthead" style="grid-column: 1 / 13;">DIGINOVA</h1>
                </div>
                <div class="band" style="margin-top: var(--lh);">
                    <p class="lede" style="grid-column: 1 / 8;">
                        Solutions digitales sur mesure pour les entreprises ambitieuses — Web, SaaS, DevOps, de la conception au déploiement.
                    </p>
                    <p class="mono-meta" style="grid-column: 9 / 13;">
                        Yaoundé, Cameroun<br>
                        diginova.cm<br>
                        Grille 12 / 8px
                    </p>
                </div>
            </div>
            <div class="guides" aria-hidden="true">
                <div class="cols"></div><div class="rows"></div>
                <div class="mline l"></div><div class="mline r"></div>
            </div>
        </div>
    </section>

    <!-- ═══════════ SPREAD 2 — STATS ═══════════ -->
    <section class="spread" style="background: var(--ink); color: var(--paper);">
        <div class="wrap">
            <div class="grid">
                <div class="band">
                    <p class="kicker kicker--light" style="grid-column: 1 / 13;">En chiffres</p>
                </div>
                <div class="band" style="margin-top: var(--bl);">
                    <div style="grid-column: 1 / 5;">
                        <div class="numeral numeral--light">08</div>
                        <p class="num-label num-label--light">ans d'expérience</p>
                    </div>
                    <div style="grid-column: 5 / 9;">
                        <div class="numeral numeral--light numeral--accent">19+</div>
                        <p class="num-label num-label--light">projets livrés</p>
                    </div>
                    <div style="grid-column: 9 / 13;">
                        <div class="numeral numeral--light">100%</div>
                        <p class="num-label num-label--light">en production</p>
                    </div>
                </div>
            </div>
            <div class="guides guides--dark" aria-hidden="true">
                <div class="cols"></div><div class="rows"></div>
                <div class="mline l"></div><div class="mline r"></div>
            </div>
        </div>
    </section>

    <!-- ═══════════ SPREAD 3 — SERVICES ═══════════ -->
    <section class="spread">
        <div class="wrap">
            <div class="grid">
                <div class="band">
                    <p class="kicker" style="grid-column: 1 / 13;">Nos expertises</p>
                </div>
                <div v-for="svc in services" :key="svc.n" class="band row" style="margin-top: var(--lh);">
                    <p class="mono-meta" style="grid-column: 1 / 2;">{{ svc.n }}</p>
                    <h2 class="h2b" style="grid-column: 2 / 6;">{{ svc.title }}</h2>
                    <p class="body-copy" style="grid-column: 6 / 12;">{{ svc.desc }}</p>
                </div>
            </div>
            <div class="guides" aria-hidden="true">
                <div class="cols"></div><div class="rows"></div>
                <div class="mline l"></div><div class="mline r"></div>
            </div>
        </div>
    </section>

    <!-- ═══════════ SPREAD 4 — ÉTUDES DE CAS ═══════════ -->
    <section class="spread">
        <div class="wrap">
            <div class="grid">
                <div class="band">
                    <p class="kicker" style="grid-column: 1 / 13;">Études de cas</p>
                </div>

                <div class="band" style="margin-top: var(--bl);">
                    <figure style="grid-column: 1 / 7; margin: 0;">
                        <img
                            src="https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=900&h=432&q=80&auto=format&fit=crop"
                            alt="BGFI LeClient"
                            loading="lazy"
                            style="height: 432px;"
                        />
                    </figure>
                    <div style="grid-column: 7 / 13;">
                        <p class="mono-meta">Banque · Fintech</p>
                        <h2 class="h2b" style="margin-top: var(--bl);">BGFI LeClient</h2>
                        <p class="body-copy" style="margin-top: var(--bl);">
                            Plateforme digitale BGFIBank Cameroun : ouverture de compte en ligne, gestion bancaire et services financiers pour particuliers et entreprises.
                        </p>
                        <p class="mono-meta" style="margin-top: var(--lh);">Laravel — Vue.js — API Banking</p>
                    </div>
                </div>

                <div class="band" style="margin-top: var(--lh);">
                    <div style="grid-column: 1 / 7;">
                        <p class="mono-meta">Administration · Recensement</p>
                        <h2 class="h2b" style="margin-top: var(--bl);">Census</h2>
                        <p class="body-copy" style="margin-top: var(--bl);">
                            Application nationale de recensement avec interface mobile offline-first et synchronisation différée.
                        </p>
                        <p class="mono-meta" style="margin-top: var(--lh);">Laravel — Flutter — MySQL</p>
                    </div>
                    <figure style="grid-column: 7 / 13; margin: 0;">
                        <img
                            src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=900&h=432&q=80&auto=format&fit=crop"
                            alt="Census"
                            loading="lazy"
                            style="height: 432px;"
                        />
                    </figure>
                </div>
            </div>
            <div class="guides" aria-hidden="true">
                <div class="cols"></div><div class="rows"></div>
                <div class="mline l"></div><div class="mline r"></div>
            </div>
        </div>
    </section>

    <!-- ═══════════ SPREAD 5 — COLOPHON ═══════════ -->
    <section class="spread" style="padding-bottom: var(--pad);">
        <div class="wrap">
            <div class="grid">
                <div class="band">
                    <p class="kicker" style="grid-column: 1 / 13;">Colophon</p>
                </div>
                <div class="band" style="margin-top: var(--bl);">
                    <div style="grid-column: 1 / 5;">
                        <p class="mono-meta">Diginova<br>Yaoundé, Cameroun<br>diginova.cm</p>
                    </div>
                    <div style="grid-column: 5 / 9;">
                        <p class="mono-meta">Typographie<br>Inter — display &amp; texte<br>Space Mono — folios &amp; annotations</p>
                    </div>
                    <div style="grid-column: 9 / 13;">
                        <p class="mono-meta">Grille<br>12 colonnes — gouttière 24px<br>Base 8px — marge 72px — maxw 1296px</p>
                    </div>
                </div>
                <div class="band" style="margin-top: var(--lh);">
                    <p class="mono-meta" style="grid-column: 1 / 13; opacity: .5;">
                        Page de test interne — système de grille Müller-Brockmann. Non indexée, non liée à la navigation publique du site.
                    </p>
                </div>
            </div>
            <div class="guides" aria-hidden="true">
                <div class="cols"></div><div class="rows"></div>
                <div class="mline l"></div><div class="mline r"></div>
            </div>
        </div>
    </section>
</template>

<style>
:root {
    --cols: 12;
    --bl: 8px;
    --lh: 24px;
    --gutter: 24px;
    --margin: 72px;
    --pad: 96px;
    --maxw: 1296px;

    --paper: #ffffff;
    --ink: #111315;
    --ink-soft: #5b6066;
    --accent: #e4002b;

    --g-col: rgba(228, 0, 43, 0.075);
    --g-edge: rgba(228, 0, 43, 0.4);
    --g-base: rgba(0, 150, 140, 0.34);
    --g-base-min: rgba(0, 150, 140, 0.12);
}

body {
    margin: 0;
    background: var(--paper);
    color: var(--ink);
    font-family: 'Inter', system-ui, sans-serif !important;
    font-size: 16px;
    line-height: var(--lh);
    -webkit-font-smoothing: antialiased;
}

.spread { position: relative; width: 100%; }
.wrap { position: relative; max-width: var(--maxw); margin: 0 auto; padding-left: var(--margin); padding-right: var(--margin); }
.grid { display: grid; grid-template-columns: repeat(var(--cols), 1fr); column-gap: var(--gutter); row-gap: var(--lh); }
.band { grid-column: 1 / -1; display: grid; grid-template-columns: subgrid; column-gap: var(--gutter); row-gap: var(--lh); align-items: start; }
@supports not (grid-template-columns: subgrid) {
    .band { grid-template-columns: repeat(var(--cols), 1fr); }
}

.guides { position: absolute; inset: 0; pointer-events: none; z-index: 60; opacity: 0; transition: opacity .26s ease; }
body.grid-on .guides { opacity: 1; }
.guides .cols { position: absolute; top: 0; bottom: 0; left: var(--margin); right: var(--margin); display: grid; grid-template-columns: repeat(var(--cols), 1fr); column-gap: var(--gutter); }
.guides .col { background: var(--g-col); box-shadow: inset 1px 0 0 var(--g-edge), inset -1px 0 0 var(--g-edge); position: relative; }
.guides .col span { position: absolute; top: 8px; left: 0; right: 0; text-align: center; font-family: 'Space Mono', monospace; font-size: 10px; line-height: 1; color: var(--accent); }
.guides .rows {
    position: absolute; left: var(--margin); right: var(--margin); top: 0; bottom: 0;
    background-image:
        repeating-linear-gradient(to bottom, var(--g-base) 0 1px, transparent 1px var(--lh)),
        repeating-linear-gradient(to bottom, var(--g-base-min) 0 1px, transparent 1px var(--bl));
}
.guides .mline { position: absolute; top: 0; bottom: 0; width: 1px; background: var(--g-edge); }
.guides .mline.l { left: var(--margin); }
.guides .mline.r { right: var(--margin); }
.guides--dark .col span { color: #fff; }
.guides--dark .mline { background: rgba(255, 255, 255, 0.4); }
.guides--dark .col { box-shadow: inset 1px 0 0 rgba(255, 255, 255, 0.4), inset -1px 0 0 rgba(255, 255, 255, 0.4); background: rgba(255, 255, 255, 0.06); }

.toggle {
    position: fixed; top: 18px; right: 18px; z-index: 200; display: flex; align-items: center; gap: 10px;
    background: var(--ink); color: #fff; border: none; cursor: pointer; font-family: 'Space Mono', monospace;
    font-size: 12px; letter-spacing: .14em; text-transform: uppercase; padding: 11px 14px;
}
.toggle .dot { width: 9px; height: 9px; border-radius: 50%; background: #555; display: inline-block; }
body.grid-on .toggle { background: var(--accent); }
body.grid-on .toggle .dot { background: #fff; }

.kicker {
    font-family: 'Space Mono', monospace; font-size: 12px; letter-spacing: .14em; text-transform: uppercase;
    color: var(--accent); margin: 0; line-height: var(--lh); border-bottom: 1px solid var(--ink); padding-bottom: calc(var(--bl) * 2);
}
.kicker--light { color: #ff5470; border-bottom-color: rgba(255,255,255,.25); }

.masthead {
    font-weight: 800; font-size: clamp(64px, 11vw, 168px); line-height: 168px; letter-spacing: -.02em;
    margin: 0; color: var(--ink);
}
.lede { font-size: 20px; line-height: var(--lh); color: var(--ink-soft); margin: 0; }
.mono-meta { font-family: 'Space Mono', monospace; font-size: 12px; line-height: var(--lh); color: var(--ink-soft); margin: 0; }

.numeral { font-weight: 800; font-size: 96px; line-height: 96px; margin: 0; color: var(--ink); }
.numeral--light { color: #fff; }
.numeral--accent { color: var(--accent); }
.num-label { font-family: 'Space Mono', monospace; font-size: 12px; text-transform: uppercase; letter-spacing: .1em; color: var(--ink-soft); margin-top: var(--bl); }
.num-label--light { color: rgba(255,255,255,.6); }

.row { padding-bottom: var(--lh); border-bottom: 1px solid rgba(17,19,21,.1); }
.h2b { font-weight: 700; font-size: 28px; line-height: var(--lh); margin: 0; }
.body-copy { font-size: 16px; line-height: var(--lh); color: var(--ink-soft); margin: 0; }

figure img { width: 100%; object-fit: cover; }
</style>
