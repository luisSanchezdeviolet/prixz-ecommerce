<?php if (!defined('ABSPATH')) die();

global $wpdb;
$table_gallery = "{$wpdb->prefix}prixz_plugin_gallery";

// Procesar formulario de creación de galería
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === '1') {
    // Validar nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'seg')) {
        echo '<script>alert("Nonce inválido.");</script>';
        exit;
    }


    $gallery_name = sanitize_text_field($_POST['name']);
    if (empty($gallery_name)) {
        echo '<script>alert("El nombre de la galería es obligatorio.");</script>';
        exit;
    }


    $data = [
        'name' => $gallery_name,
        'fecha' => date('Y-m-d'),
    ];
    $inserted = $wpdb->insert($table_gallery, $data);

    // Verificar si la inserción fue exitosa
    if ($inserted) {
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Se creó la galería correctamente.',
                })
            });
        </script>
        <?php
    } else {
        ?>
        <script>
            Swal.fire("Error", "No se pudo crear la galería. Por favor, inténtalo de nuevo.", "error");
        </script>
        <?php
    }
}

// Obtener galerías existentes
$galleries = $wpdb->get_results("SELECT * FROM {$table_gallery} ORDER BY id DESC", ARRAY_A);
?>

<?php include plugin_dir_path(__FILE__) . '../templates/modal-create-gallery.php'; ?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo esc_html(get_admin_page_title()); ?></h1>
    <p class="d-flex justify-content-end">
        <a href="javascript:void(0);" onclick="galleryCreateModal();" class="btn btn-primary" title="Crear">
            <i class="fas fa-plus"></i> Crear
        </a>
    </p>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre de la galería</th>
                    <th>Shortcode</th>
                    <th>Fotos</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($galleries as $gallery): ?>
                    <tr>
                        <td><?php echo esc_html($gallery['id']); ?></td>
                        <td><?php echo esc_html($gallery['name']); ?></td>
                        <td>[prixz_gallery id=<?php echo esc_html($gallery['id']); ?>]</td>
                        <td>
                            <a href="<?php echo esc_url(admin_url('admin.php?page=prixz_gallery_edit&id=' . $gallery['id'])); ?>">
                                <i class="fas fa-images"></i>
                            </a>
                        </td>
                        <td>
                            <a href="javascript:void(0);" class="text-danger">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
