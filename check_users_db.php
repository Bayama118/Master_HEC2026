<?php
try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/library.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $res = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'")->fetchAll(PDO::FETCH_ASSOC);
    echo count($res) ? "users_exists\n" : "no_users\n";
    if (count($res)) {
        $rows = $pdo->query('SELECT id,email,nom,prenom FROM users')->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            echo json_encode($row) . "\n";
        }
    }
} catch (Exception $e) {
    echo 'ERR: ' . $e->getMessage() . "\n";
}
