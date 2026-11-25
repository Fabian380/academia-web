<?php
/**
 * Plugin Name: Ejemplo · Retención de registros
 * Description: Borra a diario los registros ordinarios con más de N días, como dice la política de datos.
 */
defined('ABSPATH') || exit;

const EJEMPLO_DIAS = 90; // Debe coincidir con lo que dice la política de tratamiento de datos.

add_action('init', function () {
    if (!wp_next_scheduled('ejemplo_purga')) {
        wp_schedule_event(time() + 300, 'daily', 'ejemplo_purga');
    }
});

add_action('ejemplo_purga', function () {
    global $wpdb;
    $wpdb->query($wpdb->prepare(
        "DELETE FROM {$wpdb->prefix}ejemplo_registros WHERE tipo = 'ordinario' AND fecha < (NOW() - INTERVAL %d DAY)",
        EJEMPLO_DIAS
    ));
});
