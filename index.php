<?php
/*
Plugin Name: Votr
Description: Reddit-style voting for WordPress Comments
Version:     0.1
Author:      Mark Matthews
Author URI:  http://wwide.ca
*/


define('VOTRURL', WP_PLUGIN_URL . "/" . dirname(plugin_basename( __FILE__ )));
define('VOTRPATH', WP_PLUGIN_DIR . "/" . dirname(plugin_basename( __FILE__ )));

require_once( WP_PLUGIN_DIR . '/votr/view.php' );
require_once( WP_PLUGIN_DIR . '/votr/controller.php' );

function votr_install () {
  global $wpdb;
  $table_name = $wpdb->prefix . "votr";

  // Create Database
  /*
  $charset_collate = $wpdb->get_charset_collate();
  $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
    name tinytext NOT NULL,
    text text NOT NULL,
    url varchar(55) DEFAULT '' NOT NULL,
    UNIQUE KEY id (id)
  ) $charset_collate;";
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta($sql);
  */

}

register_activation_hook( __FILE__, 'votr_install' );



function votr_scripts() {

    wp_enqueue_style( 'styles', WP_PLUGIN_URL.'/votr/css/style.css' );

    wp_register_script( 'votr', WP_PLUGIN_URL.'/votr/js/votr.js', array('jquery') );
    $data = array(
        'ajaxurl' => admin_url( 'admin-ajax.php' ),
    );
    wp_localize_script( 'votr', 'myAjax', $data);
    wp_enqueue_script( 'votr' );
}
add_action('wp_enqueue_scripts', 'votr_scripts');



function show_ballot($content) {
    $id = get_comment_ID();
    $output = $content;
    $output = $output . showvotes($id);
    return $output;
}
add_filter('comment_text', 'show_ballot');


?>