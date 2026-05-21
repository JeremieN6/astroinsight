<template>
  <header class="sticky top-0 z-50 border-b border-white/10 bg-[rgba(10,10,26,0.85)] backdrop-blur-2xl">
    <div class="section-shell flex items-center justify-between py-0 h-[72px]">
      <!-- Logo -->
      <NuxtLink to="/" class="flex items-center gap-3 shrink-0 group">
        <div class="relative h-9 w-9">
          <div class="absolute inset-0 rounded-full bg-gradient-to-br from-violet-500 to-amber-400 opacity-25 blur-sm group-hover:opacity-40 transition-opacity duration-300" />
          <div class="relative flex h-9 w-9 items-center justify-center rounded-full border border-violet-400/30 bg-violet-600/15">
            <span class="text-base select-none">✦</span>
          </div>
        </div>
        <span class="font-display text-base font-semibold tracking-[0.15em] text-white">AstroInsights</span>
      </NuxtLink>

      <!-- Desktop nav -->
      <nav class="hidden md:flex items-center gap-8">
        <NuxtLink
          v-for="link in navLinks"
          :key="link.href"
          :to="link.href"
          class="relative pb-0.5 text-sm font-medium text-slate-400 tracking-[0.18em] uppercase transition-colors duration-200 hover:text-white after:absolute after:bottom-0 after:left-0 after:h-px after:w-0 after:bg-gold after:transition-all after:duration-300 hover:after:w-full"
        >
          {{ link.label }}
        </NuxtLink>
      </nav>

      <!-- CTA + mobile button -->
      <div class="flex items-center gap-3">
        <NuxtLink to="/#rapport" class="hidden sm:inline-flex cta-button text-xs px-5 py-2.5">
          Mon thème natal
          <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
        </NuxtLink>

        <!-- Mobile toggle -->
        <button
          class="flex h-9 w-9 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-slate-300 transition-colors hover:bg-white/10 md:hidden"
          aria-label="Menu"
          @click="menuOpen = !menuOpen"
        >
          <svg v-if="!menuOpen" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
          <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile menu -->
    <Transition name="slide-down">
      <div v-if="menuOpen" class="border-t border-white/10 bg-[rgba(10,10,26,0.95)] px-6 py-4 md:hidden">
        <nav class="flex flex-col gap-4">
          <NuxtLink
            v-for="link in navLinks"
            :key="link.href"
            :to="link.href"
            class="text-sm font-medium text-slate-300 tracking-[0.18em] uppercase hover:text-white transition-colors"
            @click="menuOpen = false"
          >
            {{ link.label }}
          </NuxtLink>
          <NuxtLink to="/#rapport" class="cta-button mt-2 justify-center" @click="menuOpen = false">
            Mon thème natal
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
          </NuxtLink>
        </nav>
      </div>
    </Transition>
  </header>
</template>

<script setup lang="ts">
const menuOpen = ref(false)

const navLinks = [
  { href: '/#benefits', label: 'Bénéfices' },
  { href: '/#processus', label: 'Comment ça marche' },
  { href: '/#pricing', label: 'Tarifs' },
  { href: '/#faq', label: 'FAQ' },
]
</script>

<style scoped>
.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.3s ease;
}
.slide-down-enter-from,
.slide-down-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
