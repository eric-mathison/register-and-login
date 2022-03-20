<?php
namespace Register_and_Login\Utils;

/**
 * Get Template HTML
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Renders the contents of the given template to a string and returns it.
 */
function get_template_html( $template_name, $attributes = null ) {
	if ( !$attributes ) {
		$attributes = array();
	}

	ob_start();
	do_action( 'register_and_login_before_' . $template_name );

	require( plugin_dir_path( dirname( __FILE__ ) ) .'templates/' . $template_name . '.php');

	do_action( 'register_and_login_after_' . $template_name );

	$html = ob_get_contents();
	ob_end_clean();

	return $html;
}
