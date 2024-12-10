<?php
/*
Plugin Name: Prixz Gallery
Plugin URI: luiscrruzsanch3837@gmail.com
Description: Este plugin es para insertar galerías en las páginas.
Version: 1.0.1
Author: Luis Cruz
Author URI: luiscrruzsanch3837@gmail.com
License: GPL
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: prixz_gallery
*/

class PrixzGallery
{
    private $table_name_gallery;
    private $table_name_photos;

    public function __construct()
    {
        global $wpdb;

        $this->table_name_gallery = $wpdb->prefix . 'prixz_plugin_gallery';
        $this->table_name_photos = $wpdb->prefix . 'prixz_plugin_photos';

        register_activation_hook(__FILE__, [$this, 'plugin_install']);
        register_deactivation_hook(__FILE__, [$this, 'plugin_deactivate']);

    }


    public function plugin_install()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql_gallery = "CREATE TABLE IF NOT EXISTS {$this->table_name_gallery} (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            fecha DATE,
            PRIMARY KEY (id)
        ) $charset_collate;";

        $sql_photos = "CREATE TABLE IF NOT EXISTS {$this->table_name_photos} (
            id INT NOT NULL AUTO_INCREMENT,
            prixz_galeria_id INT NOT NULL,
            wordpress_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            url VARCHAR(500) NOT NULL,
            PRIMARY KEY (id),
            FOREIGN KEY (prixz_galeria_id) REFERENCES {$this->table_name_gallery}(id) ON DELETE CASCADE
        ) $charset_collate;";


        $wpdb->query($sql_gallery);
        $wpdb->query($sql_photos);
    }


    public function plugin_deactivate()
    {
        flush_rewrite_rules();
    }

}


new PrixzGallery();
