<?php
// Function to create a custom database table
function create_guest_post_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'guest_post_meta';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        post_id bigint(20) NOT NULL,
        meta_key varchar(255) NOT NULL,
        meta_value longtext NOT NULL,
        PRIMARY KEY  (id),
        KEY post_id (post_id),
        KEY meta_key (meta_key(191))
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'create_guest_post_table');
?>
