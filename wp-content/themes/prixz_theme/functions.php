<?php

if(!defined('ABSPATH')) {
    die();;
}

if(!function_exists('prixz_setup')) {

    function prixz_setup() {
        add_theme_support('post-thumbnails');
    }

    add_action('after_setup_theme', 'prixz_setup');

}

if(!function_exists('custom_enquenue')) {
    function custom_enquenue() {
        wp_enqueue_style('bootstrapCss', get_stylesheet_uri('public/css/bootstrap.min.css', __FILE__));

        wp_enqueue_script("bootstrapJs", get_template_directory_uri().'/public/js/bootstrap.min.js', array('jquery'));
        wp_enqueue_script("sweetAlert2", get_template_directory_uri().'/public/js/sweetalert2.js', array('jquery'));
        wp_enqueue_script("funciones", get_template_directory_uri().'/public/js/funcionesjs');
    }

    add_action('wp_enqueue_scripts', 'custom_enquenue');
}


if(!function_exists('activarMenu')) {
    function activarMenu() {
        register_nav_menus(array(
            'menu-principal' => __('Menú Principal', 'farmacia_prixz')
        ));
    }
    add_action('init', 'activarMenu');
}
