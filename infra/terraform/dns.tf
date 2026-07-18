# Point foodmart.danmat.dev at the instance's public IP.
# Proxied is OFF so Caddy can complete the Let's Encrypt HTTP-01 challenge and
# issue a real cert. You can enable the Cloudflare proxy later (orange cloud)
# with SSL/TLS mode "Full (strict)".
resource "cloudflare_record" "foodmart" {
  zone_id = var.cloudflare_zone_id
  name    = var.subdomain
  type    = "A"
  value   = oci_core_instance.foodmart.public_ip
  ttl     = 300
  proxied = false
  comment = "Dragonix Foodmart live demo (Terraform-managed)"
}
