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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

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
        add_action('wp_ajax_prixz_contact_form_respuestas', [$this,'prixz_contact_form_respuestas']);
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
        $wpdb->query("ALTER TABLE {$this->table_name} ADD INDEX(`email`);");

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
        $wpdb->query("ALTER TABLE {$this->table_name_respuestas} ADD INDEX(`email`);");
        $wpdb->query("ALTER TABLE {$this->table_name_respuestas} ADD INDEX(`phone`);");
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
            wp_localize_script('funciones','datosajax',[
                'url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('seg')
            ]);

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

    public function mostrar_formulario_contacto($args, $content = ""){
        global $wpdb;
        
        $idPost = $args['id'];
        $namePost = sanitize_text_field($_POST['name_prixz']);
        $emailPost = sanitize_text_field($_POST['email_prixz']);
        $phonePost = sanitize_text_field($_POST['phone_prixz']);
        $messagePost = sanitize_text_field($_POST['message_prixz']);

        if(isset($_POST['nonce'])) {
            $data = [
                'prixz_form_principal_id' => $idPost,
                'name' => $namePost,
                'email' => $emailPost,
                'phone' => $phonePost,
                'message' => $messagePost,
                'fecha' => date('Y-m-d')
            ];
            $wpdb->insert($this->table_name_respuestas, $data);

            //enviar el correo

            $this->sendEmail($idPost, $namePost, $emailPost, $phonePost, $messagePost);



            //redireccionar al usuario
            ?>
                <script>
                    
                    document.addEventListener('DOMContentLoaded', () => {
                        Swal.fire({
                        icon: 'success',
                        title: 'OK',
                        text: 'Se envió tu mensaje exitosamente, nos pondremos en contacto contigo a la brevedad',
                        })
                    })
                    

            
            </script>
            <?php
        }
        ?>
        <script>
            function validaCorreo(valor) {
                if (/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(valor)){
                return true;
                } else {
                return false;
                }
            }

            function prixz_form_control(){
                var form=document.getElementById('prixz_form_respuestas');
                if(form.name_prixz.value==0)
                { 
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El campo nombre es obligatorio',
                });
                form.name_prixz.value='';
                return false;
                }
                
                if(form.email_prixz.value==0)
                { 
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'El campo E-Mail es obligatorio',
                });
                form.email_prixz.value='';
                return false;
                }
                if(validaCorreo(form.email_prixz.value)==false){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'El E-Mail ingresado no es válido',
                    });
                    form.email_prixz.value='';
                    return false;
                }
                
                form.submit();
            }
        </script>
        <?php

        $nonce = wp_create_nonce('seg');

        $html='';
        $html.='<div class="container"><form action="" method="POST" name="prixz_form_respuestas" id="prixz_form_respuestas">';
       
       $html.='<div class="row">';
       $html.='<div class="col-8">';
       $html.='<h5>Completa el siguiente formulario y nos pondremos en contacto contigo</h5>';
       $html.='<div class="mb-3">
                <label for="name" class="form-label">Nombre:</label>
                <input type="text" name="name_prixz" id="name_prixz" class="form-control" placeholder="Nombre" /> 
               </div>';
       $html.='<div class="mb-3">
       <label for="email" class="form-label">E-Mail:</label>
               <input type="text" name="email_prixz" id="email_prixz" class="form-control" placeholder="E-Mail" /> 
              </div>';
       $html.='<div class="mb-3">
              <label for="nombre" class="form-label">Teléfono:</label>
              <input type="text" name="phone_prixz" id="phone_prixz" class="form-control" placeholder="Telefono" /> 
             </div>';
       $html.='<div class="mb-3">
             <label for="nombre" class="form-label">Mensaje:</label>
             <textarea class="form-control" name="message_prixz" id="message_prixz" placeholder="Mensaje"></textarea>
            </div>';
       $html.='<input type="hidden" name="nonce" value="'.$nonce.'" id="nonce" />'; 
       $html.='<hr />';
       $html.='<a href="javascript:void(0);" class="btn btn-warning" onclick="prixz_form_control()"><i class="fas fa-envelope"></i> Enviar</a> ';
       $html.='</div>';
       $html.='</div>';
       $html.='</form></div>';
       return $html;

    }

    public function sendEmail($id, $name, $email, $phone, $message) {
        global $wpdb;
        $datos=$wpdb->get_results("select email from $this->table_name where id='{$id}';", ARRAY_A);

        
        require 'vendor/autoload.php';
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Debugoutput = 'error_log';
            $mail->isSMTP();
            $mail->Host ='smtp.hostinger.com';
            $mail->SMTPAuth   = true;
            $mail->Username='ventas@farmacias-ferxxo.cloud';
            $mail->Password='|cXTdfpG8>w';
            $mail->Port = 465;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

            $mail->setFrom('ventas@farmacias-ferxxo.cloud', "Farmacias seguras");
            $mail->addAddress($datos[0]['email'], utf8_decode(bloginfo('name')));

            $mail->isHTML(true);
            $mail->Subject='Asunto de el mail';
            $mail->Body = utf8_decode(
                                        '<h1>Mensaje desde sitio web</h1> <hr />
                                        <ul>
                                            <li>Nombre: '.$name.'</li>
                                            <li>E-Mail: '.$email.'</li>
                                            <li>Teléfono: '.$phone.'</li>
                                            <li>Mensaje: '.$message.'</li>
                                            
                                        </ul>
                                        '
                                    );
            $mail->send();
            return true;

        } catch (Exception $e) {
        return false;
        }
    }

    public function prixz_contact_form_respuestas() {
        $nonce = $_POST['nonce'];
        if(!wp_verify_nonce($nonce, 'seg')) {
            die('No tiene permisos para ejecutar este ajax');
        }
        global $wpdb;
        $query="select * from $this->table_name_respuestas where prixz_form_principal_id='".sanitize_text_field($_POST['id'])."' order by id desc;";
        $datos=$wpdb->get_results($query, ARRAY_A);
        ?>
        <table class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>E-Mail</th>
                    <th>Teléfono</th>
                    <th>Mensaje</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($datos as $dato){
                    ?>
                    <tr>
                        <td><?php echo $dato['name'];?></td>
                        <td><?php echo $dato['email'];?></td>
                        <td><?php echo $dato['phone'];?></td>
                        <td><?php echo $dato['message'];?></td>
                        <td><?php echo $dato['fecha'];?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php
        wp_die();
    }

}

new PrixzContactPlugin();
