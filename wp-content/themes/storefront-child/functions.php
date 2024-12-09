<?php
// Archivo functions.php del tema hijo

function prixz_enqueue_storefront_child_assets() {
    // Registrar estilos
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css', [], '5.1.3');
    wp_enqueue_style('sweetalert2-css', 'https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css', [], '11.7.3');
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', [], '6.4.0');

    // Registrar scripts
    wp_enqueue_script('jquery'); // Asegurar que jQuery está disponible
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', ['jquery'], '5.1.3', true);
    wp_enqueue_script('sweetalert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11', ['jquery'], '11.7.3', true);
}
add_action('wp_enqueue_scripts', 'prixz_enqueue_storefront_child_assets');
