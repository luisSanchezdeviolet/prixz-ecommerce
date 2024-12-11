<?php
if (!defined('ABSPATH')) die(); // Evitar acceso directo

// Encolar scripts y estilos
function enqueue_custom_home_assets() {
    if (is_front_page()) {
        // Estilos personalizados
        wp_enqueue_style('custom-home-styles', get_stylesheet_directory_uri() . '/public/css/home.css', [], '1.0');

        // Scripts personalizados
        wp_enqueue_script('custom-home-scripts', get_stylesheet_directory_uri() . '/public/js/home.js', ['jquery'], '1.0', true);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_custom_home_assets');
