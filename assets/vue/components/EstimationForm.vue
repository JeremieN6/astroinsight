<template>
  <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
    <!-- Header du formulaire -->
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
        Formulaire d'estimation de projet
      </h3>
      <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
        Remplissez les informations ci-dessous pour obtenir une estimation personnalisée
      </p>
    </div>

    <!-- Contenu du formulaire -->
    <div class="p-6">
      <!-- Sélecteur de type d'utilisateur -->
      <UserTypeSelector
        :selected-type="selectedUserType"
        @update:selected-type="handleUserTypeChange"
      />

      <!-- Sections conditionnelles selon le type d'utilisateur -->
      <div v-if="selectedUserType" class="mt-8">
        <!-- Section pour Freelance -->
        <div v-if="selectedUserType === 'freelance'" class="space-y-6">
          <!-- Sections 1 et 2 côte à côte -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
            <!-- Section 1 : Informations de base -->
            <FreelanceBasics
              :form-data="freelanceData.basics"
              @update:form-data="updateFreelanceBasics"
            />

            <!-- Section 2 : Contraintes du freelance -->
            <FreelanceConstraints
              :form-data="freelanceData.constraints"
              :technologies="extractedTechnologies"
              @update:form-data="updateFreelanceConstraints"
            />
          </div>

          <!-- Section 3 : Fonctionnalités -->
          <FreelanceFeatures
            :form-data="freelanceData.features"
            @update:form-data="updateFreelanceFeatures"
          />

          <!-- Section 4 : Livrables -->
          <FreelanceDeliverables
            :form-data="freelanceData.deliverables"
            @update:form-data="updateFreelanceDeliverables"
          />

          <!-- Section 5 : Objectifs -->
          <FreelanceObjectives
            :form-data="freelanceData.objectives"
            @update:form-data="updateFreelanceObjectives"
          />

          <!-- Section 6 : Informations Client (seulement en forfait) -->
          <FreelanceClientInfo
            :freelance-type="freelanceData.constraints.freelanceType"
            :form-data="freelanceData.clientInfo"
            @update:form-data="updateFreelanceClientInfo"
          />
        </div>

        <!-- Section pour Entreprise -->
        <div v-if="selectedUserType === 'entreprise'" class="space-y-6">
          <!-- Sections 1 et 2 côte à côte -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
            <!-- Section 1 : Informations de base -->
            <EnterpriseBasics
              :form-data="entrepriseData.basics"
              @update:form-data="updateEnterpriseBasics"
            />

            <!-- Section 2 : Structure -->
            <EnterpriseStructure
              :form-data="entrepriseData.structure"
              @update:form-data="updateEnterpriseStructure"
            />
          </div>

          <!-- Section 3 : Fonctionnalités -->
          <EnterpriseFunctionalities
            :form-data="entrepriseData.functionalities"
            @update:form-data="updateEnterpriseFunctionalities"
          />

          <!-- Section 4 : Livrables -->
          <EnterpriseDeliverables
            :form-data="entrepriseData.deliverables"
            @update:form-data="updateEnterpriseDeliverables"
          />

          <!-- Section 5 : Objectifs -->
          <EnterpriseObjectives
            :form-data="entrepriseData.objectives"
            @update:form-data="updateEnterpriseObjectives"
          />

          <!-- Section 6 : Tarification -->
          <EnterprisePricing
            :form-data="entrepriseData.pricing"
            @update:form-data="updateEnterprisePricing"
          />
        </div>

        <!-- Bouton de génération -->
        <div class="mt-8 text-center">
          <button
            @click="generateEstimation"
            :disabled="!canSubmit || isGenerating"
            :class="[
              'px-8 py-4 rounded-lg font-semibold text-lg transition-all duration-200',
              canSubmit && !isGenerating
                ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-lg hover:shadow-xl transform hover:-translate-y-0.5'
                : 'bg-gray-300 text-gray-500 cursor-not-allowed'
            ]"
          >
            <span v-if="isGenerating" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Génération en cours...
            </span>
            <span v-else>
              🎯 Générer mon estimation
            </span>
          </button>
        </div>

        <!-- Affichage des erreurs -->
        <div v-if="estimationError" class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800">Erreur</h3>
              <div class="mt-2 text-sm text-red-700">{{ estimationError }}</div>
            </div>
          </div>
        </div>

        <!-- Affichage des résultats -->
        <EstimationResults
          v-if="estimationResult"
          :result="estimationResult"
          :user-type="selectedUserType"
          :form-data="getCurrentFormData()"
          class="mt-8"
        />
      </div>
    </div>
  </div>
</template>

<script>
// Import des composants
import UserTypeSelector from './common/UserTypeSelector.vue'
import FreelanceBasics from './freelance/FreelanceBasics.vue'
import FreelanceConstraints from './freelance/FreelanceConstraints.vue'
import FreelanceFeatures from './freelance/FreelanceFeatures.vue'
import FreelanceDeliverables from './freelance/FreelanceDeliverables.vue'
import FreelanceObjectives from './freelance/FreelanceObjectives.vue'
import FreelanceClientInfo from './freelance/FreelanceClientInfo.vue'
import EnterpriseBasics from './enterprise/EnterpriseBasics.vue'
import EnterpriseStructure from './enterprise/EnterpriseStructure.vue'
import EnterpriseFunctionalities from './enterprise/EnterpriseFunctionalities.vue'
import EnterpriseDeliverables from './enterprise/EnterpriseDeliverables.vue'
import EnterpriseObjectives from './enterprise/EnterpriseObjectives.vue'
import EnterprisePricing from './enterprise/EnterprisePricing.vue'
import EstimationResults from './common/EstimationResults.vue'

export default {
  name: 'EstimationForm',
  components: {
    UserTypeSelector,
    FreelanceBasics,
    FreelanceConstraints,
    FreelanceFeatures,
    FreelanceDeliverables,
    FreelanceObjectives,
    FreelanceClientInfo,
    EnterpriseBasics,
    EnterpriseStructure,
    EnterpriseFunctionalities,
    EnterpriseDeliverables,
    EnterpriseObjectives,
    EnterprisePricing,
    EstimationResults
  },
  data() {
    return {
      selectedUserType: null, // 'freelance' ou 'entreprise'
      isGenerating: false, // État de génération de l'estimation
      estimationError: null, // Erreur d'estimation
      estimationResult: null, // Résultat de l'estimation
      rateLimitStatus: null, // Statut des limitations
      freelanceData: {
        basics: {
          projectType: '',
          customProjectType: '',
          pageCount: '',
          technologies: '',
          isExistingProject: null,
          deadlineDays: null
        },
        constraints: {
          freelanceType: 'forfait',
          skillLevels: {},
          isFullTime: null,
          hasTjmTarget: false,
          tjmTarget: null,
          hasWorkingDaysTarget: false,
          workingDaysTarget: null,
          hasMarginTarget: false,
          marginTarget: null,
          securityMargin: ''
        },
        features: {
          selectedFeatures: [],
          customFeatures: []
        },
        deliverables: {
          scope: '',
          mockupsProvided: '',
          specsProvided: '',
          frequentMeetings: '',
          additionalServices: []
        },
        objectives: {
          selectedObjectives: [],
          customObjective: '',
          primaryObjective: '',
          clientType: '',
          currentSituation: ''
        },
        clientInfo: {
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
        }
      },
      entrepriseData: {
        basics: {
          projectType: '',
          customProjectType: '',
          isExistingProject: null,
          technologies: {
            frontend: '',
            backend: '',
            database: '',
            infrastructure: ''
          },
          pageCount: '',
          deadlineType: '',
          deadlineDuration: null,
          deadlineUnit: 'semaines',
          deadlineDate: '',
          pricingReason: '',
          customPricingReason: ''
        },
        structure: {
          userRole: '',
          customUserRole: '',
          teamComposition: [],
          teamProfiles: [{ type: '', customType: '', count: null }],
          methodology: ''
        },
        functionalities: {
          selectedFeatures: [],
          hasPhase2: false,
          phase2Description: '',
          phase2Priority: '',
          scalability: '',
          functionalComplexity: ''
        },
        deliverables: {
          uiUxManagement: '',
          mockupsStatus: '',
          specsStatus: '',
          clientMeetings: '',
          includedServices: []
        },
        objectives: {
          projectObjective: '',
          budgetContext: '',
          budgetAmount: null,
          budgetFlexibility: '',
          projectUrgency: '',
          specificConstraints: ''
        },
        pricing: {
          hasDailyCosts: false,
          dailyCosts: [{ type: '', customType: '', dailyRate: null }],
          includeMargin: false,
          marginRate: null,
          marginType: '',
          billingModel: '',
          billingContext: ''
        }
      }
    }
  },
  computed: {
    canSubmit() {
      // Logique pour vérifier les données essentielles
      if (this.selectedUserType === 'freelance') {
        const basics = this.freelanceData.basics;
        return basics.projectType && basics.pageCount;
      } else if (this.selectedUserType === 'entreprise') {
        const basics = this.entrepriseData.basics;
        return basics.projectType && basics.pageCount && basics.pricingReason;
      }
      return false;
    },
    extractedTechnologies() {
      if (this.selectedUserType === 'freelance') {
        return this.freelanceData.basics.technologies;
      }
      return '';
    }
  },
  methods: {
    handleUserTypeChange(type) {
      this.selectedUserType = type;
      this.estimationResult = null;
      this.estimationError = null;
      this.saveData();
    },
    
    // Méthodes de mise à jour pour Freelance
    updateFreelanceBasics(data) {
      this.freelanceData.basics = { ...this.freelanceData.basics, ...data };
      this.saveData();
    },
    updateFreelanceConstraints(data) {
      this.freelanceData.constraints = { ...this.freelanceData.constraints, ...data };
      this.saveData();
    },
    updateFreelanceFeatures(data) {
      this.freelanceData.features = { ...this.freelanceData.features, ...data };
      this.saveData();
    },
    updateFreelanceDeliverables(data) {
      this.freelanceData.deliverables = { ...this.freelanceData.deliverables, ...data };
      this.saveData();
    },
    updateFreelanceObjectives(data) {
      this.freelanceData.objectives = { ...this.freelanceData.objectives, ...data };
      this.saveData();
    },
    updateFreelanceClientInfo(data) {
      this.freelanceData.clientInfo = { ...this.freelanceData.clientInfo, ...data };
      this.saveData();
    },
    
    // Méthodes de mise à jour pour Entreprise
    updateEnterpriseBasics(data) {
      this.entrepriseData.basics = { ...this.entrepriseData.basics, ...data };
      this.saveData();
    },
    updateEnterpriseStructure(data) {
      this.entrepriseData.structure = { ...this.entrepriseData.structure, ...data };
      this.saveData();
    },
    updateEnterpriseFunctionalities(data) {
      this.entrepriseData.functionalities = { ...this.entrepriseData.functionalities, ...data };
      this.saveData();
    },
    updateEnterpriseDeliverables(data) {
      this.entrepriseData.deliverables = { ...this.entrepriseData.deliverables, ...data };
      this.saveData();
    },
    updateEnterpriseObjectives(data) {
      this.entrepriseData.objectives = { ...this.entrepriseData.objectives, ...data };
      this.saveData();
    },
    updateEnterprisePricing(data) {
      this.entrepriseData.pricing = { ...this.entrepriseData.pricing, ...data };
      this.saveData();
    },
    
    // Sauvegarde et chargement des données
    saveData() {
      const dataToSave = {
        selectedUserType: this.selectedUserType,
        freelanceData: this.freelanceData,
        entrepriseData: this.entrepriseData
      };
      localStorage.setItem('quickesti-form-data', JSON.stringify(dataToSave));
    },
    
    loadSavedData() {
      try {
        const savedData = localStorage.getItem('quickesti-form-data');
        if (savedData) {
          const parsed = JSON.parse(savedData);
          if (parsed.selectedUserType) {
            this.selectedUserType = parsed.selectedUserType;
          }
          if (parsed.freelanceData) {
            this.freelanceData = { ...this.freelanceData, ...parsed.freelanceData };
          }
          if (parsed.entrepriseData) {
            this.entrepriseData = { ...this.entrepriseData, ...parsed.entrepriseData };
          }
        }
      } catch (error) {
        console.error('Erreur lors du chargement des données:', error);
      }
    },
    
    // Génération de l'estimation
    async generateEstimation() {
      this.isGenerating = true;
      this.estimationError = null;

      try {
        console.log('🚀 Génération d\'estimation pour:', this.selectedUserType);

        // Préparation des données selon le type d'utilisateur
        const formData = this.selectedUserType === 'freelance' ? this.freelanceData : this.entrepriseData;

        console.log('📊 Données envoyées:', formData);

        // Appel à l'API Symfony
        const response = await fetch('/api/estimation', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            userType: this.selectedUserType,
            formData: formData
          })
        });

        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.error || `Erreur HTTP ${response.status}`);
        }

        const result = await response.json();

        if (!result.success) {
          throw new Error(result.error || 'Erreur lors de la génération');
        }

        console.log('✅ Estimation reçue:', result.data);

        // Stockage du résultat
        this.estimationResult = result.data;

        // Sauvegarde dans localStorage pour persistance
        localStorage.setItem('quickesti_last_estimation', JSON.stringify({
          userType: this.selectedUserType,
          result: result.data,
          timestamp: Date.now()
        }));

      } catch (error) {
        console.error('❌ Erreur lors de la génération:', error);
        this.estimationError = error.message || 'Une erreur est survenue lors de la génération de l\'estimation';

        // Log détaillé pour debug
        console.error('Détails de l\'erreur:', {
          userType: this.selectedUserType,
          error: error,
          stack: error.stack
        });
      } finally {
        this.isGenerating = false;
      }
    },

    getCurrentFormData() {
      // Retourne les données du formulaire selon le type d'utilisateur
      if (this.selectedUserType === 'freelance') {
        return this.freelanceData;
      } else if (this.selectedUserType === 'entreprise') {
        return this.entrepriseData;
      }
      return {};
    }
  },
  
  mounted() {
    this.loadSavedData();
  }
}
</script>
