<template>
  <section class="min-h-screen pb-16 pt-20 sm:pb-20 sm:pt-24">
    <div class="section-shell">
      <div class="mx-auto max-w-3xl">
        <header class="mb-8 text-center sm:mb-10">
          <p class="eyebrow mb-3">Compte</p>
          <h1 class="font-display text-4xl text-white sm:text-5xl">
            Mon <span class="text-amber-300">espace</span>
          </h1>
          <p class="mx-auto mt-4 max-w-2xl text-sm leading-7 text-slate-300 sm:text-base">
            Retrouve ton statut premium, ton abonnement actif et l'email lie a tes achats.
          </p>
        </header>

        <div class="glass-panel border border-white/15 p-6 sm:p-8">
          <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
              <p class="text-xs uppercase tracking-[0.16em] text-slate-400">Identite</p>
              <p class="mt-1 text-sm text-slate-200">
                {{ activeEmail || 'Non connecte' }}
              </p>
            </div>

            <div v-if="activeEmail" class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs uppercase tracking-[0.14em]"
              :class="reportStore.isPremium ? 'border-emerald-400/40 bg-emerald-400/10 text-emerald-200' : 'border-white/15 bg-white/5 text-slate-300'"
            >
              <span class="h-1.5 w-1.5 rounded-full" :class="reportStore.isPremium ? 'bg-emerald-300' : 'bg-slate-400'" />
              {{ reportStore.isPremium ? 'Premium actif' : 'Mode essentiel' }}
            </div>
          </div>

          <form class="mt-6" @submit.prevent="connectEmail">
            <label class="mb-2 block text-xs uppercase tracking-[0.18em] text-slate-400">Email de compte</label>
            <div class="flex flex-col gap-3 sm:flex-row">
              <input
                v-model="emailInput"
                type="email"
                class="form-input"
                placeholder="votre@email.com"
                required
              />
              <button
                type="submit"
                class="rounded-full border border-white/20 bg-white/5 px-5 py-3 text-xs font-semibold uppercase tracking-[0.16em] text-white transition-colors hover:bg-white/10"
              >
                Mettre a jour
              </button>
            </div>
            <p class="mt-2 text-xs text-slate-500">
              Utilise le meme email que celui saisi lors du paiement Stripe.
            </p>
          </form>

          <div class="mt-8 grid gap-4 sm:grid-cols-2">
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
              <p class="text-xs uppercase tracking-[0.16em] text-slate-400">Plan detecte</p>
              <p class="mt-2 text-sm text-white">{{ planLabel }}</p>
            </div>
            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
              <p class="text-xs uppercase tracking-[0.16em] text-slate-400">Renouvellement</p>
              <p class="mt-2 text-sm text-white">{{ renewalLabel }}</p>
            </div>
          </div>

          <div class="mt-6 flex flex-wrap items-center gap-3">
            <button
              type="button"
              class="rounded-full border border-white/20 bg-white/5 px-5 py-3 text-xs font-semibold uppercase tracking-[0.16em] text-white transition-colors hover:bg-white/10"
              :disabled="loading || !activeEmail"
              :class="loading || !activeEmail ? 'cursor-not-allowed opacity-60' : ''"
              @click="refreshAccount"
            >
              {{ loading ? 'Actualisation...' : 'Actualiser le statut' }}
            </button>

            <button
              v-if="activeEmail"
              type="button"
              class="rounded-full border border-rose-300/25 bg-rose-400/10 px-5 py-3 text-xs font-semibold uppercase tracking-[0.16em] text-rose-100 transition-colors hover:bg-rose-400/20"
              @click="disconnect"
            >
              Se deconnecter
            </button>

            <NuxtLink
              to="/#pricing"
              class="rounded-full bg-[linear-gradient(135deg,#7c3aed_0%,#a855f7_50%,#d97706_100%)] px-5 py-3 text-xs font-semibold uppercase tracking-[0.16em] text-white"
            >
              Voir les offres
            </NuxtLink>
          </div>

          <p v-if="errorMessage" class="mt-4 text-sm text-rose-300">{{ errorMessage }}</p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
useSeoMeta({
  title: 'Mon compte — Stellara',
  description: 'Consultez votre statut premium et vos informations d abonnement Stellara.',
})

type ProfileResponse = {
  user: {
    id: string
    email: string
    firstName: string | null
  } | null
  subscription: {
    status: string | null
    currentPeriodEnd: string | null
  } | null
  plan: {
    planType: string | null
    billingInterval: string | null
  } | null
}

type SubscriptionStatusResponse = {
  isPremium: boolean
}

const reportStore = useReportStore()

const loading = ref(false)
const errorMessage = ref('')
const emailInput = ref('')
const profile = ref<ProfileResponse | null>(null)

const activeEmail = computed(() => reportStore.userEmail)

const planLabel = computed(() => {
  if (!activeEmail.value) return 'Aucun email renseigne'
  if (!profile.value?.plan) return reportStore.isPremium ? 'Premium actif' : 'Essentiel'

  const type = profile.value.plan.planType || 'premium'
  const interval = profile.value.plan.billingInterval

  if (!interval) return type
  if (interval === 'month') return `${type} - mensuel`
  if (interval === 'year') return `${type} - annuel`
  return `${type} - ${interval}`
})

const renewalLabel = computed(() => {
  const rawDate = profile.value?.subscription?.currentPeriodEnd
  if (!rawDate) return 'Non disponible'

  const date = new Date(rawDate)
  if (Number.isNaN(date.getTime())) return 'Non disponible'
  return date.toLocaleDateString('fr-FR')
})

async function refreshAccount() {
  const email = activeEmail.value
  if (!email) return

  loading.value = true
  errorMessage.value = ''

  try {
    const [profileData, statusData] = await Promise.all([
      $fetch<ProfileResponse>('/api/user/profile', { query: { email } }),
      $fetch<SubscriptionStatusResponse>('/api/user/subscription-status', { query: { email } }),
    ])

    profile.value = profileData
    reportStore.setPremiumStatus(Boolean(statusData?.isPremium))
  } catch (error) {
    console.error('[account] refresh failed:', error)
    errorMessage.value = 'Impossible de recuperer vos informations pour le moment.'
  } finally {
    loading.value = false
  }
}

async function connectEmail() {
  const normalized = emailInput.value.trim().toLowerCase()
  if (!normalized) return

  reportStore.setUserEmail(normalized)
  await refreshAccount()
}

function disconnect() {
  reportStore.clearSession()
  profile.value = null
  emailInput.value = ''
  errorMessage.value = ''
}

onMounted(async () => {
  reportStore.initFromStorage()
  emailInput.value = reportStore.userEmail

  if (reportStore.userEmail) {
    await refreshAccount()
  }
})
</script>
