import { pgTable, text, timestamp, uuid, boolean, real } from 'drizzle-orm/pg-core'

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
