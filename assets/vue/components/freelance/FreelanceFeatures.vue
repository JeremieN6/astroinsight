<template>
  <div class="freelance-features bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
    <!-- Header avec bouton toggle -->
    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
      <div class="flex items-center">
        <span class="text-purple-500 text-2xl mr-3">🔧</span>
        <div>
          <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
            Section 3 : Fonctionnalités additionnelles
          </h4>
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Sélectionnez les fonctionnalités spécifiques à intégrer dans votre projet
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
        <div class="space-y-6">
          <!-- Introduction -->
          <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
            <p class="text-sm text-blue-700 dark:text-blue-300">
              💡 <strong>Conseil :</strong> Chaque fonctionnalité ajoutera du temps et de la complexité à votre projet.
              Soyez réaliste dans vos sélections selon le budget et les délais.
            </p>
          </div>

          <!-- Grille des fonctionnalités -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div v-for="feature in availableFeatures" :key="feature.id"
                 class="feature-item p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
              <label class="flex items-start cursor-pointer">
                <input
                  type="checkbox"
                  :value="feature.id"
                  v-model="localFormData.selectedFeatures"
                  @change="updateFormData"
                  class="w-4 h-4 mt-1 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <div class="ml-3 flex-1">
                  <div class="flex items-center">
                    <span class="text-lg mr-2">{{ feature.icon }}</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ feature.name }}</span>
                    <span v-if="feature.complexity"
                          :class="getComplexityClass(feature.complexity)"
                          class="ml-2 px-2 py-0.5 text-xs rounded-full">
                      {{ feature.complexity }}
                    </span>
                  </div>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ feature.description }}</p>
                  <p v-if="feature.timeEstimate" class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                    ⏱️ Estimation : {{ feature.timeEstimate }}
                  </p>
                </div>
              </label>
            </div>
          </div>

          <!-- Fonctionnalités personnalisées -->
          <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <h5 class="text-md font-medium text-gray-900 dark:text-white mb-3">
              ➕ Fonctionnalités personnalisées
            </h5>
            <div class="space-y-3">
              <div v-for="(customFeature, index) in localFormData.customFeatures" :key="index"
                   class="flex items-center space-x-3">
                <input
                  type="text"
                  v-model="customFeature.name"
                  @input="updateFormData"
                  placeholder="Nom de la fonctionnalité"
                  class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                >
                <select
                  v-model="customFeature.complexity"
                  @change="updateFormData"
                  class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                >
                  <option value="">Complexité</option>
                  <option value="Simple">Simple</option>
                  <option value="Modéré">Modéré</option>
                  <option value="Complexe">Complexe</option>
                </select>
                <button
                  @click="removeCustomFeature(index)"
                  type="button"
                  class="p-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                >
                  🗑️
                </button>
              </div>

              <button
                @click="addCustomFeature"
                class="flex items-center px-4 py-2 text-sm text-blue-600 dark:text-blue-400 border border-blue-300 dark:border-blue-600 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors"
              >
                ➕ Ajouter une fonctionnalité personnalisée
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Résumé des fonctionnalités sélectionnées -->
    <div v-if="selectedFeaturesCount > 0" class="px-4 pb-4">
      <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
        <p class="text-sm text-gray-700 dark:text-gray-300">
          <strong>{{ selectedFeaturesCount }} fonctionnalité{{ selectedFeaturesCount > 1 ? 's' : '' }} sélectionnée{{ selectedFeaturesCount > 1 ? 's' : '' }}</strong>
          - Complexité globale : <span :class="getComplexityClass(getOverallComplexity())">{{ getOverallComplexity() }}</span>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FreelanceFeatures',
  props: {
    formData: {
      type: Object,
      default: () => ({
        selectedFeatures: [],
        customFeatures: []
      })
    }
  },
  emits: ['update:form-data'],
  data() {
    return {
      isExpanded: false,
      localFormData: {
        selectedFeatures: [],
        customFeatures: [],
        ...this.formData
      },
      availableFeatures: [
        {
          id: 'auth',
          name: 'Authentification / Espace membre',
          description: 'Système de connexion, inscription, gestion des comptes utilisateurs',
          icon: '🔐',
          complexity: 'Modéré',
          timeEstimate: '3-5 jours'
        },
        {
          id: 'dashboard',
          name: 'Tableau de bord / back-office',
          description: 'Interface d\'administration pour gérer le contenu et les utilisateurs',
          icon: '📊',
          complexity: 'Modéré',
          timeEstimate: '4-7 jours'
        },
        {
          id: 'api',
          name: 'Intégration API externe',
          description: 'Connexion avec des services tiers (Stripe, Google Maps, etc.)',
          icon: '🔌',
          complexity: 'Modéré',
          timeEstimate: '2-4 jours'
        },
        {
          id: 'payment',
          name: 'Paiement en ligne',
          description: 'Intégration de solutions de paiement (Stripe, PayPal, etc.)',
          icon: '💳',
          complexity: 'Complexe',
          timeEstimate: '3-6 jours'
        },
        {
          id: 'search',
          name: 'Système de recherche / filtres',
          description: 'Fonctionnalités de recherche avancée et filtrage des données',
          icon: '🔍',
          complexity: 'Modéré',
          timeEstimate: '2-4 jours'
        },
        {
          id: 'messaging',
          name: 'Messagerie / notifications',
          description: 'Système de messages internes et notifications push/email',
          icon: '💬',
          complexity: 'Complexe',
          timeEstimate: '4-8 jours'
        },
        {
          id: 'crud',
          name: 'Admin CRUD complet',
          description: 'Interface complète de création, lecture, mise à jour et suppression',
          icon: '⚙️',
          complexity: 'Modéré',
          timeEstimate: '3-5 jours'
        },
        {
          id: 'cms',
          name: 'CMS (contenu dynamique, blog)',
          description: 'Système de gestion de contenu pour articles, pages, médias',
          icon: '📝',
          complexity: 'Complexe',
          timeEstimate: '5-10 jours'
        },
        {
          id: 'responsive',
          name: 'Responsive mobile',
          description: 'Adaptation complète pour tablettes et smartphones',
          icon: '📱',
          complexity: 'Simple',
          timeEstimate: '2-3 jours'
        },
        {
          id: 'tests',
          name: 'Tests automatisés',
          description: 'Mise en place de tests unitaires et d\'intégration',
          icon: '🧪',
          complexity: 'Modéré',
          timeEstimate: '3-5 jours'
        },
        {
          id: 'maintenance',
          name: 'Maintenance / évolutivité',
          description: 'Documentation et architecture pour faciliter la maintenance future',
          icon: '🔧',
          complexity: 'Simple',
          timeEstimate: '1-2 jours'
        }
      ]
    }
  },
  computed: {
    selectedFeaturesCount() {
      return this.localFormData.selectedFeatures.length +
             this.localFormData.customFeatures.filter(f => f.name).length;
    }
  },
  methods: {
    updateFormData() {
      this.$emit('update:form-data', { ...this.localFormData });
    },

    toggleExpanded() {
      this.isExpanded = !this.isExpanded;
    },

    getComplexityClass(complexity) {
      const classes = {
        'Simple': 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200',
        'Modéré': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200',
        'Complexe': 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200'
      };
      return classes[complexity] || '';
    },

    getFeatureName(featureId) {
      const feature = this.availableFeatures.find(f => f.id === featureId);
      return feature ? feature.name : featureId;
    },

    getOverallComplexity() {
      const selectedFeatures = this.availableFeatures.filter(f =>
        this.localFormData.selectedFeatures.includes(f.id)
      );
      const customFeatures = this.localFormData.customFeatures.filter(f => f.name);

      const allFeatures = [...selectedFeatures, ...customFeatures];

      if (allFeatures.length === 0) return 'Simple';

      const complexityScores = {
        'Simple': 1,
        'Modéré': 2,
        'Complexe': 3
      };

      const totalScore = allFeatures.reduce((sum, feature) => {
        const complexity = feature.complexity || 'Simple';
        return sum + (complexityScores[complexity] || 1);
      }, 0);

      const averageScore = totalScore / allFeatures.length;

      if (averageScore <= 1.3) return 'Simple';
      if (averageScore <= 2.3) return 'Modéré';
      return 'Complexe';
    },

    addCustomFeature() {
      this.localFormData.customFeatures.push({
        name: '',
        complexity: ''
      });
      this.updateFormData();
    },

    removeCustomFeature(index) {
      this.localFormData.customFeatures.splice(index, 1);
      this.updateFormData();
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
