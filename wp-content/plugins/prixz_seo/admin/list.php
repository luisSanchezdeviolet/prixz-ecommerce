<?php

if (!defined('ABSPATH')) die();

global $wpdb;

$table_name = $wpdb->prefix.'prixz_seo';

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
                                <a href="javascript:void(0);" title="Editar" onclick="tamila_seo_modificar();"><i class="fas fa-edit"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
