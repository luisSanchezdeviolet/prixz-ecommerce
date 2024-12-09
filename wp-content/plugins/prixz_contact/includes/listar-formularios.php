<?php


if (! defined('ABSPATH')) {
    exit;
}
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
                </table>
            </div>
        </div>
    </div>
</div>