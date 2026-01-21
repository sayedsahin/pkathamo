<?php view('header', ['site' => $site]); ?>
<header class="masthead bg-primary text-white text-center">
    <div class="container d-flex align-items-center flex-column">
        <!-- Masthead Avatar Image-->
        <img class="masthead-avatar mb-5 rounded-circle" src="<?= BASE_URL; ?>/<?= $user['avatar_thumb']; ?>" alt="..." />
        <!-- Masthead Heading-->
        <h1 class="masthead-heading text-uppercase mb-0"><?= $user['name']; ?></h1>
        <!-- Icon Divider-->
        <div class="divider-custom divider-light">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon">
                <svg class="icon-star" style="fill: #fff;">
                    <use xlink:href="#icon-star"></use>
                </svg>
            </div>
            <div class="divider-custom-line"></div>
        </div>
        <!-- Masthead Subheading-->
        <p class="masthead-subheading font-weight-light mb-0"><?= $user['info']; ?></p>
        
    </div>
</header>

<section class="page-section portfolio">
    <div class="container">
        
        <!-- Portfolio Section Heading-->
        <h2 id="portfolio" class="page-section-heading text-center text-uppercase text-secondary mb-0">Portfolio</h2>
        <!-- Icon Divider-->
        <div class="divider-custom">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon">
                <svg class="icon-star" style="fill: #2c3e50;">
                    <use xlink:href="#icon-star"></use>
                </svg>
            </div>
            <div class="divider-custom-line"></div>
        </div>
        <!-- Portfolio Grid Items-->
        <div class="row justify-content-center">
            <!-- Portfolio Item 1-->
            <?php
                if ($projects) {
                foreach ($projects as $key => $project) { 
            ?>
            <div class="col-md-6 col-lg-4 mb-5">
                <a href="<?= BASE_URL; ?>/projects/<?= $project['id']; ?>" class="portfolio-item mx-auto border">
                    <div class="portfolio-item-caption d-flex align-items-center justify-content-center h-100 w-100">
                        <div class="portfolio-item-caption-content text-center text-white">
                            <svg style="width: 0.875em; font-size: 3em;">
                                <use xlink:href="#icon-plus"></use>
                            </svg>
                        </div>
                    </div>
                    <img class="img-fluid" src="<?= BASE_URL.'/'.$project['thumb']; ?>" alt="..." />
                </a>
            </div>
            <?php } } ?>
        </div>
    </div>
</section>
<!-- About Section-->
<section class="page-section bg-primary text-white mb-0" id="about">
    <div class="container">
        <!-- About Section Heading-->
        <h2 class="page-section-heading text-center text-uppercase text-white">About</h2>
        <!-- Icon Divider-->
        <div class="divider-custom divider-light">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon">
                <svg class="icon-star" style="fill: #fff;">
                    <use xlink:href="#icon-star"></use>
                </svg>
            </div>
            <div class="divider-custom-line"></div>
        </div>
        <!-- About Section Content-->
        <div class="row">
            <div class="col-lg-4 ms-auto"><p class="lead"><?= htmlspecialchars_decode($about['about_1']) ?></p></div>
            <div class="col-lg-4 me-auto"><p class="lead"><?= htmlspecialchars_decode($about['about_2']) ?></p></div>
        </div>
    </div>
</section>
<!-- Contact Section-->
<section class="page-section" id="contact">
    <div class="container">
        <!-- Contact Section Heading-->
        <h2 class="page-section-heading text-center text-uppercase text-secondary mb-0">Contact Me</h2>
        <!-- Icon Divider-->
        <div class="divider-custom">
            <div class="divider-custom-line"></div>
            <div class="divider-custom-icon">
                <svg class="icon-star" style="fill: #2c3e50;">
                    <use xlink:href="#icon-star"></use>
                </svg>
            </div>
            <div class="divider-custom-line"></div>
        </div>
        <!-- Contact Section Form-->
        <?php view('components/contact'); ?>
    </div>
</section>
<section class="page-section portfolio pt-0">
    <!-- <div class="container"> -->
        <div class="d-flex">
            <div class="col-3"><img class="w-100" style="height: 60px;" src="<?= BASE_URL; ?>/public/assets/php.svg" alt=""></div>
            <div class="col-3"><img class="w-100" style="height: 60px;" src="<?= BASE_URL; ?>/public/assets/vuejs.svg" alt=""></div>
            <div class="col-3"><img class="w-100" style="height: 60px;" src="<?= BASE_URL; ?>/public/assets/laravel.svg" alt=""></div>
            <div class="col-3"><img class="w-100" style="height: 60px;" src="<?= BASE_URL; ?>/public/assets/nuxtjs.svg" alt=""></div>
        </div>
    <!-- </div> -->
</section>
<?php view('footer', ['socials' => $socials, 'site' => $site]); ?>