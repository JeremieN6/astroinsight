<template>
  <div>
    <NavBar />
    <main>
      <slot />
    </main>
    <SiteFooter />
  </div>
</template>

<script setup lang="ts">
import NavBar from '~/components/NavBar.vue'
import SiteFooter from '~/components/SiteFooter.vue'

useReveal()
</script>
