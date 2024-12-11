<?php
if (!defined('ABSPATH')) die();


add_action('init', function () {
    add_shortcode('prixz_gallery', 'prixz_gallery_shortcode_display');
});


if (!function_exists('prixz_gallery_shortcode_display')) {
    function prixz_gallery_shortcode_display($attributes, $content = "") {
        global $wpdb;


        $attributes = shortcode_atts([
            'id' => 0,
        ], $attributes);

        $gallery_id = intval($attributes['id']);
        if ($gallery_id <= 0) {
            return '<p>No se proporcionó un ID válido para la galería.</p>';
        }


        $table_photos = "{$wpdb->prefix}prixz_plugin_photos";
        $query = $wpdb->prepare("SELECT * FROM $table_photos WHERE prixz_galeria_id = %d ORDER BY id DESC", $gallery_id);
        $photos = $wpdb->get_results($query, ARRAY_A);

        if (empty($photos)) {
            return '<p>No se encontraron fotos para esta galería.</p>';
        }


        ob_start();
        ?>
        <div id="prixzCarousel<?php echo esc_attr($gallery_id); ?>" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($photos as $key => $photo): ?>
                    <div class="carousel-item <?php echo ($key === 0) ? 'active' : ''; ?>">
                        <img src="<?php echo esc_url(get_site_url() . $photo['url']); ?>" class="d-block w-100 img-fluid" alt="Galería">
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#prixzCarousel<?php echo esc_attr($gallery_id); ?>" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#prixzCarousel<?php echo esc_attr($gallery_id); ?>" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
        <?php
        return ob_get_clean();
    }
}
