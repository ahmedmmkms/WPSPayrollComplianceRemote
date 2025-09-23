# Production Runbook (Free Tier)

> Operating guide for WPS Payroll Compliance deployed entirely on free services. Update with every environment change.

## 1. Architecture Overview
- **Render (Web Service)**: Laravel app host. 512 MB RAM, 750 hours/month. Exposes HTTPS endpoint managed by Render. Sleeps after 15 minutes idle.
- **PlanetScale (MySQL)**: Primary database. Free tier with 5 GB storage, 10M row reads/day. Production branch (`main`) + deploy requests for schema changes.
- **Upstash Redis**: Queue + cache. Free tier 10K commands/day, 256 MB. Horizon powered job queues and cache tags share instance.
- **Cloudflare R2**: Object storage for SIF exports, audit PDFs, and logs needing retention. 10 GB storage, 1M operations/month.
- **Vercel (Optional)**: Marketing/API docs. Hobby plan 100 GB bandwidth.
- **Observability**: Sentry (errors, 5K events/month) and Grafana Cloud (metrics/logs).

## 2. Provisioning Steps
1. **Render**
   - Create new Web Service from GitHub repo (`master` branch).
   - Runtime: Docker or Render native build (choose based on app setup).
   - Environment variables: load `.env.production` values from vault (see Section 5).
   - Enable auto-deploy on successful builds from protected branch.
2. **PlanetScale**
   - Create database `wps_payroll_prod`.
   - Create production branch `main`; issue deploy request for migrations.
   - Generate passwordless connection URL; store in vault and GitHub secret `PLANETSCALE_DB_URL`.
3. **Upstash Redis**
   - Create database in closest region to Render.
   - Copy REST & TLS credentials; map to secrets `UPSTASH_REDIS_REST_URL`, `UPSTASH_REDIS_REST_TOKEN`, `REDIS_URL`.
4. **Cloudflare R2**
   - Create bucket `wps-payroll-artifacts`.
  - Generate API token scoped to R2 read/write.
   - Store `R2_ACCOUNT_ID`, `R2_ACCESS_KEY_ID`, `R2_SECRET_ACCESS_KEY`, `R2_BUCKET` secrets.
5. **Sentry & Grafana**
   - Sentry: create Laravel project, copy DSN/token.
   - Grafana Cloud: create stack, enable Prometheus/Loki; note ingestion endpoints and API key.
6. **Secrets Vault**
   - Record all credentials (owner, rotation date) in shared vault.
   - Update `docs/accounts.md` owner table.

## 3. Deployment Workflow
1. **CI Build** (GitHub Actions `CI` workflow)
   - Runs tests/analysis.
2. **Release Artifact**
   - Add `build` job (TBD) to create production artifact (composer install --no-dev, assets build) and upload to GitHub Actions.
3. **Deploy Job** (`deploy.yml`)
   - Trigger manually with `workflow_dispatch` once tests green.
   - Steps (to implement):
     - Download artifact.
     - Use Render Deploy Hook (`RENDER_DEPLOY_HOOK`) to trigger redeploy.
     - Monitor Render deploy status via API.
4. **Post-Deploy**
   - Run queued migrations automatically via Render deploy script or `php artisan migrate --force` in deploy hook.
   - Warm cache and run health check endpoint.

## 4. Incident Response
- **Service Down (Render Sleeping)**: Hit uptime ping endpoint or manual visit to wake service. Consider GitHub Actions cron hitting health endpoint every 10 minutes.
- **Database Limits Reached**: PlanetScale throttles queries—review read metrics, archive batches, consider paid upgrade.
- **Redis Command Exhaustion**: Reduce queue concurrency, batch jobs off-peak, or prune cache usage.
- **Storage Near Limit**: Configure lifecycle policy to purge exports older than SLA; archive to local storage if compliance requires.
- **Access Issues**: All credentials in vault; ensure backup owner can rotate tokens.
- **Rollback**: Redeploy previous commit via Render dashboard (select prior build) or re-run deploy workflow with earlier commit SHA.

## 5. Environment Variables (Render)
| Key | Source | Notes |
| --- | --- | --- |
| `APP_ENV` | static | `production` |
| `APP_KEY` | Vault | Generate via `php artisan key:generate --show` locally. |
| `APP_URL` | Render service URL | e.g., `https://wps-payroll.onrender.com` |
| `DB_CONNECTION` | static | `mysql` |
| `DATABASE_URL` | PlanetScale | Full connection string (preferred) |
| `REDIS_URL` | Upstash | TLS URI |
| `REDIS_REST_URL` | Upstash | Optional for REST ingestion |
| `QUEUE_CONNECTION` | static | `redis` |
| `FILESYSTEM_DISK` | static | `r2` (custom disk config) |
| `R2_ACCOUNT_ID` etc. | Cloudflare R2 | Map to Laravel config |
| `SENTRY_LARAVEL_DSN` | Sentry | Error reporting |
| `SENTRY_TRACES_SAMPLE_RATE` | config | e.g., `0.1` |
| `GRAFANA_AGENT_ENDPOINT` | Grafana | For metrics shipper if used |

## 6. Compliance Notes
- SIF artifacts stored in R2—ensure bucket private with per-request signed URLs.
- PlanetScale data encrypted at rest; confirm retention meets UAE/KSA requirements.
- Audit logs: enable Laravel audit package (future) and export to R2/Grafana.

## 7. Weekly Checks
- Review Render/PlanetScale/Upstash usage dashboards for quota proximity.
- Validate Sentry issue volume < 5K events.
- Confirm vault rotation reminders still valid.
- Update `docs/accounts.md` with any owner changes.

## 8. Open Items
- Automate artifact build & Render deploy hook.
- Decide on uptime monitor (GitHub cron vs third-party).
- Implement Terraform or scripts? (Deferred by strategy.)

