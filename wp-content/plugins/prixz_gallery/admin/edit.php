<?php
if (!defined('ABSPATH')) die();

if (!isset($_GET['id'])) {
?>
    <script>
        window.location = '<?php echo get_site_url() ?>/wp-admin/';
    </script>
<?php
}

$id = sanitize_text_field($_GET['id']);
global $wpdb;
$table_gallery = "{$wpdb->prefix}prixz_plugin_gallery";
$table_photos = "{$wpdb->prefix}prixz_plugin_photos";




if(isset($_POST['nonce'])) {
    switch($_POST['action']){
        case 'create':
            $data = [
                'prixz_galeria_id'=>$id,
                'name' => sanitize_text_field($_POST['photo_value']),
                'wordpress_id' => sanitize_text_field($_POST['photo_id']),
                'url'=>substr($_POST['photo_url'],strlen(get_site_url()), strlen($_POST['photo_url']))
                //'url' => sanitize_text_field($_POST['tamila_galeria_agregar_foto_url']) 
            ]; 
              $wpdb->insert($table_photos, $data);
              ?>
              <script>
                  document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                      icon: 'success',
                      title: 'OK',
                      text: 'Se creó el registro exitosamente',
                    })
                  })
              </script>
                        <?php
        break;
        case 'delete':
            $wpdb->delete($tabla2, array('id' =>$_POST['foto_id']));
            ?>
              <script>
                  Swal.fire({
                      icon: 'success',
                      title: 'OK',
                      text: 'Se eliminó el registro exitosamente',
                  });
                  setInterval(() => {
                    window.location=location.href;
                  }, 3000);
              </script>
                        <?php
        break;

     }
}


$sql = "SELECT * FROM {$table_gallery} WHERE id='".$id."';";
$data = $wpdb->get_results($sql, ARRAY_A);
if (empty($data)) {
    ?>
        <script>
            window.location = '<?php echo get_site_url() ?>/wp-admin/';
        </script>
    <?php }

$sqlPhotos = "SELECT  * from {$table_photos} where prixz_galeria_id = '".$id."' ORDER BY id DESC";
$photos = $wpdb->get_results($sqlPhotos);

?>


<div class="wrap">
    <div class="container-fluid">
        <div class="row">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= admin_url('admin.php?page=prixz_gallery_menu'); ?>">Tamila Galería</a></li>
                <li class="breadcrumb-item active" aria-current="page">Fotos galería <strong><?php echo $data[0]['name']; ?></strong></li>
            </ol>
            <h1 class="wp-headin-inline">Fotos galería <strong><?= $data[0]['name']; ?></strong></h1>
            <p class="d-flex justify-content-end">
                <a href="javascript:void(0);" class="btn btn-primary btn-marco"><i class="fas fa-plus"></i> Agregar </a>
            </p>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach($photos as $photo) {
                                ?>
                                    <tr>
                                        <td>
                                            <img src="<?= get_site_url().$photo->url; ?>" height="200px" alt="">
                                        </td>
                                        <td><a href="javascript:void(0);"><i class="fas fa-trash"></i></a></td>
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



<!-- formulario para crear imagenes-->
<form action="" name="form_add_photo" method="POST">
<input type="hidden" name="nonce" value="<?php echo wp_create_nonce('seg');?>" id="nonce" />
<input type="hidden" name="action" value="create" />
    <input type="hidden" name="photo_id" />
    <input type="hidden" name="photo_value" />
    <input type="hidden" name="photo_url" />
</form>