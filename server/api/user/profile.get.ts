import { and, desc, eq } from 'drizzle-orm'
import { plans, subscriptions, users } from '../../../db/schema'
import { getDbOrThrow } from '../../utils/db'

export default defineEventHandler(async (event) => {
  const email = String(getQuery(event).email || '').trim().toLowerCase()

  if (!email) {
    throw createError({ statusCode: 400, statusMessage: 'email query param is required' })
  }

  const db = getDbOrThrow(event)
  const [user] = await db.select().from(users).where(eq(users.email, email)).limit(1)

  if (!user) {
    return {
      user: null,
      subscription: null,
      plan: null,
    }
  }

  const [subscription] = await db
    .select()
    .from(subscriptions)
    .where(eq(subscriptions.userId, user.id))
    .orderBy(desc(subscriptions.updatedAt))
    .limit(1)

  const [plan] = subscription?.planId
    ? await db
      .select()
      .from(plans)
      .where(and(eq(plans.id, subscription.planId)))
      .limit(1)
    : []

  return {
    user,
    subscription: subscription || null,
    plan: plan || null,
  }
})
