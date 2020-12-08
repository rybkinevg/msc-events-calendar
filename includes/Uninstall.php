<?php

class MSCEC_Uninstall
{
    public static function uninstall()
    {
        if (!current_user_can('activate_plugins'))
            return;

        check_admin_referer('bulk-plugins');

        if (__FILE__ != WP_UNINSTALL_PLUGIN)
            return;

        global $wpdb;

        $wpdb->query('DROP TABLE IF EXISTS ' . $wpdb->prefix . 'mscec_imports');
    }
}
