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
      if (import.meta.client) {
        localStorage.setItem('astroinsight_premium', val ? '1' : '0')
      }
    },

    setReportData(data: Record<string, unknown>) {
      this.reportData = data
    },

    setUserEmail(email: string) {
      this.userEmail = email.trim().toLowerCase()
      if (import.meta.client && this.userEmail) {
        localStorage.setItem('astroinsight_email', this.userEmail)
      }
    },

    async syncPremiumStatusFromServer(email?: string) {
      const targetEmail = (email || this.userEmail || '').trim().toLowerCase()
      if (!targetEmail) return

      try {
        const response = await $fetch<{ isPremium: boolean }>('/api/user/subscription-status', {
          query: { email: targetEmail },
        })
        this.setPremiumStatus(Boolean(response?.isPremium))
      } catch (error) {
        console.error('[report-store] premium status sync failed:', error)
      }
    },

    initFromStorage() {
      if (import.meta.client) {
        this.isPremium = localStorage.getItem('astroinsight_premium') === '1'
        this.userEmail = localStorage.getItem('astroinsight_email') || ''
      }
    },
  },
})
