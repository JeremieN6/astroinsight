<template>
  <div>
    <!-- Free report header -->
    <div class="glass-panel p-7 sm:p-10 mb-6">
      <div class="flex items-start justify-between gap-4 mb-8">
        <div>
          <p class="eyebrow mb-2">Votre thème natal</p>
          <h1 class="font-display text-2xl sm:text-3xl font-semibold text-white">
            {{ report.firstName }}, voici votre carte du ciel
          </h1>
          <p class="text-slate-400 text-sm mt-1">{{ report.birthDate }} · {{ report.city }}</p>
        </div>
        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full border border-violet-400/30 bg-violet-600/15 text-2xl">✦</div>
      </div>

      <!-- Big Three -->
      <div class="grid grid-cols-3 gap-3 mb-8">
        <div v-for="item in bigThree" :key="item.label" class="rounded-2xl border p-4 text-center" :class="item.borderClass" :style="`background: ${item.bg}`">
          <p class="text-2xl mb-1">{{ item.icon }}</p>
          <p class="text-xs text-slate-500 uppercase tracking-widest mb-1">{{ item.label }}</p>
          <p class="font-display font-semibold text-white text-sm sm:text-base">{{ item.value }}</p>
        </div>
      </div>

      <!-- Planets grid -->
      <h3 class="font-display text-sm font-semibold text-white mb-4 tracking-[0.15em] uppercase">10 Planètes</h3>
      <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 mb-8">
        <div
          v-for="p in report.planets"
          :key="p.planet"
          class="flex items-center gap-2 rounded-xl border border-white/10 bg-white/5 px-3 py-2 hover:bg-white/10 transition-colors"
        >
          <span class="text-base">{{ planetEmoji[p.planet] || '✦' }}</span>
          <div class="min-w-0">
            <p class="text-[10px] text-slate-500 uppercase tracking-widest truncate">{{ p.planet }}</p>
            <p class="text-xs font-medium text-slate-200 truncate">{{ p.sign }}</p>
          </div>
        </div>
      </div>

      <!-- Free AI summary -->
      <div class="rounded-2xl border border-violet-400/20 bg-violet-600/10 p-5">
        <p class="text-xs text-violet-300 uppercase tracking-[0.2em] mb-3">Résumé personnalisé</p>
        <p class="text-sm text-slate-300 leading-6">{{ report.summary }}</p>
      </div>
    </div>

    <!-- Premium upsell (if not premium) -->
    <div v-if="!isPremium" class="glass-panel p-7 sm:p-10 border-amber-400/25 relative overflow-hidden">
      <!-- Blur overlay on locked content preview -->
      <div class="mb-6 relative">
        <p class="text-xs text-amber-400 uppercase tracking-[0.2em] mb-3">Aperçu du rapport complet</p>
        <div class="relative overflow-hidden rounded-xl">
          <div class="blur-sm opacity-50 pointer-events-none select-none space-y-2 p-4 bg-white/5 rounded-xl">
            <div class="h-3 bg-slate-600 rounded w-full" />
            <div class="h-3 bg-slate-600 rounded w-4/5" />
            <div class="h-3 bg-slate-600 rounded w-full" />
            <div class="h-3 bg-slate-600 rounded w-3/4" />
            <div class="h-3 bg-slate-600 rounded w-full" />
            <div class="h-3 bg-slate-600 rounded w-5/6" />
          </div>
          <div class="absolute inset-0 flex items-center justify-center">
            <span class="flex items-center gap-2 rounded-full border border-amber-400/40 bg-amber-400/15 px-4 py-2 text-xs font-semibold text-amber-400">
              🔒 Rapport complet — Premium
            </span>
          </div>
        </div>
      </div>

      <div class="text-center">
        <h2 class="font-display text-xl sm:text-2xl font-semibold text-white mb-3">
          Débloquez votre analyse complète
        </h2>
        <p class="text-slate-400 text-sm mb-6 max-w-md mx-auto">
          Interprétation de chaque planète, aspects majeurs, guidance amoureuse et professionnelle. Rapport PDF inclus.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
          <a :href="stripeLink" class="cta-button" target="_blank" rel="noopener">
            Débloquer pour 9,99€
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </a>
          <p class="text-xs text-slate-600">Paiement sécurisé · Accès immédiat</p>
        </div>
      </div>
    </div>

    <!-- Full premium report (if unlocked) -->
    <div v-else class="glass-panel p-7 sm:p-10">
      <p class="eyebrow mb-4">Rapport complet</p>
      <div class="prose prose-invert prose-sm max-w-none" v-html="report.fullAnalysis" />
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  report: Record<string, unknown>
  isPremium: boolean
}>()

const stripeLink = 'https://buy.stripe.com/28E8wQ5Pm7uT36qfF9ePi0p'

const planetEmoji: Record<string, string> = {
  Soleil: '☀', Lune: '☽', Mercure: '☿', Vénus: '♀', Mars: '♂',
  Jupiter: '♃', Saturne: '♄', Uranus: '♅', Neptune: '♆', Pluton: '♇',
}

const bigThree = computed(() => {
  const r = props.report as Record<string, string>
  return [
    { label: 'Soleil', icon: '☀', value: r.sunSign || '—', borderClass: 'border-amber-400/20', bg: 'rgba(245,158,11,0.06)' },
    { label: 'Lune', icon: '☽', value: r.moonSign || '—', borderClass: 'border-violet-400/20', bg: 'rgba(139,92,246,0.06)' },
    { label: 'Ascendant', icon: '↑', value: r.ascendant || '—', borderClass: 'border-sky-400/20', bg: 'rgba(56,189,248,0.06)' },
  ]
})
</script>
