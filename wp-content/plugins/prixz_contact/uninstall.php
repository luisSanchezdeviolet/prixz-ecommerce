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
        error_log('Ejecutando proceso de desinstalación del plugin.');
        self::deleteDatabaseTables();
    }

    private static function deleteDatabaseTables() {
        global $wpdb;

        $tableContact = $wpdb->prefix . 'prixz_contact';
        $tableResponses = $wpdb->prefix . 'prixz_contact_respuestas';

        $wpdb->query( "DROP TABLE IF EXISTS $tableResponses" );
        if ( $wpdb->last_error ) {
            error_log( 'Error eliminando la tabla responses: ' . $wpdb->last_error );
        } else {
            error_log( "Tabla $tableResponses eliminada exitosamente." );
        }

        $wpdb->query( "DROP TABLE IF EXISTS $tableContact" );
        if ( $wpdb->last_error ) {
            error_log( 'Error eliminando la tabla contact: ' . $wpdb->last_error );
        } else {
            error_log( "Tabla $tableContact eliminada exitosamente." );
        }
    }
}

PrixzContactUninstaller::run();
