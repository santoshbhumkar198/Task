<?php
/*
Plugin Name: Guest Post Plugin
Description: A plugin to allow users to submit guest posts.
Version: 1.0
Author: Your Name
*/

// Ensure no direct access
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue plugin styles
function guest_post_plugin_enqueue_styles() {
    wp_enqueue_style('guest-post-plugin-styles', plugin_dir_url(__FILE__) . 'includes/styles.css');
}
add_action('wp_enqueue_scripts', 'guest_post_plugin_enqueue_styles');

// Include the necessary files
include(plugin_dir_path(__FILE__) . 'includes/submission-form.php');
include(plugin_dir_path(__FILE__) . 'includes/custom-post-type.php');
?>
