<?php
/*
Plugin Name: Prixz Gallery
Plugin URI: luiscrruzsanch3837@gmail.com
Description: Este plugin es para crear galería de imágenes
Version: 1.0.1
Author: Luis Fernando
Author URI: 
License: GPL
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: prixz_gallery 
*/
if (!defined('ABSPATH')) die();

class PrixzGalleryPlugin
{
    const DB_TABLE_GALLERY = 'prixz_gallery';
    const DB_TABLE_PHOTOS = 'prixz_gallery_photos';

    public function __construct()
    {
        register_activation_hook(__FILE__, [$this, 'install']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('init', [$this, 'register_shortcode']);
    }


    public function install()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();


        $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}" . self::DB_TABLE_GALLERY . " (
            id INT NOT NULL AUTO_INCREMENT,
            nombre VARCHAR(255) NOT NULL,
            fecha DATE,
            PRIMARY KEY (id)
        ) $charset_collate;";
        $wpdb->query($sql);


        $sql2 = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}" . self::DB_TABLE_PHOTOS . " (
            id INT NOT NULL AUTO_INCREMENT,
            gallery_id INT,
            wordpress_id INT,
            nombre VARCHAR(255) NOT NULL,
            url VARCHAR(500) NOT NULL,
            PRIMARY KEY (id),
            FOREIGN KEY (gallery_id) REFERENCES {$wpdb->prefix}" . self::DB_TABLE_GALLERY . "(id)
        ) $charset_collate;";
        $wpdb->query($sql2);
    }

 
    public function deactivate()
    {
        flush_rewrite_rules();
    }


    public function enqueue_admin_scripts($hook)
    {
        if (strpos($hook, 'prixz_gallery/admin') !== false) {
            wp_enqueue_style('bootstrap-css', plugins_url('assets/css/bootstrap.min.css', __FILE__));
            wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', [], '6.4.0');
            wp_enqueue_style('sweetalert2-css', plugins_url('assets/css/sweetalert2.css', __FILE__));
            wp_enqueue_script('bootstrap-js', plugins_url('assets/js/bootstrap.min.js', __FILE__), ['jquery']);
            wp_enqueue_script('sweetalert2', plugins_url('assets/js/sweetalert2.js', __FILE__), ['jquery']);
            wp_enqueue_script('admin-functions', plugins_url('assets/js/functions.js', __FILE__));
            wp_localize_script('admin-functions', 'ajaxData', [
                'url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('prixz_gallery_nonce'),
            ]);
            wp_enqueue_media(); 
        }
    }


    public function add_menu()
    {
        add_menu_page(
            'Prixz Gallery',
            'Prixz Gallery',
            'manage_options',
            plugin_dir_path(__FILE__) . 'admin/list.php',
            null,
            plugin_dir_url(__FILE__) . 'assets/images/gallery-icon.png',
            140
        );

        add_submenu_page(
            null,
            'Manage Gallery Photos',
            null,
            'manage_options',
            plugin_dir_path(__FILE__) . 'admin/edit.php',
            null
        );
    }


    public function register_shortcode()
    {
        add_shortcode('prixz_gallery', [$this, 'display_shortcode']);
    }


    public function display_shortcode($args, $content = "")
    {
        global $wpdb;
        $gallery_id = intval($args['id']);
        $photos = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}" . self::DB_TABLE_PHOTOS . " WHERE gallery_id = %d ORDER BY id DESC",
                $gallery_id
            ),
            ARRAY_A
        );

        if (empty($photos)) {
            return '<p>No hay imágenes en esta galería.</p>';
        }

        ob_start(); ?>
        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($photos as $index => $photo): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <img src="<?php echo esc_url($photo['url']); ?>" class="d-block w-100 img-fluid" alt="<?php echo esc_attr($photo['nombre']); ?>" />
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <?php return ob_get_clean();
    }
}


new PrixzGalleryPlugin();
