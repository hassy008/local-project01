<?php
/**
 * Here should be all table and column which will create table
 */
class LpfInstallTable
{
    public static function generated_pdf()
    {
        global $wpdb;
        $version = '1.0';
        $table_name       = $wpdb->prefix . 'generated_pdf';
        $charset_collate  = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(11) NOT NULL AUTO_INCREMENT,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_at DATETIME on update CURRENT_TIMESTAMP NOT NULL,
            form_id int(11) NULL,
            entry_id int(11) NULL,
            pdf_path VARCHAR(200) NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        add_option('version', $version);
    }
    public static function token()
    {
        global $wpdb;
        $version = '1.0';
        $table_name       = $wpdb->prefix . 'token';
        $charset_collate  = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(11) NOT NULL AUTO_INCREMENT,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_at DATETIME on update CURRENT_TIMESTAMP NOT NULL,
            token TEXT NULL,
            vendor VARCHAR(50) NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        add_option('version', $version);
    }

    public static function fields_association()
    {
        global $wpdb;
        $version = '1.0';
        $table_name       = $wpdb->prefix . 'fields_association';
        $charset_collate  = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(11) NOT NULL AUTO_INCREMENT,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_at DATETIME on update CURRENT_TIMESTAMP NOT NULL,
            form_id int(11) NOT NULL,
            template_id int(11) NOT NULL,
            post_id int(11) NULL,
            arr TEXT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        add_option('version', $version);
    }

}
