<template>
  <header
    class="sticky top-0 z-50 w-full transition-all duration-500"
    :class="isScrolled ? 'border-b border-white/10 bg-[rgba(10,10,26,0.88)] backdrop-blur-2xl' : 'bg-transparent'"
  >
    <nav
      class="mx-auto flex h-[72px] max-w-7xl items-center justify-between gap-4 px-6 transition-all duration-500 lg:px-10"
      aria-label="Navigation principale"
    >
      <NuxtLink to="/" class="group inline-flex shrink-0 items-center gap-3">
        <span class="relative flex h-10 w-10 items-center justify-center">
          <svg viewBox="0 0 40 40" fill="none" class="absolute inset-0 h-full w-full opacity-30" aria-hidden="true">
            <circle cx="20" cy="20" r="18" stroke="#FBBF24" stroke-width="0.5" />
            <circle cx="20" cy="20" r="12" stroke="#A855F7" stroke-width="0.5" />
          </svg>
          <svg viewBox="0 0 24 24" class="relative h-5 w-5" fill="none" aria-hidden="true">
            <polygon
              points="12,2 14.5,9.5 22,9.5 16,14 18.5,21 12,17 5.5,21 8,14 2,9.5 9.5,9.5"
              fill="url(#star-nav)"
              opacity="0.95"
            />
            <defs>
              <linearGradient id="star-nav" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stop-color="#FBBF24" />
                <stop offset="100%" stop-color="#A855F7" />
              </linearGradient>
            </defs>
          </svg>
        </span>
        <span class="font-display text-lg tracking-[0.2em] text-white transition-colors duration-300 group-hover:text-amber-400">
          Stellara
        </span>
      </NuxtLink>

      <div class="hidden items-center gap-8 md:flex">
        <template v-for="link in navLinks" :key="link.label">
          <NuxtLink
            v-if="!link.disabled"
            :to="link.href"
            class="relative pb-0.5 text-sm text-slate-300 transition-colors duration-200 hover:text-white after:absolute after:bottom-0 after:left-0 after:h-px after:w-0 after:bg-amber-400 after:transition-all after:duration-300 hover:after:w-full"
          >
            {{ link.label }}
          </NuxtLink>
          <span
            v-else
            class="inline-flex cursor-not-allowed items-center gap-2 pb-0.5 text-sm text-slate-500"
            aria-disabled="true"
            :title="link.disabledLabel"
          >
            <span>{{ link.label }}</span>
            <span class="rounded-full border border-white/10 bg-white/5 px-2 py-0.5 text-[10px] uppercase tracking-[0.18em] text-slate-400">
              Bientot
            </span>
          </span>
        </template>
      </div>

      <div class="flex items-center gap-3">
        <NuxtLink
          to="/account"
          class="hidden items-center gap-2 rounded-full border px-3.5 py-2 text-xs uppercase tracking-[0.14em] transition-colors sm:inline-flex"
          :class="isAuthenticated
            ? 'border-emerald-400/35 bg-emerald-400/10 text-emerald-200 hover:bg-emerald-400/15'
            : 'border-white/10 bg-white/5 text-slate-300 hover:text-white'"
        >
          <span class="h-1.5 w-1.5 rounded-full" :class="isAuthenticated ? 'bg-emerald-300' : 'bg-slate-400'" />
          {{ isAuthenticated ? accountLabel : 'Mon compte' }}
        </NuxtLink>

        <NuxtLink
          to="/#pricing"
          class="hidden items-center gap-2 rounded-full bg-[linear-gradient(135deg,#7c3aed,#a855f7_50%,#f59e0b)] px-5 py-2.5 text-sm font-semibold text-white shadow-[0_0_20px_rgba(124,58,237,0.4)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_0_30px_rgba(124,58,237,0.6)] sm:inline-flex"
        >
          <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <polygon
              points="12,2 15,9 22,9 16.5,13.5 18.5,21 12,17 5.5,21 7.5,13.5 2,9 9,9"
              fill="currentColor"
              opacity="0.9"
            />
          </svg>
          Obtenir mon rapport
        </NuxtLink>

        <button
          class="flex h-10 w-10 items-center justify-center rounded-full border border-white/10 bg-white/5 text-white md:hidden"
          aria-label="Menu"
          @click="menuOpen = !menuOpen"
        >
          <svg v-if="!menuOpen" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
          <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </nav>

    <Transition name="slide-down">
      <div v-if="menuOpen" class="border-t border-white/10 bg-[rgba(10,10,26,0.95)] px-6 py-4 md:hidden">
        <nav class="flex flex-col gap-4">
          <template v-for="link in navLinks" :key="`mobile-${link.label}`">
            <NuxtLink
              v-if="!link.disabled"
              :to="link.href"
              class="text-sm text-slate-300 transition-colors hover:text-white"
              @click="menuOpen = false"
            >
              {{ link.label }}
            </NuxtLink>
            <span
              v-else
              class="inline-flex items-center gap-2 text-sm text-slate-500"
              aria-disabled="true"
            >
              <span>{{ link.label }}</span>
              <span class="rounded-full border border-white/10 bg-white/5 px-2 py-0.5 text-[10px] uppercase tracking-[0.18em] text-slate-400">
                Bientot
              </span>
            </span>
          </template>
          <NuxtLink
            to="/account"
            class="inline-flex items-center justify-center rounded-full border border-white/15 bg-white/5 px-5 py-3 text-sm font-semibold text-white"
            @click="menuOpen = false"
          >
            {{ isAuthenticated ? `Mon compte (${accountLabel})` : 'Mon compte' }}
          </NuxtLink>
          <NuxtLink
            to="/#pricing"
            class="inline-flex justify-center rounded-full bg-[linear-gradient(135deg,#7c3aed,#a855f7_50%,#f59e0b)] px-5 py-3 text-sm font-semibold text-white shadow-[0_0_20px_rgba(124,58,237,0.4)] transition-all duration-300"
            @click="menuOpen = false"
          >
            Obtenir mon rapport
          </NuxtLink>
        </nav>
      </div>
    </Transition>
  </header>
</template>

<script setup lang="ts">
import { onMounted, onUnmounted } from 'vue'

const isScrolled = ref(false)
const menuOpen = ref(false)
const profileFirstName = ref('')
const reportStore = useReportStore()

const isAuthenticated = computed(() => Boolean(reportStore.userEmail))
const accountLabel = computed(() => {
  if (profileFirstName.value) return profileFirstName.value
  const email = reportStore.userEmail || ''
  if (!email.includes('@')) return 'Mon compte'
  return email.split('@')[0]
})

const navLinks = [
  { href: '/horoscope-du-jour', label: 'Horoscope du jour' },
  { href: '/#how-it-works', label: 'Comment ca marche' },
  { href: '/#pricing', label: 'Tarifs' },
  { href: '#', label: 'Blog', disabled: true, disabledLabel: 'Le blog arrive bientot' },
]

function updateScrolledState() {
  isScrolled.value = window.scrollY > 8
}

async function hydrateProfileName() {
  const email = reportStore.userEmail
  if (!email) {
    profileFirstName.value = ''
    return
  }

  try {
    const response = await ($fetch('/api/user/profile', {
      query: { email },
    }) as Promise<{ user: { firstName: string | null } | null }>)
    profileFirstName.value = response?.user?.firstName || ''
  } catch {
    profileFirstName.value = ''
  }
}

onMounted(() => {
  reportStore.initFromStorage()
  updateScrolledState()
  hydrateProfileName()
  window.addEventListener('scroll', updateScrolledState, { passive: true })
})

watch(
  () => reportStore.userEmail,
  () => {
    hydrateProfileName()
  },
)

onUnmounted(() => {
  window.removeEventListener('scroll', updateScrolledState)
  menuOpen.value = false
})
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
