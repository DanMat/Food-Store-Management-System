# ---- OCI auth (from your Oracle Cloud API key) ----
variable "tenancy_ocid" {
  type        = string
  description = "OCID of your tenancy."
}

variable "user_ocid" {
  type        = string
  description = "OCID of the user the API key belongs to."
}

variable "fingerprint" {
  type        = string
  description = "Fingerprint of the API signing key."
}

variable "private_key_path" {
  type        = string
  description = "Path to the OCI API private key (PEM)."
  default     = "~/.oci/oci_api_key.pem"
}

variable "region" {
  type        = string
  description = "OCI region, e.g. us-ashburn-1."
}

variable "compartment_ocid" {
  type        = string
  description = "Compartment to create resources in (often the tenancy OCID for a free account)."
}

# ---- Instance sizing (kept within the Always Free ARM allowance) ----
variable "instance_ocpus" {
  type        = number
  description = "ARM (A1.Flex) OCPUs. Always-Free budget is 4 total across A1 instances."
  default     = 2
}

variable "instance_memory_gb" {
  type        = number
  description = "Memory in GB. Always-Free budget is 24 total across A1 instances."
  default     = 12
}

variable "ssh_public_key" {
  type        = string
  description = "SSH public key to install on the instance (contents, not a path)."
}

# ---- App / bootstrap ----
variable "repo_url" {
  type        = string
  description = "Public Git repo cloned by cloud-init to run the stack."
  default     = "https://github.com/DanMat/Food-Store-Management-System.git"
}

# ---- Cloudflare DNS ----
variable "cloudflare_api_token" {
  type        = string
  description = "Cloudflare API token with DNS edit on the danmat.dev zone."
  sensitive   = true
}

variable "cloudflare_zone_id" {
  type        = string
  description = "Cloudflare Zone ID for danmat.dev."
}

variable "subdomain" {
  type        = string
  description = "Subdomain for the live demo."
  default     = "foodmart"
}
