<template>
  <section id="processus" class="section-transition scroll-mt-24 py-20 sm:py-24">
    <div class="section-shell">
      <div class="mx-auto mb-16 max-w-2xl text-center">
        <p class="eyebrow reveal-on-scroll mb-4">Votre parcours</p>
        <h2 class="section-title reveal-on-scroll">
          3 étapes vers<br/><span class="text-shimmer">votre thème natal</span>
        </h2>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
          v-for="(step, i) in steps"
          :key="step.number"
          class="reveal-on-scroll group glass-panel relative p-8 transition-all duration-300 cursor-pointer hover:-translate-y-3 hover:border-amber-400/60 hover:shadow-[0_28px_100px_rgba(124,58,237,0.34)]"
          :style="`transition-delay: ${i * 80}ms`"
        >
          <!-- Number circle -->
          <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-full border-2 border-amber-400/25 bg-transparent transition-all duration-300 group-hover:bg-amber-400/20 group-hover:border-amber-400/60 group-hover:shadow-[0_0_30px_rgba(251,191,36,0.3)]">
            <span class="font-display text-lg font-semibold text-amber-400/80 group-hover:text-amber-400 transition-colors duration-300">{{ step.number }}</span>
          </div>

          <!-- Icon -->
          <div class="mb-4 text-3xl transition-transform duration-300 group-hover:-translate-y-1">{{ step.icon }}</div>

          <h3 class="mb-3 font-display text-xl font-semibold text-white">{{ step.title }}</h3>
          <p class="text-sm text-slate-400 leading-6">{{ step.description }}</p>

          <!-- Connector line (not on last) -->
          <div v-if="i < steps.length - 1" class="absolute -right-3 top-1/2 hidden h-px w-6 -translate-y-1/2 bg-gradient-to-r from-violet-400/40 to-transparent md:block" />
        </div>
      </div>

      <div class="reveal-on-scroll mt-12 text-center">
        <NuxtLink to="/rapport" class="cta-button">
          Commencer maintenant — c'est gratuit
          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
          </svg>
        </NuxtLink>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const steps = [
  {
    number: '01',
    icon: '📍',
    title: 'Entrez vos données',
    description: 'Saisissez votre date, heure et lieu de naissance. Notre géocodage précis calcule vos coordonnées exactes pour un ascendant juste à la minute.',
  },
  {
    number: '02',
    icon: '⚡',
    title: 'Calcul instantané',
    description: 'Notre moteur astrologique calcule en temps réel les positions de vos 10 planètes, votre ascendant et tous les aspects majeurs de votre thème.',
  },
  {
    number: '03',
    icon: '✨',
    title: 'Rapport personnalisé',
    description: 'Recevez une analyse complète en langage naturel générée par l\'IA. L\'aperçu est gratuit, le rapport complet débloqué en un clic.',
  },
]
</script>
