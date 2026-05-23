<template>
  <section id="how-it-works" class="section-transition scroll-mt-24 py-20 sm:py-24">
    <div class="section-shell">
    <div class="mx-auto mb-16 max-w-3xl text-center">
      <p class="eyebrow reveal-on-scroll mb-4">Parcours</p>
      <h2 class="section-title reveal-on-scroll mx-auto max-w-2xl">
        3 étapes vers votre destinée
      </h2>
      <p class="reveal-on-scroll mx-auto mt-6 max-w-xl text-lg text-slate-300">
        Simple, rapide et révélateur. De vos données de naissance à votre portrait astrologique.
      </p>
    </div>

    <div class="relative">
      <div
        class="absolute left-1/2 top-16 hidden h-px w-[66%] -translate-x-1/2 bg-gradient-to-r from-transparent via-amber-400/30 to-transparent lg:block"
      />

      <div class="grid gap-8 lg:grid-cols-3">
        <article
          v-for="(step, i) in steps"
          :key="step.number"
          class="reveal-on-scroll group relative flex flex-col items-center text-center"
          :style="`transition-delay: ${Number(i) * 100}ms`"
        >
          <div class="relative mb-8 flex h-16 w-16 items-center justify-center">
            <div
              class="absolute inset-0 rounded-full bg-amber-400/10 blur-xl transition-all duration-300 group-hover:bg-amber-400/20"
            />

            <div
              class="relative flex h-16 w-16 items-center justify-center rounded-full border-2 border-amber-400/40 bg-amber-400/10 transition-all duration-300 group-hover:border-amber-400/70 group-hover:bg-amber-400/20 group-hover:shadow-[0_0_30px_rgba(251,191,36,0.3)]"
            >
              <span class="font-display text-xl font-bold text-amber-400">{{ step.number }}</span>
            </div>

            <div
              v-if="Number(i) < steps.length - 1"
              class="absolute -right-4 top-1/2 hidden h-2 w-2 -translate-y-1/2 rounded-full bg-amber-400/50 lg:block"
            />
          </div>

          <div
            class="glass-panel w-full px-6 py-6 transition-all duration-300 group-hover:-translate-y-1 group-hover:border-amber-400/20"
          >
            <div class="mb-4 text-3xl">{{ step.icon }}</div>
            <h3 class="font-display text-xl text-white">
              {{ step.title }}
            </h3>
            <p class="mt-3 text-sm leading-7 text-slate-400">
              {{ step.description }}
            </p>
          </div>
        </article>
      </div>
    </div>

    <div class="mt-14 flex justify-center reveal-on-scroll">
      <NuxtLink
        to="/#pricing"
        class="inline-flex items-center gap-3 rounded-full px-8 py-4 text-sm font-bold uppercase tracking-[0.18em] text-white shadow-[0_0_30px_rgba(124,58,237,0.4)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_0_50px_rgba(124,58,237,0.6)]"
        style="background: linear-gradient(135deg, #7c3aed 0%, #a855f7 50%, #d97706 100%);"
      >
        Commencer maintenant
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
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
    icon: '📅',
    title: 'Entrez vos données de naissance',
    description: 'Date, heure exacte et lieu de naissance. En 30 secondes, vous avez tout renseigné.',
  },
  {
    number: '02',
    icon: '⚡',
    title: 'Notre IA calcule votre thème natal',
    description: 'Notre algorithme de précision calcule la position exacte de chaque planète au moment de votre naissance.',
  },
  {
    number: '03',
    icon: '✨',
    title: 'Recevez votre rapport personnalisé',
    description: 'En quelques secondes, votre analyse astrologique complète est prête. Découvrez votre destinée.',
  },
]
</script>
