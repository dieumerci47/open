<?php
session_start();
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');
// require_once(__DIR__ . '/mysql.php');
require_once(__DIR__ . '/config/databaseconnect.php');

/**
 * On ne traite pas les super globales provenant de l'utilisateur directement,
 * ces données doivent être testées et vérifiées.
 */

$postData = $_POST;


// Validation du formulaire
if (isset($postData['email']) &&  isset($postData['password'])) {
    if (!filter_var($postData['email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Il faut un email valide pour soumettre le formulaire.';
    } else {
        $sqlQuerys = "SELECT*FROM users WHERE email=:email AND `password`=:password ";
        // echo $_SESSION['CONN'];
        $loginStatement = $mysqlClient->prepare($sqlQuerys);
        $loginStatement->execute([
            'email' => $postData['email'],
            'password' => $postData['password']
        ]);
        $login = $loginStatement->fetchAll();
        /*  echo '<pre>';
        print_r($login[0]);
        echo '</pre>'; */
        // echo $login;
        if (isset($login[0])) {
            $_SESSION['LOGGED_USER'] = [
                'email' => $login[0]['email'],
                'user_id' => $login[0]['user_id'],
            ];
        }
        /*  foreach ($users as $user) {
            if (
                $user['email'] === $postData['email'] &&
                $user['password'] === $postData['password']
            ) {
                $_SESSION['LOGGED_USER'] = [
                    'email' => $user['email'],
                    'user_id' => $user['user_id'],
                ];
            }
        } */
        if (!isset($_SESSION['LOGGED_USER'])) {
            $_SESSION['LOGIN_ERROR_MESSAGE'] = sprintf(
                'Les informations envoyées ne permettent pas de vous identifier : (%s/%s)',
                $postData['email'],
                strip_tags($postData['password'])
            );
        }
    }
    redirectToUrl('index.php');
}
