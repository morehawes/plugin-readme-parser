<?php
/*
Plugin Name: Plugin README Parser
Plugin URI: https://wordpress.org/plugins/wp-readme-parser/
Description: Show WordPress Plugin README in your Posts
Version: 2.0
Author: David Artiss
Author URI: http://www.artiss.co.uk
Text Domain: wp-readme-parser
Domain Path: /languages
*/

/**
* Plugin README Parser
*
* Main code - include various functions
*
* @package	Artiss-README-Parser
* @since	1.2
*/

define( 'artiss_readme_parser_version', '2.0' );

$functions_dir = plugin_dir_path( __FILE__ ) . 'includes/';

// Include all the various functions

include_once( $functions_dir . 'Michelf/MarkdownExtra.inc.php' );		// PHP Markdown Extra

include_once( $functions_dir . 'preprocess-file.php' );					// Various functions

include_once( $functions_dir . 'shortcode.php' );				// Generate the output
?>