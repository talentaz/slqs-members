<?php
/**
 * Plugin Name: SLQS Member Registration System
 * Description: A custom plugin for managing SLQS member registrations.
 * Version: 1.0
 * Author: Danila
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include admin and frontend files
require_once plugin_dir_path(__FILE__) . 'admin/index.php';
require_once plugin_dir_path(__FILE__) . 'frontend/login.php';
require_once plugin_dir_path(__FILE__) . 'frontend/index.php';
require_once plugin_dir_path(__FILE__) . 'frontend/list.php';
require_once plugin_dir_path(__FILE__) . 'frontend/detail.php';
require_once plugin_dir_path(__FILE__) . 'frontend/profile.php';
require_once plugin_dir_path(__FILE__) . 'frontend/referee.php';
require_once plugin_dir_path(__FILE__) . 'sql-member-functions.php';


// Create custom database table on plugin activation
function slqs_create_member_tables() {
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    // Create location table
    $location_table_name = $wpdb->prefix . 'slqs_location';
    $sql_location = "CREATE TABLE $location_table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        location varchar(100) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    // Create member_type table
    $member_type_table_name = $wpdb->prefix . 'slqs_member_type';
    $sql_member_type = "CREATE TABLE $member_type_table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        type_name varchar(100) NOT NULL,
        description text DEFAULT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    // Create slqs_members table
    $members_table_name = $wpdb->prefix . 'slqs_members';
    $sql_members = "CREATE TABLE $members_table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        first_name varchar(255) NOT NULL,
        last_name varchar(255) NOT NULL,
        user_id varchar(255) NOT NULL,
        profile_photo varchar(255) NOT NULL,
        date_of_birth date NOT NULL,
        status varchar(20) DEFAULT 'PENDING',

        emirate_of_location mediumint(9) NOT NULL,
        current_address varchar(255) NOT NULL,
        mobile_primary varchar(15) NOT NULL,
        mobile_alternative varchar(15) DEFAULT NULL,
        email_alternative varchar(100) DEFAULT NULL,
        joining_date date NOT NULL,

        current_employer varchar(255) NOT NULL,
        employer_address varchar(255) NOT NULL,
        current_work_location mediumint(9) NOT NULL,
        company_phone varchar(15) NOT NULL,
        company_email varchar(100) NOT NULL,
        current_position varchar(100) NOT NULL,

        emergency_contact_name varchar(255) NOT NULL,
        emergency_contact_relationship varchar(100) NOT NULL,
        emergency_contact_address varchar(255) NOT NULL,
        emergency_contact_mobile varchar(15) NOT NULL,
        emergency_contact_email varchar(100) NOT NULL,

        referee_name varchar(255) NOT NULL,
        referee_membership_no varchar(100) NOT NULL,
        referee_mobile varchar(15) NOT NULL,
        pdf_path varchar(255) NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    // Create member_member_type table
    $member_member_type_table_name = $wpdb->prefix . 'slqs_member_group';
    $sql_member_member_type = "CREATE TABLE $member_member_type_table_name (
        member_id mediumint(9) NOT NULL,
        member_type_id mediumint(9) NOT NULL,
        PRIMARY KEY (member_id, member_type_id),
        FOREIGN KEY (member_id) REFERENCES $members_table_name(id) ON DELETE CASCADE,
        FOREIGN KEY (member_type_id) REFERENCES $member_type_table_name(id) ON DELETE CASCADE
    ) $charset_collate;";

    // Create member_type table
    $member_special_info_table_name = $wpdb->prefix . 'slqs_member_special_info';
    $member_special_info= "CREATE TABLE $member_special_info_table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        member_id mediumint(9) NOT NULL,
        member_type_id mediumint(9) NOT NULL,
        academic_qualifications TEXT DEFAULT NULL,
        professional_qualifications TEXT DEFAULT NULL,
        bio TEXT DEFAULT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        FOREIGN KEY (member_id) REFERENCES {$wpdb->prefix}members(id) ON DELETE CASCADE,
        FOREIGN KEY (member_type_id) REFERENCES {$wpdb->prefix}member_type(id) ON DELETE CASCADE
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql_location);
    dbDelta($sql_member_type);
    dbDelta($sql_members);
    dbDelta($sql_member_member_type);
    dbDelta($member_special_info);
}

register_activation_hook(__FILE__, 'slqs_create_member_tables');

add_action( 'admin_menu', 'register_slqs_edit_member_page' );

function register_slqs_edit_member_page() {
    add_menu_page(
        'Edit Member',
        'Edit Member',
        'edit_users',
        'slqs-edit-member',
        'render_slqs_edit_member_page',
        'dashicons-admin-users',
        100
    );
}


add_action('admin_menu', 'hide_slqs_edit_member_menu');

function hide_slqs_edit_member_menu() {
    remove_menu_page('slqs-edit-member'); // Remove the menu item
}

function render_slqs_edit_member_page() {
    include_once plugin_dir_path( __FILE__ ) . 'admin/slqs-edit-member.php';
}
