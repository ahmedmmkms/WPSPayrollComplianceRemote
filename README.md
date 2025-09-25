# Sprint 0 Close-out Checklist

This repository currently hosts infrastructure scaffolding and a Render-ready Docker runtime. No Laravel application code is committed yet; a bilingual placeholder page confirms the deployment environment works.

## What’s Done
- ✅ Render, PlanetScale/Neon, Upstash, and Vercel accounts created; owner registry updated in `docs/accounts.md`.
- ✅ GitHub Actions workflows for CI/static analysis/deploy/uptime are active.
- ✅ Render web service deployed via Docker; health checks rely on `public/index.php` placeholder.
- ✅ Secrets loaded directly into GitHub repository settings (no Terraform).
- ✅ Localization & PWA acceptance criteria defined in `docs/localization-pwa.md`.

## Local Development
1. Copy `.env.example` to `.env` and fill in Render/Keycloak/PlanetScale values.
2. Install dependencies (`composer install`, `npm install`).
3. Run `npm run build` to compile assets; optional `php artisan serve` for local testing.
4. Database provisioning is managed by tenancy; use `php artisan tenants:create` (to be scripted).
5. Create a tenant when ready: `php artisan tenants:create --name="Acme" --domain="acme.local" --email="ops@acme.test"`.


## Outstanding Work (Sprint 1+)
- Implement actual Laravel multi-tenant application: migrations, tenancy, Filament admin, etc.
- Replace placeholder landing page with real app shell; wire `/health` endpoint.
- Add automated deploy status polling/log alerts (Render API).
- Document PlanetScale/Upstash provisioning in runbook as environments evolve.
- Introduce monitoring/observability stack once workloads justify it.

## Local Usage
Use Docker to validate container changes:
```powershell
# Rebuild runtime image
docker compose build app

# Quick smoke test (Ctrl+C to stop)
docker compose up app
```

Remember to copy `.env.example` to `.env` locally with real credentials (kept out of git).
