<template>
  <section class="section-transition py-20 sm:py-24">
    <div class="section-shell">
      <div class="mx-auto mb-14 max-w-2xl text-center">
        <p class="eyebrow reveal-on-scroll mb-4">Témoignages</p>
        <h2 class="section-title reveal-on-scroll">
          Ce qu'ils en disent
        </h2>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
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
    name: 'Sophie L.',
    role: 'Designer · Paris',
    quote: 'Mon ascendant Scorpion explique tellement de choses que je n\'arrivais pas à comprendre chez moi. L\'analyse est bluffante de précision.',
  },
  {
    name: 'Marc D.',
    role: 'Entrepreneur · Lyon',
    quote: 'J\'étais sceptique, mais la description de mon Mercure en Gémeaux correspond exactement à ma façon de penser. Je recommande.',
  },
  {
    name: 'Laure B.',
    role: 'Thérapeute · Bordeaux',
    quote: 'Le rapport premium est une vraie mine d\'or. Je l\'utilise en complément de mes séances pour mieux comprendre mes clients.',
  },
  {
    name: 'Thomas R.',
    role: 'Ingénieur · Strasbourg',
    quote: 'Résultat en 30 secondes, analyse de 15 pages. Excellent rapport qualité-prix. J\'ai offert le rapport complet à toute ma famille.',
  },
  {
    name: 'Amélie N.',
    role: 'Étudiante · Nantes',
    quote: 'Enfin un outil d\'astrologie sérieux qui va au-delà du signe solaire. La section sur les aspects planétaires est fascinante.',
  },
  {
    name: 'Pierre M.',
    role: 'Professeur · Toulouse',
    quote: 'La précision du calcul de l\'ascendant à la minute près est impressionnante. Interface belle et fluide. Très satisfait.',
  },
]
</script>
