<?php
if (!defined('ABSPATH')) die();

class HomeSlider {
    public function __construct() {
        add_shortcode('home_slider', [$this, 'render_slider']);
    }

    public function render_slider() {
        // Imágenes del slider (aquí puedes conectarlo con tu BD o personalizar las imágenes)
        $slider_images = [
            get_stylesheet_directory_uri() . '/public/images/slider1.jpg',
            get_stylesheet_directory_uri() . '/public/images/slider2.jpg',
            get_stylesheet_directory_uri() . '/public/images/slider3.jpg',
        ];

        if (empty($slider_images)) {
            return '<p>No hay imágenes para mostrar en el slider.</p>';
        }

        ob_start(); ?>
        <div id="homeSlider" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($slider_images as $index => $image): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <img src="<?php echo esc_url($image); ?>" class="d-block w-100" alt="Slider Image">
                    </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#homeSlider" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#homeSlider" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
        <?php
        return ob_get_clean();
    }
}

new HomeSlider();
