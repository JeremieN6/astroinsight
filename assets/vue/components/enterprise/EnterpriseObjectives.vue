<template>
  <div class="enterprise-objectives bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
    <!-- Header avec bouton toggle -->
    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
      <div class="flex items-center">
        <span class="text-indigo-500 text-2xl mr-3">🎯</span>
        <div>
          <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
            Section 5 : Objectifs business du projet
          </h4>
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Définissez les objectifs business pour adapter l'estimation aux enjeux
          </p>
        </div>
      </div>
      <button
        @click="toggleExpanded"
        class="flex items-center space-x-2 px-3 py-1 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors"
      >
        <span>{{ isExpanded ? 'Réduire' : 'Développer' }}</span>
        <svg
          :class="['w-4 h-4 transition-transform duration-200', isExpanded ? 'rotate-180' : '']"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
      </button>
    </div>

    <!-- Contenu repliable -->
    <div :class="['expand-transition', isExpanded ? 'expanded' : 'collapsed']">
      <div class="p-6">
        <div class="space-y-8">
          <!-- Introduction -->
          <div class="p-4 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg border border-indigo-200 dark:border-indigo-800">
            <p class="text-sm text-indigo-700 dark:text-indigo-300">
              💡 <strong>Pourquoi ces questions ?</strong> Les objectifs business influencent les choix techniques,
              la qualité attendue et les délais. Un MVP nécessite une approche différente d'un projet destiné
              à la production à grande échelle.
            </p>
          </div>

          <!-- Grid 50/50 pour Objectif + Contexte budgétaire -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Objectif du projet -->
            <div>
              <h5 class="text-md font-medium text-gray-900 dark:text-white mb-4">
                🚀 Quel est l'objectif de ce projet ?
              </h5>
              <div class="space-y-3">
                <label v-for="objective in projectObjectives" :key="objective.id" class="flex items-start">
                  <input
                    type="radio"
                    name="project-objective"
                    :value="objective.id"
                    v-model="localFormData.projectObjective"
                    @change="updateFormData"
                    class="w-4 h-4 mt-1 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                  >
                  <div class="ml-3">
                    <div class="flex items-center">
                      <span class="text-lg mr-2">{{ objective.icon }}</span>
                      <span class="font-medium text-gray-900 dark:text-white">{{ objective.name }}</span>
                      <span v-if="objective.impact"
                            :class="getImpactClass(objective.impact)"
                            class="ml-2 px-2 py-0.5 text-xs rounded-full">
                        {{ objective.impact }}
                      </span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ objective.description }}</p>
                    <p v-if="objective.implications" class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                      {{ objective.implications }}
                    </p>
                  </div>
                </label>
              </div>
            </div>

            <!-- Contexte budgétaire -->
            <div>
              <h5 class="text-md font-medium text-gray-900 dark:text-white mb-4">
                💰 Contexte budgétaire du projet
              </h5>
              <div class="space-y-4">
                <label class="flex items-start">
                  <input
                    type="radio"
                    name="budget-context"
                    value="defined"
                    v-model="localFormData.budgetContext"
                    @change="updateFormData"
                    class="w-4 h-4 mt-1 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                  >
                  <div class="ml-3 flex-1">
                    <span class="font-medium text-gray-900 dark:text-white">Budget défini</span>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                      Vous avez une enveloppe budgétaire précise
                    </p>
                  </div>
                </label>

                <div v-if="localFormData.budgetContext === 'defined'" class="ml-7 space-y-3">
                  <div class="flex items-center space-x-3">
                    <input
                      type="number"
                      v-model.number="localFormData.budgetAmount"
                      @input="updateFormData"
                      placeholder="50000"
                      min="1000"
                      step="1000"
                      class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                    >
                    <span class="text-sm text-gray-600 dark:text-gray-400">€ HT</span>
                  </div>

                  <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                      Flexibilité du budget
                    </label>
                    <select
                      v-model="localFormData.budgetFlexibility"
                      @change="updateFormData"
                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                    >
                      <option value="">Sélectionnez</option>
                      <option value="strict">Budget strict - Non négociable</option>
                      <option value="flexible">Budget flexible - Ajustable selon les besoins</option>
                      <option value="indicative">Budget indicatif - Ordre de grandeur</option>
                    </select>
                  </div>
                </div>

                <label class="flex items-start">
                  <input
                    type="radio"
                    name="budget-context"
                    value="estimate"
                    v-model="localFormData.budgetContext"
                    @change="updateFormData"
                    class="w-4 h-4 mt-1 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                  >
                  <div class="ml-3">
                    <span class="font-medium text-gray-900 dark:text-white">Recherche d'estimation</span>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                      Vous cherchez à connaître le coût pour budgéter
                    </p>
                  </div>
                </label>

                <label class="flex items-start">
                  <input
                    type="radio"
                    name="budget-context"
                    value="comparison"
                    v-model="localFormData.budgetContext"
                    @change="updateFormData"
                    class="w-4 h-4 mt-1 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                  >
                  <div class="ml-3">
                    <span class="font-medium text-gray-900 dark:text-white">Comparaison de devis</span>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                      Vous comparez plusieurs propositions
                    </p>
                  </div>
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EnterpriseObjectives',
  props: {
    formData: {
      type: Object,
      default: () => ({
        projectObjective: '',
        budgetContext: '',
        budgetAmount: null,
        budgetFlexibility: '',
        projectUrgency: '',
        specificConstraints: ''
      })
    }
  },
  emits: ['update:form-data'],
  data() {
    return {
      isExpanded: false,
      localFormData: {
        projectObjective: '',
        budgetContext: '',
        budgetAmount: null,
        budgetFlexibility: '',
        projectUrgency: '',
        specificConstraints: '',
        ...this.formData
      },
      projectObjectives: [
        {
          id: 'mvp',
          name: 'MVP pour tester un concept',
          description: 'Version minimale pour valider l\'idée et le marché',
          icon: '🧪',
          impact: 'Rapide',
          implications: 'Focus sur les fonctionnalités essentielles, qualité suffisante'
        },
        {
          id: 'production-scale',
          name: 'Projet destiné à la production à grande échelle',
          description: 'Application robuste pour un usage intensif',
          icon: '🏭',
          impact: 'Qualité',
          implications: 'Architecture scalable, tests approfondis, documentation complète'
        },
        {
          id: 'demo',
          name: 'Démonstrateur interne',
          description: 'Prototype pour présentation ou validation interne',
          icon: '🎨',
          impact: 'Visuel',
          implications: 'Focus sur l\'interface et l\'expérience utilisateur'
        },
        {
          id: 'v1-production',
          name: 'Version 1 en production',
          description: 'Première version fonctionnelle pour les utilisateurs finaux',
          icon: '🚀',
          impact: 'Équilibré',
          implications: 'Fonctionnalités complètes avec possibilité d\'évolution'
        }
      ]
    }
  },
  computed: {
    hasObjectiveData() {
      return this.localFormData.projectObjective ||
             this.localFormData.budgetContext ||
             this.localFormData.projectUrgency ||
             this.localFormData.specificConstraints;
    }
  },
  methods: {
    updateFormData() {
      this.$emit('update:form-data', { ...this.localFormData });
    },

    toggleExpanded() {
      this.isExpanded = !this.isExpanded;
    },

    getImpactClass(impact) {
      const classes = {
        'Rapide': 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200',
        'Qualité': 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
        'Visuel': 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-200',
        'Équilibré': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200'
      };
      return classes[impact] || '';
    },

    getProjectObjectiveLabel() {
      const objective = this.projectObjectives.find(o => o.id === this.localFormData.projectObjective);
      return objective ? objective.name : this.localFormData.projectObjective;
    },

    getBudgetContextLabel() {
      const labels = {
        'defined': 'Budget défini',
        'estimate': 'Recherche d\'estimation',
        'comparison': 'Comparaison de devis'
      };
      return labels[this.localFormData.budgetContext] || this.localFormData.budgetContext;
    },

    getBudgetFlexibilityLabel() {
      const labels = {
        'strict': 'Budget strict - Non négociable',
        'flexible': 'Budget flexible - Ajustable selon les besoins',
        'indicative': 'Budget indicatif - Ordre de grandeur'
      };
      return labels[this.localFormData.budgetFlexibility] || this.localFormData.budgetFlexibility;
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
