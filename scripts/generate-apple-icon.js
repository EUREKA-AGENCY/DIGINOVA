import puppeteer from 'puppeteer'
import fs from 'fs'
import path from 'path'
import { fileURLToPath } from 'url'

const __dirname = path.dirname(fileURLToPath(import.meta.url))
const pub = path.join(__dirname, '../public')

const html = `<!DOCTYPE html>
<html>
<head><meta charset="utf-8">
<style>* { margin:0; padding:0; } body { width:180px; height:180px; background:#070E24; }</style>
</head>
<body>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 180 180" width="180" height="180">
  <rect width="180" height="180" rx="40" fill="#070E24"/>
  <path fill="#00D8E8"
    d="M45 45 h31 C104 45 124 65 124 90 C124 115 104 135 76 135 H45 Z
       M62 65 v50 h13 C91 115 104 104 104 90 C104 76 91 65 75 65 Z"/>
</svg>
</body>
</html>`

const browser = await puppeteer.launch({ headless: 'new', args: ['--no-sandbox'] })
const page = await browser.newPage()
await page.setViewport({ width: 180, height: 180, deviceScaleFactor: 2 })
await page.setContent(html, { waitUntil: 'networkidle0' })
const png = await page.screenshot({ type: 'png', clip: { x: 0, y: 0, width: 180, height: 180 } })
await browser.close()

fs.writeFileSync(path.join(pub, 'apple-touch-icon.png'), png)
console.log('✓ apple-touch-icon.png généré (180×180)')
