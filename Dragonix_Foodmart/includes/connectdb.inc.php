<?php
    // Load the modern PDO layer + the legacy compatibility bridge + cache.
    require_once __DIR__ . '/php8-polyfills.php';
    require_once __DIR__ . '/db.php';
    require_once __DIR__ . '/mysql_compat.php';
    require_once __DIR__ . '/cache.php';

    // Establish (and verify) the primary connection.
    try {
        $db = db_pdo('write');
    } catch (PDOException $e) {
        http_response_code(503);
        echo "<h1>Database unavailable</h1><p>Please try again in a moment.</p>";
        exit;
    }
?>
