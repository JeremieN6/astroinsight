import { defineStore } from 'pinia'

export const useReportStore = defineStore('report', {
  state: () => ({
    isPremium: false as boolean,
    reportData: null as Record<string, unknown> | null,
    userEmail: '' as string,
  }),

  actions: {
    unlockPremium() {
      this.isPremium = true
      if (import.meta.client) {
        localStorage.setItem('astroinsight_premium', '1')
      }
    },

    setPremiumStatus(val: boolean) {
      this.isPremium = val
    },

    setReportData(data: Record<string, unknown>) {
      this.reportData = data
    },

    setUserEmail(email: string) {
      this.userEmail = email
    },

    initFromStorage() {
      if (import.meta.client) {
        this.isPremium = localStorage.getItem('astroinsight_premium') === '1'
      }
    },
  },
})
