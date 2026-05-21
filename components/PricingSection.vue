<template>
  <section id="pricing" class="section-transition scroll-mt-24 py-20 sm:py-24">
    <div class="section-shell">
      <div class="mx-auto mb-16 max-w-2xl text-center">
        <p class="eyebrow reveal-on-scroll mb-4">Tarifs</p>
        <h2 class="section-title reveal-on-scroll">
          Commencez gratuitement,<br/><span class="text-amber-400">allez plus loin si vous voulez</span>
        </h2>
      </div>

      <div class="mx-auto grid max-w-3xl grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Free plan -->
        <div class="reveal-on-scroll glass-panel p-8 transition-all duration-300 hover:-translate-y-2 hover:border-white/20 hover:shadow-[0_32px_80px_rgba(124,58,237,0.25)]">
          <div class="mb-6">
            <p class="eyebrow mb-2">Aperçu gratuit</p>
            <div class="flex items-end gap-2 mb-4">
              <span class="font-display text-5xl font-bold text-white">0€</span>
            </div>
            <p class="text-sm text-slate-400">Pour découvrir votre thème natal</p>
          </div>

          <ul class="mb-8 space-y-3">
            <li v-for="feat in freeFeatures" :key="feat" class="flex items-start gap-3 text-sm text-slate-300">
              <svg class="mt-0.5 h-4 w-4 shrink-0 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
              {{ feat }}
            </li>
          </ul>

          <NuxtLink to="/rapport" class="secondary-button w-full justify-center">
            Obtenir mon aperçu
          </NuxtLink>
        </div>

        <!-- Premium plan -->
        <div class="reveal-on-scroll relative glass-panel p-8 border-amber-400/25 transition-all duration-300 hover:-translate-y-2 hover:border-amber-400/60 hover:shadow-[0_0_80px_rgba(124,58,237,0.3)]">
          <!-- Popular badge -->
          <div class="absolute -top-4 left-1/2 -translate-x-1/2">
            <span class="flex items-center gap-1.5 rounded-full border border-amber-400/30 bg-amber-400/15 px-4 py-1.5 text-xs font-semibold text-amber-400 tracking-[0.2em] uppercase">
              ✦ Plus populaire
            </span>
          </div>

          <div class="mb-6 mt-2">
            <p class="eyebrow mb-2">Rapport complet</p>
            <div class="flex items-end gap-2 mb-1">
              <span class="font-display text-5xl font-bold text-white">9,99€</span>
            </div>
            <p class="text-sm text-slate-400">Paiement unique · Accès à vie</p>
          </div>

          <ul class="mb-8 space-y-3">
            <li v-for="feat in premiumFeatures" :key="feat" class="flex items-start gap-3 text-sm text-slate-300">
              <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
              {{ feat }}
            </li>
          </ul>

          <a :href="stripeLink" class="cta-button w-full justify-center" target="_blank" rel="noopener">
            Obtenir le rapport complet
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </a>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const stripeLink = 'https://buy.stripe.com/28E8wQ5Pm7uT36qfF9ePi0p'

const freeFeatures = [
  'Signe Solaire, Lunaire et Ascendant',
  'Positions des 10 planètes',
  'Résumé de personnalité (500 mots)',
  'Points forts et défis',
]

const premiumFeatures = [
  'Tout l\'aperçu gratuit',
  'Analyse approfondie de chaque planète',
  'Interprétation des aspects majeurs',
  'Guidance vie amoureuse & carrière',
  'Rapport PDF téléchargeable',
  'Accès à vie · Mises à jour incluses',
]
</script>
