<?php

/**
 * Enqueue custom admin styles
 */
add_action( 'admin_enqueue_scripts', function() {
	// Plugin styles
	wp_enqueue_style('chronoswp-css', plugin_dir_url(__FILE__) . 'assets/css/chronoswp.css', array());
});
