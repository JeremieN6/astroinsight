<template>
  <div class="freelance-deliverables bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
    <!-- Header avec bouton toggle -->
    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
      <div class="flex items-center">
        <span class="text-green-500 text-2xl mr-3">📦</span>
        <div>
          <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
            Section 4 : Livrables & périmètre attendu
          </h4>
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Définissez ce qui est inclus dans votre prestation et les responsabilités
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
          <!-- Périmètre de développement -->
          <div>
            <h5 class="text-md font-medium text-gray-900 dark:text-white mb-4">
              🎨 Périmètre de votre intervention
            </h5>
            <div class="space-y-3">
              <label class="flex items-start">
                <input
                  type="radio"
                  name="scope"
                  value="dev-only"
                  v-model="localFormData.scope"
                  @change="updateFormData"
                  class="w-4 h-4 mt-1 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <div class="ml-3">
                  <span class="font-medium text-gray-900 dark:text-white">Développement seul</span>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Je me concentre uniquement sur le code et l'intégration technique
                  </p>
                </div>
              </label>

              <label class="flex items-start">
                <input
                  type="radio"
                  name="scope"
                  value="dev-ui"
                  v-model="localFormData.scope"
                  @change="updateFormData"
                  class="w-4 h-4 mt-1 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <div class="ml-3">
                  <span class="font-medium text-gray-900 dark:text-white">Développement + UI</span>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Je gère le code et la création de l'interface utilisateur
                  </p>
                </div>
              </label>

              <label class="flex items-start">
                <input
                  type="radio"
                  name="scope"
                  value="dev-ui-ux"
                  v-model="localFormData.scope"
                  @change="updateFormData"
                  class="w-4 h-4 mt-1 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <div class="ml-3">
                  <span class="font-medium text-gray-900 dark:text-white">Développement + UI + UX</span>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Je prends en charge l'expérience utilisateur complète, du wireframe au code
                  </p>
                </div>
              </label>
            </div>
          </div>

          <!-- Maquettes et spécifications -->
          <div>
            <h5 class="text-md font-medium text-gray-900 dark:text-white mb-4">
              📋 Maquettes et spécifications
            </h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                  Le client fournit-il des maquettes ?
                </label>
                <div class="space-y-2">
                  <label class="flex items-center">
                    <input
                      type="radio"
                      name="mockups"
                      value="provided"
                      v-model="localFormData.mockupsProvided"
                      @change="updateFormData"
                      class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    >
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                      Oui, maquettes fournies
                    </span>
                  </label>
                  <label class="flex items-center">
                    <input
                      type="radio"
                      name="mockups"
                      value="to-create"
                      v-model="localFormData.mockupsProvided"
                      @change="updateFormData"
                      class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    >
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                      Non, à concevoir
                    </span>
                  </label>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                  Spécifications fonctionnelles ?
                </label>
                <div class="space-y-2">
                  <label class="flex items-center">
                    <input
                      type="radio"
                      name="specs"
                      value="provided"
                      v-model="localFormData.specsProvided"
                      @change="updateFormData"
                      class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    >
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                      Specs détaillées fournies
                    </span>
                  </label>
                  <label class="flex items-center">
                    <input
                      type="radio"
                      name="specs"
                      value="to-define"
                      v-model="localFormData.specsProvided"
                      @change="updateFormData"
                      class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    >
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                      À définir ensemble
                    </span>
                  </label>
                </div>
              </div>
            </div>
          </div>
          <!-- Communication et suivi -->
          <div>
            <h5 class="text-md font-medium text-gray-900 dark:text-white mb-4">
              💬 Communication et suivi projet
            </h5>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
              Y a-t-il des réunions ou échanges fréquents prévus ?
            </label>
            <div class="space-y-2">
              <label class="flex items-center">
                <input
                  type="radio"
                  name="meetings"
                  value="frequent"
                  v-model="localFormData.frequentMeetings"
                  @change="updateFormData"
                  class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                  Oui, suivi régulier prévu (réunions hebdomadaires, points quotidiens...)
                </span>
              </label>
              <label class="flex items-center">
                <input
                  type="radio"
                  name="meetings"
                  value="minimal"
                  v-model="localFormData.frequentMeetings"
                  @change="updateFormData"
                  class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                  Non, communication minimale (points d'étape uniquement)
                </span>
              </label>
              <label class="flex items-center">
                <input
                  type="radio"
                  name="meetings"
                  value="unknown"
                  v-model="localFormData.frequentMeetings"
                  @change="updateFormData"
                  class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                  Je ne sais pas encore
                </span>
              </label>
            </div>
          </div>

          <!-- Services additionnels -->
          <div>
            <h5 class="text-md font-medium text-gray-900 dark:text-white mb-4">
              🚀 Services additionnels
            </h5>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
              Gérez-vous aussi l'hébergement, le déploiement et la maintenance ?
            </label>
            <div class="space-y-3">
              <label class="flex items-start">
                <input
                  type="checkbox"
                  value="hosting"
                  v-model="localFormData.additionalServices"
                  @change="updateFormData"
                  class="w-4 h-4 mt-1 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <div class="ml-3">
                  <span class="font-medium text-gray-900 dark:text-white">Hébergement</span>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Configuration et gestion de l'hébergement web
                  </p>
                </div>
              </label>

              <label class="flex items-start">
                <input
                  type="checkbox"
                  value="deployment"
                  v-model="localFormData.additionalServices"
                  @change="updateFormData"
                  class="w-4 h-4 mt-1 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <div class="ml-3">
                  <span class="font-medium text-gray-900 dark:text-white">Déploiement</span>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Mise en production et configuration des environnements
                  </p>
                </div>
              </label>

              <label class="flex items-start">
                <input
                  type="checkbox"
                  value="maintenance"
                  v-model="localFormData.additionalServices"
                  @change="updateFormData"
                  class="w-4 h-4 mt-1 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <div class="ml-3">
                  <span class="font-medium text-gray-900 dark:text-white">Maintenance</span>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Support technique et évolutions post-livraison
                  </p>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- Résumé des livrables -->
        <div v-if="hasDeliverableData" class="mt-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
          <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
            📝 Résumé de vos livrables :
          </h5>
          <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
            <p v-if="localFormData.scope">
              <strong>Périmètre :</strong> {{ getScopeLabel(localFormData.scope) }}
            </p>
            <p v-if="localFormData.mockupsProvided">
              <strong>Maquettes :</strong> {{ localFormData.mockupsProvided === 'provided' ? 'Fournies par le client' : 'À concevoir' }}
            </p>
            <p v-if="localFormData.specsProvided">
              <strong>Spécifications :</strong> {{ localFormData.specsProvided === 'provided' ? 'Détaillées fournies' : 'À définir ensemble' }}
            </p>
            <p v-if="localFormData.frequentMeetings">
              <strong>Communication :</strong> {{ getMeetingsLabel(localFormData.frequentMeetings) }}
            </p>
            <p v-if="localFormData.additionalServices.length > 0">
              <strong>Services additionnels :</strong> {{ localFormData.additionalServices.join(', ') }}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FreelanceDeliverables',
  props: {
    formData: {
      type: Object,
      default: () => ({
        scope: '',
        mockupsProvided: '',
        specsProvided: '',
        frequentMeetings: '',
        additionalServices: []
      })
    }
  },
  emits: ['update:form-data'],
  data() {
    return {
      isExpanded: false,
      localFormData: {
        scope: '',
        mockupsProvided: '',
        specsProvided: '',
        frequentMeetings: '',
        additionalServices: [],
        ...this.formData
      }
    }
  },
  computed: {
    hasDeliverableData() {
      return this.localFormData.scope ||
             this.localFormData.mockupsProvided ||
             this.localFormData.specsProvided ||
             this.localFormData.frequentMeetings ||
             this.localFormData.additionalServices.length > 0;
    }
  },
  methods: {
    updateFormData() {
      this.$emit('update:form-data', { ...this.localFormData });
    },

    toggleExpanded() {
      this.isExpanded = !this.isExpanded;
    },

    getScopeLabel(scope) {
      const labels = {
        'dev-only': 'Développement seul',
        'dev-ui': 'Développement + UI',
        'dev-ui-ux': 'Développement + UI + UX'
      };
      return labels[scope] || scope;
    },

    getMeetingsLabel(meetings) {
      const labels = {
        'frequent': 'Suivi régulier prévu',
        'minimal': 'Communication minimale',
        'unknown': 'Pas encore défini'
      };
      return labels[meetings] || meetings;
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
