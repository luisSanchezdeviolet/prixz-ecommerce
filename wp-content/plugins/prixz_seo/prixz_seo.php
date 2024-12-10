<?php
/*
Plugin Name: Prixz SEO
Plugin URI: luiscrruzsanch3837@gmail.com
Description: Este plugin es para mejorar el SEO del e-commerce
Version: 1.0.1
Author: Luis Cruz
Author URI: luiscrruzsanch3837@gmail.com
License: GPL
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: prixz_seo
*/

if (!defined('ABSPATH')) die();

class PrixzSEO
{
    private $table_name;


    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'prixz_seo';
        register_activation_hook(__FILE__, [$this, 'instalarPluginSeo']);
        register_deactivation_hook(__FILE__, [$this, 'tamila_seo_desactivar']);
        register_uninstall_hook(__FILE__, [$this, 'uninstall_plugin']);
        add_action('admin_enqueue_scripts', [$this, 'upload_enqueue']);
        add_action('admin_menu', [$this, 'add_menu_plugin']);
        add_action('wp_ajax_edit', [$this,'prixz_seo_edit']);
        add_action('wp_head', [$this,'insertarKyewordsPage']);
    }

    public function instalarPluginSeo()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
            id INT NOT NULL AUTO_INCREMENT,
            keywords LONGTEXT NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        $wpdb->query($sql);

        // Insertar un registro por defecto si no existe
        $existe = $wpdb->get_var("SELECT COUNT(*) FROM $this->table_name;");
        if (!$existe) {
            $wpdb->insert($this->table_name, ['keywords' => '']);
        }
    }

    public function tamila_seo_desactivar()
    {
        flush_rewrite_rules();
    }

    public function uninstall_plugin()
    {
        global $wpdb;
        $wpdb->query("drop table $this->table_name; ");
        #limpiador de enlaces permanentes
        flush_rewrite_rules();
    }

    public function upload_enqueue($hook)
    {
        if ($hook !== 'toplevel_page_prixz_seo_menu') {
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
    }

    public function add_menu_plugin()
    {
        add_menu_page(
            "Prixz SEO",
            "Prixz SEO",
            "manage_options",
            "prixz_seo_menu", // <- slug simple
            [$this, 'mostrarLista'], // Aquí llamamos al método mostrarLista
            'dashicons-menu-alt',
            139
        );
    }


    public function mostrarLista()
    {
        require_once plugin_dir_path(__FILE__) . 'admin/list.php';
    }

    public function prixz_seo_edit() {

        $nonce = $_POST['nonce'];
        if (!wp_verify_nonce($nonce, 'seg')) {
            die('No tiene permisos para ejecutar este ajax');
        }
        global $wpdb;
        $query = "SELECT keywords FROM {$this->table_name} WHERE id=1;";
        $datos = $wpdb->get_results($query, ARRAY_A);
        ?>
        <form action="" name="prixz_seo_form" method="POST">
            <div class="row">
                <div class="mb-3">
                    <label for="keywords" class="form-label">Keywords:</label>
                    <textarea name="keywords" id="keywords" class="form-control" placeholder="Keywords"><?php echo esc_html($datos[0]['keywords']); ?></textarea>
                    <hr />
                    <input type="hidden" name="nonce" value="<?php echo esc_attr($nonce); ?>" id="nonce" />
                    <a href="javascript:void(0);" class="btn btn-warning" onclick="prixz_seo_enviar()" title="Editar">
                        <span class="dashicons dashicons-edit"></span> Editar
                    </a>
                </div>
            </div>
        </form>
        <?php
        wp_die(); // Finalizar la llamada AJAX adecuadamente.
    }

    public function insertarKyewordsPage() {
        global $wpdb;
        $sql = "SELECT keywords from {$this->table_name} where id=1";
        $data = $wpdb->get_results($sql, ARRAY_A);
        ?>
            <meta name="description" content="<?= bloginfo("description"); ?>" />
            <meta name="keywords" content="<?= $data[0]['keywords']; ?>" />
        <?php
    }

}

new PrixzSEO();
