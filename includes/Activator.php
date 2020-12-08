<?php

class MSCEC_Activator
{
    public static function activate()
    {
        self::create_table();
        flush_rewrite_rules();
    }

    public static function create_table()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        global $wpdb;

        $table_name = $wpdb->prefix . 'mscec_imports';
        $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset} COLLATE {$wpdb->collate}";

        $sql = "CREATE TABLE $table_name (
                id int(11) NOT NULL AUTO_INCREMENT,
                name varchar(255) DEFAULT NULL,
                date varchar(255) DEFAULT NULL,
                time varchar(255) DEFAULT NULL,
                count varchar(255) DEFAULT NULL,
                file varchar(255) DEFAULT NULL,
                UNIQUE KEY id (id)
                ) $charset_collate;";

        dbDelta($sql);
    }
}
