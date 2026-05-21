<template>
  <section id="faq" class="section-transition scroll-mt-24 py-20 sm:py-24">
    <div class="section-shell">
      <div class="mx-auto mb-14 max-w-2xl text-center">
        <p class="eyebrow reveal-on-scroll mb-4">FAQ</p>
        <h2 class="section-title reveal-on-scroll">Questions fréquentes</h2>
      </div>

      <div class="mx-auto max-w-3xl space-y-3">
        <div
          v-for="(item, i) in faqs"
          :key="i"
          class="reveal-on-scroll glass-panel overflow-hidden transition-all duration-300 hover:border-white/20"
          :style="`transition-delay: ${i * 40}ms`"
        >
          <button
            class="flex w-full items-center justify-between px-7 py-5 text-left"
            @click="toggle(i)"
          >
            <span class="font-medium text-white text-sm sm:text-base pr-5">{{ item.question }}</span>
            <svg
              class="h-5 w-5 shrink-0 text-amber-400 transition-transform duration-300"
              :class="open === i ? 'rotate-45' : ''"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14M5 12h14"/>
            </svg>
          </button>

          <div
            class="overflow-hidden transition-all duration-500"
            :style="open === i ? 'max-height: 300px; opacity: 1' : 'max-height: 0; opacity: 0'"
          >
            <p class="px-7 pb-6 text-sm text-slate-400 leading-6">{{ item.answer }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const open = ref<number | null>(0)

function toggle(i: number) {
  open.value = open.value === i ? null : i
}

const faqs = [
  {
    question: 'Ai-je besoin de connaître mon heure de naissance exacte ?',
    answer: 'L\'heure de naissance est importante pour calculer votre ascendant avec précision. Si vous ne la connaissez pas, nous effectuons une estimation basée sur midi, mais l\'ascendant peut être légèrement imprécis. Les positions des planètes restent exactes.',
  },
  {
    question: 'Comment est calculé l\'ascendant ?',
    answer: 'L\'ascendant (ou signe montant) est le degré de l\'écliptique qui se levait à l\'est au moment de votre naissance. Il est calculé à partir de votre heure, date et lieu de naissance via les éphémérides et la formule trigonométrique de la maison 1.',
  },
  {
    question: 'L\'aperçu gratuit est-il vraiment gratuit ?',
    answer: 'Oui, complètement gratuit et sans carte bancaire. Vous recevez votre signe solaire, lunaire, ascendant, les positions des 10 planètes et un résumé de personnalité de 500 mots. Aucun abonnement, aucune surprise.',
  },
  {
    question: 'Qu\'est-ce que le rapport premium contient de plus ?',
    answer: 'Le rapport complet inclut une interprétation approfondie de chaque planète dans son signe et sa maison, l\'analyse des aspects majeurs (conjonctions, oppositions, carrés, trigones), des conseils pratiques pour la vie amoureuse, professionnelle et spirituelle, ainsi qu\'un PDF téléchargeable.',
  },
  {
    question: 'Comment fonctionne l\'analyse par intelligence artificielle ?',
    answer: 'Une fois vos positions planétaires calculées par notre moteur astrologique, nous envoyons un prompt structuré à GPT-4o qui synthétise ces données en un texte personnalisé. L\'IA est guidée par notre base de connaissances en astrologie classique et moderne.',
  },
  {
    question: 'Mes données sont-elles en sécurité ?',
    answer: 'Vos données de naissance sont chiffrées en transit (HTTPS) et ne sont jamais vendues à des tiers. Elles sont uniquement utilisées pour générer votre rapport et sont supprimées sur demande.',
  },
]
</script>
