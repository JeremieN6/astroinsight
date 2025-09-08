<template>
  <div v-if="freelanceType === 'forfait'" class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <!-- En-tête avec toggle -->
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center space-x-3">
        <div class="flex-shrink-0">
          <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
            <span class="text-blue-600 dark:text-blue-400 text-sm font-semibold">6</span>
          </div>
        </div>
        <div>
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
            Informations Client
          </h3>
          <p class="text-sm text-gray-600 dark:text-gray-400">
            Optionnel - Pour générer des devis professionnels
          </p>
        </div>
      </div>
      
      <!-- Toggle pour activer/désactiver -->
      <div class="flex items-center">
        <label class="relative inline-flex items-center cursor-pointer">
          <input 
            type="checkbox" 
            v-model="localFormData.enabled"
            @change="updateFormData"
            class="sr-only peer"
          >
          <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
          <span class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ localFormData.enabled ? 'Activé' : 'Désactivé' }}
          </span>
        </label>
      </div>
    </div>

    <!-- Contenu conditionnel -->
    <div v-if="localFormData.enabled" class="space-y-4">
      <!-- Informations projet -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Nom du projet -->
        <div>
          <label for="projectName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Nom du projet
          </label>
          <input 
            type="text"
            id="projectName"
            v-model="localFormData.projectName"
            @input="updateFormData"
            placeholder="Ex: Refonte site e-commerce"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
          >
        </div>

        <!-- Nom du client -->
        <div>
          <label for="clientName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Nom du client
          </label>
          <input 
            type="text"
            id="clientName"
            v-model="localFormData.clientName"
            @input="updateFormData"
            placeholder="Ex: Entreprise ABC"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
          >
        </div>
      </div>

      <!-- Contact -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Email de contact -->
        <div>
          <label for="contactEmail" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Email de contact
          </label>
          <input 
            type="email"
            id="contactEmail"
            v-model="localFormData.contactEmail"
            @input="updateFormData"
            placeholder="contact@entreprise.com"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
          >
        </div>

        <!-- Nom de l'entreprise -->
        <div>
          <label for="companyName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Nom de l'entreprise
          </label>
          <input 
            type="text"
            id="companyName"
            v-model="localFormData.companyName"
            @input="updateFormData"
            placeholder="Ex: ABC Solutions"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
          >
        </div>
      </div>

      <!-- Description détaillée -->
      <div>
        <label for="projectDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
          Description détaillée du projet
        </label>
        <textarea 
          id="projectDescription"
          v-model="localFormData.projectDescription"
          @input="updateFormData"
          placeholder="Décrivez le contexte, les objectifs et les enjeux du projet..."
          rows="4"
          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
        ></textarea>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
          Cette description sera utilisée pour générer un devis professionnel détaillé
        </p>
      </div>

      <!-- Informations client -->
      <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">
          🏢 Informations sur le client
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <!-- Type de client -->
          <div>
            <label for="clientType" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Type de client
            </label>
            <select
              id="clientType"
              v-model="localFormData.clientType"
              @change="updateFormData"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            >
              <option value="">Sélectionnez le type de client</option>
              <option value="startup">Startup / Jeune entreprise</option>
              <option value="pme">PME (10-250 salariés)</option>
              <option value="grande-entreprise">Grande entreprise (250+ salariés)</option>
              <option value="association">Association / ONG</option>
              <option value="particulier">Particulier</option>
            </select>
          </div>

          <!-- Budget indicatif -->
          <div>
            <label for="clientBudgetRange" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Budget indicatif du client
            </label>
            <select
              id="clientBudgetRange"
              v-model="localFormData.clientBudgetRange"
              @change="updateFormData"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            >
              <option value="">Budget non communiqué</option>
              <option value="low">&lt; 5 000€</option>
              <option value="medium">5 000€ - 15 000€</option>
              <option value="high">15 000€ - 50 000€</option>
              <option value="enterprise">50 000€+</option>
            </select>
          </div>
        </div>

        <!-- Contexte concurrentiel -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Contexte concurrentiel
          </label>
          <div class="space-y-2">
            <label class="flex items-center">
              <input
                type="radio"
                name="competitive"
                value="low"
                v-model="localFormData.competitiveContext"
                @change="updateFormData"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
              >
              <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                Peu de concurrence - Client me fait confiance
              </span>
            </label>
            <label class="flex items-center">
              <input
                type="radio"
                name="competitive"
                value="medium"
                v-model="localFormData.competitiveContext"
                @change="updateFormData"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
              >
              <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                Concurrence modérée - Quelques devis en parallèle
              </span>
            </label>
            <label class="flex items-center">
              <input
                type="radio"
                name="competitive"
                value="high"
                v-model="localFormData.competitiveContext"
                @change="updateFormData"
                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
              >
              <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                Forte concurrence - Appel d'offres ou nombreux devis
              </span>
            </label>
          </div>
        </div>
      </div>

      <!-- Informations contractuelles -->
      <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
        <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">
          Informations contractuelles (optionnel)
        </h4>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <!-- Validité du devis -->
          <div>
            <label for="validityDays" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Validité du devis (jours)
            </label>
            <input 
              type="number"
              id="validityDays"
              v-model.number="localFormData.validityDays"
              @input="updateFormData"
              placeholder="30"
              min="1"
              max="365"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            >
          </div>

          <!-- Conditions de paiement -->
          <div>
            <label for="paymentTerms" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Conditions de paiement
            </label>
            <select 
              id="paymentTerms"
              v-model="localFormData.paymentTerms"
              @change="updateFormData"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            >
              <option value="">Sélectionner</option>
              <option value="30-70">30% acompte, 70% livraison</option>
              <option value="50-50">50% acompte, 50% livraison</option>
              <option value="33-33-34">3 fois (33% - 33% - 34%)</option>
              <option value="monthly">Paiement mensuel</option>
              <option value="custom">Personnalisé</option>
            </select>
          </div>

          <!-- Garantie -->
          <div>
            <label for="warranty" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
              Garantie (mois)
            </label>
            <input 
              type="number"
              id="warranty"
              v-model.number="localFormData.warranty"
              @input="updateFormData"
              placeholder="3"
              min="0"
              max="24"
              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
            >
          </div>
        </div>
      </div>
    </div>

    <!-- Message si désactivé -->
    <div v-else class="text-center py-8">
      <div class="text-gray-400 dark:text-gray-500 mb-2">
        <svg class="w-12 h-12 mx-auto" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6z" clip-rule="evenodd" />
        </svg>
      </div>
      <p class="text-sm text-gray-500 dark:text-gray-400">
        Activez cette section pour générer des devis professionnels avec vos informations client
      </p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FreelanceClientInfo',
  props: {
    freelanceType: {
      type: String,
      default: 'forfait'
    },
    formData: {
      type: Object,
      default: () => ({
        enabled: false,
        projectName: '',
        clientName: '',
        contactEmail: '',
        companyName: '',
        projectDescription: '',
        clientType: '',
        clientBudgetRange: '',
        competitiveContext: '',
        validityDays: 30,
        paymentTerms: '',
        warranty: 3
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
      return this.localFormData.enabled && (
        this.localFormData.projectName ||
        this.localFormData.clientName ||
        this.localFormData.contactEmail ||
        this.localFormData.projectDescription
      );
    }
  },
  methods: {
    updateFormData() {
      this.$emit('update:form-data', { ...this.localFormData });
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
/* Styles spécifiques si nécessaire */
</style>
