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
    global $wpdb;

    $nonce = wp_create_nonce("prixz_contact_form_nonce");

    // Procesar el formulario antes de cualquier salida
    if (isset($_POST['nonce']) && wp_verify_nonce($_POST['nonce'], 'prixz_contact_form_nonce')) {
        $data = [
            'prixz_form_principal_id' => isset($args['id']) ? $args['id'] : 0,
            'name' => sanitize_text_field($_POST['name']),
            'email' => sanitize_text_field($_POST['email']),
            'phone' => sanitize_text_field($_POST['phone']),
            'message' => sanitize_textarea_field($_POST['message']),
            'fecha' => date('Y-m-d')
        ];

        // Inserción en la base de datos
        $wpdb->insert("{$wpdb->prefix}prixz_contact_respuestas", $data);

        // Redirigir al usuario con un mensaje
        ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Gracias!',
                text: 'Tu mensaje se ha enviado correctamente. Nos pondremos en contacto contigo a la brevedad.',
            }).then(() => {
                window.location = location.href; // Refrescar la página después de la confirmación
            });
        </script>
        <?php
    }
    ?>

    <script>
        function validaCorreo(valor) {
            const regex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\\.,;:\s@\"]+\.)+[^<>()[\]\\.,;:\s@\"]{2,})$/i;
            return regex.test(valor);
        }

        function prixz_form_contact_validate() {
            const form = document.forms['prixz_contact_form'];

            if (!form.name.value.trim()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El campo Nombre es obligatorio.',
                });
                return false;
            }

            if (!form.email.value.trim()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El campo E-Mail es obligatorio.',
                });
                return false;
            }

            if (!validaCorreo(form.email.value)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El E-Mail ingresado no es válido.',
                });
                return false;
            }

            if (!form.phone.value.trim()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El campo Teléfono es obligatorio.',
                });
                return false;
            }

            if (!form.message.value.trim()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El campo Mensaje es obligatorio.',
                });
                return false;
            }

            return true; // Permitir el envío si todo es válido
        }
    </script>

    <?php ob_start(); ?>

    <div class="container">
        <form action="" method="POST" name="prixz_contact_form" onsubmit="return prixz_form_contact_validate();">
            <div class="row">
                <div class="col-8">
                    <h5><?php _e('Completa el siguiente formulario y nos pondremos en contacto contigo', 'prixz_contact'); ?></h5>
                    <div class="mb-3">
                        <label for="name" class="form-label"><?php _e('Nombre:', 'prixz_contact'); ?></label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="<?php _e('Nombre', 'prixz_contact'); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label"><?php _e('E-Mail:', 'prixz_contact'); ?></label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="<?php _e('E-Mail', 'prixz_contact'); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label"><?php _e('Teléfono:', 'prixz_contact'); ?></label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="<?php _e('Teléfono', 'prixz_contact'); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label"><?php _e('Mensaje:', 'prixz_contact'); ?></label>
                        <textarea name="message" id="message" class="form-control" placeholder="<?php _e('Mensaje', 'prixz_contact'); ?>"></textarea>
                    </div>
                    <input type="hidden" name="nonce" value="<?php echo esc_attr($nonce); ?>">
                    <button type="submit" class="btn btn-warning"><i class="fas fa-envelope"></i> <?php _e('Enviar', 'prixz_contact'); ?></button>
                </div>
            </div>
        </form>
    </div>

    <?php
    return ob_get_clean();
}

}

new PrixzContactPlugin();
