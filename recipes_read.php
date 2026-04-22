<?php
session_start();
require_once(__DIR__ . "/config/databaseconnect.php");
$postData = $_GET;
if (!isset($postData['id']) || !is_numeric($postData['id'])) {
    echo "La valeur de l'id n'est pas reconnue";
    return;
}
$recipeState = $mysqlClient->prepare('SELECT r.* FROM recipes r WHERE r.recipe_id = :id');
$recipeState->execute([
    'id' => (int)$postData['id'],
]);
$recipe = $recipeState->fetch();
$commentState = $mysqlClient->prepare('SELECT full_name,comment FROM users u JOIN comments c ON u.user_id=c.user_id JOIN recipes r ON r.recipe_id=c.recipe_id WHERE c.recipe_id=:id');
$commentState->execute([
    'id' => (int)$postData['id'],
]);
$comments = $commentState->fetchAll();

if (!$recipe) {
    echo ('La recette n\'existe pas');
    return;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de recettes - Page d'accueil</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <div class="container">

        <?php require_once(__DIR__ . '/header.php'); ?>
        <h1><?php echo ($recipe['title']); ?></h1>
        <div class="row">
            <article class="col">
                <?php echo ($recipe['recipe']); ?>
            </article>
            <aside class="col">
                <p><i>Contribuée par <?php echo ($recipe['author']); ?></i></p>
            </aside>

        </div>
        <hr />
        <?php if (count($comments) > 0): ?>
            <h2>Les Commentaires...</h2>
            <div class="row">
                <?php foreach ($comments as $comment) : ?>
                    <div class="comment">

                        <p><?php echo $comment['full_name'] ?></p>
                        <i><?php echo $comment['comment'] ?></i>
                    </div>

                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="row">
                <p>Aucun commentaire</p>
            </div>
        <?php endif; ?>
        <hr />
        <?php if (isset($_SESSION['LOGGED_USER'])): ?>
            <form action="comments_post.php" method="POST">
                <!-- <label for="id">id</label> -->
                <div class="mb-3 visually-hidden">
                    <input class="form-control" type="text" name="id" value="<?php echo ($recipe['recipe_id']); ?>" />
                </div>
                <div class="mb-3">
                    <label for="comment" class="form-label">Postez un commentaire</label>
                    <textarea class="form-control" placeholder="Soyez respectueux/se, nous sommes humain(e)s." id="comment" name="comment"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
                <!-- <label for=""></label>
                <input type="text" name="" id=""> -->
            </form>
        <?php endif; ?>



    </div>
    <?php require_once(__DIR__ . '/footer.php'); ?>
</body>

</html>