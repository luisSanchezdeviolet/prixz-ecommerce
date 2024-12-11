<?php
if (!defined('ABSPATH')) die();


function prixz_create_gallery($name) {
    global $wpdb;
    $table = "{$wpdb->prefix}prixz_plugin_gallery";
    return $wpdb->insert($table, ['name' => sanitize_text_field($name), 'fecha' => current_time('mysql')]);
}


function prixz_get_galleries() {
    global $wpdb;
    $table = "{$wpdb->prefix}prixz_plugin_gallery";
    return $wpdb->get_results("SELECT * FROM $table ORDER BY id DESC", ARRAY_A);
}


function prixz_get_photos($gallery_id) {
    global $wpdb;
    $table = "{$wpdb->prefix}prixz_plugin_photos";
    return $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE prixz_galeria_id = %d ORDER BY id DESC", $gallery_id));
}
