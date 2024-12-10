<?php


if (! defined('ABSPATH')) {
    exit;
}

global $wpdb;
$table_name = $wpdb->prefix . 'prixz_contact';
$table_name_respuestas = $wpdb->prefix . 'prixz_contact_respuestas';

if (isset($_POST['nonce'])) {
    switch ($_POST['action']) {
        case 'create':
            $data = [
                'name' => sanitize_text_field($_POST['name']),
                'email' => sanitize_text_field($_POST['email']),
                'fecha' => date('Y-m-d')
            ];

            $wpdb->insert($table_name, $data);
?>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Ok',
                        text: 'Se creó el registro exitósamente',
                    }).then(() => {
                        window.location.reload();
                    })
                })
            </script>
<?php
            break;

        case 'edit':
                $data = [
            
                    'name' => sanitize_text_field($_POST['name']),
                    'email' => sanitize_text_field($_POST['email'])
                ]; 
                $wpdb->update($table_name, $data, array('id'=>$_POST['id']));
                ?>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Ok',
                        text: 'Se creó el registro exitósamente',
                    }).then(() => {
                        window.location.reload();
                    })
                })
                    
                </script>
                <?php
            break;

        case 'delete':

            break;
    }
}

$datos = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC;");

?>

<div class="wrap">
    <div class="container-fluid">
        <div class="row">
            <h1 class="wp-heading-inline"><?= get_admin_page_title(); ?></h1>
            <p class="d-flex justify-content-end">
                <a href="javascript:void(0);" title="Crear" onclick="abrirModalFormulario('create', 'Crear nuevo formulario', '', '', '');" class="btn btn-primary"><i class="fas fa-plus "></i> Crear</a>
            </p>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Shortcode</th>
                            <th>Respuestas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($datos as $dato) {
                        ?>
                            <tr>
                                <td><?= $dato->id;  ?></td>
                                <td><?= $dato->name;  ?></td>
                                <td><?= $dato->email;  ?></td>
                                <td> [prixz_contact id="<?= $dato->id ?>"]</td>
                                <td>
                                    <a href="javascript:void(0);" onclick="obtenerRespuestasFormulario(<?= $dato->id; ?>);" class="fas fa-search"></a>
                                </td>
                                <td>
                                    <a href="javascript:void(0);" onclick="abrirModalFormulario('edit', 'Editar formuñario N° <?= $dato->id; ?>', '<?= $dato->name; ?>', '<?= $dato->email; ?>', '<?= $dato->id; ?>');"><i class="fas fa-edit"></i></a>
                                    <a href=""><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<!---Modal: crear formulario-->

<div class="modal fade" id="crear_formulario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crear_formulario_title"></h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form name="prixz_contact_form_crear" action="" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" placeholder="Nombre" name="name" id="prixz_input_name">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Correo:</label>
                            <input type="text" class="form-control" placeholder="Email" name="email" id="prixz_input_email">
                        </div>

                        <hr>
                        <input type="hidden" name="nonce" value="<?= wp_create_nonce('seg'); ?>" />
                        <input type="hidden" name="id" id="prixz_input_id" />
                        <input type="hidden" name="action" id="prixz_input_action" />

                        <a href="javascript:void(0);" onclick="validarYEnviarFormulario();" title="Enviar" class="btn btn-warning"><i class="fas fa-plus"></i> Enviar</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!--Respuestas-->
<div class="modal fade" id="respuesta_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="respuesta_modal_title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="respuesta_modal_body">

            </div>

        </div>
    </div>
</div>