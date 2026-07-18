#!/bin/sh
# One-shot: wait for the replica, create the read-only app user, and start
# GTID replication from the primary. Runs over the network (unlike initdb.d).
set -e

echo "Waiting for db-replica to accept connections..."
until mysqladmin ping -h db-replica -uroot -p"$DB_ROOT_PASSWORD" --silent 2>/dev/null; do
  sleep 2
done

echo "Configuring replication (db-primary -> db-replica)..."
mysql -h db-replica -uroot -p"$DB_ROOT_PASSWORD" <<SQL
-- Read-only app account (reads hit the replica). Schema arrives via replication.
CREATE USER IF NOT EXISTS 'foodmart'@'%' IDENTIFIED WITH caching_sha2_password BY '$DB_APP_PASSWORD';
GRANT SELECT ON cart.* TO 'foodmart'@'%';

STOP REPLICA;
RESET REPLICA ALL;
CHANGE REPLICATION SOURCE TO
  SOURCE_HOST='db-primary',
  SOURCE_PORT=3306,
  SOURCE_USER='repl',
  SOURCE_PASSWORD='replpass',
  SOURCE_AUTO_POSITION=1,
  GET_SOURCE_PUBLIC_KEY=1;
START REPLICA;
SQL

echo "Replica configured. Current status:"
mysql -h db-replica -uroot -p"$DB_ROOT_PASSWORD" -e "SHOW REPLICA STATUS\G" | grep -E "Replica_IO_Running|Replica_SQL_Running|Seconds_Behind|Last_Error" || true
