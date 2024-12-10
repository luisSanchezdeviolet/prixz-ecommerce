<?php
if (!defined('ABSPATH')) die();

global $wpdb;
$table_gallery = "{$wpdb->prefix}prixz_plugin_gallery";
$table_photos = "{$wpdb->prefix}prixz_plugin_photos";
if(isset($_POST['nonce'])){
    switch($_POST["accion"]){
        case '1':
            $data = [
        
                'nombre' => sanitize_text_field($_POST['nombre']),  
                'fecha'=>date('Y-m-d')
            ]; 
              $wpdb->insert($tabla, $data);
              ?>
              <script>
                  Swal.fire({
                      icon: 'success',
                      title: 'OK',
                      text: 'Se creó el registro exitosamente',
                  });
                  setInterval(() => {
                    window.location=location.href;
                  }, 5000);
              </script>
                        <?php 
        break;
    }
}

$query="select * from {$table_gallery} order by id desc;";
$datos=$wpdb->get_results($query, ARRAY_A);

?>


<div class="wrap">
    <h1 class="wp-heading-inline"><?= get_admin_page_title(); ?></h1>
    <p class="d-flex justify-content-end">
        <a href="javascript:void(0);" class="btn btn-primary" title="Crear"><i class="fas fa-plus"></i> Crear </a>
    </p>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de la galeria</th>
                    <th>Shortcode</th>
                    <th>Fotos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($datos as $dato) {
                    ?>
                        <tr>
                            <td><?= $dato['id']; ?></td>
                            <td><?= $dato['name']; ?></td>
                            <td>[prixz_gallery id=<?= $dato['id']; ?>]</td>
                            <td><a href=""><i class="fas fa-images"></i></a></td>
                            <td><a href="javascript:void(0);"><i class="fas fa-trash"></i></a></td>
                        </tr>
                    <?php
                } 
                
                ?>
            </tbody>
        </table>
    </div>
</div>
