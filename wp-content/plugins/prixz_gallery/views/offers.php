<?php if (!defined('ABSPATH')) die(); ?>

<div class="wrap">
    <h1 class="wp-heading-inline">Ofertas del Día</h1>
    <form method="POST">
        <?php
        // Obtener productos en oferta
        $offers = prixz_get_offers(); // Asegúrate de que esta función obtiene productos con descuentos
        $selected_offers = prixz_get_daily_offers(); // Recuperar las ofertas guardadas

        if (!empty($offers)): ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>Producto</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($offers as $product): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="products[]" value="<?php echo esc_attr($product->ID); ?>" <?php echo in_array($product->ID, $selected_offers) ? 'checked' : ''; ?>>
                            </td>
                            <td><?php echo esc_html($product->post_title); ?></td>
                            <td><?php echo wc_price(get_post_meta($product->ID, '_sale_price', true)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="button button-primary">Guardar Ofertas</button>
        <?php else: ?>
            <p>No hay productos con descuento disponibles.</p>
        <?php endif; ?>
    </form>
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar los datos enviados
    $products = isset($_POST['products']) ? array_map('sanitize_text_field', $_POST['products']) : [];
    prixz_save_daily_offers($products);

    // Mostrar notificación en el administrador
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            Swal.fire({
                icon: "success",
                title: "Ofertas guardadas",
                text: "Las ofertas del día se han guardado correctamente.",
            });
        });
    </script>';
}
?>
