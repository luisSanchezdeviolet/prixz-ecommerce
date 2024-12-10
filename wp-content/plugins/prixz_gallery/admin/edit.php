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
$sql = "SELECT * FROM {$table_gallery} WHERE id='".$id."';";
$data = $wpdb->get_results($sql, ARRAY_A);
if (empty($data)) {
    ?>
        <script>
            window.location = '<?php echo get_site_url() ?>/wp-admin/';
        </script>
    <?php }

$photos = $wpdb->get_results("SELECT  * from {$table_photos} where id ='".$id."' ORDER BY id DESC");
error_log('Hook actual: ' . $hook);

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
                                        <td>Foto</td>
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