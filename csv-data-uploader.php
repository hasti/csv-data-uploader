<?php
/**
 * Plugin Name: CSV Data Uploader
 * Description: A simple plugin to upload CSV data to WordPress
 * Version: 1.0
 * Author: Hastimal Shah
 * Author URI: https://hastishah.com
 * Plugin URI: https://hastishah.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// First, let’s add the shortcode to display the form for uploading the CSV file.

define('CSV_DATA_UPLOADER_DIR', plugin_dir_path(__FILE__));
define('CSV_DATA_UPLOADER_URL', plugin_dir_url(__FILE__));

//echo CSV_DATA_UPLOADER_DIR. 'template/csv-form.php';
add_shortcode('csv_data_uploader', 'handle_csv_data_uploader');

function handle_csv_data_uploader() {

    // start PHP buffer
    ob_start();

    include_once CSV_DATA_UPLOADER_DIR . 'template/csv_form.php';

    // Read Buffer Contents
    $content = ob_get_contents();

    // Clear & Close Buffer
    ob_end_clean();

    return $content;
    
}
 
// DB Table creation on plugin activation

register_activation_hook(__FILE__, 'csv_data_uploader_create_table');

function csv_data_uploader_create_table() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'students_data';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE `".$table_name."` (
    `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` varchar(50) NULL,
    `email` varchar(50) NULL,
    `age` int(5) NULL,
    `photo` varchar(30) NULL,
    `phone` varchar(120) NULL
    ) ". $charset_collate."";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// To Add Script and Styles
add_action('wp_enqueue_scripts', 'csv_data_uploader_scripts');

function csv_data_uploader_scripts() {
    //wp_enqueue_style('csv-data-uploader-css', CSV_DATA_UPLOADER_URL . 'assets/css/csv-data-uploader.css');
    wp_enqueue_script('csv-data-uploader-js', CSV_DATA_UPLOADER_URL . 'assets/script.js', array('jquery'), '', true);
    wp_localize_script('csv-data-uploader-js', 'csv_data_uploader', array('ajax_url' => admin_url('admin-ajax.php')));  
}

// To Handle AJAX Request
add_action('wp_ajax_csv_data_uploader', 'csv_data_uploader'); // When user is logged in
add_action('wp_ajax_nopriv_csv_data_uploader', 'csv_data_uploader'); // When user is not logged in

function csv_data_uploader() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'students_data';

    $csv_file = $_FILES['csv_file']['tmp_name'];

    if (empty($csv_file)) {
        wp_send_json_error(array('message' => 'Please select a CSV file to upload'));
    }

    $csv = array_map('str_getcsv', file($csv_file));

    $header = array_shift($csv);

    $data = array();

    foreach ($csv as $row) {
        $data[] = array_combine($header, $row);
    }

    foreach ($data as $row) {
        $wpdb->insert($table_name, $row);
    }

    wp_send_json_success(array('message' => 'CSV data uploaded successfully'));
}