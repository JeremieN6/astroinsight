<template>
  <section class="min-h-screen py-16 sm:py-20">
    <div class="section-shell">
      <div class="mx-auto max-w-2xl">

        <!-- Step indicator -->
        <div class="mb-10 flex items-center justify-center gap-2">
          <div v-for="(s, i) in 3" :key="i" class="flex items-center gap-2">
            <div
              class="flex h-8 w-8 items-center justify-center rounded-full border text-xs font-semibold transition-all duration-300"
              :class="step > i ? 'border-amber-400 bg-amber-400/20 text-amber-400' : step === i ? 'border-amber-400/60 bg-amber-400/10 text-amber-400' : 'border-white/10 bg-white/5 text-slate-500'"
            >
              <svg v-if="step > i" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
              </svg>
              <span v-else>{{ i + 1 }}</span>
            </div>
            <div v-if="i < 2" class="h-px w-8 sm:w-12 transition-all duration-300" :class="step > i ? 'bg-amber-400/40' : 'bg-white/10'" />
          </div>
        </div>

        <!-- STEP 0 — Form -->
        <Transition name="fade-up" mode="out-in">
          <div v-if="step === 0" key="form" class="glass-panel p-7 sm:p-10">
            <h1 class="font-display text-2xl sm:text-3xl font-semibold text-white mb-2">
              Votre thème natal
            </h1>
            <p class="text-slate-400 text-sm mb-8">Renseignez vos informations pour calculer votre thème natal complet.</p>

            <form class="space-y-5" @submit.prevent="calculate">
              <!-- Name -->
              <div>
                <label class="block text-xs text-slate-400 mb-1.5 tracking-[0.15em] uppercase">Prénom</label>
                <input
                  v-model="form.firstName"
                  type="text"
                  class="form-input"
                  placeholder="Votre prénom"
                  required
                />
              </div>

              <!-- Birth date + time -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-xs text-slate-400 mb-1.5 tracking-[0.15em] uppercase">Date de naissance</label>
                  <input
                    v-model="form.birthDate"
                    type="date"
                    class="form-input"
                    required
                  />
                </div>
                <div>
                  <label class="block text-xs text-slate-400 mb-1.5 tracking-[0.15em] uppercase">Heure (optionnel)</label>
                  <input
                    v-model="form.birthTime"
                    type="time"
                    class="form-input"
                  />
                </div>
              </div>

              <!-- City autocomplete -->
              <div class="relative">
                <label class="block text-xs text-slate-400 mb-1.5 tracking-[0.15em] uppercase">Lieu de naissance</label>
                <input
                  v-model="cityQuery"
                  type="text"
                  class="form-input pr-10"
                  placeholder="Ville, pays..."
                  autocomplete="off"
                  @input="debouncedGeoSearch"
                />
                <div v-if="geoLoading" class="absolute right-3 top-[38px]">
                  <svg class="animate-spin h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                  </svg>
                </div>

                <!-- Dropdown -->
                <ul v-if="geoResults.length > 0" class="absolute z-20 mt-1 w-full rounded-2xl border border-white/10 bg-[rgba(10,10,26,0.95)] backdrop-blur-xl overflow-hidden shadow-[0_8px_32px_rgba(10,10,26,0.6)]">
                  <li
                    v-for="r in geoResults"
                    :key="r.place_id"
                    class="cursor-pointer px-4 py-3 text-sm text-slate-300 hover:bg-white/10 hover:text-white transition-colors"
                    @click="selectCity(r)"
                  >
                    {{ r.display_name }}
                  </li>
                </ul>
              </div>

              <!-- Gender -->
              <div>
                <label class="block text-xs text-slate-400 mb-2 tracking-[0.15em] uppercase">Genre</label>
                <div class="flex gap-3">
                  <label
                    v-for="g in genders"
                    :key="g.value"
                    class="flex flex-1 cursor-pointer items-center gap-3 rounded-2xl border p-3 transition-all duration-200"
                    :class="form.gender === g.value ? 'border-amber-400/40 bg-amber-400/10' : 'border-white/10 bg-white/5 hover:bg-white/10'"
                  >
                    <input v-model="form.gender" type="radio" :value="g.value" class="sr-only" />
                    <span class="text-lg">{{ g.icon }}</span>
                    <span class="text-sm text-slate-300">{{ g.label }}</span>
                  </label>
                </div>
              </div>

              <!-- Email (optional for premium) -->
              <div>
                <label class="block text-xs text-slate-400 mb-1.5 tracking-[0.15em] uppercase">
                  Email <span class="text-slate-600 normal-case">(pour recevoir votre rapport)</span>
                </label>
                <input
                  v-model="form.email"
                  type="email"
                  class="form-input"
                  placeholder="votre@email.com"
                />
              </div>

              <!-- Submit -->
              <button
                type="submit"
                class="cta-button w-full justify-center mt-4"
                :disabled="calculating"
                :class="calculating ? 'opacity-60 cursor-not-allowed' : ''"
              >
                <svg v-if="calculating" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                </svg>
                <span>{{ calculating ? 'Calcul en cours…' : 'Calculer mon thème natal' }}</span>
              </button>
            </form>
          </div>
        </Transition>

        <!-- STEP 1 — Calculating -->
        <Transition name="fade-up" mode="out-in">
          <div v-if="step === 1" key="loading" class="glass-panel p-12 text-center">
            <div class="mb-6 text-6xl animate-drift">✦</div>
            <h2 class="font-display text-2xl font-semibold text-white mb-3">Calcul en cours…</h2>
            <p class="text-slate-400 text-sm">Nous calculons vos positions planétaires et générons votre analyse.</p>
            <div class="mt-8 flex justify-center gap-1">
              <span v-for="i in 3" :key="i" class="h-2 w-2 rounded-full bg-amber-400/60 animate-pulse" :style="`animation-delay: ${i * 200}ms`" />
            </div>
          </div>
        </Transition>

        <!-- STEP 2 — Report -->
        <Transition name="fade-up" mode="out-in">
          <div v-if="step === 2 && reportStore.reportData" key="report">
            <ReportDisplay :report="reportStore.reportData" :is-premium="reportStore.isPremium" />
          </div>
        </Transition>

      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import ReportDisplay from '~/components/ReportDisplay.vue'

useSeoMeta({
  title: 'Mon Thème Natal — AstroInsights',
  description: 'Calculez votre thème natal complet avec signe solaire, lunaire, ascendant et 10 planètes analysés par IA.',
})

const reportStore = useReportStore()
const step = ref(0)
const calculating = ref(false)

const form = reactive({
  firstName: '',
  birthDate: '',
  birthTime: '',
  gender: 'other',
  email: '',
  lat: null as number | null,
  lon: null as number | null,
  city: '',
})

const cityQuery = ref('')
const geoResults = ref<Array<{ place_id: string; display_name: string; lat: string; lon: string }>>([])
const geoLoading = ref(false)
let geoTimer: ReturnType<typeof setTimeout> | null = null

const genders = [
  { value: 'female', icon: '♀', label: 'Féminin' },
  { value: 'male', icon: '♂', label: 'Masculin' },
  { value: 'other', icon: '✦', label: 'Autre' },
]

function debouncedGeoSearch() {
  if (geoTimer) clearTimeout(geoTimer)
  geoTimer = setTimeout(geoSearch, 350)
}

async function geoSearch() {
  const q = cityQuery.value.trim()
  if (q.length < 2) { geoResults.value = []; return }
  geoLoading.value = true
  try {
    const res = await fetch(`https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(q)}&format=json&limit=5&addressdetails=0`, {
      headers: { 'Accept-Language': 'fr' },
    })
    geoResults.value = await res.json()
  } catch {
    geoResults.value = []
  } finally {
    geoLoading.value = false
  }
}

function selectCity(r: { place_id: string; display_name: string; lat: string; lon: string }) {
  form.lat = parseFloat(r.lat)
  form.lon = parseFloat(r.lon)
  form.city = r.display_name
  cityQuery.value = r.display_name
  geoResults.value = []
}

async function calculate() {
  if (!form.lat || !form.lon) {
    alert('Veuillez sélectionner votre lieu de naissance dans la liste.')
    return
  }

  calculating.value = true
  step.value = 1

  try {
    const res = await $fetch('/api/generate-report', {
      method: 'POST',
      body: {
        firstName: form.firstName,
        birthDate: form.birthDate,
        birthTime: form.birthTime || '12:00',
        lat: form.lat,
        lon: form.lon,
        city: form.city,
        gender: form.gender,
        email: form.email,
      },
    })

    reportStore.setReportData(res as Record<string, unknown>)
    if (form.email) reportStore.setUserEmail(form.email)
    step.value = 2
  } catch (err) {
    console.error(err)
    step.value = 0
    alert('Une erreur s\'est produite. Veuillez réessayer.')
  } finally {
    calculating.value = false
  }
}
</script>

<style scoped>
.fade-up-enter-active,
.fade-up-leave-active {
  transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
}
.fade-up-enter-from {
  opacity: 0;
  transform: translateY(1.5rem);
}
.fade-up-leave-to {
  opacity: 0;
  transform: translateY(-0.5rem);
}
</style>
