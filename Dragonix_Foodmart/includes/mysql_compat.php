<?php
/**
 * Legacy mysql_* API, backed by the PDO layer (db.php). This is a MIGRATION
 * BRIDGE: it lets the 30+ legacy files run on PHP 8 immediately, and it already
 * demonstrates read/write splitting (SELECTs go to the read replica, everything
 * else to the primary). Converting the individual call sites to prepared
 * statements is the ongoing security refactor.
 */

if (!defined('MYSQL_ASSOC')) define('MYSQL_ASSOC', PDO::FETCH_ASSOC);
if (!defined('MYSQL_NUM'))   define('MYSQL_NUM',   PDO::FETCH_NUM);
if (!defined('MYSQL_BOTH'))  define('MYSQL_BOTH',  PDO::FETCH_BOTH);

$GLOBALS['__mysql_last_error'] = '';
// Read-your-writes: once this request has written to the primary, its later
// reads must hit the primary too (the replica may still be catching up).
$GLOBALS['__db_wrote'] = false;

function mysql_connect($host = null, $user = null, $pass = null)
{
    return db_pdo('write'); // db name is part of the DSN
}

function mysql_select_db($name = null, $link = null): bool
{
    return true;
}

function mysql_error($link = null): string
{
    return $GLOBALS['__mysql_last_error'];
}

/** SELECTs -> replica, everything else -> primary. Returns a PDOStatement or false. */
function mysql_query($sql, $link = null)
{
    $isRead = (bool) preg_match('/^\s*\(?\s*(SELECT|SHOW|DESCRIBE|EXPLAIN)\b/i', (string) $sql);
    // Reads use the replica ONLY if this request hasn't written yet.
    $useReplica = $isRead && !$GLOBALS['__db_wrote'];
    if (!$isRead) {
        $GLOBALS['__db_wrote'] = true;
    }
    $pdo  = db_pdo($useReplica ? 'read' : 'write');
    $stmt = $pdo->query($sql);
    if ($stmt === false) {
        $info = $pdo->errorInfo();
        $GLOBALS['__mysql_last_error'] = $info[2] ?? 'query error';
        return false;
    }
    return $stmt;
}

function mysql_fetch_array($result, $type = PDO::FETCH_BOTH)
{
    return $result instanceof PDOStatement ? $result->fetch($type) : false;
}

function mysql_fetch_assoc($result)
{
    return $result instanceof PDOStatement ? $result->fetch(PDO::FETCH_ASSOC) : false;
}

function mysql_fetch_row($result)
{
    return $result instanceof PDOStatement ? $result->fetch(PDO::FETCH_NUM) : false;
}

function mysql_fetch_object($result)
{
    return $result instanceof PDOStatement ? $result->fetch(PDO::FETCH_OBJ) : false;
}

function mysql_num_rows($result): int
{
    return $result instanceof PDOStatement ? $result->rowCount() : 0;
}

function mysql_insert_id($link = null)
{
    return db_pdo('write')->lastInsertId();
}

function mysql_affected_rows($link = null): int
{
    return 0;
}

function mysql_real_escape_string($str, $link = null): string
{
    $quoted = db_pdo('write')->quote((string) $str);
    return substr($quoted, 1, -1); // strip the surrounding quotes
}

function mysql_result($result, $row, $field = 0)
{
    static $cache = null;
    if (!($result instanceof PDOStatement)) return false;
    $all = $result->fetchAll(PDO::FETCH_BOTH);
    return $all[$row][$field] ?? false;
}

function mysql_free_result($result): bool
{
    if ($result instanceof PDOStatement) $result->closeCursor();
    return true;
}
