<?php

/**
 * On ne traite pas les super globales provenant de l'utilisateur directement,
 * ces données doivent être testées et vérifiées.
 */

// Vérification de la méthode HTTP utilisée
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$fileData = $_FILES;

// Récupération des données selon la méthode
if ($method === 'POST') {
    $postData = $_POST;
} else {
    // Si ce n'est pas POST, rediriger vers le formulaire
    header('Location: contact.php');
    exit;
}

// Vérification si le formulaire a été soumis
if (empty($postData)) {
    echo ('Erreur : Aucune donnée reçue. Méthode utilisée : ' . htmlspecialchars($method));
    echo ('<br><a href="contact.php">Retour au formulaire</a>');
    return;
}

// Validation des données
if (
    !isset($postData['email'])
    || !isset($postData['message'])
    || !filter_var($postData['email'], FILTER_VALIDATE_EMAIL)
    || empty($postData['message'])
    || trim($postData['message']) === ''
) {
    echo ('Il faut un email et un message valides pour soumettre le formulaire.');
    echo ('<br><a href="contact.php">Retour au formulaire</a>');
    return;
}

$nameFile = null;
if (isset($fileData['screenshot']) && $fileData['screenshot']['error'] == 0) {
    // echo("Erreur lors de l'envoie du fichier");
    if ($fileData['screenshot']['size'] > 1000000) {
        echo ("Fichier trop volumineux ! Minimum requis 1 MO");
        return;
    }
    $fileInfo = pathinfo($fileData['screenshot']['name']);
    $extension = $fileInfo['extension'];
    $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
    if (!in_array($extension, $allowedExtensions)) {
        echo "L'envoi n'a pas pu être effectué, l'extension {$extension} n'est pas autorisée";
        return;
    }
    $path = __DIR__ . '/uploads/';
    if (!is_dir($path)) {
        echo "L'envoi n'a pas pu être effectué, le dossier uploads est manquant";
        return;
    }
    $nameFile = basename($fileData['screenshot']['name']);
    move_uploaded_file($fileData['screenshot']['tmp_name'], $path . $nameFile);
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de Recettes - Contact reçu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">

        <?php require_once(__DIR__ . '/header.php'); ?>
        <h1>Message bien reçu !</h1>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Rappel de vos informations</h5>
                <p class="card-text"><b>Email</b> : <?php echo (strip_tags($postData['email'])); ?></p>
                <p class="card-text"><b>Message</b> : <?php echo (htmlspecialchars($postData['message'])); ?></p>
                <?php if ($nameFile !== null): ?>
                    <img class="card-img" src="./uploads/<?php echo htmlspecialchars($nameFile, ENT_QUOTES, 'UTF-8'); ?>" alt="Capture d'écran jointe" height="350">
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>