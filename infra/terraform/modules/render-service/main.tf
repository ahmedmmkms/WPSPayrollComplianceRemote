variable "project_slug" {
  type = string
}

variable "environment" {
  type = string
}

variable "docker_image" {
  type        = string
  description = "Container image reference (e.g. ghcr.io/org/app:tag)."
}

variable "deploy_hook" {
  type        = string
  sensitive   = true
  default     = null
}

variable "region" {
  type        = string
  description = "Render region slug."
  default     = "oregon"
}

variable "branch" {
  type        = string
  description = "Git branch that should trigger auto-deploys."
  default     = "master"
}

variable "environment_variables" {
  type        = map(string)
  description = "Key/value environment variables to apply to the service."
  default     = {}
}

# TODO: Add render_service resources once the staging and production services are available.
# resource "render_service" "app" {
#   name        = "${var.project_slug}-${var.environment}"
#   type        = "web_service"
#   plan        = "free"
#   region      = var.region
#   branch      = var.branch
#   service_env = var.environment
#   image       = var.docker_image
# }
