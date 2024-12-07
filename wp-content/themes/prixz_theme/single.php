<?php

if (!defined('ABSPATH')) {
    die();;
}

get_header();

while (have_posts()):
the_post();
?>

<main class="container">
    <div class="row">
    <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo get_site_url(); ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo get_site_url(); ?>/blog">Blog</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php the_title(); ?></li>
            </ol>
        </nav>

        <div class="col-8">
            <h1><?php the_title(); ?></h1>
            <div class="card">
            <?php the_post_thumbnail('full', array('class' => 'img-fluid img-blog')); ?>
                <div class="card-body">
                <p><?= the_category(' '); ?> | <?= get_the_date(); ?></p>
                <p><?= the_content(); ?></p>
                </div>
            </div>
        </div>
        <div class="col-4">
            sidebard
        </div>
    </div>
</main>

<?php
endwhile;
get_footer();
?>