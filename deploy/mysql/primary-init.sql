-- Runs once on the primary's first init (docker-entrypoint-initdb.d).
-- Creates the account the replica uses to stream the binlog.
CREATE USER IF NOT EXISTS 'repl'@'%' IDENTIFIED WITH caching_sha2_password BY 'replpass';
GRANT REPLICATION SLAVE ON *.* TO 'repl'@'%';
FLUSH PRIVILEGES;
