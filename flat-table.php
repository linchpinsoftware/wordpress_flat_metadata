<?php
/*
Plugin Name: Flat Meta Data

@todo provide option to split multiple values per key into multiple columns
@todo add ability to filter data
*/
if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_init', function () {
    if ($_GET['flat_table_download'] ?? 0) {
        require_once 'flat-table-download.php';
    }
});

add_action('admin_menu', function () {
    add_menu_page(
        $title = 'Flat Meta Data',
        $title,
        'manage_options',
        'flat-table/flat-table-admin.php',
        '',
        'dashicons-media-spreadsheet'
    );
});
