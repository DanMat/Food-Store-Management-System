output "public_ip" {
  description = "Public IP of the instance."
  value       = oci_core_instance.foodmart.public_ip
}

output "url" {
  description = "Live demo URL (once DNS + cert settle)."
  value       = "https://${var.subdomain}.danmat.dev"
}

output "ssh" {
  description = "SSH into the box."
  value       = "ssh ubuntu@${oci_core_instance.foodmart.public_ip}"
}
