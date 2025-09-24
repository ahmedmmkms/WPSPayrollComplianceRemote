# Project Plan - WPS Payroll Compliance & SIF Generator

## Goal
Deliver a compliant, multi-tenant payroll tooling that ingests employee payroll data, validates against UAE WPS and KSA Mudad regulations, and exports bank-ready SIF packages with full auditability within a 4-6 week MVP window.

## Immediate DevOps Requirement
- Stand up GitHub Actions CI/CD pipelines on day zero covering build, test, security scans, and deployment promotion gates for every branch. This is the first execution step for DevOps, prerequisite to any application work.

## Technology Stack
- **Backend:** PHP 8.3, Laravel 11, Filament 3 (admin UX), Laravel Tenancy/Stancl for tenant scoping, MySQL 8, Redis 7, Laravel Horizon, Pest/PHPUnit, PHPStan/Psalm, OpenAPI-powered controllers, Guzzle for external calls.
- **Admin Experience:** Filament resource pages, Livewire v3, Tailwind CSS, Alpine.js, Chart.js for KPIs, Filament Shield for RBAC policy scaffolding.
- **Localization & PWA:** Laravel Localization (Spatie package) for English/Arabic content, RTL-friendly Tailwind config, dynamic locale switcher, Laravel PWA (or Workbox/Vite plugin) for manifest, service workers, offline fallback, install prompts.
- **Data & Storage:** Encrypted MySQL schemas (per-tenant scoping) with on-demand export streaming (no persistent object store in MVP), Laravel Scout (optional) for advanced search.
- **Async & Integrations:** Horizon-managed queues on Redis for validation/export jobs, REST adapters for bank and Mudad endpoints, Laravel Events feeding shared audit/event bus.
- **DevOps & QA Tooling:** Docker Compose, GitHub Actions CI/CD, Terraform/IaC scripts, Prometheus + Loki for observability, Trivy/Snyk for security scans, Lighthouse CI for PWA/RTL regressions.

## Free Tier Deployment & Services
- **Vercel (Hobby):** Host public marketing site, API documentation (Next.js) and status page; 100 GB bandwidth per month and 12 serverless function executions per minute limit - backend API remains on PHP runtime elsewhere.
- **Render (Free Web Service):** Run the Laravel app via Docker runtime (Render builds from the repository `Dockerfile`), one instance, 512 MB, 750 hours per month. Alternative: **Railway** free tier (up to `$5` credits) for burstable staging environments.
- **PlanetScale (Free):** Serverless MySQL with branch-based workflows; supports 5 GB storage and 10 million row reads per day - ideal for staging/UAT, migrate to paid or managed MySQL for production.
- **Upstash Redis (Free):** Serves queue workloads with 10,000 commands per day - suitable for dev/staging; upgrade for production throughput.
- **GitHub Actions (Free for org up to 2K minutes per month):** Automate tests, linting, container builds, deploy pipelines.

## Scope and Deliverables (MVP)
- Tenant onboarding with RBAC via shared Keycloak and Laravel tenancy scaffolding.
- Employee and payroll batch importers (CSV/XLSX) with schema validation and preview before commit.
- Configurable validation engine covering UAE WPS SIF rules and initial KSA Mudad checks with versioned rule sets.
- SIF generation service supporting multiple bank profile templates and queue-based export jobs (CSV/PDF summaries).
- Exception management workspace with assignment, status tracking, and notification hooks.
- Immutable audit logging, batch-level dashboards, and shared reporting widgets.
- Bilingual English/Arabic experience with full RTL support (layouts, typography, components, exports).
- Progressive Web App capability with responsive design across desktop, tablet, and mobile, including offline notice and install prompts.

## Sprint Breakdown (6-week MVP)
### Sprint 0 - Initial Deployment & Platform Setup (Week 0)
- **Objectives:** Establish baseline environments, deployment guidelines, and compliance-ready foundations.
- **Key Tasks:**
  - Execute GitHub Actions CI/CD pipeline setup (build/test/security/deploy) as first DevOps action; enforce branch protections.
  - Register and secure free-tier accounts (Render/Railway, PlanetScale, Upstash, Vercel) and document quotas.
  - Define environment topology (Dev/Staging/Prod), networking, and secret management standards; publish deployment runbook v0.
  - Finalize the Render Dockerfile and validate a production build locally or via GitHub Actions.
  - Provision Terraform/IaC skeleton for hosting, database, Redis, and storage resources.
  - Configure baseline Docker Compose stack; validate local parity with staging targets.
  - Draft localization/PWA acceptance criteria and gather bilingual content sources.
- **Exit Criteria:** Deployment playbook approved, pipelines green, baseline environments reachable, localization/PWA requirements captured.

### Sprint 1 - Tenant, Imports & Localization Foundations (Weeks 1-2)
- **Objectives:** Deliver tenant-aware core domain, import flows, and bilingual/RTL scaffolding.
- **Key Tasks:**
  - Implement Company/Employee/PayrollBatch domain models with tenancy middleware and encrypted storage.
  - Integrate Keycloak (OIDC) and Filament Shield policies; seed RBAC roles with locale awareness.
  - Build CSV/XLSX ingestion pipeline with schema validation, preview, and rollback.
  - Establish audit logging (event bus emitters) and baseline metrics probes.
  - Implement localization framework (language files, runtime locale switch, RTL Tailwind config) and responsive design tokens.
  - Stand up initial PWA manifest, service worker shell, and responsive layout smoke tests.
  - QA: write Pest feature tests for tenant isolation, importer validation, bilingual UI coverage.
  - DevOps: configure Render/Railway staging deploy, bind to PlanetScale and Upstash dev resources.
- **Exit Criteria:** Multi-tenant bilingual login, data import wizard, automated tests passing in CI, PWA install prompt and RTL layout validated on staging.

### Sprint 2 - Validation Engine & SIF Generation (Weeks 3-4)
- **Objectives:** Codify WPS/Mudad rules, deliver bank-compliant exports, and deepen PWA capabilities.
- **Key Tasks:**
  - Create rule definition DSL (JSON/YAML) and execution service with Horizon workers.
  - Implement UAE WPS validators, stub KSA Mudad adapter with sandbox endpoints.
  - Develop SIF template library with versioned bank profiles (including Arabic labels where required) and export scheduling.
  - Stream generated files directly to users, exposing download/audit endpoints with locale-aware metadata.
  - Enhance service worker for offline queues, background sync, and push notification hooks.
  - QA: expand regression suite for validation outcomes, SIF golden files, and Lighthouse CI thresholds.
- **Exit Criteria:** Successful bilingual batch validation plus SIF export in staging, bank profiles versioned, queue metrics visible, PWA scores meeting targets.

### Sprint 3 - Exceptions, Reporting & Launch Readiness (Weeks 5-6)
- **Objectives:** Complete exception workflows, reporting, and production readiness with localization/PWA polish.
- **Key Tasks:**
  - Build exception center UI with assignment, SLA timers, activity logs, and bilingual notifications.
  - Implement KPI dashboards (Chart.js) with locale-aware number/date formatting and RTL charts.
  - Harden audit log retention and privacy review (DPIA, encryption validation) including data residency considerations for multilingual exports.
  - Performance tuning: load-test importer and queue throughput, adjust worker scaling, measure Lighthouse performance/SEO/PWA audits for desktop/mobile.
  - DevOps: finalize IaC, blue/green playbooks, secret rotation procedures.
  - QA/PM: coordinate UAT across English/Arabic audiences, capture feedback, prep training collateral, support runbooks, and handover.
- **Exit Criteria:** Exceptions resolved within SLA in staging, KPIs live, PWA & RTL acceptance criteria signed off, security checklist closed, go-live plan approved.

## Detailed Task Backlog (per Workstream)
- **Tenant & Security:** tenancy middleware, policy enforcement hooks, data seeding scripts, penetration test preparation.
- **Localization, RTL & Experience:** translation file management, RTL component audits, responsive breakpoints, accessibility reviews, Lighthouse PWA/regression automation.
- **Data Import & Quality:** file schema registry, anomaly detection (for example, missing salary fields), resumable imports, data purge policy.
- **Validation & SIF Engine:** configuration UI for rule toggles, rule version history, bank profile testing harness, scheduled re-validation jobs.
- **Exceptions & Reporting:** exception triage states (new/in review/resolved), SLA breach alerts, export reconciliation reports, operational dashboards (tooling TBD).
- **Platform & DevOps:** GitHub Actions workflows (lint/test/deploy), IaC for database/redis buckets, incident response runbooks, SAST/DAST gating, observability dashboards.

## Team and Responsibilities
- PM (Accountable): roadmap alignment, stakeholder reporting, risk management.
- PHP Lead (Responsible): architecture decisions, code reviews, milestone delivery.
- Backend Engineer: feature implementation across imports, validation, exports, reporting.
- QA (Shared): test strategy, regression packs, UAT coordination.
- DevOps (Shared): environment provisioning, CI/CD, monitoring integration.
- InfoSec (Consulted): encryption, audit, DPIA sign-offs.
- Support Lead (Informed): runbooks, on-call preparation.

## Risks and Mitigations
- Bank format drift: maintain versioned profile definitions, automated regression tests on export outputs.
- Regulatory changes: externalize rule definitions, schedule monthly compliance review, document change log.
- Data quality gaps: enforce schema validation, highlight anomalies pre-import, track exception SLA.
- Queue bottlenecks: size Horizon workers, add Prometheus alerts on batch duration, support manual rerun tooling.
- Localization/PWA regressions: include lighthouse/i18n checks in CI, schedule bilingual usability testing.
- Tenant isolation defects: add multi-tenant test coverage, perform penetration test focused on access control.

## KPIs
- Payroll batches processed on time (percent meeting submission window).
- Exception resolution lead time (average hours per exception).
- WPS/Mudad rejection rate (percent of submissions rejected by authority/bank).
- MTTR for failed exports.
- PWA Lighthouse scores (performance/accessibility/best practices/SEO) for desktop and mobile.

## Immediate Next Actions
1. Validate deployment topology and free-tier usage assumptions; refine Sprint 0 runbook with DevOps and InfoSec.
2. Confirm bilingual content sources and RTL design requirements with stakeholders; collect sample Arabic payroll artifacts.
3. Specify PWA acceptance criteria (offline scope, caching strategy, install prompts) and align with QA on automated checks.

