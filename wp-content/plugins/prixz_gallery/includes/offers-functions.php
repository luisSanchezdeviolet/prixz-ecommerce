<?php
if (!defined('ABSPATH')) die();


function prixz_get_offers() {
    $args = [
        'post_type' => 'product',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => '_sale_price',
                'value' => '',
                'compare' => '!='
            ]
        ]
    ];
    $query = new WP_Query($args);

    return $query->posts;
}


function prixz_save_daily_offers($products) {
    update_option('prixz_daily_offers', $products);
}


function prixz_get_daily_offers() {
    return get_option('prixz_daily_offers', []);
}
