<?php

/**
 * Here should be all table and column which will create table
 */
class OpsInstallTable
{
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

    public function schedules()
    {
        global $wpdb;
        $version = '1.0';
        $table_name      = $wpdb->prefix . 'schedules';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name(
            id mediumint(11) NOT NULL AUTO_INCREMENT,
            event_name varchar(100),
            date_day varchar(50),
            time_from varchar(10),
            time_to varchar(10),
            type varchar(50),
            availability varchar(50),
            user_id int(10),
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_at DATETIME on update CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        add_action('version', $version);
    }

    public static function crud()
    {
        global $wpdb;
        $version = '1.0';
        $table_name       = $wpdb->prefix . 'crud';
        $charset_collate  = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(11) NOT NULL AUTO_INCREMENT,
            first_name varchar(250) Null,
            last_name varchar(250) Null,
            home_address varchar(250) Null,
            office_address varchar(250) Null,
            mobile_phone varchar(20) NULL,
            messages varchar(300) Null,
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

    public static function progress()
    {
        global $wpdb;
        $version = '1.0';
        $table_name       = $wpdb->prefix . 'progress';
        $charset_collate  = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(11) NOT NULL AUTO_INCREMENT,
            user_id int(11) NOT NULL,
            details text,
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

    public static function serialize()
    {
        global $wpdb;
        $version = '1.0';
        $table_name = $wpdb->prefix . 'serialize';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name(
            id mediumint(11) NOT NULL AUTO_INCREMENT,
            user_id int(11) NOT NULL,
            details text,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_at DATETIME on update CURRENT_TIMESTAMP NOT NULL,
            primary key (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        add_option('version', $version);
    }
}
