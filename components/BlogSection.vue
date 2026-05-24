<template>
  <section id="blog" class="section-transition scroll-mt-24 py-20 sm:py-24">
    <div class="section-shell">
      <div class="mx-auto max-w-3xl text-center">
        <p class="eyebrow reveal-on-scroll mb-4">Blog Stellara</p>
        <h2 class="section-title reveal-on-scroll mx-auto max-w-3xl">
          Des repères concrets pour mieux utiliser l'astrologie au quotidien
        </h2>
        <p class="reveal-on-scroll mx-auto mt-6 max-w-2xl text-lg text-slate-300">
          Analyses claires, conseils pratiques et lectures utiles pour mieux comprendre vos cycles, vos priorités et vos fenêtres d'opportunités.
        </p>
      </div>

      <div class="mt-14 grid gap-6 lg:grid-cols-3">
        <article
          v-for="post in featuredPosts"
          :key="post.slug"
          class="reveal-on-scroll glass-panel group relative overflow-hidden border border-white/10 p-6 transition-all duration-500 hover:-translate-y-2 hover:border-amber-400/25 hover:shadow-[0_24px_70px_rgba(10,10,26,0.45)]"
        >
          <div class="pointer-events-none absolute inset-0 bg-gradient-to-br from-amber-400/10 via-transparent to-violet-500/10 opacity-0 transition-opacity duration-500 group-hover:opacity-100" />
          <div class="relative z-10 flex items-center justify-between gap-4 text-sm text-slate-400">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs uppercase tracking-[0.2em] text-slate-300">
              <span aria-hidden="true">{{ post.image }}</span>
              Article
            </span>
            <time :datetime="post.date">{{ formatDate(post.date) }}</time>
          </div>

          <h3 class="relative z-10 mt-5 text-2xl font-semibold text-white transition-colors duration-300 group-hover:text-amber-300">
            {{ post.titre }}
          </h3>

          <p class="relative z-10 mt-4 text-sm leading-7 text-slate-400">
            {{ post.intro }}
          </p>

          <div class="relative z-10 mt-6 flex items-center justify-between gap-4">
            <NuxtLink
              :to="`/blog/${post.slug}`"
              class="inline-flex items-center gap-2 text-sm font-medium text-amber-300 transition hover:text-amber-200"
            >
              Lire l'article
              <span aria-hidden="true">→</span>
            </NuxtLink>
            <span class="text-xs uppercase tracking-[0.2em] text-slate-500">SEO friendly</span>
          </div>
        </article>
      </div>

      <div class="mt-10 flex justify-center">
        <NuxtLink to="/blog" class="cta-button">
          Voir tous les articles
        </NuxtLink>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import blogPosts from '~/data/blog.json'

type BlogPost = {
  slug: string
  titre: string
  intro: string
  date: string
  image: string
}

const featuredPosts = computed(() => {
  return [...(blogPosts as BlogPost[])]
    .sort((left, right) => right.date.localeCompare(left.date))
    .slice(0, 3)
})

function formatDate(dateValue: string) {
  return new Intl.DateTimeFormat('fr-FR', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  }).format(new Date(`${dateValue}T00:00:00`))
}
</script>