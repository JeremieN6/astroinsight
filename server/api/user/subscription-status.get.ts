import { and, desc, eq, inArray, isNull } from 'drizzle-orm'
import { invoices, subscriptions, users } from '../../../db/schema'
import { getDbOrThrow } from '../../utils/db'

const PREMIUM_STATUSES = new Set(['active', 'trialing'])
const PAID_INVOICE_STATUSES = ['paid', 'succeeded'] as const

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

  const [oneShotInvoice] = await db
    .select()
    .from(invoices)
    .where(and(
      eq(invoices.userId, user.id),
      isNull(invoices.subscriptionId),
      inArray(invoices.status, PAID_INVOICE_STATUSES),
    ))
    .orderBy(desc(invoices.createdAt))
    .limit(1)

  if (!subscription && !oneShotInvoice) {
    return {
      email,
      isPremium: false,
      reason: 'premium_entitlement_not_found',
      subscription: null,
      oneShotInvoice: null,
    }
  }

  const now = Date.now()
  const hasActiveSubscription = Boolean(subscription)
    && PREMIUM_STATUSES.has(subscription.status)
    && (!subscription.currentPeriodEnd || subscription.currentPeriodEnd.getTime() >= now)

  const hasPaidOneShot = Boolean(oneShotInvoice)

  const isPremium = hasActiveSubscription || hasPaidOneShot
  const reason = hasActiveSubscription
    ? 'ok_subscription'
    : hasPaidOneShot
      ? 'ok_one_shot'
      : 'inactive_or_expired'

  return {
    email,
    isPremium,
    reason,
    subscription: subscription || null,
    oneShotInvoice: oneShotInvoice || null,
  }
})
