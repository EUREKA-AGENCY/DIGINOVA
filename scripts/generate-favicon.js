import puppeteer from 'puppeteer'
import pngToIco from 'png-to-ico'
import fs from 'fs'
import path from 'path'
import { fileURLToPath } from 'url'

const __dirname = path.dirname(fileURLToPath(import.meta.url))
const pub = path.join(__dirname, '../public')

const svgContent = fs.readFileSync(path.join(pub, 'favicon.svg'), 'utf8')
const dataUrl = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svgContent)

const html = `<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  * { margin: 0; padding: 0; }
  body { width: 256px; height: 256px; background: transparent; }
  img { width: 256px; height: 256px; }
</style>
</head>
<body>
  <img src="${dataUrl}" />
</body>
</html>`

const browser = await puppeteer.launch({ headless: 'new', args: ['--no-sandbox'] })
const page = await browser.newPage()
await page.setViewport({ width: 256, height: 256, deviceScaleFactor: 1 })
await page.setContent(html, { waitUntil: 'networkidle0' })

// Generate sizes: 256, 64, 32, 16
const pngs = []
for (const size of [256, 64, 32, 16]) {
    await page.setViewport({ width: size, height: size, deviceScaleFactor: 1 })
    const png = await page.screenshot({ type: 'png', omitBackground: true, clip: { x: 0, y: 0, width: size, height: size } })
    pngs.push(Buffer.from(png))
    fs.writeFileSync(path.join(pub, `favicon-${size}.png`), png)
}
await browser.close()

// Build ICO from PNGs (16, 32, 64, 256)
const ico = await pngToIco(pngs.reverse())
fs.writeFileSync(path.join(pub, 'favicon.ico'), ico)

// Clean temp PNGs
for (const size of [256, 64, 32, 16]) {
    fs.unlinkSync(path.join(pub, `favicon-${size}.png`))
}

console.log('✓ favicon.ico généré avec 4 tailles (16, 32, 64, 256)')
