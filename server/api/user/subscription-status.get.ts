import { desc, eq } from 'drizzle-orm'
import { subscriptions, users } from '../../../db/schema'
import { getDbOrThrow } from '../../utils/db'

const PREMIUM_STATUSES = new Set(['active', 'trialing'])

export default defineEventHandler(async (event) => {
  const email = String(getQuery(event).email || '').trim().toLowerCase()

  if (!email) {
    throw createError({ statusCode: 400, statusMessage: 'email query param is required' })
  }

  const db = getDbOrThrow(event)
  const [user] = await db.select().from(users).where(eq(users.email, email)).limit(1)

  if (!user) {
    return {
      email,
      isPremium: false,
      reason: 'user_not_found',
      subscription: null,
    }
  }

  const [subscription] = await db
    .select()
    .from(subscriptions)
    .where(eq(subscriptions.userId, user.id))
    .orderBy(desc(subscriptions.updatedAt))
    .limit(1)

  if (!subscription) {
    return {
      email,
      isPremium: false,
      reason: 'subscription_not_found',
      subscription: null,
    }
  }

  const now = Date.now()
  const isValidWindow = !subscription.currentPeriodEnd || subscription.currentPeriodEnd.getTime() >= now
  const isPremium = PREMIUM_STATUSES.has(subscription.status) && isValidWindow

  return {
    email,
    isPremium,
    reason: isPremium ? 'ok' : 'inactive_or_expired',
    subscription,
  }
})
