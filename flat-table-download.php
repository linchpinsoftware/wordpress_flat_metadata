<?php

global $wpdb;

$suffix = time() . rand() . rand();
$tempTable = 'temp_flat_table_' . $suffix;
$wpdb->query('drop table if exists ' . $tempTable);
$wpdb->query("create temporary table {$tempTable}(id bigint unsigned not null primary key, meta_value longtext)");
$type = $_GET['flat_table_download'];
$baseTable = $wpdb->prefix . $type . 's';
$table = $wpdb->prefix . $type . 'meta_flat';
$wpdb->query('drop table if exists ' . $table);
$wpdb->query("create table {$table} like {$baseTable}");
$wpdb->query("insert into {$table} select * from {$baseTable}");
$query = "select meta_key from {$wpdb->prefix}{$type}meta group by meta_key";

foreach ($wpdb->get_results($query) as $k1 => $v1) {
    $column = $wpdb->_real_escape($v1->meta_key);
    $column = str_replace('-', '_', $column);
    $wpdb->query("alter table {$table} add column {$column} longtext");
    $wpdb->query('truncate ' . $tempTable);
    $wpdb->query("insert into {$tempTable} select {$type}_id, group_concat(meta_value) from {$wpdb->prefix}{$type}meta where meta_key = '{$column}' group by {$type}_id, meta_key");
    $wpdb->query("update {$table} f inner join {$tempTable} t on f.{$type}_id = t.id set {$column} = t.meta_value");
}

$path = wp_upload_dir();
$dir = $path['path'];
$source = $dir . "/{$type}_{$suffix}.csv";
$fp = fopen($source, 'w');

foreach ($wpdb->get_results("select * from {$table}", ARRAY_A) as $k1 => $v1) {
    if ($k1 == 0) {
        fputcsv($fp, array_keys($v1));
    }
    fputcsv($fp, $v1);
}

fclose($fp);
$dest = $dir . "/{$type}meta_flat.csv";
rename($source, $dest);
$wpdb->query('drop table ' . $tempTable);

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=file.csv");
header("Pragma: no-cache");
header("Expires: 0");
die(file_get_contents($dest));

// @todo secure or delete file?