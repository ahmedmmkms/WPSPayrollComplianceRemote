# Deployment Account Registry

> Maintain current credential owners, quotas, and GitHub secret mappings. Update after every account change.

## Usage Checklist
- [ ] Account created and verified
- [ ] GitHub secrets populated
- [ ] Limits reviewed against Sprint objectives
- [ ] Owner & rotation dates confirmed

## Account Matrix

| Service | Purpose | URL | Plan / Limits | GitHub Secrets | Notes |
| --- | --- | --- | --- | --- | --- |
| GitHub Actions | CI/CD pipelines and repo automation | https://github.com/ahmedmmkms/WPSPayrollComplianceRemote | Default minutes (2K/month) | `COMPOSER_AUTH`, `GH_BOT_PAT`, `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY` | Enable branch protection once status checks stable; create required secrets via Settings ? Secrets and variables |
| Render | Primary app hosting (staging/prod) | https://render.com | Free web service (512 MB, 750 hrs/mo) | `RENDER_API_KEY` | Runtime set to Docker; document region, start command, and blueprint once deployed |
| Railway | Ephemeral staging alternative | https://railway.app | Free tier with $5 credits | `RAILWAY_TOKEN` | Disable auto top-up until approved; track resource usage |
| PlanetScale | Serverless MySQL | https://planetscale.com | Free: 5?GB storage, 10M row reads/day | `PLANETSCALE_USERNAME`, `PLANETSCALE_PASSWORD`, `PLANETSCALE_HOST`, `PLANETSCALE_DB` | Configure development + staging branches; enable daily backups |
| Upstash Redis | Queue + cache | https://upstash.com | Free: 10K commands/day | `UPSTASH_REDIS_REST_URL`, `UPSTASH_REDIS_REST_TOKEN`, `UPSTASH_REDIS_URL`, `UPSTASH_REDIS_PASSWORD` | Match region to app host; note throughput ceilings |
| Cloudflare DNS | Domain + SSL | https://dash.cloudflare.com | Free tier | `CLOUDFLARE_API_TOKEN`, `CLOUDFLARE_ZONE_ID` | Needed for custom domains (Render/Vercel); document DNS zone owner |
| Vercel | Marketing site / API docs | https://vercel.com | Hobby plan: 100?GB bandwidth/mo | `VERCEL_TOKEN`, `VERCEL_ORG_ID`, `VERCEL_PROJECT_ID` | Configure preview/prod envs; link Next.js docs repo |

## Owner Registry

| Service | Primary Owner | Backup Owner | Rotation Interval | Last Updated |
| --- | --- | --- | --- | --- |
| GitHub Actions | AMM | AMM | 90 days (PAT) | 2025-09-24 |
| Render | AMM | AMM | 180 days | 2025-09-24 |
| Railway | AMM | AMM | 90 days | 2025-09-24 |
| PlanetScale | AMM | AMM | 180 days | 2025-09-24 |
| Upstash Redis | AMM | AMM | 90 days | 2025-09-24 |
| Cloudflare DNS | AMM | AMM | 180 days | 2025-09-24 |
| Vercel | AMM | AMM | 90 days | 2025-09-24 |

## Action Items
- Sign up for each service using the engineering shared email alias.
- Capture API keys/tokens in the agreed secure storage with expiration reminders.
- Populate `Repository secrets` in GitHub after tokens exist.
- Update this registry during sprint review or when owners change.

