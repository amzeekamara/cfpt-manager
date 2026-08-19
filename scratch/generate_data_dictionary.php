<?php

$mysql = new PDO('mysql:host=127.0.0.1;port=3306;dbname=pfpt_db;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Exclude internal technical cache/migration tables if needed, or list all business & system tables
$tablesStmt = $mysql->query("
    SELECT table_name 
    FROM information_schema.tables 
    WHERE table_schema = 'pfpt_db' 
      AND table_name NOT IN ('migrations', 'cache_locks', 'job_batches')
    ORDER BY table_name
");
$tables = $tablesStmt->fetchAll(PDO::FETCH_COLUMN);

$output = "# DICTIONNAIRE DES DONNÉES - SCHÉMA EXACT MYSQL (pfpt_db)\n\n";

foreach ($tables as $table) {
    $output .= "### Table : `{$table}`\n\n";
    $output .= "| Champ technique | Type de données | Nullable | Défaut | Clé | Description métier |\n";
    $output .= "| :--- | :--- | :--- | :--- | :--- | :--- |\n";

    $colsStmt = $mysql->prepare("
        SELECT 
            COLUMN_NAME, 
            COLUMN_TYPE, 
            IS_NULLABLE, 
            COLUMN_DEFAULT, 
            COLUMN_KEY, 
            EXTRA,
            COLUMN_COMMENT
        FROM information_schema.columns 
        WHERE table_schema = 'pfpt_db' AND table_name = ?
        ORDER BY ORDINAL_POSITION
    ");
    $colsStmt->execute([$table]);
    $cols = $colsStmt->fetchAll(PDO::FETCH_ASSOC);

    // Get Foreign Keys
    $fkStmt = $mysql->prepare("
        SELECT COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
        FROM information_schema.key_column_usage
        WHERE table_schema = 'pfpt_db' AND table_name = ? AND REFERENCED_TABLE_NAME IS NOT NULL
    ");
    $fkStmt->execute([$table]);
    $fks = [];
    foreach ($fkStmt->fetchAll(PDO::FETCH_ASSOC) as $fkRow) {
        $fks[$fkRow['COLUMN_NAME']] = "FK -> {$fkRow['REFERENCED_TABLE_NAME']}({$fkRow['REFERENCED_COLUMN_NAME']})";
    }

    foreach ($cols as $col) {
        $name = $col['COLUMN_NAME'];
        $type = strtoupper($col['COLUMN_TYPE']);
        $nullable = $col['IS_NULLABLE'] === 'YES' ? 'Oui' : 'Non';
        $default = $col['COLUMN_DEFAULT'] !== null ? "`{$col['COLUMN_DEFAULT']}`" : 'NULL';
        
        $key = '-';
        if ($col['COLUMN_KEY'] === 'PRI') {
            $key = '**PK** (Auto-increment)';
        } elseif (isset($fks[$name])) {
            $key = "**{$fks[$name]}**";
        } elseif ($col['COLUMN_KEY'] === 'UNI') {
            $key = 'UNIQUE';
        } elseif ($col['COLUMN_KEY'] === 'MUL') {
            $key = 'INDEX';
        }

        $description = match(true) {
            $name === 'id' => "Identifiant unique de la table `{$table}`",
            $name === 'created_at' => "Date et heure de création de l'enregistrement",
            $name === 'updated_at' => "Date et heure de dernière modification",
            $name === 'name' => "Nom ou intitulé",
            $name === 'title' => "Titre officiel",
            $name === 'email' => "Adresse de messagerie électronique (unique)",
            $name === 'password' => "Mot de passe haché en Bcrypt",
            $name === 'role_id' => "Référence au rôle attribué à l'utilisateur",
            $name === 'cohort_id' => "Référence à la cohorte / édition annuelle rattachée",
            $name === 'seminar_id' => "Référence au séminaire de formation rattaché",
            $name === 'user_id' => "Référence au compte utilisateur rattaché",
            $name === 'status' => "Statut de l'élément (ex: active, pending, approved, draft...)",
            default => ucfirst(str_replace('_', ' ', $name))
        };

        $output .= "| `{$name}` | `{$type}` | {$nullable} | {$default} | {$key} | {$description} |\n";
    }
    $output .= "\n";
}

file_put_contents('scratch/dictionnaire_donnees_pfpt.md', $output);
echo "Dictionnaire des données généré avec succès dans scratch/dictionnaire_donnees_pfpt.md\n";
