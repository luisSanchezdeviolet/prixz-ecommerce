<?php

if (!defined('ABSPATH')) die();

add_filter('template_include', function ($template) {
    if (is_front_page()) {
        $custom_front_page = get_stylesheet_directory() . '/templates/front-page.php';
        if (file_exists($custom_front_page)) {
            return $custom_front_page;
        }
    }
    return $template;
});


require_once get_stylesheet_directory() . '/includes/enqueue-scripts.php';