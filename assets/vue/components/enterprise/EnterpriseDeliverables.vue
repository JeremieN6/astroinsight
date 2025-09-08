<template>
  <div class="enterprise-deliverables bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
    <!-- Header avec bouton toggle -->
    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
      <div class="flex items-center">
        <span class="text-green-500 text-2xl mr-3">📦</span>
        <div>
          <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
            Section 4 : Livrables attendus & périmètre
          </h4>
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Définissez ce qui est inclus dans le projet et les responsabilités
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
        <!-- Grid 3 colonnes pour les 3 premières sections -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <!-- Gestion UI/UX -->
          <div>
            <h5 class="text-md font-medium text-gray-900 dark:text-white mb-4">
              🎨 Gestion de l'UI/UX
            </h5>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
              Qui gère l'UI/UX du projet ?
            </label>
            <div class="space-y-3">
              <label class="flex items-start">
                <input
                  type="radio"
                  name="ui-ux-management"
                  value="internal"
                  v-model="localFormData.uiUxManagement"
                  @change="updateFormData"
                  class="w-4 h-4 mt-1 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <div class="ml-3">
                  <span class="font-medium text-gray-900 dark:text-white">Géré en interne</span>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Votre équipe design s'occupe de l'UI/UX
                  </p>
                </div>
              </label>

              <label class="flex items-start">
                <input
                  type="radio"
                  name="ui-ux-management"
                  value="external"
                  v-model="localFormData.uiUxManagement"
                  @change="updateFormData"
                  class="w-4 h-4 mt-1 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <div class="ml-3">
                  <span class="font-medium text-gray-900 dark:text-white">Prestataire externe</span>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    Agence ou freelance design externe
                  </p>
                </div>
              </label>

              <label class="flex items-start">
                <input
                  type="radio"
                  name="ui-ux-management"
                  value="included"
                  v-model="localFormData.uiUxManagement"
                  @change="updateFormData"
                  class="w-4 h-4 mt-1 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <div class="ml-3">
                  <span class="font-medium text-gray-900 dark:text-white">À inclure dans le chiffrage</span>
                  <p class="text-sm text-gray-600 dark:text-gray-400">
                    L'équipe de développement gère aussi l'UI/UX
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
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                  Maquettes ou wireframes
                </label>
                <div class="space-y-2">
                  <label class="flex items-center">
                    <input
                      type="radio"
                      name="mockups"
                      value="available"
                      v-model="localFormData.mockupsStatus"
                      @change="updateFormData"
                      class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    >
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                      Disponibles
                    </span>
                  </label>
                  <label class="flex items-center">
                    <input
                      type="radio"
                      name="mockups"
                      value="to-create"
                      v-model="localFormData.mockupsStatus"
                      @change="updateFormData"
                      class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    >
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                      À créer
                    </span>
                  </label>
                </div>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                  Spécifications fonctionnelles
                </label>
                <div class="space-y-2">
                  <label class="flex items-center">
                    <input
                      type="radio"
                      name="specs"
                      value="detailed"
                      v-model="localFormData.specsStatus"
                      @change="updateFormData"
                      class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    >
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                      Détaillées
                    </span>
                  </label>
                  <label class="flex items-center">
                    <input
                      type="radio"
                      name="specs"
                      value="basic"
                      v-model="localFormData.specsStatus"
                      @change="updateFormData"
                      class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    >
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                      Basiques
                    </span>
                  </label>
                  <label class="flex items-center">
                    <input
                      type="radio"
                      name="specs"
                      value="to-define"
                      v-model="localFormData.specsStatus"
                      @change="updateFormData"
                      class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                    >
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                      À définir
                    </span>
                  </label>
                </div>
              </div>
            </div>
          </div>

          <!-- Communication avec le client final -->
          <div>
            <h5 class="text-md font-medium text-gray-900 dark:text-white mb-3">
              💬 Communication projet
            </h5>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
              Des réunions régulières sont-elles prévues avec le client final ?
            </label>
            <div class="space-y-2">
              <label class="flex items-center">
                <input
                  type="radio"
                  name="client-meetings"
                  value="frequent"
                  v-model="localFormData.clientMeetings"
                  @change="updateFormData"
                  class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                  Oui, suivi régulier prévu
                </span>
              </label>
              <label class="flex items-center">
                <input
                  type="radio"
                  name="client-meetings"
                  value="milestone"
                  v-model="localFormData.clientMeetings"
                  @change="updateFormData"
                  class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                  Points d'étape uniquement
                </span>
              </label>
              <label class="flex items-center">
                <input
                  type="radio"
                  name="client-meetings"
                  value="minimal"
                  v-model="localFormData.clientMeetings"
                  @change="updateFormData"
                  class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                  Communication minimale
                </span>
              </label>
            </div>
          </div>
        </div>

        <!-- Sections supplémentaires en pleine largeur -->
        <div class="mt-8 space-y-6">
          <!-- Services additionnels -->
          <div>
            <h5 class="text-md font-medium text-gray-900 dark:text-white mb-4">
              🚀 Services additionnels
            </h5>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
              Quels services supplémentaires doivent être inclus dans le chiffrage ?
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <label v-for="service in additionalServices" :key="service.id" class="flex items-start">
                <input
                  type="checkbox"
                  :value="service.id"
                  v-model="localFormData.selectedServices"
                  @change="updateFormData"
                  class="w-4 h-4 mt-1 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <div class="ml-3">
                  <div class="flex items-center">
                    <span class="text-lg mr-2">{{ service.icon }}</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ service.name }}</span>
                  </div>
                  <p class="text-sm text-gray-600 dark:text-gray-400">{{ service.description }}</p>
                </div>
              </label>
            </div>
          </div>

          <!-- Contraintes spécifiques -->
          <div>
            <h5 class="text-md font-medium text-gray-900 dark:text-white mb-4">
              ⚠️ Contraintes spécifiques
            </h5>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
              Y a-t-il des contraintes particulières à prendre en compte ?
            </p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <label v-for="constraint in specificConstraints" :key="constraint.id" class="flex items-start">
                <input
                  type="checkbox"
                  :value="constraint.id"
                  v-model="localFormData.selectedConstraints"
                  @change="updateFormData"
                  class="w-4 h-4 mt-1 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                >
                <div class="ml-3">
                  <div class="flex items-center">
                    <span class="text-lg mr-2">{{ constraint.icon }}</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ constraint.name }}</span>
                  </div>
                  <p class="text-sm text-gray-600 dark:text-gray-400">{{ constraint.description }}</p>
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
  name: 'EnterpriseDeliverables',
  props: {
    formData: {
      type: Object,
      default: () => ({
        uiUxManagement: '',
        mockupsStatus: '',
        specsStatus: '',
        clientMeetings: '',
        selectedServices: [],
        selectedConstraints: []
      })
    }
  },
  emits: ['update:form-data'],
  data() {
    return {
      isExpanded: false,
      localFormData: {
        uiUxManagement: '',
        mockupsStatus: '',
        specsStatus: '',
        clientMeetings: '',
        selectedServices: [],
        selectedConstraints: [],
        ...this.formData
      },
      additionalServices: [
        {
          id: 'deployment-only',
          name: 'Déploiement uniquement',
          description: 'Mise en production sur l\'environnement cible',
          icon: '🚀'
        },
        {
          id: 'deployment-cicd',
          name: 'Déploiement & CI/CD',
          description: 'Pipeline d\'intégration et déploiement continu',
          icon: '🔄'
        },
        {
          id: 'monitoring',
          name: 'Monitoring',
          description: 'Surveillance applicative et infrastructure',
          icon: '📊'
        },
        {
          id: 'maintenance',
          name: 'Maintenance évolutive',
          description: 'Support technique et évolutions post-livraison',
          icon: '🔧'
        },
        {
          id: 'sla-support',
          name: 'SLA ou support',
          description: 'Engagement de niveau de service et support',
          icon: '🛡️'
        },
        {
          id: 'training',
          name: 'Formation équipe',
          description: 'Formation de l\'équipe client sur l\'outil',
          icon: '🎓'
        }
      ],
      specificConstraints: [
        {
          id: 'tight-deadline',
          name: 'Délai très serré',
          description: 'Contrainte de temps importante nécessitant des ressources supplémentaires',
          icon: '⏰'
        },
        {
          id: 'legacy-integration',
          name: 'Intégration système legacy',
          description: 'Connexion avec des systèmes anciens ou complexes',
          icon: '🏛️'
        },
        {
          id: 'high-availability',
          name: 'Haute disponibilité',
          description: 'Exigences de disponibilité 24/7 ou très élevées',
          icon: '🔒'
        },
        {
          id: 'compliance',
          name: 'Conformité réglementaire',
          description: 'Respect de normes spécifiques (RGPD, ISO, etc.)',
          icon: '📋'
        },
        {
          id: 'performance',
          name: 'Performance critique',
          description: 'Exigences de performance très élevées',
          icon: '⚡'
        },
        {
          id: 'multi-tenant',
          name: 'Architecture multi-tenant',
          description: 'Isolation des données entre plusieurs clients',
          icon: '🏢'
        }
      ]
    }
  },
  computed: {
    hasDeliverableData() {
      return this.localFormData.uiUxManagement ||
             this.localFormData.mockupsStatus ||
             this.localFormData.specsStatus ||
             this.localFormData.clientMeetings ||
             this.localFormData.selectedServices.length > 0 ||
             this.localFormData.selectedConstraints.length > 0;
    }
  },
  methods: {
    updateFormData() {
      this.$emit('update:form-data', { ...this.localFormData });
    },

    toggleExpanded() {
      this.isExpanded = !this.isExpanded;
    },

    getUiUxLabel() {
      const labels = {
        'internal': 'Géré en interne',
        'external': 'Prestataire externe',
        'included': 'Inclus dans le chiffrage'
      };
      return labels[this.localFormData.uiUxManagement] || this.localFormData.uiUxManagement;
    },

    getMockupsLabel() {
      const labels = {
        'available': 'Disponibles',
        'to-create': 'À créer'
      };
      return labels[this.localFormData.mockupsStatus] || this.localFormData.mockupsStatus;
    },

    getSpecsLabel() {
      const labels = {
        'detailed': 'Détaillées',
        'basic': 'Basiques',
        'to-define': 'À définir'
      };
      return labels[this.localFormData.specsStatus] || this.localFormData.specsStatus;
    },

    getClientMeetingsLabel() {
      const labels = {
        'frequent': 'Suivi régulier prévu',
        'milestone': 'Points d\'étape uniquement',
        'minimal': 'Communication minimale'
      };
      return labels[this.localFormData.clientMeetings] || this.localFormData.clientMeetings;
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
