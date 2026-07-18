# Deploy — the modern Foodmart stack

Reproduces the original project's server-level architecture with modern,
containerized equivalents. One command brings up the whole thing.

```
        ┌──────────── Caddy (TLS + gzip + load balancer) ────────────┐
client ─▶  round-robins every PHP request across two app nodes        │
        └───────────────┬────────────────────────┬───────────────────┘
                     app1 (app-1)             app2 (app-2)   ← PHP 8.3-FPM
                        │ writes                  │ writes
                 reads  ▼                  reads  ▼
              db-replica ◀────── GTID replication ────── db-primary
                        └────────── redis (cache + sessions) ──────────┘
```

| Original (2010s) | Now |
|---|---|
| Apache `mod_proxy_balancer` "switcher" | **Caddy** `php_fastcgi` round-robin across 2 FPM nodes |
| Two copied app folders (`_2`) | One image, **two app services** (`app1`/`app2`) |
| MySQL replication + sharding | **MySQL 8 primary → replica** (GTID), read/write split |
| Memcache | **Redis** (cache + shared sessions → no sticky LB) |
| Apache SSL cert | **Automatic HTTPS** (Let's Encrypt via Caddy) |
| `balancer-manager` | app "served by app-1/app-2" banner + `/architecture` (coming) |

## Run it

```sh
cd deploy
cp .env.example .env          # DOMAIN=localhost for local
docker compose up -d --build
# open http://localhost
```

On the VM, Terraform's cloud-init sets `DOMAIN=foodmart.danmat.dev` and runs the same command.

## First-boot notes
- **Replication:** the `replica-setup` one-shot points the replica at the primary via GTID once both are healthy. Verify with:
  `docker compose exec db-replica mysql -uroot -prootpw -e "SHOW REPLICA STATUS\G"` — both `Replica_IO_Running` and `Replica_SQL_Running` should be `Yes`. (The app also falls back to the primary for reads if the replica isn't ready.)
- **Writable dirs:** the cart writes to `orders/` and `prodgfx/`. If uploads/checkout fail on perms, `chmod -R a+rw Dragonix_Foodmart/{orders,prodgfx,addons}`.
- **DB ports** are intentionally not published; use `docker compose exec` to poke them.
