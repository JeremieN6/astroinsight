<template>
  <div class="user-type-selector">
    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
      👤 Quel est votre profil ?
    </h4>
    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
      Sélectionnez votre profil pour adapter les questions à vos besoins spécifiques
    </p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- Option Freelance -->
      <div 
        class="user-type-option"
        :class="optionClasses('freelance')"
        @click="selectType('freelance')"
      >
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <div class="text-3xl mr-4">👨‍💻</div>
            <div>
              <h5 class="font-semibold text-gray-900 dark:text-white">
                Développeur / Freelance
              </h5>
              <p class="text-sm text-gray-600 dark:text-gray-400">
                Vous travaillez en indépendant
              </p>
            </div>
          </div>
          <div class="radio-indicator">
            <div 
              class="w-4 h-4 rounded-full border-2"
              :class="selectedType === 'freelance' 
                ? 'border-blue-500 bg-blue-500' 
                : 'border-gray-300 dark:border-gray-600'"
            >
              <div 
                v-if="selectedType === 'freelance'"
                class="w-2 h-2 bg-white rounded-full mx-auto mt-0.5"
              ></div>
            </div>
          </div>
        </div>
        
        <!-- Détails pour freelance -->
        <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
          <div class="flex flex-wrap gap-2">
            <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded">
              TJM
            </span>
            <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded">
              Rentabilité
            </span>
            <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded">
              Autonomie
            </span>
          </div>
        </div>
      </div>

      <!-- Option Entreprise -->
      <div 
        class="user-type-option"
        :class="optionClasses('entreprise')"
        @click="selectType('entreprise')"
      >
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <div class="text-3xl mr-4">🏢</div>
            <div>
              <h5 class="font-semibold text-gray-900 dark:text-white">
                Entreprise / Agence
              </h5>
              <p class="text-sm text-gray-600 dark:text-gray-400">
                Vous représentez une organisation
              </p>
            </div>
          </div>
          <div class="radio-indicator">
            <div 
              class="w-4 h-4 rounded-full border-2"
              :class="selectedType === 'entreprise' 
                ? 'border-blue-500 bg-blue-500' 
                : 'border-gray-300 dark:border-gray-600'"
            >
              <div 
                v-if="selectedType === 'entreprise'"
                class="w-2 h-2 bg-white rounded-full mx-auto mt-0.5"
              ></div>
            </div>
          </div>
        </div>
        
        <!-- Détails pour entreprise -->
        <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
          <div class="flex flex-wrap gap-2">
            <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded">
              Budget
            </span>
            <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded">
              Équipe
            </span>
            <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded">
              ROI
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Informations supplémentaires -->
    <div v-if="selectedType" class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
      <div class="flex items-start">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
            {{ selectedType === 'freelance' ? 'Profil Freelance sélectionné' : 'Profil Entreprise sélectionné' }}
          </h3>
          <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
            <p v-if="selectedType === 'freelance'">
              Les questions seront adaptées à votre activité d'indépendant : calcul de rentabilité, 
              gestion du temps, tarification au TJM, et objectifs personnels.
            </p>
            <p v-else>
              Les questions seront adaptées à votre contexte d'entreprise : gestion d'équipe, 
              budget global, méthodologie projet, et objectifs business.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserTypeSelector',
  props: {
    selectedType: {
      type: String,
      default: null
    }
  },
  emits: ['update:selected-type'],
  methods: {
    selectType(type) {
      this.$emit('update:selected-type', type);
    },
    
    optionClasses(type) {
      if (this.selectedType === type) {
        return 'user-type-option p-6 rounded-lg border-2 cursor-pointer transition-all duration-200 hover:shadow-md border-blue-500 bg-blue-50 dark:bg-blue-900/20 shadow-md ring-2 ring-blue-200 dark:ring-blue-800';
      } else {
        return 'user-type-option p-6 rounded-lg border-2 cursor-pointer transition-all duration-200 hover:shadow-md border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-600';
      }
    }
  }
}
</script>


