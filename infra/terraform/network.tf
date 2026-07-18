# A minimal public network: VCN -> Internet Gateway -> public subnet, with a
# security list that only opens SSH (22) and the web ports (80/443).

resource "oci_core_vcn" "foodmart" {
  compartment_id = var.compartment_ocid
  cidr_blocks    = ["10.0.0.0/16"]
  display_name   = "foodmart-vcn"
  dns_label      = "foodmart"
}

resource "oci_core_internet_gateway" "foodmart" {
  compartment_id = var.compartment_ocid
  vcn_id         = oci_core_vcn.foodmart.id
  display_name   = "foodmart-igw"
}

resource "oci_core_route_table" "foodmart" {
  compartment_id = var.compartment_ocid
  vcn_id         = oci_core_vcn.foodmart.id
  display_name   = "foodmart-rt"

  route_rules {
    destination       = "0.0.0.0/0"
    destination_type  = "CIDR_BLOCK"
    network_entity_id = oci_core_internet_gateway.foodmart.id
  }
}

resource "oci_core_security_list" "foodmart" {
  compartment_id = var.compartment_ocid
  vcn_id         = oci_core_vcn.foodmart.id
  display_name   = "foodmart-sl"

  egress_security_rules {
    destination = "0.0.0.0/0"
    protocol    = "all"
  }

  # SSH
  ingress_security_rules {
    protocol = "6" # TCP
    source   = "0.0.0.0/0"
    tcp_options {
      min = 22
      max = 22
    }
  }

  # HTTP + HTTPS (Caddy terminates TLS and serves the load balancer)
  ingress_security_rules {
    protocol = "6"
    source   = "0.0.0.0/0"
    tcp_options {
      min = 80
      max = 80
    }
  }
  ingress_security_rules {
    protocol = "6"
    source   = "0.0.0.0/0"
    tcp_options {
      min = 443
      max = 443
    }
  }
}

resource "oci_core_subnet" "foodmart" {
  compartment_id    = var.compartment_ocid
  vcn_id            = oci_core_vcn.foodmart.id
  cidr_block        = "10.0.1.0/24"
  display_name      = "foodmart-public-subnet"
  dns_label         = "public"
  route_table_id    = oci_core_route_table.foodmart.id
  security_list_ids = [oci_core_security_list.foodmart.id]
}
