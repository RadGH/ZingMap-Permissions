<?php
/*
Plugin Name: Zingmap Permissions
Plugin URI:  https://zingmap.com/
Author:      Radley Sustaire and Rosie Leung, ZingMap LLC
Description: Adds permission management by user and by role for posts and taxonomy terms.
Version:     1.1.0
Author URI:  https://zingmap.com/
*/

defined( 'ABSPATH' ) || exit;

define( 'ZMPM_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'ZMPM_PATH', dirname( __FILE__ ) );
define( 'ZMPM_VERSION', '1.1.0' );

add_action( 'plugins_loaded', 'zmpm_init_plugin' );

// Initialize plugin: Load plugin files
function zmpm_init_plugin() {
	if ( ! class_exists( 'acf' ) ) {
		add_action( 'admin_notices', 'zmpm_warn_no_acf' );
		
		return;
	}
	
	// register post type and acf fields
	include_once( ZMPM_PATH . '/includes/zmpm-register.php' );
	
	// configure admin dashboard
	include_once( ZMPM_PATH . '/includes/zmpm-dashboard.php' );
	
	// add ACF hooks
	include_once( ZMPM_PATH . '/includes/zmpm-acf-hooks.php' );
	
	// control visibility of posts and terms
	include_once( ZMPM_PATH . '/includes/zmpm-frontend.php' );
}

// Require ACF
function zmpm_warn_no_acf() {
	?>
	<div class="error">
		<p><strong>Zingmap Permissions:</strong> This plugin requires Advanced Custom Fields in order to operate. Please install and activate ACF or disable this plugin.</p>
	</div>
	<?php
}