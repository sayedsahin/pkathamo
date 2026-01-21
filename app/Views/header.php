<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="<?= $site['description'] ?>" />
        <meta name="author" content="<?= $site['title'] ?>" />
        <title><?= $site['title'] ?> - <?= $site['tagline'] ?></title>

        <!-- Favicon-->
        <link rel="icon" type="image/svg+xml" href="<?= BASE_URL; ?>/public/assets/favicon.svg" />
        <link rel="alternate icon" type="image/png" href="<?= BASE_URL; ?>/public/assets/favicon.png" />
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link href="<?= BASE_URL; ?>/public/css/styles.css" rel="stylesheet" />
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg bg-secondary text-uppercase fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand" href="<?= BASE_URL; ?>#page-top"><?= $site['title'] ?></a>
                <button class="navbar-toggler text-uppercase font-weight-bold bg-primary text-white rounded" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item mx-0 mx-lg-1">
                            <a onclick="clickRedirect(event, '#portfolio')" class="navlink nav-link py-3 px-0 px-lg-3 rounded" href="#portfolio">Portfolio</a>
                        </li>
                        <li class="nav-item mx-0 mx-lg-1">
                            <a onclick="clickRedirect(event, '#about')" class="navlink nav-link py-3 px-0 px-lg-3 rounded" href="#about">About</a>
                        </li>
                        <li class="nav-item mx-0 mx-lg-1">
                            <a onclick="clickRedirect(event, '#contact')" class="navlink nav-link py-3 px-0 px-lg-3 rounded" href="#contact">Contact</a>
                        </li>
                        <?php if (session()->get('login')): ?>
                        <li class="nav-item mx-0 mx-lg-1">
                            <a class="nav-link py-3 px-0 px-lg-3 rounded" href="<?= BASE_URL ?>/dashboard">Dashboard</a>
                        </li>
                        <?php else: ?>
                        <li class="nav-item mx-0 mx-lg-1">
                            <a class="nav-link py-3 px-0 px-lg-3 rounded" href="<?= BASE_URL ?>/login">Login</a>
                        </li>
                        <?php endif ?>
                    </ul>
                </div>
            </div>
        </nav>
        <script>
            function clickRedirect(event, link) {
                window.location.href='<?= BASE_URL ?>'+link;
                event.preventDefault();
            }
        </script>