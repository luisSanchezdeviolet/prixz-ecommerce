<?php

if (!defined('ABSPATH')) die();

global $wpdb;

$table_name = $wpdb->prefix . 'prixz_seo';

if (isset($_POST['nonce'])) {
    $data = [
        'keywords' => sanitize_text_field($_POST['keywords'])
    ];
    $wpdb->update($table_name, $data, array('id' => '1'));
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

$datos = $wpdb->get_results("SELECT * FROM $table_name where id=1;", ARRAY_A);

?>

<div class="wrap">
    <div class="container-fluid">
        <div class="row">
            <h1 class="wp-heading-inline"><?= get_admin_page_title(); ?></h1>
            <p>Configure las palabras clave de su sitio web, para que le envie información a los motores de busqueda</p>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Keywords</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo ((isset($datos)) ? esc_html($datos[0]['keywords']) : '') ?></td>
                            <td style="text-align:center;">
                                <a href="javascript:void(0);" title="Editar" onclick="edit_register();"><i class="fas fa-edit"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!--Edit-->

<div class="modal fade" id="prixz_seo_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="prixz_seo_modal_title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="respuesta_modal_body">

            </div>

        </div>
    </div>
</div>