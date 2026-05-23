<template>
  <section class="section-transition py-20 sm:py-24">
    <div class="section-shell">
      <div class="mx-auto max-w-3xl">
        <div class="glass-panel p-8 sm:p-12 text-center border-violet-500/20 bg-[radial-gradient(circle_at_top,rgba(168,85,247,0.22),transparent_34%),radial-gradient(circle_at_82%_18%,rgba(251,191,36,0.18),transparent_22%)]">
          <div class="reveal-on-scroll mb-4 flex items-center justify-center">
            <span class="flex items-center gap-2 rounded-full border border-amber-400/20 bg-amber-400/5 px-4 py-1.5">
              <span class="text-amber-400 text-xs">✦</span>
              <span class="eyebrow">Prêt à vous découvrir ?</span>
            </span>
          </div>

          <h2 class="reveal-on-scroll section-title mb-5">
            Votre thème natal<br/>vous attend
          </h2>

          <p class="reveal-on-scroll mb-10 text-slate-400 text-base leading-7 max-w-lg mx-auto">
            Rejoignez des milliers de personnes qui ont découvert leur personnalité profonde grâce à l'astrologie et à l'intelligence artificielle.
          </p>

          <div class="reveal-on-scroll flex flex-col sm:flex-row gap-4 justify-center">
            <NuxtLink to="/rapport" class="cta-button">
              Obtenir mon rapport gratuit
              <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
              </svg>
            </NuxtLink>
          </div>

          <!-- Trust indicators -->
          <div class="reveal-on-scroll mt-8 flex flex-wrap justify-center gap-6 text-xs text-slate-500">
            <span class="flex items-center gap-1.5"><span class="text-emerald-400">✓</span> Sans carte bancaire</span>
            <span class="flex items-center gap-1.5"><span class="text-emerald-400">✓</span> Résultat en 30 secondes</span>
            <span class="flex items-center gap-1.5"><span class="text-emerald-400">✓</span> Données protégées</span>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
