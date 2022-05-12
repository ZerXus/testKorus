<?php
require_once "pdo.php";
ini_set("display_errors", 1);
/**
 * @return array|false
 */
function getDepartments(): bool|array
{
    global $pdo;

    $sql = "SELECT d.id, d.name FROM department d";
    $departments = $pdo->prepare($sql);
    $departments->execute();
    return $departments->fetchAll(PDO::FETCH_ASSOC);
}

$departments = getDepartments();

echo json_encode($departments);