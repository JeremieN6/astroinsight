<template>
  <div class="freelance-basics bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 flex flex-col h-full">
    <div class="flex items-center mb-6">
      <span class="text-blue-500 text-2xl mr-3">📋</span>
      <div>
        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
          Section 1 : Informations de base
        </h4>
        <p class="text-sm text-gray-600 dark:text-gray-400">
          Décrivez les caractéristiques principales de votre projet
        </p>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 flex-grow">
      <!-- Type de projet -->
      <div>
        <label for="project-type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Type de projet *
        </label>
        <select 
          id="project-type"
          v-model="localFormData.projectType"
          @change="updateFormData"
          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
        >
          <option value="">Sélectionnez un type</option>
          <option value="site-vitrine">Site vitrine</option>
          <option value="saas">SaaS</option>
          <option value="e-commerce">E-commerce</option>
          <option value="api">API</option>
          <option value="app-mobile">App mobile</option>
          <option value="dashboard">Dashboard</option>
          <option value="autre">Autre</option>
        </select>
        
        <!-- Champ texte pour "Autre" -->
        <div v-if="localFormData.projectType === 'autre'" class="mt-3">
          <input 
            type="text"
            v-model="localFormData.customProjectType"
            @input="updateFormData"
            placeholder="Décrivez brièvement votre type de projet"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
          >
        </div>
      </div>

      <!-- Nombre de pages -->
      <div>
        <label for="page-count" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Nombre de pages/écrans *
        </label>
        <select 
          id="page-count"
          v-model="localFormData.pageCount"
          @change="updateFormData"
          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
        >
          <option value="">Sélectionnez une fourchette</option>
          <option value="1-5">1-5 pages</option>
          <option value="6-10">6-10 pages</option>
          <option value="11-20">11-20 pages</option>
          <option value="21-50">21-50 pages</option>
          <option value="50+">Plus de 50 pages</option>
        </select>
      </div>

      <!-- Technologies -->
      <div class="md:col-span-2">
        <label for="technologies" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Technologies souhaitées
        </label>
        <textarea 
          id="technologies"
          v-model="localFormData.technologies"
          @input="updateFormData"
          placeholder="Ex: React, Node.js, PostgreSQL, AWS..."
          rows="3"
          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
        ></textarea>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
          Listez les technologies que vous maîtrisez ou que le client souhaite utiliser
        </p>
      </div>

      <!-- Projet existant -->
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Type de projet
        </label>
        <div class="space-y-2">
          <label class="flex items-center">
            <input 
              type="radio" 
              :value="false"
              v-model="localFormData.isExistingProject"
              @change="updateFormData"
              class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600"
            >
            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Nouveau projet</span>
          </label>
          <label class="flex items-center">
            <input 
              type="radio" 
              :value="true"
              v-model="localFormData.isExistingProject"
              @change="updateFormData"
              class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600"
            >
            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Refonte/évolution</span>
          </label>
        </div>
      </div>

      <!-- Délai -->
      <div>
        <label for="deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Délai souhaité (en jours)
        </label>
        <input 
          type="number"
          id="deadline"
          v-model.number="localFormData.deadlineDays"
          @input="updateFormData"
          placeholder="Ex: 30"
          min="1"
          max="365"
          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
        >
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
          Nombre de jours calendaires pour livrer le projet
        </p>
      </div>
    </div>

    <!-- Indicateur de progression -->
    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between text-sm">
        <span class="text-gray-600 dark:text-gray-400">
          Section {{ hasData ? 'complétée' : 'en cours' }}
        </span>
        <div class="flex items-center">
          <div class="w-2 h-2 rounded-full mr-2" :class="hasData ? 'bg-green-500' : 'bg-gray-300'"></div>
          <span class="text-xs text-gray-500 dark:text-gray-400">
            {{ hasData ? '✓' : '○' }} Informations de base
          </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FreelanceBasics',
  props: {
    formData: {
      type: Object,
      default: () => ({
        projectType: '',
        customProjectType: '',
        pageCount: '',
        technologies: '',
        isExistingProject: null,
        deadlineDays: null
      })
    }
  },
  emits: ['update:form-data'],
  data() {
    return {
      localFormData: { ...this.formData }
    }
  },
  computed: {
    hasData() {
      return this.localFormData.projectType || 
             this.localFormData.pageCount || 
             this.localFormData.technologies ||
             this.localFormData.isExistingProject !== null ||
             this.localFormData.deadlineDays;
    }
  },
  methods: {
    updateFormData() {
      this.$emit('update:form-data', { ...this.localFormData });
    },
    
    getProjectTypeLabel() {
      const types = {
        'site-vitrine': 'Site vitrine',
        'saas': 'SaaS',
        'e-commerce': 'E-commerce',
        'api': 'API',
        'app-mobile': 'App mobile',
        'dashboard': 'Dashboard',
        'autre': this.localFormData.customProjectType || 'Autre'
      };
      return types[this.localFormData.projectType] || this.localFormData.projectType;
    }
  },
  watch: {
    formData: {
      handler(newData) {
        this.localFormData = { ...this.localFormData, ...newData };
      },
      deep: true
    }
  }
}
</script>

<style scoped>
/* Styles spécifiques au composant si nécessaire */
</style>
