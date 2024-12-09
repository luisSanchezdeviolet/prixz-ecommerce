<?php

/*
Plugin Name: Prixz Form Contact
Plugin URL: luiscrruzsanch3837@gmail.com
Description: Este es un plugin para el manejo de formularios.
Version: 1.0.1
Author: Luis Fernando
License: GPL
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: prixz_contact
*/


if (! defined('ABSPATH')) {
    exit;
}

class PrixzContactPlugin
{
    private $table_name;
    private $table_name_respuestas;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'prixz_contact';
        $this->table_name_respuestas = $wpdb->prefix . 'prixz_contact_respuestas';

        register_activation_hook(__FILE__, [$this, 'activar']);
        register_deactivation_hook(__FILE__, [$this, 'desactivar']);
        add_action('admin_enqueue_scripts', [$this, 'cargar_dependencias']);
        add_action('admin_menu', [$this, 'agregar_menu_administracion']);
        add_action('init', [$this, 'registrarShortcode']);
    }

    public function activar()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(200) NOT NULL,
            email VARCHAR(100) NOT NULL,
            fecha DATE,
            PRIMARY KEY (id)
        ) $charset_collate;";
        $wpdb->query($sql);
        $wpdb->query("ALTER TABLE $table_name ADD INDEX(`email`);");

        $sql_respuestas = "CREATE TABLE IF NOT EXISTS $this->table_name_respuestas (
            id INT NOT NULL AUTO_INCREMENT,
            prixz_form_principal_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone VARCHAR(15) NOT NULL,
            message TEXT NOT NULL,
            fecha DATE,
            PRIMARY KEY (id),
            FOREIGN KEY (prixz_form_principal_id) REFERENCES $this->table_name (id)
        ) $charset_collate;";
        $wpdb->query($sql_respuestas);
        $wpdb->query("ALTER TABLE $table_name_respuestas ADD INDEX(`email`);");
        $wpdb->query("ALTER TABLE $table_name_respuestas ADD INDEX(`phone`);");
    }

    public function desactivar()
    {
        flush_rewrite_rules();
    }

    /**
     * Carga las dependencias necesarias para el área de administración.
     *
     * @param string $hook Página actual del administrador.
     */
    public function cargar_dependencias($hook)
    {
        // Verifica que estamos en una página específica del plugin
        if (strpos($hook, 'prixz_contact') !== false) {
            wp_enqueue_style('bootstrapCss', plugins_url('assets/css/bootstrap.min.css', __FILE__));
            wp_enqueue_style('sweetAlert2css', plugins_url('assets/css/sweetalert2.css', __FILE__));
            wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', [], '6.4.0');
            wp_enqueue_script('bootstrapJs', plugins_url('assets/js/bootstrap.min.js', __FILE__), ['jquery'], null, true);
            wp_enqueue_script('sweetAlert2', 'https://cdn.jsdelivr.net/npm/sweetalert2@11', ['jquery'], null, true);
            wp_enqueue_script('funciones', plugins_url('assets/js/funciones.js', __FILE__), ['jquery', 'sweetAlert2'], null, true);
        }
    }


    public function agregar_menu_administracion()
    {
        add_menu_page(
            "Prixz Form Contact",
            "Prixz Form Contact",
            "manage_options",
            "prixz_contact_admin_menu",
            [$this, 'mostrar_lista_formularios'],
            null,
            'dashicons-menu-alt',
            137
        );
    }

    public function mostrar_lista_formularios()
    {
        $ruta_archivo = plugin_dir_path(__FILE__) . 'includes/listar-formularios.php';

        if (file_exists($ruta_archivo)) {
            include_once $ruta_archivo;
        } else {
            echo '<div class="notice notice-error"><p>Error: No se encontró el archivo</p></div>';
        }
    }

    //Registrar shortcode
    public function registrarShortcode()
    {
        add_shortcode('prixz_contact', [$this, 'mostrar_formulario_contacto']);
    }

    public function mostrar_formulario_contacto($args, $content = "")
    {
        $nonce = wp_create_nonce("prixz_contact_form_nonce");
        ob_start();
?>
        <div class="container">
            <form action="" method="POST" name="contact_form_respuestas">
                <div class="row">
                    <div class="col-8">
                        <h5><?php _e('Completa el siguiente formulario y nos pondremos en contacto contigo', 'prixz_contact'); ?></h5>
                        <div class="mb-3">
                            <label for="name" class="form-label"><?php _e('Nombre:', 'prixz_contact'); ?></label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="<?php _e('Nombre', 'prixz_contact'); ?>" />
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label"><?php _e('E-Mail:', 'prixz_contact'); ?></label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="<?php _e('E-Mail', 'prixz_contact'); ?>" />
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label"><?php _e('Teléfono:', 'prixz_contact'); ?></label>
                            <input type="text" name="phone" id="phone" class="form-control" placeholder="<?php _e('Teléfono', 'prixz_contact'); ?>" />
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label"><?php _e('Mensaje:', 'prixz_contact'); ?></label>
                            <textarea class="form-control" name="message" id="message" placeholder="<?php _e('Mensaje', 'prixz_contact'); ?>"></textarea>
                        </div>
                        <input type="hidden" name="nonce" value="<?php echo esc_attr($nonce); ?>" id="nonce" />
                        <hr />
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-envelope"></i> <?php _e('Enviar', 'prixz_contact'); ?>
                        </button>
                    </div>
                </div>
            </form>
        </div>
<?php
        return ob_get_clean();
    }
}

new PrixzContactPlugin();
