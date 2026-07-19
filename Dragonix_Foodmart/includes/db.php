<?php
/**
 * Modern PDO data layer.
 *   - Writes  -> primary   (DB_WRITE_HOST)
 *   - Reads   -> replica   (DB_READ_HOST), falling back to the primary if the
 *                replica isn't ready — so the demo stays up while replication
 *                catches up.
 */

function db_config(): array
{
    $write = getenv('DB_WRITE_HOST') ?: 'localhost';
    return [
        'write' => $write,
        'read'  => getenv('DB_READ_HOST') ?: $write,
        'name'  => getenv('DB_NAME') ?: 'cart',
        'user'  => getenv('DB_USER') ?: 'root',
        'pass'  => getenv('DB_PASS') !== false ? getenv('DB_PASS') : '',
    ];
}

/** Get a pooled PDO for 'write' (primary) or 'read' (replica w/ fallback). */
function db_pdo(string $mode = 'write'): PDO
{
    static $pool = [];
    $cfg  = db_config();
    $host = $mode === 'read' ? $cfg['read'] : $cfg['write'];
    $key  = $mode . '@' . $host;

    if (isset($pool[$key])) {
        return $pool[$key];
    }

    $dsn = "mysql:host={$host};dbname={$cfg['name']};charset=utf8mb4";
    try {
        $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_SILENT,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        // Reads fall back to the primary when the replica is unavailable.
        if ($mode === 'read' && $host !== $cfg['write']) {
            return db_pdo('write');
        }
        throw $e;
    }

    return $pool[$key] = $pdo;
}
