<?php

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=laravel', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $result = $pdo->query("SHOW TABLES LIKE 'service_credits'");
    if ($result->rowCount() > 0) {
        echo "Table 'service_credits' exists\n";
        $columns = $pdo->query('DESCRIBE service_credits');
        echo "Columns:\n";
        foreach ($columns as $column) {
            echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
        }
    } else {
        echo "Table 'service_credits' does not exist\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
