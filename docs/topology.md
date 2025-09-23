# Environment Topology

```mermaid
graph TD
    subgraph Client
        Browser
    end

    Browser -->|HTTPS| CloudflareCDN[Cloudflare DNS / SSL]
    CloudflareCDN --> RenderApp[Render Web Service (Laravel)]
    RenderApp -->|MySQL TLS| PlanetScale[(PlanetScale MySQL)]
    RenderApp -->|Redis TLS| Upstash[(Upstash Redis)]
    RenderApp -->|S3 API| CloudflareR2[(Cloudflare R2 Bucket)]
    RenderApp -->|Webhook| Sentry[Sentry]
    RenderApp -->|Metrics| Grafana[Grafana Cloud]
    GitHubActions[GitHub Actions] -->|Deploy Hook| RenderApp
    GitHubActions -->|Artifacts| CloudflareR2
    Developers -->|Vault Secrets| Vault[Team Secret Vault]
    Vault --> GitHubActions
```

**Legend**
- **Render Web Service** hosts the Laravel production app on the free tier.
- **PlanetScale** stores tenant data with branch-based schema management.
- **Upstash Redis** powers queues and caching.
- **Cloudflare R2** retains generated SIF exports and audit artifacts.
- **Sentry / Grafana Cloud** capture app errors and metrics respectively.
- **GitHub Actions** handles CI, artifact publication, and deploy hook execution.
- **Secret Vault** (1Password/Bitwarden) stores credentials referenced by both the app and CI jobs.

Update this diagram when endpoints or integrations change.
