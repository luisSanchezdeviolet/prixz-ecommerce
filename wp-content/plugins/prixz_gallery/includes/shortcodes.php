<?php
if (!defined('ABSPATH')) die();

// Registrar shortcodes al inicializar
add_action('init', function () {
    add_shortcode('prixz_gallery', 'prixz_gallery_shortcode_display');
    add_shortcode('prixz_daily_offers', 'prixz_daily_offers_shortcode');
});

// Shortcode para mostrar galeria de imagenes
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

        // Generar salida del carrusel
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

// Shortcode para mostrar las ofertas del día
function prixz_daily_offers_shortcode() {
    // Obtener los IDs de los productos seleccionados como ofertas
    $offer_ids = prixz_get_daily_offers();

    if (empty($offer_ids)) {
        return '<p>No hay ofertas disponibles en este momento.</p>';
    }

    ob_start();

    if (count($offer_ids) > 4) {
        ?>
        <div id="prixzOffersSlider" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach (array_chunk($offer_ids, 4) as $index => $chunk): ?>
                    <div class="carousel-item <?php echo ($index === 0) ? 'active' : ''; ?>">
                        <div class="row">
                            <?php foreach ($chunk as $product_id): ?>
                                <?php
                                $product = wc_get_product($product_id);
                                if (!$product) continue;
                                ?>
                                <div class="col-md-3">
                                    <div class="offer-item text-center">
                                        <img src="<?php echo get_the_post_thumbnail_url($product_id, 'medium'); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" class="img-fluid offer-image">
                                        <h3 class="offer-title"><?php echo esc_html($product->get_name()); ?></h3>
                                        <p class="offer-price"><?php echo wc_price($product->get_sale_price()); ?></p>
                                        <?php if ($product->get_regular_price() > $product->get_sale_price()): ?>
                                            <p class="offer-regular-price"><del><?php echo wc_price($product->get_regular_price()); ?></del></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#prixzOffersSlider" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#prixzOffersSlider" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
        <?php
    } else {
        
        ?>
        <div class="row">
            <?php foreach ($offer_ids as $product_id): ?>
                <?php
                $product = wc_get_product($product_id);
                if (!$product) continue;
                ?>
                <div class="col-md-3">
                    <div class="offer-item text-center">
                        <img src="<?php echo get_the_post_thumbnail_url($product_id, 'medium'); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" class="img-fluid offer-image">
                        <h3 class="offer-title"><?php echo esc_html($product->get_name()); ?></h3>
                        <p class="offer-price"><?php echo wc_price($product->get_sale_price()); ?></p>
                        <?php if ($product->get_regular_price() > $product->get_sale_price()): ?>
                            <p class="offer-regular-price"><del><?php echo wc_price($product->get_regular_price()); ?></del></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
    }

    return ob_get_clean();
}

