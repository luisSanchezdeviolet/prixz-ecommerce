<?php
if (!defined('ABSPATH')) die();

if (!function_exists('cargar_dependencias_tema_hijo')) {
    function cargar_dependencias_tema_hijo() {
        // Cargar estilos desde CDN
        wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css', [], '5.3.0');
        wp_enqueue_style('sweetalert2-css', 'https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css', [], '11.7.0');
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', [], '6.4.0');
    
        // Cargar scripts desde CDN
        wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', ['jquery'], '5.3.0', true);
        wp_enqueue_script('sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.js', ['jquery'], '11.7.0', true);
    }
}

add_action('wp_enqueue_scripts', 'cargar_dependencias_tema_hijo');


function enqueue_custom_home_assets() {
    if (is_front_page()) {

        wp_enqueue_style('custom-home-styles', get_stylesheet_directory_uri() . '/public/css/home.css', [], '1.0');


        wp_enqueue_script('custom-home-scripts', get_stylesheet_directory_uri() . '/public/js/home.js', ['jquery'], '1.0', true);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_custom_home_assets');
