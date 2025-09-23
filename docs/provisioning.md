# Account Provisioning & Secrets Playbook

> Follow these steps to provision free-tier production services and load secrets into GitHub Actions. Update after every change.

## 1. Preparation
- Confirm access to the shared credential vault (1Password or Bitwarden). Create a dedicated vault/folder `WPS Payroll Compliance` if not already present.
- Gather the GitHub repository link (`https://github.com/ahmedmmkms/WPSPayrollComplianceRemote`) and ensure you have Maintainer access for Secrets configuration.
- Keep `docs/accounts.md`, `docs/runbook.md`, and this playbook open; update owner tables and notes as you proceed.

## 2. Render (Laravel App Host)
1. Sign in at https://dashboard.render.com using the engineering account. Create account if necessary.
2. Click **New** → **Web Service**; connect the GitHub repository. Authorize Render to access the repo if prompted.
3. Select branch `master`, choose runtime:
   - If Dockerfile exists later, choose Docker.
   - Otherwise, use Render’s Native build (Auto). Keep plan as **Free**.
4. In **Environment** section, add placeholder env vars (e.g., `APP_ENV=production`) or leave for later once `.env.production` is ready.
5. Save service. Note the automatically generated URL (e.g., `https://wps-payroll.onrender.com`).
6. Navigate to **Settings** → **Deploy Hooks** → click **Add Deploy Hook**. Copy the provided URL.
7. Store the hook in the vault entry (`Render → Deploy Hook`). Note creation date and owner.
8. Add GitHub secret `RENDER_DEPLOY_HOOK` with the hook URL.
9. Record Render service owner and backup in `docs/accounts.md` and mark checklist items (account created, token vaulted, secret added).

## 3. PlanetScale (MySQL Database)
1. Sign up / log in at https://planetscale.com. Create organization `WPS Payroll Compliance`.
2. Click **Create database** → name `wps_payroll_prod` → region closest to Render.
3. Keep branch `main` for production. Enable daily backups (Settings → Backups).
4. Go to **Connect** → choose “Passwordless” → framework `Laravel`. Copy the connection string.
5. Store connection string in vault as `PlanetScale → PLANETSCALE_DB_URL`. Document rotation interval (180 days recommended).
6. Add GitHub secret `PLANETSCALE_DB_URL`.
7. Optional: configure query alerts (Settings → Alerts) for high reads/writes. Note alert recipients.
8. Update `docs/accounts.md` owner table with primary/backup owners and rotation schedule.

## 4. Upstash (Redis Queue/Cache)
1. Login at https://console.upstash.com. Create database `wps-payroll-prod`. Select region closest to Render (e.g., Frankfurt, Oregon).
2. From the database page, copy:
   - `REDIS URL` (TLS). Use this for Laravel `REDIS_URL`.
   - REST API URL and token (Settings → REST API).
3. Save credentials in vault (`Upstash → REDIS_URL`, `REST_URL`, `REST_TOKEN`). Note command quotas (10K/day).
4. Create GitHub secrets:
   - `REDIS_URL`
   - `UPSTASH_REDIS_REST_URL`
   - `UPSTASH_REDIS_REST_TOKEN`
5. Update `docs/accounts.md` with owner info and mark secrets as populated.

## 5. Cloudflare R2 (Object Storage)
1. Sign in at https://dash.cloudflare.com. On sidebar select **R2** → **Create bucket** → name `wps-payroll-artifacts`.
2. Under **Manage tokens**, create API token:
   - Permissions: R2 Read + Write limited to the bucket.
   - Copy Access Key ID, Secret Access Key, Account ID.
3. Store credentials in vault entry (`Cloudflare R2`). Include bucket name and lifecycle policy notes.
4. In GitHub Secrets add:
   - `R2_ACCOUNT_ID`
   - `R2_ACCESS_KEY_ID`
   - `R2_SECRET_ACCESS_KEY`
   - `R2_BUCKET`
5. Document owners/rotation in `docs/accounts.md`.

## 6. Sentry (Error Monitoring)
1. Login to https://sentry.io, create organization if needed.
2. Add new project → Platform `Laravel`. Name it `wps-payroll-prod`.
3. Copy DSN displayed on setup page.
4. In Sentry Settings → Client Keys (DSN) copy DSN; optionally create Auth Token (Settings → Developer Settings → Auth Tokens) for release tracking.
5. Vault entries:
   - `SENTRY_LARAVEL_DSN`
   - Optional `SENTRY_AUTH_TOKEN`
6. GitHub secrets:
   - `SENTRY_LARAVEL_DSN`
   - `SENTRY_AUTH_TOKEN` (only if release automation planned)
7. Update owner table.

## 7. Grafana Cloud (Metrics & Logs)
1. Register at https://grafana.com/cloud; create stack `wps-payroll`.
2. Enable Prometheus and Loki data sources.
3. In **Administration → Stack → API Keys**, create key with metrics ingest scope.
4. Store in vault (`Grafana → API Key`, include ingest URLs).
5. Add GitHub secret `GRAFANA_API_KEY` if planning agent integration.
6. Update documentation with owner/rotation.

## 8. Vercel (Marketing/API Docs)
1. Log in at https://vercel.com. Create team `WPS Payroll`.
2. Click **New Project**, link docs repo when available (optional stage).
3. Generate personal/team token (Account Settings → Tokens).
4. Save `VERCEL_TOKEN`, plus project/org IDs if using GitHub Actions deploy (
   - accessible via `vercel link` or Vercel dashboard URL).
5. Add GitHub secrets (`VERCEL_TOKEN`, `VERCEL_ORG_ID`, `VERCEL_PROJECT_ID`) if deployment from Actions is required.
6. Update `docs/accounts.md`.

## 9. Secret Vault Maintenance
- For each vault entry, capture:
  - Service name, admin URL, notes (limits, plan).
  - Primary owner, backup owner, rotation interval, last rotated date.
  - Secret values (deploy hook, DSN, API keys). Use secure note fields.
- Set rotation reminders (quarterly for API keys, 90 days for tokens).
- Add a checklist referencing `docs/accounts.md` to ensure documentation alignment.

## 10. GitHub Actions Secrets Checklist
Navigate to repo → **Settings → Secrets and variables → Actions** and create or update:
- `RENDER_DEPLOY_HOOK`
- `RENDER_HEALTHCHECK_URL` (populate once health endpoint exists)
- `PLANETSCALE_DB_URL`
- `REDIS_URL`
- `UPSTASH_REDIS_REST_URL`
- `UPSTASH_REDIS_REST_TOKEN`
- `R2_ACCOUNT_ID`
- `R2_ACCESS_KEY_ID`
- `R2_SECRET_ACCESS_KEY`
- `R2_BUCKET`
- `SENTRY_LARAVEL_DSN`
- `GRAFANA_API_KEY` (optional)
- `VERCEL_TOKEN`, `VERCEL_ORG_ID`, `VERCEL_PROJECT_ID` (optional)
- Any additional third-party API keys once integrations exist

After adding secrets, trigger a dummy run of the `Deploy` workflow with sample `ci_run_id` to confirm the hook works (it should fail gracefully if artifact missing but must read secrets successfully).

## 11. Documentation Updates
- Mark each service’s checklist in `docs/accounts.md` as completed.
- Fill the owner registry with names, backup, rotation interval, last updated date.
- Update `docs/runbook.md` if URLs or secrets change.
- Note completion date and responsible engineer at the top of this playbook.

## 12. Verification & Handover
- Run `Uptime Ping` workflow once to ensure `RENDER_HEALTHCHECK_URL` secret is recognized.
- Validate `ci.yml` build job uploads `app-release` artifact (Actions → latest CI run → Artifacts).
- Share vault access with backup owner; perform brief handover reviewing runbook, topology, and this playbook.
- Schedule quarterly review to reconfirm quotas, secrets, and account ownership.

Document owner: _TBD_. Last updated: _TBD_.
