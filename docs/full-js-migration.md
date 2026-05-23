# Full JS Migration Plan (Nuxt + Nitro + Drizzle)

This document tracks the progressive removal of Symfony/PHP code in favor of a single JavaScript backend.

## Current State

- Active frontend/backend runtime: Nuxt 3 + Nitro (`npm run dev`)
- Legacy backend still present in repository: Symfony + Doctrine + Twig
- Drizzle schema already exists in `db/schema.ts`
- JS API routes are active in `server/api`

## What Is Already Done

- Added shared Drizzle connection utility for Nitro server routes: `server/utils/db.ts`
- Added report persistence in JS API route when `DATABASE_URL` is configured: `server/api/generate-report.post.ts`
- Added migration visibility endpoint: `GET /api/backend-status`
- Added core Neon-ready domain tables in Drizzle schema:
   - `users_js`
   - `plans_js`
   - `subscriptions_js`
   - `invoices_js`
- Added JS account endpoints:
   - `GET /api/user/profile`
   - `GET /api/user/subscription-status`
- Added Stripe webhook endpoint with signature verification:
   - `POST /api/stripe/webhook`

## Migration Principles

1. Keep user-facing behavior stable while replacing internals.
2. Migrate by domain (reports, auth, billing, admin), not by file type.
3. Introduce JS replacements first, then delete Symfony equivalents.
4. Keep one source of truth for database schema: Drizzle.

## Phase 1 - Stabilize JS Backend

1. Keep all new API features under `server/api` only.
2. Add DB access through `server/utils/db.ts` only (no direct DB code in routes).
3. Add simple smoke checks for key routes:
   - `POST /api/generate-report`
   - `GET /api/horoscope-today`
   - `GET /api/backend-status`

## Phase 2 - Data Model for Neon Postgres

1. Extend Drizzle schema for product domains:
   - users
   - subscriptions
   - plans
   - invoices
2. Generate and apply migrations with Drizzle.
3. Move all read/write operations for those domains to Nitro routes.

## Phase 3 - Stripe and Auth on JS Side

1. Implement Stripe webhooks in Nitro route handlers.
2. Store Stripe entities in Postgres via Drizzle.
3. Replace Symfony security/auth flows with JS-native approach (Nuxt server auth stack).

## Phase 4 - Remove Symfony Runtime Paths

1. Identify active dependencies on Symfony-only endpoints (legacy `assets/vue` paths).
2. Replace each with Nitro endpoint equivalent.
3. Stop using PHP entrypoint in deployment for app runtime.

## Phase 5 - Repository Cleanup

After parity is confirmed:

1. Remove unused Symfony directories progressively:
   - `src/`
   - `config/`
   - `templates/`
   - `migrations/` (Doctrine)
2. Remove `composer.json` and PHP runtime from deployment.
3. Update CI/CD to run only JS checks/tests/build.

## Next Immediate Actions

1. Generate and apply Drizzle SQL migrations for the new JS tables.
2. Seed `plans_js` with production Stripe price ids.
3. Connect frontend premium state to `GET /api/user/subscription-status` (replace local-only unlock behavior).
4. Replace remaining legacy `/api/estimation*` Symfony dependencies with Nitro routes.
