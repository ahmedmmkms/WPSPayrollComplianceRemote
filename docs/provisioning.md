# Account Provisioning & Secrets Playbook

> Follow these steps to provision free-tier production services and load secrets into GitHub Actions. Update after every change.

## 1. Preparation
- Gather the GitHub repository link (`https://github.com/ahmedmmkms/WPSPayrollComplianceRemote`) and ensure you have Maintainer access for Secrets configuration.
- Keep `docs/accounts.md`, `docs/runbook.md`, and this playbook open; update owner tables and notes as you proceed.

## 2. Render (Laravel App Host)
1. Sign in at https://dashboard.render.com using the engineering account. Create account if necessary.
2. Click **New** -> **Web Service**; connect the GitHub repository. Authorize Render to access the repo if prompted.
3. Select branch `master`, set runtime to **Docker**, and confirm the repository `Dockerfile` builds the production image (Render builds from it automatically). Keep plan as **Free**.
4. Review the Docker settings (build logs, start command). Leave defaults unless the project requires overrides.
5. In **Environment** section, add placeholder env vars (e.g., `APP_ENV=production`) or leave for later once `.env.production` is ready.
6. Save the service. Note the automatically generated URL (e.g., `https://wps-payroll.onrender.com`).
7. Navigate to **Settings** -> **Deploy Hooks** -> click **Add Deploy Hook**. Copy the provided URL.
8. Add GitHub secret `RENDER_DEPLOY_HOOK` with the hook URL.
9. Record Render service owner and backup in `docs/accounts.md` and mark checklist items (account created, secret added).

## 3. Neon (PostgreSQL Database)
1. Sign up / log in at https://neon.tech. Create project `wps-payroll` under the shared org.
2. From the project dashboard, create branch `production` (rename the default branch) in the region closest to Render. Set autosuspend to 5 min to stay within the free tier.
3. In **Databases**, create database `wps_payroll_prod` if it does not already exist. Keep the default role (`neondb_owner`).
4. Open the branch -> **Connection Details** and copy the pooled connection string (e.g., `postgresql://...neon.tech/neondb?sslmode=require`). Rotate the password if needed.
5. Document the connection details and rotation interval (180 days recommended), plus autosuspend settings in project notes.
6. Add GitHub secret `NEON_DATABASE_URL`. If migrations require a separate shadow database, create branch `shadow` and add `NEON_SHADOW_DATABASE_URL`.
7. Update `docs/accounts.md` owner table with primary/backup owners and rotation schedule.

## 4. Upstash (Redis Queue/Cache)
1. Login at https://console.upstash.com. Create database `wps-payroll-prod`. Select region closest to Render (e.g., Frankfurt, Oregon).
2. From the database page, copy:
   - `REDIS URL` (TLS). Use this for Laravel `REDIS_URL`.
   - REST API URL and token (Settings -> REST API).
3. Capture credentials and note command quotas (10K/day) for internal documentation.
4. Create GitHub secrets:
   - `REDIS_URL`
   - `UPSTASH_REDIS_REST_URL`
   - `UPSTASH_REDIS_REST_TOKEN`
5. Update `docs/accounts.md` with owner info and mark secrets as populated.

## 5. Export Delivery (On-Demand Downloads)
1. Configure the export job to stream generated SIF files directly in the HTTP response (`Storage::temporaryUrl` is not required).
2. Confirm the queue worker uses Laravel's `StreamedResponse` (or equivalent) so no artifact is persisted once the request completes.
3. Document in the runbook that operators should expect no historical archive; regeneration must run the export workflow again.

## 6. Vercel (Marketing/API Docs)
1. Log in at https://vercel.com. Create team `WPS Payroll`.
2. Click **New Project**, link docs repo when available (optional stage).
3. Generate personal/team token (Account Settings -> Tokens).
4. Save `VERCEL_TOKEN`, plus project/org IDs if using GitHub Actions deploy (
   - accessible via `vercel link` or Vercel dashboard URL).
5. Add GitHub secrets (`VERCEL_TOKEN`, `VERCEL_ORG_ID`, `VERCEL_PROJECT_ID`) if deployment from Actions is required.
6. Update `docs/accounts.md`.

## 7. GitHub Actions Secrets Checklist
Navigate to repo -> **Settings -> Secrets and variables -> Actions** and create or update:
- `RENDER_DEPLOY_HOOK`
- `RENDER_HEALTHCHECK_URL` (populate once health endpoint exists)
- `NEON_DATABASE_URL`
- `NEON_SHADOW_DATABASE_URL` (if migrations require a separate shadow database)
- `REDIS_URL`
- `UPSTASH_REDIS_REST_URL`
- `UPSTASH_REDIS_REST_TOKEN`
- `VERCEL_TOKEN`, `VERCEL_ORG_ID`, `VERCEL_PROJECT_ID` (optional)
- Any additional third-party API keys once integrations exist

After adding secrets, trigger a dummy run of the `Deploy` workflow with sample `ci_run_id` to confirm the hook works (it should fail gracefully if artifact missing but must read secrets successfully).

## 8. Documentation Updates
- Mark each service’s checklist in `docs/accounts.md` as completed.
- Fill the owner registry with names, backup, rotation interval, last updated date.
- Update `docs/runbook.md` if URLs or secrets change.
- Note completion date and responsible engineer at the top of this playbook.

## 9. Verification & Handover
- Run `Uptime Ping` workflow once to ensure `RENDER_HEALTHCHECK_URL` secret is recognized.
- Validate `ci.yml` build job uploads `app-release` artifact (Actions -> latest CI run -> Artifacts).
- Perform brief handover reviewing runbook, topology, and this playbook.
- Schedule quarterly review to reconfirm quotas, secrets, and account ownership.

Document owner: _TBD_. Last updated: _TBD_.
