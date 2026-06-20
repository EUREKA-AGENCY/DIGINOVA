import { ref, watch, onMounted, onUnmounted } from 'vue'

export function prefersReducedMotion() {
    return typeof window !== 'undefined' && window.matchMedia('(prefers-reduced-motion: reduce)').matches
}

/**
 * Reveals once when the target enters the viewport. Reused across Hero stats,
 * services, portfolio and process sections instead of duplicating an
 * IntersectionObserver per section.
 */
export function useReveal(threshold = 0.2) {
    const target = ref(null)
    const visible = ref(false)
    let observer

    onMounted(() => {
        if (prefersReducedMotion()) {
            visible.value = true
            return
        }
        observer = new IntersectionObserver(
            ([entry]) => {
                if (entry.isIntersecting) {
                    visible.value = true
                    observer?.disconnect()
                }
            },
            { threshold }
        )
        if (target.value) observer.observe(target.value)
    })

    onUnmounted(() => observer?.disconnect())

    return { target, visible }
}

/**
 * Animates a numeric stat ("19+", "100%", "8+") counting up from 0 once visible.
 * Non-numeric values (e.g. "24/7") are returned as-is, unanimated.
 */
export function useCountUp(rawValue, visible, duration = 1200) {
    const display = ref(rawValue)
    const match = /^(\d+)(.*)$/.exec(rawValue)

    if (!match || prefersReducedMotion()) {
        return display
    }

    const target = parseInt(match[1], 10)
    const suffix = match[2]
    let started = false

    const run = () => {
        if (started) return
        started = true
        const start = performance.now()
        const tick = (now) => {
            const progress = Math.min((now - start) / duration, 1)
            const eased = 1 - Math.pow(1 - progress, 3)
            display.value = `${Math.round(eased * target)}${suffix}`
            if (progress < 1) requestAnimationFrame(tick)
        }
        requestAnimationFrame(tick)
    }

    watch(visible, (v) => { if (v) run() }, { immediate: true })

    return display
}
