#!/bin/sh
# One-shot: wait for the replica, create the read-only app user, and start
# GTID replication from the primary. Runs over the network (unlike initdb.d).
set -e

echo "Waiting for db-replica to accept connections..."
until mysqladmin ping -h db-replica -uroot -p"$DB_ROOT_PASSWORD" --silent 2>/dev/null; do
  sleep 2
done

echo "Configuring replication (db-primary -> db-replica)..."
# NOTE: do NOT pre-create the app user here — replication replays the primary's
# own CREATE USER (from the initdb), and a duplicate would stop the SQL thread.
# The schema, the foodmart account, and its grants all arrive via replication.
mysql -h db-replica -uroot -p"$DB_ROOT_PASSWORD" <<SQL
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
