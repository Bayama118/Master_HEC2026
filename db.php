<?php
function getPdo(): PDO
{
    $dbFile = __DIR__ . '/library.sqlite';
    if (!file_exists($dbFile)) {
        throw new RuntimeException('SQLite database file not found: ' . $dbFile);
    }
    $dsn = 'sqlite:' . $dbFile;

    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    ensureDatabaseSchema($pdo);

    return $pdo;
}

function ensureDatabaseSchema(PDO $pdo): void
{
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS salles (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nom TEXT NOT NULL UNIQUE,
            capacite INTEGER NOT NULL,
            description TEXT,
            emplacement TEXT,
            equipements TEXT,
            actif INTEGER NOT NULL DEFAULT 1
        )"
    );

    $columns = $pdo->query("PRAGMA table_info(salles)")->fetchAll(PDO::FETCH_ASSOC);
    $existing = [];
    foreach ($columns as $column) {
        $existing[$column['name']] = true;
    }

    $required = [
        'emplacement' => "ALTER TABLE salles ADD COLUMN emplacement TEXT",
        'equipements' => "ALTER TABLE salles ADD COLUMN equipements TEXT",
        'actif' => "ALTER TABLE salles ADD COLUMN actif INTEGER NOT NULL DEFAULT 1",
    ];

    foreach ($required as $name => $alter) {
        if (!isset($existing[$name])) {
            $pdo->exec($alter);
        }
    }
}
