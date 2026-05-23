import {
  pgTable,
  text,
  timestamp,
  uuid,
  boolean,
  real,
  integer,
  uniqueIndex,
  index,
} from 'drizzle-orm/pg-core'

export const reports = pgTable('reports', {
  id: uuid('id').primaryKey().defaultRandom(),
  firstName: text('first_name').notNull(),
  birthDate: text('birth_date').notNull(),
  birthTime: text('birth_time'),
  city: text('city').notNull(),
  lat: real('lat').notNull(),
  lon: real('lon').notNull(),
  gender: text('gender'),
  email: text('email'),
  sunSign: text('sun_sign').notNull(),
  moonSign: text('moon_sign').notNull(),
  ascendant: text('ascendant').notNull(),
  summary: text('summary'),
  isPremium: boolean('is_premium').default(false),
  stripeSessionId: text('stripe_session_id'),
  createdAt: timestamp('created_at').defaultNow().notNull(),
})

export const horoscopeCache = pgTable('horoscope_cache', {
  id: uuid('id').primaryKey().defaultRandom(),
  sign: text('sign').notNull(),
  date: text('date').notNull(),
  content: text('content').notNull(),
  createdAt: timestamp('created_at').defaultNow().notNull(),
})

export const users = pgTable('users_js', {
  id: uuid('id').primaryKey().defaultRandom(),
  email: text('email').notNull(),
  firstName: text('first_name'),
  stripeCustomerId: text('stripe_customer_id'),
  isActive: boolean('is_active').default(true).notNull(),
  createdAt: timestamp('created_at').defaultNow().notNull(),
  updatedAt: timestamp('updated_at').defaultNow().notNull(),
}, (table) => ({
  emailUnique: uniqueIndex('users_js_email_unique').on(table.email),
  stripeCustomerUnique: uniqueIndex('users_js_stripe_customer_unique').on(table.stripeCustomerId),
}))

export const plans = pgTable('plans_js', {
  id: uuid('id').primaryKey().defaultRandom(),
  name: text('name').notNull(),
  slug: text('slug').notNull(),
  stripePriceId: text('stripe_price_id'),
  amountCents: integer('amount_cents'),
  currency: text('currency').default('eur').notNull(),
  isActive: boolean('is_active').default(true).notNull(),
  createdAt: timestamp('created_at').defaultNow().notNull(),
  updatedAt: timestamp('updated_at').defaultNow().notNull(),
}, (table) => ({
  slugUnique: uniqueIndex('plans_js_slug_unique').on(table.slug),
  stripePriceUnique: uniqueIndex('plans_js_stripe_price_unique').on(table.stripePriceId),
}))

export const subscriptions = pgTable('subscriptions_js', {
  id: uuid('id').primaryKey().defaultRandom(),
  userId: uuid('user_id').notNull().references(() => users.id, { onDelete: 'cascade' }),
  planId: uuid('plan_id').references(() => plans.id, { onDelete: 'set null' }),
  stripeSubscriptionId: text('stripe_subscription_id').notNull(),
  status: text('status').notNull(),
  currentPeriodStart: timestamp('current_period_start'),
  currentPeriodEnd: timestamp('current_period_end'),
  cancelAtPeriodEnd: boolean('cancel_at_period_end').default(false).notNull(),
  createdAt: timestamp('created_at').defaultNow().notNull(),
  updatedAt: timestamp('updated_at').defaultNow().notNull(),
}, (table) => ({
  stripeSubscriptionUnique: uniqueIndex('subscriptions_js_stripe_sub_unique').on(table.stripeSubscriptionId),
  userIdx: index('subscriptions_js_user_idx').on(table.userId),
  statusIdx: index('subscriptions_js_status_idx').on(table.status),
}))

export const invoices = pgTable('invoices_js', {
  id: uuid('id').primaryKey().defaultRandom(),
  userId: uuid('user_id').references(() => users.id, { onDelete: 'set null' }),
  subscriptionId: uuid('subscription_id').references(() => subscriptions.id, { onDelete: 'set null' }),
  stripeInvoiceId: text('stripe_invoice_id').notNull(),
  amountPaidCents: integer('amount_paid_cents'),
  currency: text('currency').default('eur').notNull(),
  status: text('status'),
  hostedInvoiceUrl: text('hosted_invoice_url'),
  issuedAt: timestamp('issued_at'),
  createdAt: timestamp('created_at').defaultNow().notNull(),
}, (table) => ({
  stripeInvoiceUnique: uniqueIndex('invoices_js_stripe_invoice_unique').on(table.stripeInvoiceId),
  userIdx: index('invoices_js_user_idx').on(table.userId),
  subscriptionIdx: index('invoices_js_subscription_idx').on(table.subscriptionId),
}))
