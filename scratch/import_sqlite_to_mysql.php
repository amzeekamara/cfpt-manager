<?php

$sqlite = new PDO('sqlite:database/database.sqlite');
$mysql = new PDO('mysql:host=127.0.0.1;port=3306;dbname=pfpt_db;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Disable foreign key checks in MySQL
$mysql->exec("SET FOREIGN_KEY_CHECKS = 0;");

$tables = [
    'roles',
    'users',
    'cohorts',
    'seminars',
    'departments',
    'topics',
    'department_seminar',
    'trainers',
    'candidate_profiles',
    'registrations',
    'educations',
    'application_documents',
    'form_templates',
    'form_submissions',
    'expense_categories',
    'expenses',
    'messages',
    'message_user',
    'seminar_trainer',
    'trainer_sessions',
    'attendances',
    'resources',
    'activity_logs',
    'notifications',
    'seminar_change_requests',
];

foreach ($tables as $table) {
    echo "Syncing table: {$table}...\n";
    
    // Empty MySQL table
    $mysql->exec("TRUNCATE TABLE `{$table}`");
    
    // Fetch all from SQLite
    $stmt = $sqlite->query("SELECT * FROM `{$table}`");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($rows)) {
        echo "  0 rows copied.\n";
        continue;
    }
    
    $columns = array_keys($rows[0]);
    $colList = implode('`, `', $columns);
    $placeholders = implode(', ', array_fill(0, count($columns), '?'));
    
    $insertSql = "INSERT INTO `{$table}` (`{$colList}`) VALUES ({$placeholders})";
    $insertStmt = $mysql->prepare($insertSql);
    
    $count = 0;
    foreach ($rows as $row) {
        $insertStmt->execute(array_values($row));
        $count++;
    }
    
    echo "  {$count} rows successfully transferred.\n";
}

// Ensure admin user does not have must_change_password = 1 so they don't get trapped
$mysql->exec("UPDATE `users` SET `must_change_password` = 0 WHERE `email` = 'admin@pfpt.sn'");

// Enable foreign key checks
$mysql->exec("SET FOREIGN_KEY_CHECKS = 1;");

echo "\n>>> ALL SQLITE DATA SUCCESSFULLY RESTORED TO MYSQL pfpt_db! <<<\n";
