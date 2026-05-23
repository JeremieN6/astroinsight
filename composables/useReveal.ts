/**
 * useReveal — IntersectionObserver composable
 * Adds `.is-visible` to `.reveal-on-scroll` elements when they enter the viewport
 */
export function useReveal() {
  if (import.meta.server) return

  onMounted(() => {
    const elements = document.querySelectorAll<HTMLElement>('.reveal-on-scroll')

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-visible')
            observer.unobserve(entry.target)
          }
        })
      },
      {
        threshold: 0.08,
        rootMargin: '0px 0px -48px 0px',
      },
    )

    elements.forEach((el) => observer.observe(el))

    onUnmounted(() => observer.disconnect())
  })
}
