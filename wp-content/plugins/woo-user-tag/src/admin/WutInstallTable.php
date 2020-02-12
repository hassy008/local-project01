<?php
namespace admin;

/**
 * Here should be all table and column which will create table
 */
class WutInstallTable
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
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        add_option('version', $version);
    }

    public static function wut_categories()
    {
        global $wpdb;
        $version = '1.0';
        $table_name       = $wpdb->prefix . 'wut_categories';
        $charset_collate  = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(11) NOT NULL AUTO_INCREMENT,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_at DATETIME on update CURRENT_TIMESTAMP NOT NULL,
            name VARCHAR(110) NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        add_option('version', $version);
    }

    public static function wut_tags()
    {
        global $wpdb;
        $version = '1.0';
        $table_name       = $wpdb->prefix . 'wut_tags';
        $table_category   = $wpdb->prefix . 'wut_categories';
        $charset_collate  = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(11) NOT NULL AUTO_INCREMENT,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_at DATETIME on update CURRENT_TIMESTAMP NOT NULL,
            name VARCHAR(110) NOT NULL,
            category_id mediumint(11) NOT NULL,
            PRIMARY KEY (id),
            FOREIGN KEY (category_id) REFERENCES $table_category (id)
            ON DELETE CASCADE
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        add_option('version', $version);
    }

    public static function wut_tag_rules()
    {
        global $wpdb;
        $version = '1.0';
        $table_name       = $wpdb->prefix . 'wut_tag_rules';
        $charset_collate  = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(11) NOT NULL AUTO_INCREMENT,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_at DATETIME on update CURRENT_TIMESTAMP NOT NULL,
            product_id int(11) NOT NULL,
            ref_no int(11) NULL,
            type varchar(50) NULL,
            tag_id int(11) NOT NULL,
            action VARCHAR(20) NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        add_option('version', $version);
    }

    public static function wut_emails()
    {
        global $wpdb;
        $version = '1.0';
        $table_name       = $wpdb->prefix . 'wut_emails';
        $charset_collate  = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(11) NOT NULL AUTO_INCREMENT,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_at DATETIME on update CURRENT_TIMESTAMP NOT NULL,
            tag_id int(11) NOT NULL,
            trigger_at DATETIME NOT NULL,
            subject VARCHAR(250) NULL,
            content TEXT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        add_option('version', $version);
    }

    public static function wut_sequence()
    {
        global $wpdb;
        $version = '1.0';
        $table_name       = $wpdb->prefix . 'wut_sequence';
        $charset_collate  = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(11) NOT NULL AUTO_INCREMENT,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_at DATETIME on update CURRENT_TIMESTAMP NOT NULL,
            trigger_at DATETIME NOT NULL,
            user_id int(11) NOT NULL,
            email VARCHAR(250) NULL,
            status VARCHAR(25) NULL,
            campaign_id int(11) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        add_option('version', $version);
    }



    public static function wut_campaign()
    {
        global $wpdb;
        $version = '1.0';
        $table_name       = $wpdb->prefix . 'wut_campaign';
        $charset_collate  = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(11) NOT NULL AUTO_INCREMENT,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            modified_at DATETIME on update CURRENT_TIMESTAMP NOT NULL,
            tag_ids TEXT NULL,
            title varchar(200) NULL,
            delay VARCHAR(20) NULL,
            delay_type VARCHAR(20) NULL,
            subject VARCHAR(250) NULL,
            content TEXT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        add_option('version', $version);
    }
}
