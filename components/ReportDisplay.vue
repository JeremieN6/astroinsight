<template>
  <div class="space-y-5 sm:space-y-6">
    <header class="mx-auto max-w-4xl pb-4 pt-2 text-center">
      <p class="eyebrow mb-3">Theme natal</p>
      <h1 class="font-display text-4xl text-white sm:text-6xl">
        Le theme de <span class="text-amber-300 uppercase">{{ firstName }}</span>
      </h1>
      <p class="mx-auto mt-4 max-w-3xl text-sm leading-7 text-slate-300 sm:text-base">
        Decouvrez les influences celestes qui guident votre chemin de vie,
        vos relations et votre potentiel cache.
      </p>
    </header>

    <article
      v-for="(card, index) in coreCards"
      :key="card.title"
      class="glass-panel relative overflow-hidden border border-white/15 px-5 py-5 sm:px-8"
      :class="index === 0 ? 'sm:py-7' : ''"
    >
      <div class="absolute inset-y-0 right-0 w-28 bg-gradient-to-l from-violet-500/15 to-transparent" />
      <div class="relative">
        <p class="eyebrow mb-1">{{ card.eyebrow }}</p>
        <div class="flex items-start justify-between gap-4">
          <div>
            <h2 class="font-display text-3xl text-white sm:text-4xl">{{ card.title }}</h2>
            <p class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-400">{{ card.subtitle }}</p>
          </div>
          <div class="hidden h-16 w-16 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-4xl text-violet-300 sm:flex">
            {{ card.glyph }}
          </div>
        </div>
        <p class="mt-5 max-w-4xl text-sm leading-7 text-slate-200 sm:text-[15px]">{{ card.description }}</p>
        <div class="mt-4 flex flex-wrap gap-2">
          <span
            v-for="tag in card.tags"
            :key="tag"
            class="rounded-full border border-white/15 bg-white/5 px-3 py-1 text-[11px] text-slate-300"
          >
            {{ tag }}
          </span>
        </div>
      </div>
    </article>

    <section class="glass-panel relative overflow-hidden border border-violet-400/20 px-5 py-5 sm:px-8 sm:py-7">
      <p class="eyebrow mb-2">Resume personnalise</p>
      <h3 class="font-display text-2xl text-white sm:text-3xl">Votre lecture rapide</h3>
      <p class="mt-4 text-sm leading-7 text-slate-200 sm:text-[15px]">{{ summaryText }}</p>
    </section>

    <section class="glass-panel overflow-hidden border border-white/15 px-5 py-5 sm:px-8 sm:py-7">
      <div class="mb-4 flex items-center justify-between gap-4">
        <div>
          <p class="eyebrow mb-1">Planetes</p>
          <h3 class="font-display text-2xl text-white sm:text-3xl">Positions planetaires</h3>
        </div>
        <span class="rounded-full border border-white/15 bg-white/5 px-3 py-1 text-xs text-slate-300">Apercu gratuit</span>
      </div>

      <div class="grid grid-cols-2 gap-2 sm:grid-cols-5 sm:gap-3">
        <div
          v-for="(planet, index) in planets"
          :key="`${planet.planet}-${index}`"
          class="relative rounded-xl border border-white/15 px-3 py-3"
          :class="isLockedPlanet(index) ? 'bg-white/5' : 'bg-white/10'"
        >
          <div :class="isLockedPlanet(index) ? 'blur-[3px] opacity-65 select-none' : ''">
            <p class="text-center text-lg text-violet-300">{{ planetEmoji[planet.planet] || '✦' }}</p>
            <p class="mt-1 text-center text-[10px] uppercase tracking-[0.2em] text-slate-400">{{ planet.planet }}</p>
            <p class="mt-1 text-center text-xs text-white">{{ planet.sign }}</p>
            <p class="text-center text-[11px] text-slate-400">{{ elementForSign(planet.sign) }}</p>
          </div>
          <div v-if="isLockedPlanet(index)" class="absolute inset-0 flex items-end justify-center pb-2">
            <span class="rounded-full border border-amber-400/35 bg-black/45 px-2 py-0.5 text-[10px] uppercase tracking-[0.14em] text-amber-300">🔒</span>
          </div>
        </div>
      </div>
    </section>

    <section class="glass-panel overflow-hidden border border-white/15 px-5 py-5 sm:px-8 sm:py-7">
      <div class="mb-4 flex items-center justify-between gap-4">
        <div>
          <p class="eyebrow mb-1">Maisons astrologiques</p>
          <h3 class="font-display text-2xl text-white sm:text-3xl">Les 12 maisons</h3>
        </div>
        <span class="rounded-full border border-white/15 bg-white/5 px-3 py-1 text-xs text-slate-300">Apercu gratuit</span>
      </div>

      <div class="space-y-2">
        <div
          v-for="house in houses"
          :key="house.index"
          class="relative rounded-2xl border border-white/15 bg-white/5 px-4 py-3"
        >
          <div :class="house.locked && !isPremium ? 'blur-[4px] opacity-65 select-none' : ''">
            <p class="text-xs uppercase tracking-[0.16em] text-slate-400">Maison {{ toRoman(house.index) }} - {{ house.title }}</p>
            <p class="mt-1 text-sm text-slate-200">{{ house.description }}</p>
          </div>
          <div v-if="house.locked && !isPremium" class="absolute inset-y-0 right-3 flex items-center">
            <span class="rounded-full border border-amber-400/35 bg-black/50 px-2.5 py-1 text-[10px] uppercase tracking-[0.14em] text-amber-300">🔒 Premium</span>
          </div>
        </div>
      </div>
    </section>

    <section class="glass-panel relative overflow-hidden border border-white/15 px-5 py-5 sm:px-8 sm:py-7">
      <div :class="isPremium ? '' : 'blur-[4px] opacity-60 select-none'">
        <p class="eyebrow mb-1">Aspects planetaires</p>
        <h3 class="font-display text-2xl text-white sm:text-3xl">Harmonies et tensions du ciel</h3>
        <p class="mt-2 max-w-3xl text-sm text-slate-300">
          Conjonctions, carres, trigones, oppositions et sextiles - la carte complete de vos dynamiques interieures.
        </p>
      </div>
      <div v-if="!isPremium" class="absolute inset-0 flex items-center justify-end pr-4 sm:pr-8">
        <span class="rounded-2xl border border-amber-400/35 bg-black/45 px-4 py-3 text-xs uppercase tracking-[0.2em] text-amber-300">🔒 Debloquer pour voir</span>
      </div>
    </section>

    <section class="glass-panel text-center relative overflow-hidden border border-amber-400/30 bg-gradient-to-b from-violet-500/15 to-transparent px-5 py-7 sm:px-8 sm:py-9">
      <p class="mx-auto mb-3 inline-flex rounded-full border border-amber-400/35 bg-amber-400/10 px-4 py-1 text-xs uppercase tracking-[0.2em] text-amber-300">
        ✦ Rapport complet premium ✦
      </p>
      <h3 class="text-center font-display text-3xl text-white sm:text-3xl">Debloquez l'integralite de votre theme natal</h3>
      <p class="mx-auto mt-4 max-w-3xl text-center text-sm leading-7 text-slate-200 sm:text-base">
        Accedez aux 10 planetes, aux 12 maisons, aux aspects planetaires, aux transits et a la compatibilite amoureuse.
      </p>

      <div class="mx-auto mt-6 grid max-w-4xl grid-cols-2 gap-3 sm:grid-cols-4">
        <div v-for="feat in premiumFeatures" :key="feat.title" class="rounded-2xl border border-white/15 bg-white/5 px-3 py-3 text-center">
          <p class="text-lg">{{ feat.icon }}</p>
          <p class="mt-1 text-xs font-semibold text-white">{{ feat.title }}</p>
          <p class="mt-1 text-[11px] text-slate-400">{{ feat.subtitle }}</p>
        </div>
      </div>

      <div class="mt-8 text-center">
        <a :href="stripeLink" class="cta-button w-full max-w-xl justify-center" target="_blank" rel="noopener">
          ✦ Debloquer mon rapport complet - 9,99€ ✦
        </a>
        <p class="text-xs text-slate-500 flex flex-wrap items-center justify-center gap-3 mt-5"><span data-v-47d01a91="">🔒 Paiement sécurisé Stripe</span><span data-v-47d01a91="">·</span><span data-v-47d01a91="">✅ Garantie 7 jours</span><span data-v-47d01a91="">·</span><span data-v-47d01a91="">📄 PDF téléchargeable</span></p>      </div>
    </section>

    <section v-if="isPremium && fullAnalysis" class="glass-panel border border-violet-400/30 p-6 sm:p-8">
      <p class="eyebrow mb-2">Rapport detaille</p>
      <h3 class="font-display text-2xl text-white sm:text-3xl">Analyse complete</h3>
      <div class="prose prose-invert mt-5 max-w-none prose-sm" v-html="fullAnalysis" />
    </section>

    <div class="py-2 text-center">
      <NuxtLink to="/rapport" class="text-sm text-slate-400 underline underline-offset-4 transition-colors hover:text-white">
        Calculer un autre theme
      </NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  report: Record<string, unknown>
  isPremium: boolean
}>()

const config = useRuntimeConfig()
const stripeLink = config.public.stripeOneShotLink || '/#pricing'

const planetEmoji: Record<string, string> = {
  Soleil: '☀',
  Lune: '☽',
  Mercure: '☿',
  'Vénus': '♀',
  Mars: '♂',
  Jupiter: '♃',
  Saturne: '♄',
  Uranus: '♅',
  Neptune: '♆',
  Pluton: '♇',
}

const reportData = computed(() => props.report as Record<string, unknown>)

type PlanetEntry = { planet: string; sign: string }

const planets = computed(() => {
  const value = reportData.value.planets
  if (!Array.isArray(value)) return [] as PlanetEntry[]
  return value as PlanetEntry[]
})

const firstName = computed(() => String(reportData.value.firstName || ''))
const summaryText = computed(() => String(reportData.value.summary || 'Resume indisponible.'))
const fullAnalysis = computed(() => String(reportData.value.fullAnalysis || ''))

const signInsight: Record<string, { subtitle: string; description: string; tags: string[]; glyph: string }> = {
  belier: { subtitle: 'Feu - impulsion', description: 'Votre energie est directe, entreprenante et orientee action.', tags: ['Initiative', 'Courage', 'Action'], glyph: '♈' },
  taureau: { subtitle: 'Terre - stabilite', description: 'Vous avancez avec constance et un fort sens du concret.', tags: ['Loyaute', 'Stabilite', 'Sensuel'], glyph: '♉' },
  gemeaux: { subtitle: 'Air - communication', description: 'Votre esprit curieux capte vite les nuances et les idees.', tags: ['Curiosite', 'Adaptable', 'Esprit vif'], glyph: '♊' },
  cancer: { subtitle: 'Eau - sensibilite', description: 'Votre monde interieur nourrit une grande intuition relationnelle.', tags: ['Intuition', 'Protection', 'Empathie'], glyph: '♋' },
  lion: { subtitle: 'Feu - rayonnement', description: 'Vous exprimez une energie creative et une presence chaleureuse.', tags: ['Creatif', 'Noble', 'Charisme'], glyph: '♌' },
  vierge: { subtitle: 'Terre - discernement', description: 'Vous cherchez la justesse, la precision et l utilite.', tags: ['Analyse', 'Service', 'Rigueur'], glyph: '♍' },
  balance: { subtitle: 'Air - harmonie', description: 'Vous cultivez l equilibre, le charme et la cooperation.', tags: ['Diplomatie', 'Esthetique', 'Equilibre'], glyph: '♎' },
  scorpion: { subtitle: 'Eau - transformation', description: 'Votre intensite emotionnelle alimente des metamorphoses profondes.', tags: ['Intensite', 'Perception', 'Profondeur'], glyph: '♏' },
  sagittaire: { subtitle: 'Feu - expansion', description: 'Votre quete de sens vous pousse vers de nouveaux horizons.', tags: ['Optimiste', 'Explorateur', 'Vision'], glyph: '♐' },
  capricorne: { subtitle: 'Terre - structure', description: 'Votre trajectoire se construit avec ambition et discipline.', tags: ['Ambition', 'Maturite', 'Perseverance'], glyph: '♑' },
  verseau: { subtitle: 'Air - innovation', description: 'Vous pensez differemment et aimez ouvrir de nouvelles voies.', tags: ['Original', 'Visionnaire', 'Libre'], glyph: '♒' },
  poissons: { subtitle: 'Eau - receptivite', description: 'Votre sensibilite capte les subtilites invisibles.', tags: ['Intuitif', 'Imaginaire', 'Compassion'], glyph: '♓' },
}

function normalizeSign(sign: string): string {
  return sign
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
}

function getInsight(sign: string) {
  return signInsight[normalizeSign(sign)] || {
    subtitle: 'Energie astrologique',
    description: 'Votre configuration celeste met en avant une dynamique singuliere a explorer.',
    tags: ['Unique', 'Nuance', 'Evolution'],
    glyph: '✦',
  }
}

function elementForSign(sign: string): string {
  const key = normalizeSign(sign)
  if (['belier', 'lion', 'sagittaire'].includes(key)) return 'Feu'
  if (['taureau', 'vierge', 'capricorne'].includes(key)) return 'Terre'
  if (['gemeaux', 'balance', 'verseau'].includes(key)) return 'Air'
  if (['cancer', 'scorpion', 'poissons'].includes(key)) return 'Eau'
  return 'Element'
}

const coreCards = computed(() => {
  const sun = String(reportData.value.sunSign || 'Inconnu')
  const moon = String(reportData.value.moonSign || 'Inconnu')
  const asc = String(reportData.value.ascendant || 'Inconnu')
  const sunInsight = getInsight(sun)
  const moonInsight = getInsight(moon)
  const ascInsight = getInsight(asc)

  return [
    {
      eyebrow: '☀ Soleil en',
      title: sun,
      subtitle: sunInsight.subtitle,
      description: `${firstName.value}, ${sunInsight.description}`,
      tags: sunInsight.tags,
      glyph: sunInsight.glyph,
    },
    {
      eyebrow: '☽ Lune en',
      title: moon,
      subtitle: moonInsight.subtitle,
      description: moonInsight.description,
      tags: moonInsight.tags,
      glyph: moonInsight.glyph,
    },
    {
      eyebrow: '↑ Ascendant',
      title: asc,
      subtitle: ascInsight.subtitle,
      description: `Votre presence sociale reflete l energie ${asc.toLowerCase()}. ${ascInsight.description}`,
      tags: ascInsight.tags,
      glyph: ascInsight.glyph,
    },
  ]
})

function isLockedPlanet(index: number): boolean {
  return !props.isPremium && index >= 5
}

const houses = computed(() => {
  const asc = String(reportData.value.ascendant || 'votre ascendant')
  return [
    { index: 1, title: 'Identite', description: `Avec votre ascendant en ${asc}, votre maniere d entrer en relation est marquee par cette energie.`, locked: false },
    { index: 2, title: 'Valeurs et ressources', description: 'Votre rapport a l argent et a vos talents naturels.', locked: true },
    { index: 3, title: 'Communication', description: 'Votre facon de penser, d apprendre et d echanger.', locked: true },
    { index: 4, title: 'Foyer et racines', description: 'Votre base emotionnelle et vos besoins de securite.', locked: true },
    { index: 5, title: 'Creativite et amour', description: 'Expression de soi, joie et elan du coeur.', locked: true },
    { index: 6, title: 'Sante et travail', description: 'Organisation, habitudes et sens du service.', locked: true },
    { index: 7, title: 'Partenariats', description: 'Dynamique de couple et collaborations majeures.', locked: true },
    { index: 8, title: 'Transformations', description: 'Mues profondes, pouvoir personnel et intimite.', locked: true },
    { index: 9, title: 'Vision et philosophie', description: 'Croyances, etudes et ouverture au monde.', locked: true },
    { index: 10, title: 'Carriere et mission', description: 'Direction de vie et realisation publique.', locked: true },
    { index: 11, title: 'Amities et projets', description: 'Reseaux et ideal collectif.', locked: true },
    { index: 12, title: 'Inconscient', description: 'Monde interieur, intuition et retrait.', locked: true },
  ]
})

function toRoman(num: number): string {
  const map = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII']
  return map[num - 1] || String(num)
}

const premiumFeatures = [
  { icon: '🪐', title: '10 planetes', subtitle: 'Uranus, Neptune, Pluton et plus' },
  { icon: '🏠', title: '12 maisons', subtitle: 'Chaque domaine de votre vie' },
  { icon: '⚡', title: 'Aspects', subtitle: 'Tensions et harmonies celestes' },
  { icon: '💕', title: 'Compatibilite', subtitle: 'Affinites amoureuses par signe' },
]
</script>
