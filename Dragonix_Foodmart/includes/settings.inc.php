<?php
    // Database settings — read from the environment (Docker) with local fallbacks.
    $dbtablesprefix = "aws_";
    $dblocation = getenv('DB_WRITE_HOST') ?: "localhost";
    $dbname     = getenv('DB_NAME')       ?: "cart";
    $dbuser     = getenv('DB_USER')       ?: "root";
    $dbpass     = getenv('DB_PASS');
    if ($dbpass === false) { $dbpass = ""; }
?>
