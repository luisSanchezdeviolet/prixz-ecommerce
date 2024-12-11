<?php
if (!defined('ABSPATH')) die();

if (!isset($_GET['id'])) {
    ?>
    <script>
        window.location = '<?php echo get_site_url(); ?>/wp-admin/';
    </script>
    <?php
}

$id = sanitize_text_field($_GET['id']);
global $wpdb;
$table_gallery = "{$wpdb->prefix}prixz_plugin_gallery";
$table_photos = "{$wpdb->prefix}prixz_plugin_photos";

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'create':
            $data = [
                'prixz_galeria_id' => $id,
                'name' => sanitize_text_field($_POST['photo_value']),
                'wordpress_id' => sanitize_text_field($_POST['photo_id']),
                'url' => substr($_POST['photo_url'], strlen(get_site_url()), strlen($_POST['photo_url'])),
            ];
            $wpdb->insert($table_photos, $data);
            ?>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'OK',
                        text: 'Se creó el registro exitosamente.',
                    })
                });
            </script>
            <?php
            break;

        case 'delete':
            $photoId = sanitize_text_field($_POST['photo_id']);
            $photoPath = sanitize_text_field($_POST['photo_path']);

            $wpdb->delete($table_photos, ['id' => $photoId]);

            // Eliminar archivo físico
            if (file_exists(ABSPATH . $photoPath)) {
                unlink(ABSPATH . $photoPath);
            }

            ?>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'OK',
                        text: 'Se eliminó el registro exitosamente.',
                    }).then(() => {
                        window.location.reload(); // Recargar la página
                    });
                });
            </script>
            <?php
            break;
    }
}

// Obtener galería y fotos
$sql = "SELECT * FROM {$table_gallery} WHERE id = %d";
$data = $wpdb->get_row($wpdb->prepare($sql, $id), ARRAY_A);

if (empty($data)) {
    ?>
    <script>
        window.location = '<?php echo get_site_url(); ?>/wp-admin/';
    </script>
    <?php
}

$sqlPhotos = "SELECT * FROM {$table_photos} WHERE prixz_galeria_id = %d ORDER BY id DESC";
$photos = $wpdb->get_results($wpdb->prepare($sqlPhotos, $id));

?>

<div class="wrap">
    <div class="container-fluid">
        <h1 class="wp-heading-inline">Fotos de la Galería: <strong><?php echo esc_html($data['name']); ?></strong></h1>
        <p class="d-flex justify-content-end">
            <!-- Botón para añadir imágenes -->
            <a href="javascript:void(0);" class="btn btn-primary btn-marco">
                <i class="fas fa-plus"></i> Agregar
            </a>
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
                <?php foreach ($photos as $photo): ?>
                    <tr>
                        <td>
                            <img src="<?php echo esc_url(get_site_url() . $photo->url); ?>" 
                                 data-photo-id="<?php echo esc_attr($photo->id); ?>" 
                                 data-photo-path="<?php echo esc_attr($photo->url); ?>" 
                                 alt="Foto de la galería" height="200px">
                        </td>
                        <td>
                            <a href="javascript:void(0);" 
                               class="text-danger" 
                               onclick="deletePhoto('<?php echo esc_attr($photo->id); ?>', '<?php echo esc_attr($photo->prixz_galeria_id); ?>', '<?php echo esc_js($photo->url); ?>');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Formularios -->
<form name="form_add_photo" method="POST">
    <input type="hidden" name="action" value="create">
    <input type="hidden" name="photo_id">
    <input type="hidden" name="photo_value">
    <input type="hidden" name="photo_url">
</form>

<form name="form_delet_photo" method="POST">
    <input type="hidden" name="action" value="">
    <input type="hidden" name="gallery_id" value="">
    <input type="hidden" name="photo_id" value="">
    <input type="hidden" name="photo_path" value="">
</form>
