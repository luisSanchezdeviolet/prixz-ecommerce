<?php


if (! defined('ABSPATH')) {
    exit;
}

global $wpdb;
$table_name = $wpdb->prefix.'prixz_contact';
$table_name_respuestas = $wpdb->prefix.'prixz_contact_respuestas';

$datos = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC;");

?>

<div class="wrap">
    <div class="container-fluid">
        <div class="row">
            <h1 class="wp-heading-inline"><?= get_admin_page_title(); ?></h1>
            <p class="d-flex justify-content-end">
                <a href="" class="btn btn-primary"><i class="fas fa-plus "></i> Crear</a>
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
                            foreach($datos as $dato) {
                                ?>
                                    <tr>
                                        <td><?= $dato->id;  ?></td>
                                        <td><?= $dato->name;  ?></td>
                                        <td><?= $dato->email;  ?></td>
                                        <td> [prixz_contact id="<?= $dato->id ?>"]</td>
                                        <td>
                                            <a href="" class="fas fa-search"></a>
                                        </td>
                                        <td>
                                            <a href="" ><i class="fas fa-edit"></i></a>
                                            <a href="" ><i class="fas fa-trash"></i></a>
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