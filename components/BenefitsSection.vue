<template>
  <section id="benefits" class="section-transition scroll-mt-24 py-20 sm:py-24">
    <div class="section-shell">
      <div class="mx-auto mb-16 max-w-2xl text-center">
        <p class="eyebrow reveal-on-scroll mb-4">Pourquoi AstroInsights</p>
        <h2 class="section-title reveal-on-scroll">
          Ce que révèle<br/><span class="text-amber-400">votre thème natal</span>
        </h2>
        <p class="reveal-on-scroll mt-5 text-slate-400 text-base leading-7">
          L'astrologie n'est pas de la divination. C'est un langage symbolique millénaire que l'IA traduit aujourd'hui avec une précision remarquable.
        </p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="(benefit, i) in benefits"
          :key="benefit.title"
          class="reveal-on-scroll glass-panel group cursor-pointer p-7 transition-all duration-300 hover:-translate-y-2 hover:border-violet-400/30 hover:shadow-[0_0_50px_rgba(124,58,237,0.6)]"
          :style="`transition-delay: ${i * 60}ms`"
        >
          <!-- Icon -->
          <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-2xl transition-all duration-300"
               :class="benefit.iconBg">
            <span class="text-2xl">{{ benefit.icon }}</span>
          </div>
          <h3 class="mb-3 font-display text-lg font-semibold text-white leading-snug">{{ benefit.title }}</h3>
          <p class="text-sm text-slate-400 leading-6">{{ benefit.description }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const benefits = [
  {
    icon: '☀',
    iconBg: 'bg-amber-500/10 border border-amber-400/20',
    title: 'Signe Solaire précis',
    description: 'Votre identité profonde, votre volonté, la lumière que vous projetez dans le monde. Bien plus qu\'un simple signe du zodiaque.',
  },
  {
    icon: '☽',
    iconBg: 'bg-violet-500/10 border border-violet-400/20',
    title: 'Signe Lunaire révélé',
    description: 'Vos émotions, réactions instinctives et besoins émotionnels. La face cachée de votre personnalité que peu de gens connaissent.',
  },
  {
    icon: '↑',
    iconBg: 'bg-sky-500/10 border border-sky-400/30',
    title: 'Ascendant calculé',
    description: 'Votre image sociale, votre façade au monde. L\'ascendant influence profondément la manière dont les autres vous perçoivent.',
  },
  {
    icon: '♄',
    iconBg: 'bg-purple-500/10 border border-violet-500/20',
    title: '10 Planètes analysées',
    description: 'Mercure, Vénus, Mars, Jupiter, Saturne, Uranus, Neptune et Pluton. Chaque planète raconte une partie de votre histoire.',
  },
  {
    icon: '⬡',
    iconBg: 'bg-emerald-500/10 border border-emerald-400/30',
    title: 'Aspects planétaires',
    description: 'Les angles formés entre vos planètes révèlent des tensions, harmonies et potentiels cachés de votre personnalité.',
  },
  {
    icon: '✦',
    iconBg: 'bg-rose-500/10 border border-rose-400/30',
    title: 'Analyse IA personnalisée',
    description: 'Un texte d\'interprétation unique généré pour vous par GPT-4o, qui synthétise toutes vos positions planétaires.',
  },
]
</script>
