<?php
require __DIR__ . '/db.php';
try {
    $pdo = getPdo();
    echo "OK CONNECT\n";
    $rows = $pdo->query('PRAGMA table_info(salles)')->fetchAll(PDO::FETCH_ASSOC);
    if (!$rows) {
        echo "NO TABLE\n";
        exit(0);
    }
    foreach ($rows as $row) {
        echo $row['name'] . ' | ' . $row['type'] . ' | ' . $row['notnull'] . "\n";
    }
    $count = $pdo->query('SELECT COUNT(*) as c FROM salles')->fetchColumn();
    echo "COUNT = $count\n";
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
}
