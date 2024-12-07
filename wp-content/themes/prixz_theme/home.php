<?php

if (!defined('ABSPATH')) {
    die();;
}

get_header();
?>

<main class="container">
    <div class="row">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo get_site_url(); ?>">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Blog</li>
            </ol>
        </nav>
        <div class="col-8">
            <h1>Publicaciones recientes sobre medicina</h1>

            <?php
            while (have_posts()):
                the_post();

            ?>
                <div class="card mb-3 blog-container">
                    <?php the_post_thumbnail('full', array('class' => 'img-fluid img-blog')); ?>
                    <div class="card-body">
                        <div class="card-title">
                            <p><?= the_category(' '); ?> | <?= get_the_date(); ?></p>
                            <a class="blog-titulo" href="<?= get_permalink(); ?>"><h3><?php the_title(); ?></h3></a>

                        </div>
                    </div>
                </div>
            <?php
            endwhile;
            ?>

        </div>
        <div class="col-4">
            sidebar
        </div>
    </div>
</main>


<?php

get_footer();

?>