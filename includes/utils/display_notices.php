<?php
namespace Register_and_Login\Utils;

/**
 * Display Notices
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

// add_action( 'admin_notices', array( $this, 'display_errors' ) );

function display_errors() {
	$class = 'notice notice-error';
	$message = __( 'Register and Login: An error has occured', 'register-and-login' );
	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}
/**
 * Displays an error notice for user switching
 */
function user_switching_error() {
	$class = 'notice notice-error';
	$message = __( 'Error switching to user', 'register-and-login' );
	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
}
