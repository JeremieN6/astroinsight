<template>
  <footer class="border-t border-white/10 py-12">
    <div class="section-shell">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
        <!-- Logo -->
        <div class="flex items-center gap-3">
          <div class="flex h-8 w-8 items-center justify-center rounded-full border border-violet-400/30 bg-violet-600/15">
            <span class="text-sm">✦</span>
          </div>
          <span class="font-display text-sm font-semibold tracking-[0.18em] text-white">AstroInsights</span>
        </div>

        <!-- Links -->
        <nav class="flex items-center gap-6 flex-wrap justify-center">
          <NuxtLink to="/#benefits" class="text-xs text-slate-500 tracking-widest uppercase hover:text-slate-300 transition-colors">Bénéfices</NuxtLink>
          <NuxtLink to="/#pricing" class="text-xs text-slate-500 tracking-widest uppercase hover:text-slate-300 transition-colors">Tarifs</NuxtLink>
          <NuxtLink to="/#faq" class="text-xs text-slate-500 tracking-widest uppercase hover:text-slate-300 transition-colors">FAQ</NuxtLink>
          <NuxtLink to="/mentions-legales" class="text-xs text-slate-500 tracking-widest uppercase hover:text-slate-300 transition-colors">Mentions légales</NuxtLink>
        </nav>

        <!-- Copy -->
        <p class="text-xs text-slate-600">
          © {{ new Date().getFullYear() }} Stellara · Tous droits réservés
        </p>
      </div>
    </div>
  </footer>
</template>
