# Deployment Account Registry

> Maintain current credential owners, quotas, and GitHub secret mappings. Update after every account change.

## Usage Checklist
- [ ] Account created and verified
- [ ] API tokens stored in shared vault
- [ ] GitHub secrets populated
- [ ] Limits reviewed against Sprint objectives
- [ ] Owner & rotation dates confirmed

## Account Matrix

| Service | Purpose | URL | Plan / Limits | GitHub Secrets | Notes |
| --- | --- | --- | --- | --- | --- |
| GitHub Actions | CI/CD pipelines and repo automation | https://github.com/ahmedmmkms/WPSPayrollComplianceRemote | Default minutes (2K/month) | `COMPOSER_AUTH`, `GH_BOT_PAT`, `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `SENTRY_DSN` (placeholder) | Enable branch protection once status checks stable; create required secrets via Settings → Secrets and variables |
| Render | Primary app hosting (staging/prod) | https://render.com | Free web service (512 MB, 750 hrs/mo) | `RENDER_API_KEY` | Document region, runtime, and blueprint once app is deployed |
| Railway | Ephemeral staging alternative | https://railway.app | Free tier with $5 credits | `RAILWAY_TOKEN` | Disable auto top-up until approved; track resource usage |
| PlanetScale | Serverless MySQL | https://planetscale.com | Free: 5 GB storage, 10M row reads/day | `PLANETSCALE_USERNAME`, `PLANETSCALE_PASSWORD`, `PLANETSCALE_HOST`, `PLANETSCALE_DB` | Configure development + staging branches; enable daily backups |
| Upstash Redis | Queue + cache | https://upstash.com | Free: 10K commands/day | `UPSTASH_REDIS_REST_URL`, `UPSTASH_REDIS_REST_TOKEN`, `UPSTASH_REDIS_URL`, `UPSTASH_REDIS_PASSWORD` | Match region to app host; note throughput ceilings |
| Cloudflare R2 | Artifact/object storage | https://dash.cloudflare.com | Free: 10 GB storage, 1M ops/mo | `R2_ACCOUNT_ID`, `R2_ACCESS_KEY_ID`, `R2_SECRET_ACCESS_KEY`, `R2_BUCKET` | Requires Cloudflare account + optional domain; keep token scope minimal |
| Cloudflare DNS | Domain + SSL | https://dash.cloudflare.com | Free tier | `CLOUDFLARE_API_TOKEN`, `CLOUDFLARE_ZONE_ID` | Needed for custom domains (Render/Vercel); document DNS zone owner |
| Vercel | Marketing site / API docs | https://vercel.com | Hobby plan: 100 GB bandwidth/mo | `VERCEL_TOKEN`, `VERCEL_ORG_ID`, `VERCEL_PROJECT_ID` | Configure preview/prod envs; link Next.js docs repo |
| Sentry | Error & performance monitoring | https://sentry.io | Free: 5K events/mo | `SENTRY_DSN`, `SENTRY_AUTH_TOKEN` | Set sampling rates low for MVP; add alert rules |
| Grafana Cloud | Metrics & logs | https://grafana.com/cloud | Free: 10K series, 50 GB logs/mo | `GRAFANA_API_KEY`, `GRAFANA_STACK_NAME` | Enable Prometheus + Loki; record ingestion endpoints |
| Secret Vault (1Password/Bitwarden) | Central credential storage | https://1password.com / https://bitwarden.com | Team trial/free | n/a | Store master credentials and rotation schedule here |

## Owner Registry

| Service | Primary Owner | Backup Owner | Rotation Interval | Last Updated |
| --- | --- | --- | --- | --- |
| GitHub Actions | _TBD_ | _TBD_ | 90 days (PAT) | _TBD_ |
| Render | _TBD_ | _TBD_ | 180 days | _TBD_ |
| Railway | _TBD_ | _TBD_ | 90 days | _TBD_ |
| PlanetScale | _TBD_ | _TBD_ | 180 days | _TBD_ |
| Upstash Redis | _TBD_ | _TBD_ | 90 days | _TBD_ |
| Cloudflare (R2/DNS) | _TBD_ | _TBD_ | 180 days | _TBD_ |
| Vercel | _TBD_ | _TBD_ | 90 days | _TBD_ |
| Sentry | _TBD_ | _TBD_ | 120 days | _TBD_ |
| Grafana Cloud | _TBD_ | _TBD_ | 120 days | _TBD_ |
| Secret Vault | _TBD_ | _TBD_ | 365 days | _TBD_ |

## Action Items
- Sign up for each service using the engineering shared email alias.
- Capture API keys/tokens immediately in the vault with expiration reminders.
- Populate `Repository secrets` in GitHub after tokens exist.
- Update this registry during sprint review or when owners change.

