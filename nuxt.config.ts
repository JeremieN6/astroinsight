// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-04-03',
  devtools: { enabled: true },

  modules: [
    ['@nuxtjs/tailwindcss', { configPath: '~/tailwind.config.ts' }],
    '@pinia/nuxt',
    '@nuxtjs/color-mode',
  ],

  colorMode: {
    preference: 'dark',
    fallback: 'dark',
    classSuffix: '',
  },

  css: ['~/assets/css/main.css'],

  app: {
    head: {
      htmlAttrs: { lang: 'fr', class: 'dark' },
      link: [
        { rel: 'preconnect', href: 'https://fonts.googleapis.com' },
        { rel: 'preconnect', href: 'https://fonts.gstatic.com', crossorigin: '' },
        {
          rel: 'stylesheet',
          href: 'https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap',
        },
      ],
    },
  },

  runtimeConfig: {
    // Server-only
    databaseUrl: process.env.DATABASE_URL || '',
    openaiApiKey: process.env.OPENAI_API_KEY || '',
    stripeSecretKey: process.env.STRIPE_SECRET_KEY || '',
    stripeWebhookSecret: process.env.STRIPE_WEBHOOK_SECRET || '',
    stripePriceId: process.env.STRIPE_PRICE_ID || '',
    stripeBuyLink: process.env.STRIPE_BUY_LINK || 'https://buy.stripe.com/test_example',
    emailHost: process.env.EMAIL_HOST || '',
    emailPort: process.env.EMAIL_PORT || '587',
    emailUser: process.env.EMAIL_USER || '',
    emailPass: process.env.EMAIL_PASS || '',
    // Public (exposed to client)
    public: {
      siteUrl: process.env.NUXT_PUBLIC_SITE_URL || 'http://localhost:3000',
      stripeOneShotLink: process.env.NUXT_PUBLIC_STRIPE_ONE_SHOT_LINK || '',
      stripeSubMonthlyLink: process.env.NUXT_PUBLIC_STRIPE_SUB_MONTHLY_LINK || '',
      stripeSubYearlyLink: process.env.NUXT_PUBLIC_STRIPE_SUB_YEARLY_LINK || '',
    },
  },

  nitro: {
    experimental: {
      wasm: false,
    },
  },
})
