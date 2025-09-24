variable "repository" {
  type        = string
  description = "Repository in owner/name format."

  validation {
    condition     = length(split("/", var.repository)) == 2
    error_message = "repository must be provided as owner/name."
  }
}

variable "secrets" {
  type        = map(string)
  description = "Map of GitHub Actions secrets to populate. Values should be pulled from a secure source at apply-time."
  default     = {}
  sensitive   = true
}

locals {
  repo_parts = split("/", var.repository)
  repo_name  = element(local.repo_parts, 1)

  valid_secrets = {
    for key, value in var.secrets :
    key => trimspace(value)
    if trimspace(value) != "" && !startswith(trimspace(value), "<")
  }
}

resource "github_actions_secret" "this" {
  for_each        = local.valid_secrets
  repository      = local.repo_name
  secret_name     = each.key
  plaintext_value = each.value
}
