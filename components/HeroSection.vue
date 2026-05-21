<template>
  <section class="relative min-h-screen flex items-center overflow-hidden">
    <!-- Background orbs -->
    <div class="absolute inset-0 pointer-events-none">
      <div class="absolute -top-40 left-1/4 h-[600px] w-[600px] rounded-full bg-violet-500/10 blur-[120px]" />
      <div class="absolute -top-40 right-1/4 h-[400px] w-[400px] rounded-full bg-amber-500/10 blur-[100px]" />
      <div class="absolute bottom-0 left-1/2 h-64 w-[400px] -translate-x-1/2 rounded-full bg-violet-600/10 blur-[80px]" />
    </div>

    <div class="section-shell relative z-10 pt-14 pb-16">
      <div class="mx-auto max-w-4xl text-center">
        <!-- Eyebrow -->
        <div class="reveal-on-scroll mb-6 flex items-center justify-center">
          <span class="flex items-center gap-2 rounded-full border border-amber-400/20 bg-amber-400/5 px-4 py-1.5">
            <span class="text-amber-400 text-xs">✦</span>
            <span class="eyebrow">Thème Natal · IA · Gratuit</span>
          </span>
        </div>

        <!-- Title -->
        <h1 class="reveal-on-scroll section-title mb-6 text-5xl sm:text-6xl md:text-7xl xl:text-7xl leading-[1.1]">
          Découvrez votre<br />
          <span class="text-shimmer">Carte du Ciel</span>
        </h1>

        <!-- Subtitle -->
        <p class="reveal-on-scroll mx-auto mb-10 max-w-2xl text-lg text-slate-400 leading-7">
          Votre thème natal complet en 30 secondes. Soleil, Lune, Ascendant et 10 planètes.
          Analyse personnalisée par intelligence artificielle — <strong class="text-white font-medium">gratuitement</strong>.
        </p>

        <!-- Trust badges -->
        <div class="reveal-on-scroll mb-12 flex flex-wrap justify-center gap-4">
          <span v-for="badge in badges" :key="badge.label" class="flex items-center gap-2 text-xs text-slate-400">
            <span :class="badge.color">{{ badge.icon }}</span>
            {{ badge.label }}
          </span>
        </div>

        <!-- Orbital hero graphic -->
        <div class="reveal-on-scroll relative mx-auto mb-14 flex h-52 w-52 sm:h-80 sm:w-80 items-center justify-center">
          <!-- Orbit ring -->
          <div class="absolute inset-0 rounded-full border border-violet-400/20" />
          <div class="absolute inset-4 rounded-full border border-amber-400/10" />
          <div class="absolute inset-8 rounded-full border border-violet-400/10" />
          <!-- Center orb -->
          <div class="relative flex h-20 w-20 sm:h-24 sm:w-24 items-center justify-center rounded-full border border-violet-400/30 bg-violet-600/15 shadow-[0_0_60px_rgba(124,58,237,0.2)]">
            <span class="text-4xl sm:text-5xl animate-drift drop-shadow-[0_0_60px_rgba(168,85,247,0.4)]">☽</span>
          </div>
          <!-- Orbiting stars -->
          <div v-for="orb in orbits" :key="orb.symbol"
               :style="orb.style"
               class="absolute flex h-8 w-8 sm:h-10 sm:w-10 items-center justify-center rounded-full border bg-[rgba(10,10,26,0.85)] text-base sm:text-lg shadow-[0_0_20px_rgba(124,58,237,0.4)]"
               :class="orb.borderClass">
            {{ orb.symbol }}
          </div>
        </div>

        <!-- CTA buttons -->
        <div id="rapport" class="reveal-on-scroll flex flex-col sm:flex-row gap-4 justify-center scroll-mt-24">
          <NuxtLink to="/rapport" class="cta-button">
            Obtenir mon thème natal
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
          </NuxtLink>
          <NuxtLink to="/#benefits" class="secondary-button">
            En savoir plus
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </NuxtLink>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const badges = [
  { icon: '✦', color: 'text-amber-400', label: 'Analyse IA personnalisée' },
  { icon: '☽', color: 'text-violet-400', label: '10 planètes calculées' },
  { icon: '★', color: 'text-amber-400', label: '100% gratuit' },
]

const orbits = [
  { symbol: '☀', borderClass: 'border-amber-400/30', style: 'top: 5%; left: 50%; transform: translateX(-50%)' },
  { symbol: '♄', borderClass: 'border-violet-400/30', style: 'bottom: 5%; left: 50%; transform: translateX(-50%)' },
  { symbol: '♃', borderClass: 'border-sky-400/30', style: 'top: 50%; left: 5%; transform: translateY(-50%)' },
  { symbol: '♂', borderClass: 'border-rose-400/30', style: 'top: 50%; right: 5%; transform: translateY(-50%)' },
]
</script>
