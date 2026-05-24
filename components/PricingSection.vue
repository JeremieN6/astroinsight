<template>
  <section id="pricing" class="section-transition scroll-mt-24 py-20 sm:py-24">
    <div class="section-shell">
    <div class="mx-auto mb-16 max-w-3xl text-center">
      <p class="eyebrow reveal-on-scroll mb-4">Tarifs</p>
      <h2 class="section-title reveal-on-scroll mx-auto max-w-2xl">Choisissez votre destinée</h2>
      <p class="reveal-on-scroll mx-auto mt-6 max-w-xl text-lg text-slate-300">
        Commencez gratuitement, débloquez votre rapport complet pour aller plus loin.
      </p>
    </div>

    <div class="mx-auto grid max-w-6xl gap-6 md:grid-cols-2 lg:grid-cols-3">
      <div
        class="reveal-on-scroll glass-panel relative flex flex-col overflow-hidden px-7 py-8 transition-all duration-300 hover:-translate-y-1 hover:border-white/20"
      >
        <div class="mb-6">
          <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Aperçu Gratuit</p>
          <div class="mt-3 flex items-end gap-2">
            <span class="font-display text-5xl text-white">0€</span>
          </div>
          <p class="mt-2 text-sm text-slate-400">Découvrez les bases de votre thème natal</p>
        </div>

        <ul class="flex-1 space-y-3">
          <li v-for="feat in freeFeatures" :key="feat" class="flex items-start gap-3 text-sm text-slate-300">
            <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"
              />
            </svg>
            {{ feat }}
          </li>

          <li v-for="feat in lockedFreeFeatures" :key="feat" class="flex items-start gap-3 text-sm text-slate-600">
            <svg class="mt-0.5 h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path
                fill-rule="evenodd"
                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2V7a3 3 0 00-6 0v2h6z"
                clip-rule="evenodd"
              />
            </svg>
            {{ feat }}
          </li>
        </ul>

        <div class="mt-8">
          <NuxtLink
            to="/rapport"
            class="block w-full rounded-full border border-white/15 bg-white/5 py-3.5 text-center text-sm font-bold uppercase tracking-[0.18em] text-white transition-all duration-300 hover:-translate-y-0.5 hover:bg-white/10"
          >
            Commencer gratuitement
          </NuxtLink>
        </div>
      </div>

      <div
        class="reveal-on-scroll glass-panel relative flex flex-col overflow-hidden border border-violet-500/40 px-7 py-8 shadow-[0_0_60px_rgba(124,58,237,0.2)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_0_80px_rgba(124,58,237,0.3)]"
      >
        <div
          class="absolute -right-8 top-5 rotate-45 bg-gradient-to-r from-violet-600 to-fuchsia-500 px-10 py-1.5 text-center text-xs font-bold uppercase tracking-[0.2em] text-white"
        >
          Populaire
        </div>

        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-violet-400/60 to-transparent" />

        <div class="mb-6">
          <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Rapport Complet Premium</p>
          <div class="mt-3 flex items-end gap-2">
            <span class="font-display text-5xl text-white">9,99€</span>
            <span class="mb-1.5 text-sm text-slate-400">paiement unique</span>
          </div>
          <p class="mt-2 text-sm text-slate-400">Votre thème natal complet en 30+ pages</p>
        </div>

        <ul class="flex-1 space-y-3">
          <li v-for="feat in premiumFeatures" :key="feat" class="flex items-start gap-3 text-sm text-slate-300">
            <svg class="mt-0.5 h-4 w-4 shrink-0 text-amber-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"
              />
            </svg>
            {{ feat }}
          </li>
        </ul>

        <div class="mt-8">
          <a
            :href="oneShotStripeLink"
            target="_blank"
            rel="noopener"
            class="block w-full rounded-full bg-[linear-gradient(135deg,#7c3aed_0%,#a855f7_50%,#d97706_100%)] py-3.5 text-center text-sm font-bold uppercase tracking-[0.18em] text-white shadow-[0_0_30px_rgba(124,58,237,0.5)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_0_50px_rgba(124,58,237,0.7)]"
          >
            Obtenir mon rapport complet
          </a>
        </div>

        <p class="mt-4 text-center text-xs text-slate-500">🛡️ Garantie satisfait ou remboursé 7 jours</p>
      </div>

      <div
        class="reveal-on-scroll glass-panel relative flex flex-col overflow-hidden border border-cyan-400/30 px-7 py-8 shadow-[0_0_50px_rgba(34,211,238,0.16)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_0_70px_rgba(34,211,238,0.24)]"
      >
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-cyan-300/70 to-transparent" />

        <div class="mb-6">
          <p class="text-xs uppercase tracking-[0.35em] text-slate-400">Abonnement Premium</p>
          <div class="mt-3 flex items-end gap-2">
            <span class="font-display text-5xl text-white">19€</span>
            <span class="mb-1.5 text-sm text-slate-400">/mois</span>
          </div>
          <p class="mt-2 text-sm text-slate-400">Votre guidance quotidienne complete, evolutive et personnalisee</p>
        </div>

        <ul class="flex-1 space-y-3">
          <li v-for="feat in monthlyFeatures" :key="feat" class="flex items-start gap-3 text-sm text-slate-300">
            <svg class="mt-0.5 h-4 w-4 shrink-0 text-cyan-300" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path
                fill-rule="evenodd"
                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                clip-rule="evenodd"
              />
            </svg>
            {{ feat }}
          </li>
        </ul>

        <div class="mt-8">
          <a
            :href="monthlyStripeLink"
            target="_blank"
            rel="noopener"
            class="block w-full rounded-full border border-cyan-200/60 bg-[linear-gradient(135deg,#0891b2_0%,#06b6d4_45%,#67e8f9_100%)] py-3.5 text-center text-sm font-bold uppercase tracking-[0.18em] text-slate-950 shadow-[0_0_28px_rgba(34,211,238,0.35)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_0_45px_rgba(34,211,238,0.45)]"
          >
            Passer en premium mensuel
          </a>
        </div>

        <p class="mt-4 text-center text-xs text-slate-500">Resiliable a tout moment</p>
      </div>
    </div>

    <div class="reveal-on-scroll mt-12 flex flex-wrap items-center justify-center gap-8 text-sm text-slate-500">
      <div v-for="badge in trustBadges" :key="badge.label" class="flex items-center gap-2">
        <svg class="h-5 w-5 text-amber-400/60" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path :d="badge.path" :fill-rule="badge.fillRule" :clip-rule="badge.clipRule" />
        </svg>
        {{ badge.label }}
      </div>
    </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const config = useRuntimeConfig()

const oneShotStripeLink = config.public.stripeOneShotLink || 'https://buy.stripe.com/28E8wQ5Pm7uT36qfF9ePi0p'
const monthlyStripeLink = config.public.stripeSubMonthlyLink || oneShotStripeLink

const freeFeatures = [
  'Signe solaire, lunaire et ascendant',
  'Résumé de votre personnalité',
  '3 insights astrologiques',
  'Aperçu de vos forces',
]

const lockedFreeFeatures = [
  'Analyse des 10 planètes',
  'Les 12 maisons astrologiques',
  'Aspects planétaires complets',
  'Rapport PDF 30+ pages',
]

const premiumFeatures = [
  'Position exacte de vos 10 planètes',
  'Les 12 maisons astrologiques',
  'Tous les aspects planétaires',
  'Ascendant & planètes maîtresses',
  'Compatibilité amoureuse & relationnelle',
  'Transit annuel personnalisé',
  'Lune Noire & Chiron',
  'Rapport PDF premium 30+ pages',
  'Paiement unique, sans abonnement',
]

const monthlyFeatures = [
  'Horoscope quotidien premium personnalise',
  'Interpretations approfondies de vos transits',
  'Tendances de la semaine et du mois',
  'Conseils actionnables amour, travail, energie',
  'Priorite sur les nouvelles fonctionnalites',
  'Acces continu tant que l abonnement est actif',
]

const trustBadges = [
  {
    label: 'Paiement 100% sécurisé',
    path: 'M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z',
    fillRule: 'evenodd',
    clipRule: 'evenodd',
  },
  {
    label: 'Paiement unique',
    path: 'M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3z',
  },
  {
    label: 'Rapport en quelques secondes',
    path: 'M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z',
    fillRule: 'evenodd',
    clipRule: 'evenodd',
  },
  {
    label: '4.9/5 - +12 000 thèmes',
    path: 'M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z',
  },
]
</script>
