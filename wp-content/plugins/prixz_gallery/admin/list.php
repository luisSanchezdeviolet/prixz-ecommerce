<?php
if (!defined('ABSPATH')) die();

global $wpdb;
$table_gallery = "{$wpdb->prefix}prixz_plugin_gallery";
$table_photos = "{$wpdb->prefix}prixz_plugin_photos";
if(isset($_POST['nonce'])){
    switch($_POST["action"]){
        case '1':
            $data = [
        
                'name' => sanitize_text_field($_POST['name']),  
                'fecha'=>date('Y-m-d')
            ]; 
              $wpdb->insert($table_gallery, $data);
            ?>
              <script>
                  addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                      icon: 'success',
                      title: 'OK',
                      text: 'Se creó el registro exitosamente',
                  });
                  })
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
        <a href="javascript:void(0);" onclick="galleryCreateModal();" class="btn btn-primary" title="Crear"><i class="fas fa-plus"></i> Crear </a>
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
                            <td><a href="<?= admin_url('admin.php?page=prixz_gallery_edit');?>&id=<?= $dato['id']; ?>"><i class="fas fa-images"></i></a></td>
                            <td><a href="javascript:void(0);"><i class="fas fa-trash"></i></a></td>
                        </tr>
                    <?php
                } 
                
                ?>
            </tbody>
        </table>
    </div>
</div>



<!---Crear-->

<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="gallery_modal_title">Crear nueva galería</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!--formulario-->
            <div class="row">
            <form action="" method="POST" name="formCrearGaleria">    
            <div class="mb-3">
                    <label for="name" class="form-label">Nombre:</label>
                    <input type="text" name="name" id="gallery_name" class="form-control" placeholder="Nombre" /> 
                   
                    <hr />
                    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('seg');?>" id="nonce" />
                    <input type="hidden" name="action" id="gallery_action" value="1" />
                    <a href="javascript:void(0);" class="btn btn-warning" onclick="galleryCreateRegister()" title="Enviar"><i class="fas fa-plus"></i> Enviar</a>
               </div>
               </form>
            </div>
        <!--//formulario-->
      </div>
      
    </div>
  </div>
</div>