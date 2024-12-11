<?php

if (!defined('ABSPATH')) die();


get_header(); ?>

<div class="custom-home">

    <section class="home-slider">
        <?php echo do_shortcode('[prixz_gallery id=1]'); ?>
    </section>


    <section class="daily-offers">
        <h2>Ofertas del Día</h2>
        <?php echo do_shortcode('[prixz_daily_offers]'); ?>
    </section>

    <section class="bastion-products-container">
        <?php include get_stylesheet_directory() . '/includes/bastion-products.php'; ?>
    </section>
</div>

<?php
get_footer();
