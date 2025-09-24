# Free Tier Quota & Uptime Guide

> Track limits, automate alerts, and plan mitigations for production running on free services.

## Quota Dashboard

| Service | Limit | Current Monitoring Plan | Upgrade Trigger |
| --- | --- | --- | --- |
| Render | 750 instance hours/month, 512 MB RAM | Weekly manual check in Render dashboard; GitHub Action cron ping to prevent idle sleep | Sustained >50% CPU/mem or need >1 instance |
| PlanetScale | 5 GB storage, 10M row reads/day | Enable PlanetScale Slack/email alerts; review query stats weekly | Approaching 4 GB storage or sustained >8M reads/day |
| Upstash Redis | 10K commands/day, 256 MB | Daily command count email; Horizon dashboard watch | >8K commands/day or backlog builds |
| Vercel | 100 GB bandwidth/mo | Vercel analytics monthly | >80 GB bandwidth |

## Monitoring Automation
- **GitHub Actions Cron**: Add scheduled workflow hitting `/health` endpoint every 10 minutes to keep Render awake and capture availability.
- **Status Alerts**:
  - PlanetScale: enable query alert for read/write spikes.
  - Upstash: set command threshold notifications.
- **Manual Reviews**: Document in runbook (weekly check). Assign owner to review Mondays and update `docs/accounts.md` owner registry.

## Mitigation Playbook
- **Queue Throttling**: Lower queue worker count via Render env var, re-deploy.
- **Batch Windows**: Schedule heavy exports off-peak (cron job from GitHub runner).
- **Data Regeneration**: Re-run export jobs if a file is requested after the original download window.
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
