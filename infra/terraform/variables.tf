variable "project_name" {
  type        = string
  description = "Slug used across infrastructure resources."
  default     = "wps-payroll"
}

variable "environments" {
  type        = list(string)
  description = "Environments managed by IaC."
  default     = ["staging", "production"]
}

variable "github_repository" {
  type        = string
  description = "Repository in owner/name format."
  default     = "ahmedmmkms/WPSPayrollComplianceRemote"
}

variable "github_token" {
  type        = string
  description = "GitHub personal access token with repo and actions:write scopes. Prefer loading via TF_VAR or workspace variables."
  sensitive   = true
  default     = null
}

variable "planetscale_service_token_id" {
  type        = string
  description = "PlanetScale service token ID used for API access."
  sensitive   = true
  default     = null
}

variable "planetscale_service_token_secret" {
  type        = string
  description = "PlanetScale service token secret."
  sensitive   = true
  default     = null
}

variable "render_api_key" {
  type        = string
  description = "Render API key with access to the project."
  sensitive   = true
  default     = null
}

variable "render_docker_image" {
  type        = string
  description = "Container image tag to deploy via Render once published to a registry."
  default     = "ghcr.io/example/wps-payroll:latest"
}

variable "render_deploy_hook" {
  type        = string
  description = "Render deploy hook URL for manual triggers."
  sensitive   = true
  default     = null
}

variable "github_actions_secrets" {
  type        = map(string)
  description = "Map of GitHub Actions secrets that should be managed via Terraform."
  sensitive   = true
  default     = {}
}
