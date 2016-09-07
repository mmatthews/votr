<?php
/*
Plugin Name: Votr
Description: Reddit-style voting for WordPress Comments
Version:     0.1
Author:      Mark Matthews
Author URI:  http://markmatthews.ca
*/

//define('VOTRPATH', WP_PLUGIN_DIR . "/" . dirname(plugin_basename( __FILE__ )));
//define('VOTRURL', WP_PLUGIN_URL . "/" . dirname(plugin_basename( __FILE__ )));

require_once( WP_PLUGIN_DIR . '/votr/view.php' );
require_once( WP_PLUGIN_DIR . '/votr/controller.php' );

function votr_install() {
  global $wpdb;
  $table_name = $wpdb->prefix . "votr";
  // Create Database
  if($wpdb->get_var("show tables like " . $table_name) != $table_name) {
    $sql = "CREATE TABLE $table_name (
    comment_id varchar(55) NOT NULL,
    voter_ip varchar(55) NOT NULL,
    vote_value int(10) NOT NULL
    );";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
  }
}
register_activation_hook(__FILE__, 'votr_install');

function votr_scripts() {
  wp_enqueue_style( 'styles', plugins_url('/css/style.css', __FILE__));
  wp_register_script( 'votr', plugins_url('/js/votr.js', __FILE__), array('jquery') );
  $data = array(
      'ajaxurl' => admin_url( 'admin-ajax.php' ),
  );
  wp_localize_script( 'votr', 'myAjax', $data);
  wp_enqueue_script( 'votr' );
}
add_action('wp_enqueue_scripts', 'votr_scripts');

// Display voting element after comment_text
function show_ballot($content) {
  $id = get_comment_ID();
  $output = $content . showvotes($id);
  return $output;
}
add_filter('comment_text', 'show_ballot');

// Remove filter before comment is inserted
function preprocess_comment( $commentdata ) {
  remove_filter("comment_text", "show_ballot");
  return $commentdata;
}
add_filter( 'preprocess_comment' , 'preprocess_comment' );

?>