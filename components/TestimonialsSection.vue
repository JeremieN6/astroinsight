<template>
  <section class="section-transition py-20 sm:py-24">
    <div class="section-shell">
      <div class="mx-auto mb-14 max-w-3xl text-center">
        <p class="eyebrow reveal-on-scroll mb-4">Témoignages</p>
        <h2 class="section-title reveal-on-scroll">
          Ce que disent les étoiles... et nos utilisateurs
        </h2>
        <p class="reveal-on-scroll mx-auto mt-6 max-w-xl text-lg text-slate-300">
          Des retours authentiques après découverte de leur thème natal.
        </p>
      </div>

      <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
        <div
          v-for="(t, i) in testimonials"
          :key="t.name"
          class="reveal-on-scroll glass-panel p-7 transition-all duration-300 hover:-translate-y-1 hover:border-white/20"
          :style="`transition-delay: ${i * 60}ms`"
        >
          <!-- Stars -->
          <div class="mb-4 flex gap-1">
            <span v-for="n in 5" :key="n" class="text-amber-400 text-sm">★</span>
          </div>

          <!-- Quote -->
          <p class="mb-5 text-sm text-slate-300 leading-6 italic">"{{ t.quote }}"</p>

          <!-- Author -->
          <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-full border border-violet-400/30 bg-violet-600/15 text-sm font-semibold text-violet-300">
              {{ t.name[0] }}
            </div>
            <div>
              <p class="text-sm font-semibold text-white">{{ t.name }}</p>
              <p class="text-xs text-slate-500">{{ t.role }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const testimonials = [
  {
    name: 'Camille R.',
    role: 'Scorpion',
    quote: 'Incroyablement précis pour mon ascendant Scorpion. Chaque détail correspondait exactement à ce que je ressens profondément.',
  },
  {
    name: 'Sophie M.',
    role: 'Balance',
    quote: 'Je ne m\'attendais pas à quelque chose de générique, mais ce rapport est d\'une précision troublante. La description de ma Lune en Vierge m\'a coupé le souffle.',
  },
  {
    name: 'Léa B.',
    role: 'Poissons',
    quote: 'Les aspects planétaires ont tout éclairé sur mes relations amoureuses. En 30 secondes j\'ai obtenu plus d\'infos que des années d\'horoscopes.',
  },
]
</script>
