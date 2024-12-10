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
if (!defined('ABSPATH')) die();

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
        add_action('admin_enqueue_scripts', [$this, 'upload_enqueue']);
        add_action('admin_menu', [$this, 'add_menu_plugin']);
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

    public function upload_enqueue($hook)
    {
        if ($hook !== 'toplevel_page_prixz_gallery_menu') {
            return;
        }

        wp_enqueue_style("bootstrapcss",  plugins_url('assets/css/bootstrap.min.css', __FILE__));
        wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0');
        wp_enqueue_style("sweetalert2",  plugins_url('assets/css/sweetalert2.css', __FILE__));
        wp_enqueue_script("bootstrapjs",  plugins_url('assets/js/bootstrap.min.js', __FILE__), array('jquery'));
        wp_enqueue_script("sweetalert2",  plugins_url('assets/js/sweetalert2.js', __FILE__), array('jquery'));
        wp_enqueue_script("funciones",  plugins_url('assets/js/funciones.js', __FILE__));
        wp_localize_script('funciones', 'prixz_seo_data', [
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('seg')
        ]);
        wp_enqueue_media();
    }


    public function add_menu_plugin() {
        add_menu_page(
            'Prixz Gallery',
            'Prixz Gallery',
            'manage_options',
            "prixz_gallery_menu", // <- slug simple
            [$this, 'mostrarLista'], // Aquí llamamos al método mostrarLista
            plugin_dir_url(__FILE__)."assets/images/galeria.png",
            142
        );
    }


    public function mostrarLista()
    {
        require_once plugin_dir_path(__FILE__) . 'admin/list.php';
    }

}


new PrixzGallery();
