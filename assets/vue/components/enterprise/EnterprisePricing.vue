<template>
  <div class="enterprise-pricing bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
    <!-- Header avec bouton toggle -->
    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
      <div class="flex items-center">
        <span class="text-emerald-500 text-2xl mr-3">💰</span>
        <div>
          <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
            Section 6 : Coûts et tarification
          </h4>
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Définissez la structure de coûts et le modèle de tarification
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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Coût journalier par profil -->
          <div>
            <h5 class="text-md font-medium text-gray-900 dark:text-white mb-4">
              👥 Estimation du coût journalier par profil
            </h5>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
              Avez-vous une estimation du coût journalier moyen par profil ?
            </p>

            <div class="space-y-4">
              <label class="flex items-center">
                <input
                  type="checkbox"
                  v-model="localFormData.hasDailyCosts"
                  @change="updateFormData"
                  class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                  Oui, j'ai des estimations de coûts journaliers
                </span>
              </label>

              <div v-if="localFormData.hasDailyCosts" class="ml-7 space-y-4">
                <div v-for="(cost, index) in localFormData.dailyCosts" :key="index"
                     class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                  <select
                    v-model="cost.profile"
                    @change="updateFormData"
                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white"
                  >
                    <option value="">Type de profil</option>
                    <option value="dev-junior">Développeur Junior</option>
                    <option value="dev-senior">Développeur Senior</option>
                    <option value="lead-dev">Lead Developer</option>
                    <option value="architect">Architecte</option>
                    <option value="devops">DevOps</option>
                    <option value="designer">Designer</option>
                    <option value="po">Product Owner</option>
                    <option value="chef-projet">Chef de projet</option>
                  </select>

                  <div class="flex items-center space-x-2">
                    <input
                      type="number"
                      v-model.number="cost.dailyRate"
                      @input="updateFormData"
                      placeholder="500"
                      min="100"
                      max="2000"
                      step="50"
                      class="w-24 px-2 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white"
                    >
                    <span class="text-sm text-gray-600 dark:text-gray-400">€/j</span>
                  </div>

                  <button
                    @click="removeDailyCost(index)"
                    type="button"
                    class="p-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                  >
                    🗑️
                  </button>
                </div>

                <button
                  @click="addDailyCost"
                  class="flex items-center px-4 py-2 text-sm text-blue-600 dark:text-blue-400 border border-blue-300 dark:border-blue-600 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors"
                >
                  ➕ Ajouter un profil
                </button>
              </div>
            </div>
          </div>

          <!-- Modèle de tarification -->
          <div>
            <h5 class="text-md font-medium text-gray-900 dark:text-white mb-4">
              📊 Modèle de tarification souhaité
            </h5>
            <div class="space-y-3">
              <label v-for="model in pricingModels" :key="model.id" class="flex items-start">
                <input
                  type="radio"
                  name="pricing-model"
                  :value="model.id"
                  v-model="localFormData.pricingModel"
                  @change="updateFormData"
                  class="w-4 h-4 mt-1 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <div class="ml-3">
                  <div class="flex items-center">
                    <span class="text-lg mr-2">{{ model.icon }}</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ model.name }}</span>
                  </div>
                  <p class="text-sm text-gray-600 dark:text-gray-400">{{ model.description }}</p>
                  <p v-if="model.pros" class="text-xs text-green-600 dark:text-green-400 mt-1">
                    ✓ {{ model.pros }}
                  </p>
                  <p v-if="model.cons" class="text-xs text-orange-600 dark:text-orange-400">
                    ⚠️ {{ model.cons }}
                  </p>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- Marge et facteurs de risque -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Marge de sécurité -->
          <div>
            <h5 class="text-md font-medium text-gray-900 dark:text-white mb-3">
              🛡️ Marge de sécurité
            </h5>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
              Quelle marge appliquer pour gérer les aléas ?
            </p>
            <select
              v-model="localFormData.securityMargin"
              @change="updateFormData"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            >
              <option value="">Sélectionnez une marge</option>
              <option value="0">0% - Pas de marge</option>
              <option value="10">10% - Marge faible</option>
              <option value="15">15% - Marge standard</option>
              <option value="20">20% - Marge élevée</option>
              <option value="30">30% - Marge très élevée</option>
            </select>

            <div v-if="localFormData.securityMargin" class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
              <p class="text-sm text-blue-700 dark:text-blue-300">
                💡 Avec {{ localFormData.securityMargin }}% de marge, un projet estimé à 100 jours sera facturé
                {{ Math.round(100 * (1 + localFormData.securityMargin / 100)) }} jours.
              </p>
            </div>
          </div>

          <!-- Facteurs de risque -->
          <div>
            <h5 class="text-md font-medium text-gray-900 dark:text-white mb-3">
              ⚠️ Facteurs de risque identifiés
            </h5>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
              Quels risques pourraient impacter le projet ?
            </p>
            <div class="space-y-2">
              <label v-for="risk in riskFactors" :key="risk.id" class="flex items-start">
                <input
                  type="checkbox"
                  :value="risk.id"
                  v-model="localFormData.selectedRisks"
                  @change="updateFormData"
                  class="w-4 h-4 mt-1 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <div class="ml-3">
                  <span class="text-sm font-medium text-gray-900 dark:text-white">{{ risk.name }}</span>
                  <p class="text-xs text-gray-600 dark:text-gray-400">{{ risk.description }}</p>
                </div>
              </label>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EnterprisePricing',
  props: {
    formData: {
      type: Object,
      default: () => ({
        hasDailyCosts: false,
        dailyCosts: [{ profile: '', dailyRate: null }],
        pricingModel: '',
        securityMargin: '',
        selectedRisks: []
      })
    }
  },
  emits: ['update:form-data'],
  data() {
    return {
      isExpanded: false,
      localFormData: {
        hasDailyCosts: false,
        dailyCosts: [{ profile: '', dailyRate: null }],
        pricingModel: '',
        securityMargin: '',
        selectedRisks: [],
        ...this.formData
      },
      pricingModels: [
        {
          id: 'fixed-price',
          name: 'Forfait',
          description: 'Prix fixe défini à l\'avance pour l\'ensemble du projet',
          icon: '📦',
          pros: 'Budget prévisible pour le client',
          cons: 'Risque de dépassement pour le prestataire'
        },
        {
          id: 'time-material',
          name: 'Régie',
          description: 'Facturation au temps passé (jours/homme)',
          icon: '⏱️',
          pros: 'Flexibilité et transparence',
          cons: 'Budget moins prévisible pour le client'
        },
        {
          id: 'mixed',
          name: 'Mix selon projets',
          description: 'Adaptation du modèle selon le type et la taille du projet',
          icon: '🔄',
          pros: 'Approche adaptée à chaque contexte',
          cons: 'Complexité de gestion'
        }
      ],
      riskFactors: [
        {
          id: 'unclear-specs',
          name: 'Spécifications floues',
          description: 'Cahier des charges incomplet ou ambigu'
        },
        {
          id: 'changing-requirements',
          name: 'Évolution des besoins',
          description: 'Risque de changements fréquents en cours de projet'
        },
        {
          id: 'technical-complexity',
          name: 'Complexité technique élevée',
          description: 'Technologies nouvelles ou architecture complexe'
        },
        {
          id: 'integration-challenges',
          name: 'Défis d\'intégration',
          description: 'Connexions avec systèmes existants complexes'
        },
        {
          id: 'team-availability',
          name: 'Disponibilité équipe',
          description: 'Risque de manque de ressources ou compétences'
        },
        {
          id: 'client-responsiveness',
          name: 'Réactivité client',
          description: 'Délais de validation ou feedback du client'
        }
      ]
    }
  },
  computed: {
    hasPricingData() {
      return this.localFormData.hasDailyCosts ||
             this.localFormData.pricingModel ||
             this.localFormData.securityMargin ||
             this.localFormData.selectedRisks.length > 0;
    }
  },
  methods: {
    updateFormData() {
      this.$emit('update:form-data', { ...this.localFormData });
    },

    toggleExpanded() {
      this.isExpanded = !this.isExpanded;
    },

    addDailyCost() {
      this.localFormData.dailyCosts.push({ profile: '', dailyRate: null });
      this.updateFormData();
    },

    removeDailyCost(index) {
      this.localFormData.dailyCosts.splice(index, 1);
      this.updateFormData();
    },

    getValidDailyCosts() {
      return this.localFormData.dailyCosts.filter(cost =>
        cost.profile && cost.dailyRate && parseInt(cost.dailyRate) > 0
      );
    },

    getDailyCostLabel(cost) {
      const labels = {
        'dev-junior': 'Dev Junior',
        'dev-senior': 'Dev Senior',
        'lead-dev': 'Lead Dev',
        'architect': 'Architecte',
        'devops': 'DevOps',
        'designer': 'Designer',
        'po': 'Product Owner',
        'chef-projet': 'Chef de projet'
      };
      return labels[cost.profile] || cost.profile;
    },

    getPricingModelLabel() {
      const model = this.pricingModels.find(m => m.id === this.localFormData.pricingModel);
      return model ? model.name : this.localFormData.pricingModel;
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
