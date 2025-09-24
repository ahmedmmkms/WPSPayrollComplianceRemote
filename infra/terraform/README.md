# Terraform Skeleton

This directory holds the initial infrastructure-as-code scaffolding for the WPS Payroll Compliance environments. The modules are placeholders to be completed once the Render services and associated credentials are finalised.

## Structure
- `main.tf` - root module wiring providers and placeholder child modules.
- `variables.tf` - input variables for project metadata and credentials.
- `outputs.tf` - reserved for future exports once resources exist.
- `modules/render-service` - staging area for Render service provisioning.
- `modules/github-secrets` - manages GitHub Actions secret population for the repository.

## Usage Notes
1. Export provider credentials as environment variables (e.g. `TF_VAR_github_token`) before running `terraform init`.
2. Replace the TODO blocks with concrete resources once the target services are provisioned and IDs captured.
3. Consider adopting a remote backend (Terraform Cloud or S3) before applying changes in shared environments.
4. The skeleton currently assumes two environments (`staging`, `production`). Extend `var.environments` as needed.

## Secret Inputs
- Copy `secrets.auto.tfvars.example` to `secrets.auto.tfvars` and populate credentials locally, or rely solely on environment variables. The real file is gitignored; store it securely.
- Populating `var.github_actions_secrets` now provisions/upserts GitHub Actions secrets via the `github-secrets` module.
