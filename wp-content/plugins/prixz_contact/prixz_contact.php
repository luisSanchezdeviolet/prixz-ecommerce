<?php

/*
Plugin Name: Prixz Form Contact
Plugin URL: luiscrruzsanch3837@gmail.com
Description: Este es un plugin para el manejo de formularios
Version: 1.0.1
Author: Luis Fernando
license: GPL
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: prixz_contact
*/

if(!function_exists('prixz_activar_plugin')) {
    function prixz_activar_plugin() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'prixz_contact';

        $sql = "CREATE TABLE if not exists $table_name(
            id int not null auto_increment,
            name varchar(200) NOT NULL,
            email varchar(100) NOT NULL,
            message TEXT NOT NULL,
            primary key(id)
        );";

        $wpdb->query($sql);
    }
}

if(!function_exists('prixz_desactivar_plugin')) {
    function prixz_desactivar_plugin() {

    }
}


register_activation_hook(__FILE__, 'prixz_activar_plugin');
register_deactivation_hook(__FILE__, 'prixz_desactivar_plugin');