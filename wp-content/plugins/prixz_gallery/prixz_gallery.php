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
        add_action('init', [$this, 'add_shortcode_page']);
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
        if (strpos($hook, 'prixz_gallery') === false) {
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
        add_submenu_page(
            'prixz_gallery_menu',          // Menú padre.
            'Fotos de la Galeria',         // Título de la página.
            '',
            'manage_options',              // Capacidad requerida.
            'prixz_gallery_edit',                // Slug único.
            [$this, 'viewEditGallery']     // Callback para mostrar el contenido.
        );
        
    }


    public function mostrarLista()
    {
        require_once plugin_dir_path(__FILE__) . 'admin/list.php';
    }

    public function viewEditGallery()
    {
        require_once plugin_dir_path(__FILE__) . 'admin/edit.php';
    }


    public function add_shortcode_page() {
        add_shortcode('prixz_gallery', [$this,'prixz_gallery_display']);
    }

    public function prixz_gallery_display($args, $content = '') {
        global $wpdb;
        $sql="select * from {$this->table_name_photos} where prixz_galeria_id='".sanitize_text_field($args['id'])."' order by id desc;"; 
        $datos=$wpdb->get_results($sql, ARRAY_A);
        ?>
        <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php
                foreach($datos as $key=>$dato){
                    ?>
                    <div class="carousel-item <?php echo ($key==0) ? 'active':'';?>">
                        <img src="<?php echo get_site_url().$dato['url'];?>" class="d-block w-100 img-fluid" />
                        </div>
                    <?php
                }
                ?>
                
                 
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
        <?php
    }



}


new PrixzGallery();
