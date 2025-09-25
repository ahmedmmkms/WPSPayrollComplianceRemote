# Production Runbook (Free Tier)

> Operating guide for WPS Payroll Compliance deployed entirely on free services. Update with every environment change.

## 1. Architecture Overview
- Topology diagram: see [docs/topology.md](topology.md).
- **Render (Web Service)**: Laravel app host. 512 MB RAM, 750 hours/month. Exposes HTTPS endpoint managed by Render. Sleeps after 15 minutes idle.
- **PlanetScale (MySQL)**: Primary database. Free tier with 5 GB storage, 10M row reads/day. Production branch (`main`) plus deploy requests for schema changes.
- **Upstash Redis**: Queue and cache. Free tier 10K commands/day, 256 MB. Horizon powered job queues and cache tags share the instance.
- **Vercel (Optional)**: Marketing/API documentation site. Hobby plan 100 GB bandwidth.
- **Observability**: Application logs stored via default Laravel channels; rely on manual checks until monitoring stack is added.

## 2. Provisioning Steps
1. **Render**
   - Create new Web Service from GitHub repo (`master` branch).
   - Configure the service using the Docker runtime (Render builds from the repository `Dockerfile`).
   - Load environment variables from `.env.production` (see Section 5) once finalized and synced with GitHub secrets.
   - Enable auto-deploy on successful builds from the protected branch.
2. **PlanetScale**
   - Create database `wps_payroll_prod`.
   - Generate passwordless connection URL; add it to the GitHub secret `PLANETSCALE_DB_URL` and document rotation details.
3. **Upstash Redis**
   - Create database in the closest region to Render.
   - Copy REST and TLS credentials; map to secrets `UPSTASH_REDIS_REST_URL`, `UPSTASH_REDIS_REST_TOKEN`, `REDIS_URL`.
4. **Observability Placeholder**
   - Document where logs are written (Render logs, Laravel log files).
   - Capture manual steps for reviewing recent errors.
5. **Credential Tracking**
   - Track credential owners and rotation cadence in docs/accounts.md.
   - Update `docs/accounts.md` owner table.

## 3. Deployment Workflow
1. **CI Build** (`ci.yml`)
   - Runs tests and static analysis.
2. **Release Artifact**
   - Build job produces `app-release.tar.gz` (composer --no-dev, cached assets) and publishes as GitHub artifact.
3. **Deploy Job** (`deploy.yml`)
   - Trigger via `workflow_dispatch` after green builds. Provide CI run ID (`ci_run_id`) and commit SHA.
   - Steps executed:
     - Download `app-release` artifact from referenced CI run.
     - Verify contents for traceability.
     - POST to Render deploy hook (`RENDER_DEPLOY_HOOK`) to kick service rebuild.
     - TODO: poll Render API for deployment status (future enhancement).
4. **Post-Deploy**
   - Run migrations (`php artisan migrate --force`).
   - Warm cache and hit health endpoint.
   - Confirm `Uptime Ping` workflow succeeding against `/health` URL.

## 4. Incident Response
- **Render Sleeping/Down**: Hit uptime ping endpoint or visit app to wake. Ensure scheduled workflow is running.
- **PlanetScale Limits**: Review read metrics, archive batches, or schedule maintenance window; upgrade tier if sustained load.
- **Redis Commands Exhausted**: Reduce queue concurrency, batch jobs off-peak, or trim cache usage.
- **Credential Issues**: Retrieve from the documented secret source; rotate via backup owner if compromised.
- **Rollback**: Redeploy prior build via Render dashboard or rerun deploy workflow with previous commit SHA.

## 5. Environment Variables (Render)

GitHub Secrets:
- `RENDER_HEALTHCHECK_URL`: HTTPS link to `/health` endpoint used by uptime workflow.
- `RENDER_DEPLOY_HOOK`: Render deploy hook URL triggered by deploy workflow.
- `PLANETSCALE_DB_URL`: PlanetScale passwordless connection string.
- `UPSTASH_REDIS_REST_URL`, `UPSTASH_REDIS_REST_TOKEN`: Optional REST credentials for queue management.

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
| `FILESYSTEM_DISK` | config | Stream exports directly; leave unset or use `local` for temporary files |

## 6. Compliance Notes
- Export jobs stream results directly to the requester; regenerate files when a historical copy is needed.
- PlanetScale provides encryption at rest; confirm retention policies match UAE/KSA expectations.
- Plan Laravel audit logging strategy after selecting a hosted monitoring service.

## 7. Weekly Checks
- Review Render, PlanetScale, and Upstash dashboards for quota proximity.
- Verify `Uptime Ping` workflow succeeded during the week (no failed runs).
- Update `docs/accounts.md` if ownership or secrets change.

## 8. Localization & PWA Sign-off
- Follow `docs/localization-pwa.md` for bilingual acceptance criteria; QA documents sign-off each release.
- Archive Arabic copy approvals in docs/quotas.md and sync translation keys when content evolves.

## 9. Open Items
- Render service reachable at https://wpspayrollcomplianceremote.onrender.com (Docker runtime).
- GitHub Actions secrets maintained manually in repository settings (Terraform removed).
- Bilingual placeholder (`public/index.php`) returns 200; full Laravel app rollout tracked for Sprint 1.
- Track Render configuration changes directly in this runbook until alternative automation is adopted.
- Document PlanetScale/Upstash provisioning details and update docs/accounts.md after each rotation.
- Implement Render deploy status polling and surfaced alerts.
- Decide on additional uptime monitoring or alerting beyond GitHub Actions (e.g., log aggregation later).
- Evaluate future IaC/script automation (currently deferred).

## 10. Image Validation
- Use `docker compose build app` to ensure the Render Dockerfile remains healthy.
- `docker compose up app` will boot the container against the remote services configured in `.env`; stop after smoke-testing endpoints.
- No local MySQL/Redis containers are bundled; managed services provisioned in Sprint 0 remain the single source.
- Secrets maintained directly in GitHub repository settings; document rotations in docs/accounts.md.
