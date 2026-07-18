# Infrastructure (Terraform)

Provisions the live demo entirely as code:

- **OCI (Oracle Cloud, Always Free):** an ARM (Ampere A1) VM, a VCN + public subnet + internet gateway + route table, and a security list opening 22/80/443.
- **cloud-init:** installs Docker, opens the host firewall, clones this repo, and runs the Compose stack (Caddy load balancer → 2 app replicas → MySQL primary/replica → Redis).
- **Cloudflare:** an `A` record `foodmart.danmat.dev` → the VM's public IP.

`terraform apply` → a live, load-balanced, replicated store.

## One-time setup

1. **Oracle Cloud** — create a free account, then **Profile → API Keys → Add API Key**. Download the private key to `~/.oci/oci_api_key.pem` and note the tenancy/user OCIDs, fingerprint, and region.
2. **Cloudflare** — create an API token with **Zone → DNS → Edit** on `danmat.dev`; grab the zone ID from the DNS page sidebar.
3. **SSH key** — `ssh-keygen -t ed25519` if you don't have one; use the `.pub` contents.

## Deploy

```sh
cd infra/terraform
cp terraform.tfvars.example terraform.tfvars   # fill in your values (gitignored)
terraform init
terraform plan       # review what will be created
terraform apply      # creates everything
terraform output url # https://foodmart.danmat.dev
```

First boot takes a few minutes (Docker install + image builds + cert issuance). `terraform destroy` tears it all down.

> Nothing is created until **you** run `terraform apply` with your own credentials.
