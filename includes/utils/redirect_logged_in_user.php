<?php
namespace Register_and_Login\Utils;

/**
 * Redirect logged in user
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
 * Redirects the user to the correct page depending if the user has admin role
 */
function redirect_logged_in_user( $redirect_to = null ) {
	$user = wp_get_current_user();
	if ( user_can( $user, 'manage_options' ) ) {
		if ( $redirect_to ) {
			wp_safe_redirect( $redirect_to );
		} else {
			wp_safe_redirect( admin_url() );
		}
	} else {
		if ( class_exists( 'woocommerce' ) && isset( $options['enable_woocommerce'] ) ) {
			$account_page = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
			wp_safe_redirect( $account_page );
		} else {
			wp_safe_redirect( home_url() );
		}
	}
}
