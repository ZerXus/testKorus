<?php
$host = 'localhost';
$charset = 'utf8';

$database = 'testKorus';
$user = 'sanya9_23';
$pass = 'sanya9_23';

$dsn = "mysql:host=$host;dbname=$database;charset=$charset";

$pdo = new PDO($dsn, $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);