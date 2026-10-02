<?php

// Local launcher helper, not a web endpoint. Tables and records live in SQL files.
if (PHP_SAPI !== 'cli') {
    exit('Run this helper from the terminal.');
}

$port = (int) ($argv[1] ?? 3307);
$expectedDataDir = realpath($argv[2] ?? '');
if ($expectedDataDir === false || $expectedDataDir === '') {
    throw new RuntimeException('A valid local database directory is required.');
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$db = new mysqli('127.0.0.1', 'root', '', '', $port);
$db->set_charset('utf8mb4');
$actualDataDir = realpath($db->query('SELECT @@datadir')->fetch_row()[0]);
if ($actualDataDir === false || strcasecmp($actualDataDir, $expectedDataDir) !== 0) {
    throw new RuntimeException('This port belongs to a different database. Nothing was changed.');
}

$runSqlFile = static function (string $filename) use ($db): void {
    $sql = file_get_contents(dirname(__DIR__) . '/database/' . $filename);
    if ($sql === false) {
        throw new RuntimeException("Could not read $filename.");
    }
    $db->multi_query($sql);
    do {
        if ($result = $db->store_result()) {
            $result->free();
        }
    } while ($db->more_results() && $db->next_result());
};

$exists = $db->query("SELECT SCHEMA_NAME FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = 'rally_supply_ta2'")->num_rows > 0;
if (!$exists) {
    foreach (['schema.sql', 'sample-data.sql'] as $file) {
        $runSqlFile($file);
    }
    echo "Created rally_supply_ta2 with five customers and five users.\n";
} else {
    // Never replace existing records when the launcher is run again.
    $db->select_db('rally_supply_ta2');
    foreach (['customers', 'users'] as $table) {
        $count = $db->query("SELECT COUNT(*) FROM `$table`")->fetch_row()[0];
        echo "Existing $table: $count records (preserved).\n";
    }
}

// The optional POS tables are added safely to existing TA2 databases too.
// Repeated imports preserve product edits, current stock and completed sales.
$runSqlFile('commerce-schema.sql');
$runSqlFile('commerce-seed.sql');
echo "POS catalog and inventory are ready.\n";

if ((int) $db->query('SELECT COUNT(*) FROM staff_credentials')->fetch_row()[0] === 0) {
    $firstUser = $db->query('SELECT id, username FROM users ORDER BY id ASC LIMIT 1')->fetch_assoc();
    if ($firstUser === null) {
        throw new RuntimeException('Add a user account before creating a staff login.');
    }
    $password = bin2hex(random_bytes(12));
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $statement = $db->prepare('INSERT INTO staff_credentials (user_id, password_hash, is_active, created_at) VALUES (?, ?, 1, NOW())');
    $statement->bind_param('is', $firstUser['id'], $hash);
    $statement->execute();
    $credentialsFile = dirname(__DIR__) . '/.local/staff-login.txt';
    file_put_contents($credentialsFile, "Rally Supply local staff login\nUsername: {$firstUser['username']}\nPassword: $password\n");
    echo "First staff login saved to .local/staff-login.txt. Keep it private.\n";
}
