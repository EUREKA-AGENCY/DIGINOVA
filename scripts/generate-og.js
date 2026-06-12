import puppeteer from 'puppeteer'
import path from 'path'
import { fileURLToPath } from 'url'

const __dirname = path.dirname(fileURLToPath(import.meta.url))

const html = `<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    width: 1200px;
    height: 630px;
    overflow: hidden;
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(150deg, #050D2A 0%, #0B1437 55%, #0d2060 100%);
    color: white;
    position: relative;
  }

  /* Dot grid */
  .grid {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, rgba(255,255,255,0.07) 1px, transparent 1px);
    background-size: 36px 36px;
  }

  /* Cyan glow top-right */
  .glow-tr {
    position: absolute;
    top: -120px;
    right: -100px;
    width: 600px;
    height: 500px;
    border-radius: 50%;
    background: radial-gradient(ellipse at center, rgba(0,216,232,0.18) 0%, transparent 65%);
  }

  /* Cyan glow bottom-left */
  .glow-bl {
    position: absolute;
    bottom: -100px;
    left: -80px;
    width: 400px;
    height: 400px;
    border-radius: 50%;
    background: radial-gradient(ellipse at center, rgba(27,45,140,0.5) 0%, transparent 65%);
  }

  /* Left accent bar */
  .accent-bar {
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 5px;
    background: linear-gradient(180deg, #00D8E8 0%, #4EB8FF 50%, transparent 100%);
  }

  /* Content */
  .content {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 0 80px;
  }

  /* Badge */
  .badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255,255,255,0.05);
    border: 1px solid rgba(0,216,232,0.3);
    border-radius: 100px;
    padding: 6px 18px;
    margin-bottom: 32px;
    width: fit-content;
  }
  .badge-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #00D8E8;
    flex-shrink: 0;
  }
  .badge-text {
    font-size: 13px;
    font-weight: 600;
    color: #00D8E8;
    letter-spacing: 0.12em;
    text-transform: uppercase;
  }

  /* Logo row */
  .logo-row {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 28px;
  }
  .logo-mark {
    width: 52px;
    height: 52px;
    border-radius: 14px;
    background: linear-gradient(135deg, #00D8E8 0%, #1B2D8C 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    font-weight: 800;
    color: white;
    letter-spacing: -1px;
  }
  .logo-name {
    font-size: 36px;
    font-weight: 800;
    color: white;
    letter-spacing: -0.5px;
  }

  /* Headline */
  h1 {
    font-size: 58px;
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -1px;
    margin-bottom: 20px;
  }
  h1 .cyan {
    background: linear-gradient(90deg, #00D8E8 0%, #4EB8FF 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  /* Tagline */
  .tagline {
    font-size: 18px;
    color: rgba(255,255,255,0.55);
    margin-bottom: 40px;
    font-weight: 400;
    line-height: 1.5;
  }

  /* Stats */
  .stats {
    display: flex;
    gap: 32px;
  }
  .stat {
    display: flex;
    flex-direction: column;
  }
  .stat-value {
    font-size: 28px;
    font-weight: 800;
    color: #00D8E8;
    line-height: 1;
  }
  .stat-label {
    font-size: 12px;
    color: rgba(255,255,255,0.4);
    margin-top: 3px;
    font-weight: 400;
  }

  /* Divider */
  .stat-sep {
    width: 1px;
    height: 40px;
    background: rgba(255,255,255,0.1);
    align-self: center;
  }

  /* URL chip bottom right */
  .url-chip {
    position: absolute;
    bottom: 40px;
    right: 80px;
    background: rgba(0,216,232,0.1);
    border: 1px solid rgba(0,216,232,0.25);
    border-radius: 8px;
    padding: 8px 18px;
    font-size: 15px;
    font-weight: 600;
    color: rgba(0,216,232,0.8);
    letter-spacing: 0.04em;
  }

  /* Right side decorative code block */
  .code-block {
    position: absolute;
    right: 70px;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 16px;
    padding: 28px 32px;
    width: 300px;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    line-height: 1.8;
  }
  .code-line { color: rgba(255,255,255,0.3); }
  .code-key { color: #4EB8FF; }
  .code-val { color: #00D8E8; }
  .code-str { color: rgba(255,255,255,0.6); }
  .code-num { color: #FFB347; }
  .code-bool { color: #A78BFA; }
</style>
</head>
<body>
  <div class="grid"></div>
  <div class="glow-tr"></div>
  <div class="glow-bl"></div>
  <div class="accent-bar"></div>

  <div class="content">
    <div class="badge">
      <div class="badge-dot"></div>
      <span class="badge-text">Yaoundé · Cameroun</span>
    </div>

    <div class="logo-row">
      <div class="logo-mark">Dn</div>
      <span class="logo-name">Diginova</span>
    </div>

    <h1>
      Solutions digitales<br>
      <span class="cyan">sur mesure</span>
    </h1>

    <p class="tagline">
      Développement web · Applications SaaS · DevOps<br>
      De la conception au déploiement.
    </p>

    <div class="stats">
      <div class="stat">
        <span class="stat-value">8 ans</span>
        <span class="stat-label">d'expérience</span>
      </div>
      <div class="stat-sep"></div>
      <div class="stat">
        <span class="stat-value">19+</span>
        <span class="stat-label">projets livrés</span>
      </div>
      <div class="stat-sep"></div>
      <div class="stat">
        <span class="stat-value">100%</span>
        <span class="stat-label">en production</span>
      </div>
    </div>
  </div>

  <!-- Decorative code block -->
  <div class="code-block">
    <div class="code-line">// <span style="color:rgba(255,255,255,0.2)">diginova.cm</span></div>
    <div class="code-line"><span class="code-key">const</span> <span class="code-str">project</span> = {</div>
    <div class="code-line">&nbsp;&nbsp;<span class="code-key">stack</span>: <span class="code-val">'Laravel + Vue'</span>,</div>
    <div class="code-line">&nbsp;&nbsp;<span class="code-key">deploy</span>: <span class="code-bool">true</span>,</div>
    <div class="code-line">&nbsp;&nbsp;<span class="code-key">quality</span>: <span class="code-num">100</span>,</div>
    <div class="code-line">&nbsp;&nbsp;<span class="code-key">client</span>: <span class="code-val">'satisfied'</span>,</div>
    <div class="code-line">}</div>
    <br>
    <div class="code-line" style="color:#00D8E8;">✓ <span style="color:rgba(255,255,255,0.35)">built in 10.41s</span></div>
  </div>

  <div class="url-chip">diginova.cm</div>
</body>
</html>`

const browser = await puppeteer.launch({
    headless: 'new',
    args: ['--no-sandbox', '--disable-setuid-sandbox'],
})
const page = await browser.newPage()
await page.setViewport({ width: 1200, height: 630, deviceScaleFactor: 2 })
await page.setContent(html, { waitUntil: 'networkidle0' })

// Wait for font to load
await page.evaluate(() => document.fonts.ready)
await new Promise(r => setTimeout(r, 1500))

const outPath = path.join(__dirname, '../public/og-image.png')
await page.screenshot({ path: outPath, type: 'png' })
await browser.close()

console.log(`✓ OG image générée → public/og-image.png`)
