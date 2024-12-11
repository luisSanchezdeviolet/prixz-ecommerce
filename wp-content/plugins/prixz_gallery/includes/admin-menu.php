<?php
if (!defined('ABSPATH')) die();

add_action('admin_menu', function () {
    add_menu_page(
        'Prixz Gallery',
        'Prixz Gallery',
        'manage_options',
        'prixz_gallery_menu',
        'prixz_gallery_list',
        'dashicons-images-alt2'
    );

    add_submenu_page(
        'prixz_gallery_menu',
        'Editar Galería',
        null,
        'manage_options',
        'prixz_gallery_edit',
        'prixz_gallery_edit'
    );
});

// Cargar vistas
function prixz_gallery_list() {
    include plugin_dir_path(__FILE__) . '../views/list.php';
}

function prixz_gallery_edit() {
    include plugin_dir_path(__FILE__) . '../views/edit.php';
}
