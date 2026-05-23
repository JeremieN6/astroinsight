<template>
  <section class="min-h-screen py-24">
    <div class="section-shell max-w-3xl">
      <h1 class="font-display text-3xl sm:text-4xl text-white mb-10">Mentions légales</h1>

      <div class="glass-panel p-8 sm:p-10 space-y-8 text-slate-300 leading-7 text-sm">
        <div>
          <h2 class="font-display text-lg text-white mb-3">Éditeur du site</h2>
          <p>AstroInsights est un service édité par une personne physique.</p>
          <p class="mt-2">Contact : <a href="mailto:contact@astroinsights.fr" class="text-amber-400 hover:text-amber-300">contact@astroinsights.fr</a></p>
        </div>

        <div>
          <h2 class="font-display text-lg text-white mb-3">Hébergement</h2>
          <p>Le site est hébergé par Vercel Inc., 340 Pine Street, Suite 701, San Francisco, CA 94104, USA.</p>
        </div>

        <div>
          <h2 class="font-display text-lg text-white mb-3">Propriété intellectuelle</h2>
          <p>
            Tous les contenus présents sur ce site (textes, images, logos) sont la propriété exclusive
            d'AstroInsights et sont protégés par les lois relatives à la propriété intellectuelle.
            Toute reproduction, même partielle, est strictement interdite sans autorisation préalable.
          </p>
        </div>

        <div>
          <h2 class="font-display text-lg text-white mb-3">Données personnelles</h2>
          <p>
            Les données collectées (prénom, date de naissance, email) sont utilisées uniquement pour
            générer votre thème natal et vous envoyer votre rapport. Conformément au RGPD, vous disposez
            d'un droit d'accès, de rectification et de suppression de vos données. Pour exercer ces droits,
            contactez-nous à l'adresse email ci-dessus.
          </p>
        </div>

        <div>
          <h2 class="font-display text-lg text-white mb-3">Cookies</h2>
          <p>
            Ce site utilise uniquement des cookies techniques nécessaires au bon fonctionnement du service.
            Aucun cookie publicitaire ou de traçage tiers n'est utilisé.
          </p>
        </div>

        <div>
          <h2 class="font-display text-lg text-white mb-3">Responsabilité</h2>
          <p>
            Les thèmes natals et analyses astrologiques fournis par AstroInsights sont proposés à titre
            divertissant et indicatif uniquement. Ils ne constituent pas un conseil professionnel de quelque
            nature que ce soit.
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
useSeoMeta({
  title: 'Mentions légales — AstroInsights',
  robots: 'noindex',
})
</script>
