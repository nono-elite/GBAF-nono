<!DOCTYPE html>
<html lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width" />
        <link rel="stylesheet" href="public/css/style.css" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" integrity="sha384-KA6wR/X5RY4zFAHpv/CnoG2UW1uogYfdnP67Uv7eULvTveboZJg0qUpmJZb5VqzN" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Martel%7cOpen+Sans&display=swap" />
        <link rel="icon" href="public/img/fav_icon_gbaf.png" />
        <title><?= $pageTitle ?></title>
    </head>

    <body>
        <?php ob_start(); ?> <!-- -->
        <header id="header_form">
            <a href="index.php"><img id="logo" src="public/img/logo_gbaf.png" alt="logo de GBAF" /></a>
            <p>Le Groupement Banque Assurance Français</p>
        </header>
        <?php $headerOut = ob_get_clean(); ?>

        <?php ob_start(); ?>
        <header id="header">
            <a href="index.php"><img id="logo" src="public/img/logo_gbaf.png" alt="logo de GBAF" /></a>
            <div id="user">
                <div class="fas fa-user-tie fa-2x"></div>
                <div id="userLink">
                    <p><a href="index.php?action=account"><?= $_SESSION['firstname']; ?> <?= $_SESSION['lastname']; ?></a></p>
                    <p id="deco"><a href="index.php?action=logout">Se déconnecter</a></p>
                </div>
            </div>
        </header>
        <?php $headerIn = ob_get_clean(); ?>

        <?= ($header == 'noconnect') ? $headerOut : '' ?>
        <?= ($header == 'connect') ? $headerIn : '' ?>

        <?= $pageContent ?>

        <footer>
            <p><a href="#">Mentions légales</a> | <a href="#">Contact</a></p>
        </footer>
    </body>
</html>
