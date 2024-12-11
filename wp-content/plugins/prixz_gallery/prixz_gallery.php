<?php
/**
 * Plugin Name: Prixz Gallery
 * Description: Un plugin para gestionar galerías y fotos.
 * Version: 1.0.0
 * Author: Tu Nombre
 * Text Domain: prixz-gallery
 */

if (!defined('ABSPATH')) die();

// Cargar archivos necesarios
include_once plugin_dir_path(__FILE__) . 'includes/admin-menu.php';
include_once plugin_dir_path(__FILE__) . 'includes/gallery-functions.php';
include_once plugin_dir_path(__FILE__) . 'includes/offers-functions.php';
include_once plugin_dir_path(__FILE__) . 'includes/shortcodes.php';


add_action('admin_enqueue_scripts', 'prixz_gallery_enqueue_assets');

function prixz_gallery_enqueue_assets($hook) {
    // Validar si la URL contiene 'prixz_gallery'
    if (strpos($hook, 'prixz_gallery') === false) {
        return;
    }


    wp_enqueue_style("bootstrapcss", plugins_url('assets/css/bootstrap.min.css', __FILE__));
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', [], '6.4.0');
    wp_enqueue_style("sweetalert2", plugins_url('assets/css/sweetalert2.css', __FILE__));


    wp_enqueue_script("bootstrapjs", plugins_url('assets/js/bootstrap.min.js', __FILE__), ['jquery'], null, true);
    wp_enqueue_script("sweetalert2", plugins_url('assets/js/sweetalert2.js', __FILE__), ['jquery'], null, true);
    wp_enqueue_script("funciones", plugins_url('assets/js/funciones.js', __FILE__), ['jquery'], null, true);


    wp_localize_script('funciones', 'prixz_gallery_data', [
        'url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('seg'),
    ]);

    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'prixz_gallery_enqueue_assets');



