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
    GitHubActions[GitHub Actions] -->|Deploy Hook| RenderApp
```

**Legend**
- **Render Web Service** hosts the Laravel production app on the free tier.
- **PlanetScale** stores tenant data with branch-based schema management.
- **Upstash Redis** powers queues and caching.
- **Observability** is manual via Render logs until a monitoring stack is selected.
- **On-demand exports** are streamed directly from the Laravel app; rerun jobs to regenerate historical files.
- **GitHub Actions** handles CI, artifact publication, and deploy hook execution.

Update this diagram when endpoints or integrations change.
