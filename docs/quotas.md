# Free Tier Quota & Uptime Guide

> Track limits, automate alerts, and plan mitigations for production running on free services.

## Quota Dashboard

| Service | Limit | Current Monitoring Plan | Upgrade Trigger |
| --- | --- | --- | --- |
| Render | 750 instance hours/month, 512 MB RAM | Weekly manual check in Render dashboard; GitHub Action cron ping to prevent idle sleep | Sustained >50% CPU/mem or need >1 instance |
| PlanetScale | 5 GB storage, 10M row reads/day | Enable PlanetScale Slack/email alerts; review query stats weekly | Approaching 4 GB storage or sustained >8M reads/day |
| Upstash Redis | 10K commands/day, 256 MB | Daily command count email; Horizon dashboard watch | >8K commands/day or backlog builds |
| Cloudflare R2 | 10 GB storage, 1M ops/month | Cloudflare analytics weekly; apply lifecycle policy for old exports | Storage >8 GB or ops >800K |
| Sentry | 5K events/month | Sentry quota alerts + email | >4K events or sampling throttled |
| Grafana Cloud | 10K series, 50 GB logs/mo | Usage graphs weekly | >40 GB logs or metrics drop |
| Vercel | 100 GB bandwidth/mo | Vercel analytics monthly | >80 GB bandwidth |

## Monitoring Automation
- **GitHub Actions Cron**: Add scheduled workflow hitting `/health` endpoint every 10 minutes to keep Render awake and capture availability.
- **Status Alerts**:
  - PlanetScale: enable query alert for read/write spikes.
  - Upstash: set command threshold notifications.
  - Cloudflare R2: configure analytics email summary.
  - Sentry: set budget notifications for error bursts.
- **Manual Reviews**: Document in runbook (weekly check). Assign owner to review Mondays and update `docs/accounts.md` owner registry.

## Mitigation Playbook
- **Queue Throttling**: Lower queue worker count via Render env var, re-deploy.
- **Batch Windows**: Schedule heavy exports off-peak (cron job from GitHub runner).
- **Data Archival**: Move old R2 artifacts to local encrypted storage, delete remote copies.
- **Selective Sampling**: Reduce Sentry tracing rate to stay within cap.
- **Graceful Degradation**: If quotas hit mid-cycle, disable non-essential features (e.g., dashboards) until reset.

## Incident Log Template
```
Date:
Service:
Limit Reached:
Impact:
Mitigation Applied:
Follow-up Action:
```
Store entries under `docs/incidents/` for audit trail.

