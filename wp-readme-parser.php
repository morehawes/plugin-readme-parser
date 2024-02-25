<?php
/*
Plugin Name: Plugin README Parser
Plugin URI: https://wordpress.org/plugins/wp-readme-parser/
Description: Display a WordPress plugin's README file in XHTML format, for embedding on a post or page.
Version: 1.3.14
Author: Joe Hawes
Author URI: https://www.morehawes.ca/
Text Domain: wp-readme-parser
 */

/**
 * Plugin README Parser
 *
 * Main code - include various functions
 *
 * @package	Artiss-README-Parser
 */

define('artiss_readme_parser_version', '1.3.14');

/**
 * Main Includes
 *
 * Include all the plugin's functions
 *
 * @since	1.2
 */

$functions_dir = plugin_dir_path(__FILE__) . 'includes/';

// Include all the various functions

if (is_admin()) {

	include_once $functions_dir . 'admin-config.php'; // Various admin. functions

}

include_once $functions_dir . 'Michelf/MarkdownExtra.inc.php'; // PHP Markdown Extra

include_once $functions_dir . 'functions.php'; // Various functions

include_once $functions_dir . 'generate-output.php'; // Generate the output
?>
