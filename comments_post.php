<?php
session_start();
require_once(__DIR__ . "/config/databaseconnect.php");
require_once(__DIR__ . "/functions.php");
$postData = $_POST;
if (!isset($postData['id']) || !isset($postData['comment']) || empty($postData['comment'])) {
    echo 'les informations sont manquantes';
    return;
}
try {
    $requete = $mysqlClient->prepare(
        "INSERT INTO comments(user_id,recipe_id,comment) VALUES (:u_id,:r_id,:c)"
    );
    $requete->execute([
        "u_id" => $_SESSION['LOGGED_USER']['user_id'],
        "r_id" => $postData['id'],
        "c" => $postData['comment']
    ]);
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}
redirectToUrl("recipes_read.php?id=" . $postData['id']);
