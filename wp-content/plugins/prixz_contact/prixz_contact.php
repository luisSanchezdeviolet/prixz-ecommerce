<?php

/*
Plugin Name: Prixz Form Contact
Plugin URL: https://example.com
Description: Este es un plugin para el manejo de formularios.
Version: 1.0.1
Author: Luis Fernando
License: GPL
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: prixz_contact
*/


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Evita acceso directo.
}

class PrixzContactPlugin {
    private $table_name;
    private $table_name_respuestas;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'prixz_contact';
        $this->table_name_respuestas = $wpdb->prefix . 'prixz_contact_respuestas';

        register_activation_hook(__FILE__, [ $this, 'activar' ]);
        register_deactivation_hook(__FILE__, [ $this, 'desactivar' ]);
    }

    public function activar() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $this->table_name (
            id INT NOT NULL AUTO_INCREMENT,
            name VARCHAR(200) NOT NULL,
            email VARCHAR(100) NOT NULL,
            fecha DATE,
            PRIMARY KEY (id)
        ) $charset_collate;";
        $wpdb->query($sql);
        $wpdb->query("ALTER TABLE $table_name ADD INDEX(`email`);");

        $sql_respuestas = "CREATE TABLE IF NOT EXISTS $this->table_name_respuestas (
            id INT NOT NULL AUTO_INCREMENT,
            prixz_form_principal_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(100) NOT NULL,
            phone VARCHAR(15) NOT NULL,
            message TEXT NOT NULL,
            fecha DATE,
            PRIMARY KEY (id),
            FOREIGN KEY (prixz_form_principal_id) REFERENCES $this->table_name (id)
        ) $charset_collate;";
        $wpdb->query($sql_respuestas);
        $wpdb->query("ALTER TABLE $table_name_respuestas ADD INDEX(`email`);");
        $wpdb->query("ALTER TABLE $table_name_respuestas ADD INDEX(`phone`);");
    }

    public function desactivar() {
        
    }
}

new PrixzContactPlugin();
