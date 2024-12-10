<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

class TamilaGaleriaUninstaller
{

    public static function run()
    {
        self::deleteDatabaseTables();
    }


    private static function deleteDatabaseTables()
    {
        global $wpdb;

        $tablePhotos = $wpdb->prefix . 'prixz_plugin_photos';
        $tableGallery = $wpdb->prefix . 'prixz__plugin_gallery';

        $wpdb->query("DROP TABLE IF EXISTS $tablePhotos");
        $wpdb->query("DROP TABLE IF EXISTS $tableGallery");
    }
}


TamilaGaleriaUninstaller::run();
