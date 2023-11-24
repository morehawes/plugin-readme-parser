<?php
/**
* Admin Menu Functions
*
* Various functions relating to the various administration screens
*
* @package	Artiss-README-Parser
*/

/**
* Add meta to plugin details
*
* Add options to plugin meta line
*
* @since	1.2
*
* @param	string  $links	Current links
* @param	string  $file	File in use
* @return   string			Links, now with settings added
*/

function arp_set_plugin_meta( $links, $file ) {

	if ( strpos( $file, 'wp-readme-parser.php' ) !== false ) { $links = array_merge( $links, array( '<a href="https://wordpress.org/support/plugin/wp-readme-parser">' . __( 'Support','wp-readme-parser' ) . '</a>' ) ); }

	return $links;
}

add_filter( 'plugin_row_meta', 'arp_set_plugin_meta', 10, 2 );

/**
 * Show Admin Message
 *
 * Display message on the administration screen once 2 months away from the plugins' closure.
 */
function arp_add_admin_notice() {

	if ( gmdate( 'Ymd' ) >= '20241001' && is_admin() ) {
		echo '<div class="notice notice-error"><p>';
		echo __( sprintf( '⛔️ The Plugin README Praser plugin will be discontinued December 2024. After this time there will be no further updates, including security vulnerabilities. It is important that you disable it and find an alternative plugin before then. <a href="%s">Find out more here</a>.', 'https://wordpress.org/support/topic/important-please-read-before-posting-6/' ), 'wp-readme-parser' );
		echo '</p></div>';
	}
}

add_action( 'admin_notices', 'arp_add_admin_notice' );
