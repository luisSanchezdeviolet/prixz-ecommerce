<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}


class PrixzContactUninstaller {

    /**
     * Ejecuta el proceso de desinstalación.
     */
    public static function run() {
        self::deleteDatabaseTables();
    }


    private static function deleteDatabaseTables() {
        global $wpdb;


        $tableContact = $wpdb->prefix . 'prixz_contact';
        $tableResponses = $wpdb->prefix . 'prixz_contact_respuestas';


        $wpdb->query( "DROP TABLE IF EXISTS $tableResponses" );
        $wpdb->query( "DROP TABLE IF EXISTS $tableContact" );
    }
}

PrixzContactUninstaller::run();
