<?php
require_once(__DIR__ . "/config/databaseconnect.php");
$sqlQuerys = "SELECT*FROM users ";
// echo $_SESSION['CONN'];
$loginStatement = $mysqlClient->prepare($sqlQuerys);
$loginStatement->execute();
$users = $loginStatement->fetchAll();

$sqlQuery = "SELECT * FROM recipes WHERE is_enabled=true";
// $recipeStatement = $mysqlClient->prepare($sqlQuery);
$recipeStatement = $mysqlClient->prepare($sqlQuery);
$recipeStatement->execute();
$recipes = $recipeStatement->fetchAll();
