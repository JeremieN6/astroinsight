import { getDbIfConfigured } from '../utils/db'

export default defineEventHandler(async (event) => {
  const db = getDbIfConfigured(event)

  return {
    backend: 'nuxt-nitro',
    mode: 'progressive-symfony-removal',
    databaseConfigured: Boolean(db),
    timestamp: new Date().toISOString(),
  }
})
