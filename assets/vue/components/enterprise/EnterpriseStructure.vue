<template>
  <div class="enterprise-structure bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6 flex flex-col h-full">
    <div class="flex items-center mb-6">
      <span class="text-orange-500 text-2xl mr-3">👥</span>
      <div>
        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
          Section 2 : Structure & organisation de l'entreprise
        </h4>
        <p class="text-sm text-gray-600 dark:text-gray-400">
          Définissez l'organisation et les ressources mobilisées pour ce projet
        </p>
      </div>
    </div>

    <div class="space-y-8 flex-grow">
      <!-- Rôle de la personne qui répond -->
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
          Quel est votre rôle dans l'entreprise ? *
        </label>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
          <label v-for="role in availableRoles" :key="role.id" class="flex items-center">
            <input
              type="radio"
              name="user-role"
              :value="role.id"
              v-model="localFormData.userRole"
              @change="updateFormData"
              class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
            >
            <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
              {{ role.label }}
            </span>
          </label>
        </div>

        <!-- Champ texte pour "Autre" -->
        <div v-if="localFormData.userRole === 'other'" class="mt-3">
          <input
            type="text"
            v-model="localFormData.customUserRole"
            @input="updateFormData"
            placeholder="Précisez votre rôle"
            class="w-full md:w-64 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
          >
        </div>
      </div>

      <!-- Qui travaille sur ce projet -->
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
          Qui travaille sur ce projet ? *
        </label>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
          Plusieurs choix possibles
        </p>
        <div class="space-y-3">
          <label v-for="teamType in teamTypes" :key="teamType.id" class="flex items-start">
            <input
              type="checkbox"
              :value="teamType.id"
              v-model="localFormData.teamComposition"
              @change="updateFormData"
              class="w-4 h-4 mt-1 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
            >
            <div class="ml-3">
              <span class="font-medium text-gray-900 dark:text-white">{{ teamType.label }}</span>
              <p class="text-sm text-gray-600 dark:text-gray-400">{{ teamType.description }}</p>
            </div>
          </label>
        </div>
      </div>

      <!-- Nombre de personnes mobilisées -->
      <div>
        <h5 class="text-md font-medium text-gray-900 dark:text-white mb-4">
          Combien de personnes seront mobilisées ?
        </h5>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
          Nombre approximatif par type de profil
        </p>

        <div class="space-y-4">
          <div v-for="(profile, index) in localFormData.teamProfiles" :key="index"
               class="flex items-center space-x-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <select
              v-model="profile.type"
              @change="updateFormData"
              class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white"
            >
              <option value="">Type de profil</option>
              <option value="dev-frontend">Développeur Frontend</option>
              <option value="dev-backend">Développeur Backend</option>
              <option value="dev-fullstack">Développeur Fullstack</option>
              <option value="lead-dev">Lead Developer</option>
              <option value="devops">DevOps</option>
              <option value="po">Product Owner</option>
              <option value="designer">Designer UI/UX</option>
              <option value="qa">QA / Testeur</option>
              <option value="chef-projet">Chef de projet</option>
              <option value="architecte">Architecte technique</option>
              <option value="autre">Autre</option>
            </select>

            <input
              v-if="profile.type === 'autre'"
              type="text"
              v-model="profile.customType"
              @input="updateFormData"
              placeholder="Précisez"
              class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white"
            >

            <div class="flex items-center space-x-2">
              <input
                type="number"
                v-model.number="profile.count"
                @input="updateFormData"
                placeholder="Nb"
                min="0"
                max="20"
                class="w-16 px-2 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white"
              >
              <span class="text-sm text-gray-600 dark:text-gray-400">pers.</span>
            </div>

            <button
              @click="removeTeamProfile(index)"
              type="button"
              class="p-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
            >
              🗑️
            </button>
          </div>

          <button
            @click="addTeamProfile"
            class="flex items-center px-4 py-2 text-sm text-blue-600 dark:text-blue-400 border border-blue-300 dark:border-blue-600 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors"
          >
            ➕ Ajouter un profil
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EnterpriseStructure',
  props: {
    formData: {
      type: Object,
      default: () => ({
        userRole: '',
        customUserRole: '',
        teamComposition: [],
        teamProfiles: [{ type: '', customType: '', count: null }],
        methodology: ''
      })
    }
  },
  emits: ['update:form-data'],
  data() {
    return {
      localFormData: {
        userRole: '',
        customUserRole: '',
        teamComposition: [],
        teamProfiles: [{ type: '', customType: '', count: null }],
        methodology: '',
        ...this.formData
      },
      availableRoles: [
        { id: 'cto', label: 'CTO' },
        { id: 'chef-projet', label: 'Chef de projet' },
        { id: 'dev', label: 'Développeur' },
        { id: 'cmo', label: 'CMO' },
        { id: 'fondateur', label: 'Fondateur' },
        { id: 'other', label: 'Autre' }
      ],
      teamTypes: [
        {
          id: 'internal-devs',
          label: 'Développeurs internes',
          description: 'Équipe de développement en interne'
        },
        {
          id: 'external-freelances',
          label: 'Freelances externes',
          description: 'Développeurs indépendants'
        },
        {
          id: 'agency',
          label: 'Agence ou sous-traitant',
          description: 'Prestataire externe spécialisé'
        },
        {
          id: 'mixed',
          label: 'Mix interne / externe',
          description: 'Combinaison d\'équipes internes et externes'
        }
      ]
    }
  },
  computed: {
    hasStructureData() {
      return this.localFormData.userRole ||
             this.localFormData.teamComposition.length > 0 ||
             this.totalTeamMembers > 0 ||
             this.localFormData.methodology;
    },

    totalTeamMembers() {
      return this.localFormData.teamProfiles
        .filter(profile => profile.type && profile.count)
        .reduce((total, profile) => total + parseInt(profile.count || 0), 0);
    }
  },
  methods: {
    updateFormData() {
      this.$emit('update:form-data', { ...this.localFormData });
    },

    addTeamProfile() {
      this.localFormData.teamProfiles.push({ type: '', customType: '', count: null });
      this.updateFormData();
    },

    removeTeamProfile(index) {
      this.localFormData.teamProfiles.splice(index, 1);
      this.updateFormData();
    },

    getUserRoleLabel() {
      const role = this.availableRoles.find(r => r.id === this.localFormData.userRole);
      if (role) {
        return role.id === 'other' ? this.localFormData.customUserRole : role.label;
      }
      return this.localFormData.userRole;
    },

    getTeamCompositionLabel() {
      return this.localFormData.teamComposition
        .map(id => {
          const team = this.teamTypes.find(t => t.id === id);
          return team ? team.label : id;
        })
        .join(', ');
    },

    getValidProfiles() {
      return this.localFormData.teamProfiles.filter(profile =>
        profile.type && profile.count && parseInt(profile.count) > 0
      );
    },

    getProfileLabel(profile) {
      const labels = {
        'dev-frontend': 'Dev Frontend',
        'dev-backend': 'Dev Backend',
        'dev-fullstack': 'Dev Fullstack',
        'lead-dev': 'Lead Dev',
        'devops': 'DevOps',
        'po': 'Product Owner',
        'designer': 'Designer',
        'qa': 'QA',
        'chef-projet': 'Chef de projet',
        'architecte': 'Architecte',
        'autre': profile.customType || 'Autre'
      };
      return labels[profile.type] || profile.type;
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
