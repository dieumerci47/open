<?php
// session_start();
session_start();
require_once(__DIR__ . '/variables.php');
require_once(__DIR__ . '/functions.php');
session_destroy();
redirectToUrl("index.php")
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

</body>

</html>