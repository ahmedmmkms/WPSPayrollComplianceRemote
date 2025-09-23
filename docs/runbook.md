# Production Runbook (Free Tier)

> Operating guide for WPS Payroll Compliance deployed entirely on free services. Update with every environment change.

## 1. Architecture Overview
- **Render (Web Service)**: Laravel app host. 512 MB RAM, 750 hours/month. Exposes HTTPS endpoint managed by Render. Sleeps after 15 minutes idle.
- **PlanetScale (MySQL)**: Primary database. Free tier with 5 GB storage, 10M row reads/day. Production branch (`main`) plus deploy requests for schema changes.
- **Upstash Redis**: Queue and cache. Free tier 10K commands/day, 256 MB. Horizon powered job queues and cache tags share the instance.
- **Cloudflare R2**: Object storage for SIF exports, audit PDFs, and other retained artifacts. 10 GB storage, 1M operations/month.
- **Vercel (Optional)**: Marketing/API documentation site. Hobby plan 100 GB bandwidth.
- **Observability**: Sentry (errors, 5K events/month) and Grafana Cloud (metrics/logs).

## 2. Provisioning Steps
1. **Render**
   - Create new Web Service from GitHub repo (`master` branch).
   - Choose runtime (Dockerfile vs Render native build) once app scaffold exists.
   - Load environment variables from vaulted `.env.production` (see Section 5).
   - Enable auto-deploy on successful builds from the protected branch.
2. **PlanetScale**
   - Create database `wps_payroll_prod`.
   - Keep production branch `main`; issue deploy requests for migrations.
   - Generate passwordless connection URL; store in vault and GitHub secret `PLANETSCALE_DB_URL`.
3. **Upstash Redis**
   - Create database in the closest region to Render.
   - Copy REST and TLS credentials; map to secrets `UPSTASH_REDIS_REST_URL`, `UPSTASH_REDIS_REST_TOKEN`, `REDIS_URL`.
4. **Cloudflare R2**
   - Create bucket `wps-payroll-artifacts`.
   - Generate API token scoped to R2 read/write.
   - Store `R2_ACCOUNT_ID`, `R2_ACCESS_KEY_ID`, `R2_SECRET_ACCESS_KEY`, `R2_BUCKET` secrets.
5. **Sentry & Grafana**
   - Sentry: create Laravel project, capture DSN and auth token.
   - Grafana Cloud: create stack, enable Prometheus/Loki; note ingestion endpoints and API key.
6. **Secrets Vault**
   - Record all credentials (owner, rotation date) in shared vault.
   - Update `docs/accounts.md` owner table.

## 3. Deployment Workflow
1. **CI Build** (`ci.yml`)
   - Runs tests and static analysis.
2. **Release Artifact**
   - Extend pipeline with build job (composer install --no-dev, asset compilation) and upload artifact.
3. **Deploy Job** (`deploy.yml`)
   - Trigger via `workflow_dispatch` after green builds.
   - Steps to implement:
     - Download artifact.
     - Use Render deploy hook (`RENDER_DEPLOY_HOOK`) to trigger redeploy.
     - Monitor Render deploy status via API.
4. **Post-Deploy**
   - Run migrations (`php artisan migrate --force`).
   - Warm cache and hit health endpoint.
   - Confirm `Uptime Ping` workflow succeeding against `/health` URL.

## 4. Incident Response
- **Render Sleeping/Down**: Hit uptime ping endpoint or visit app to wake. Ensure scheduled workflow is running.
- **PlanetScale Limits**: Review read metrics, archive batches, or schedule maintenance window; upgrade tier if sustained load.
- **Redis Commands Exhausted**: Reduce queue concurrency, batch jobs off-peak, or trim cache usage.
- **R2 Storage Near Limit**: Apply lifecycle policy to purge old exports; archive locally if compliance demands.
- **Credential Issues**: Retrieve from vault; rotate via backup owner if compromised.
- **Rollback**: Redeploy prior build via Render dashboard or rerun deploy workflow with previous commit SHA.

## 5. Environment Variables (Render)

GitHub Secrets:
- `RENDER_HEALTHCHECK_URL`: HTTPS link to `/health` endpoint used by uptime workflow.

| Key | Source | Notes |
| --- | --- | --- |
| `APP_ENV` | static | `production` |
| `APP_KEY` | Vault | Generate with `php artisan key:generate --show` locally |
| `APP_URL` | Render service URL | Example `https://wps-payroll.onrender.com` |
| `DB_CONNECTION` | static | `mysql` |
| `DATABASE_URL` | PlanetScale | Preferred full connection string |
| `REDIS_URL` | Upstash | TLS URI |
| `REDIS_REST_URL` | Upstash | Optional for REST ingestion |
| `QUEUE_CONNECTION` | static | `redis` |
| `FILESYSTEM_DISK` | static | `r2` (custom disk config) |
| `R2_ACCOUNT_ID`, `R2_ACCESS_KEY_ID`, `R2_SECRET_ACCESS_KEY`, `R2_BUCKET` | Cloudflare R2 | Required for storage driver |
| `SENTRY_LARAVEL_DSN` | Sentry | Error reporting |
| `SENTRY_TRACES_SAMPLE_RATE` | config | Example `0.1` |
| `GRAFANA_AGENT_ENDPOINT` | Grafana | Metrics/log shipping endpoint |

## 6. Compliance Notes
- Keep R2 bucket private; generate signed URLs per download.
- PlanetScale provides encryption at rest; confirm retention policies match UAE/KSA expectations.
- Plan Laravel audit logging to forward events to R2 or Grafana for traceability.

## 7. Weekly Checks
- Review Render, PlanetScale, and Upstash dashboards for quota proximity.
- Verify `Uptime Ping` workflow succeeded during the week (no failed runs).
- Validate Sentry event volume below 5K/month threshold.
- Confirm vault rotation reminders still valid.
- Update `docs/accounts.md` if ownership changes.

## 8. Open Items
- Automate artifact build and Render deploy hook integration.
- Decide on additional uptime monitoring or alerting beyond GitHub Actions.
- Evaluate future IaC/script automation (currently deferred).
