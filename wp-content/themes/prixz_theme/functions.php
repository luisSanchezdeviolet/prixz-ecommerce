<?php

if(!defined('ABSPATH')) {
    die();;
}

if(!function_exists('prixz_setup')) {
    
    function prixz_setup() {
        add_theme_support('post-thumbnails');
    }

    add_action('after_setup_theme', 'prixz_setup');

}