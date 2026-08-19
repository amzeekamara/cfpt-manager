<?php

$sqlite = new PDO('sqlite:database/database.sqlite');
$rows = $sqlite->query("SELECT * FROM seminars")->fetchAll(PDO::FETCH_ASSOC);
print_r($rows);

$depts = $sqlite->query("SELECT * FROM departments")->fetchAll(PDO::FETCH_ASSOC);
print_r($depts);
