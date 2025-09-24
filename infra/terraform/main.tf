terraform {
  required_version = ">= 1.6.0"

  required_providers {
    github = {
      source  = "integrations/github"
      version = "~> 5.43"
    }

    planetscale = {
      source  = "planetscale/planetscale"
      version = "~> 0.2"
    }

    upstash = {
      source  = "upstash/upstash"
      version = "~> 1.3"
    }

    render = {
      source  = "render-oss/render"
      version = "~> 1.0"
    }
  }
}

locals {
  project_slug = var.project_name
  environments = var.environments
}

# Configure providers (credentials supplied via environment variables or TF Cloud workspaces)
provider "github" {
  owner = split("/", var.github_repository)[0]
  token = var.github_token
}

provider "planetscale" {
  service_token_id     = var.planetscale_service_token_id
  service_token_secret = var.planetscale_service_token_secret
}

provider "upstash" {}

provider "render" {
  api_key = var.render_api_key
}

# Placeholder modules for each environment. Flesh out with actual resources once schemas are defined.
module "render_services" {
  source = "./modules/render-service"

  for_each      = toset(local.environments)
  project_slug  = local.project_slug
  environment   = each.key
  docker_image  = var.render_docker_image
  deploy_hook   = var.render_deploy_hook
}

module "secrets" {
  source = "./modules/github-secrets"

  repository = var.github_repository
  secrets    = var.github_actions_secrets
}
