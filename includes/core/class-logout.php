<?php
namespace Register_and_Login\Core;

/**
 * Logout
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class Logout {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'wp_logout', array( $this, 'redirect_after_logout' ) );
	}

	/**
     * Redirect after the user has logged out.
     */
    public function redirect_after_logout() {
		$login_page = get_permalink( get_page_by_title( 'Login' ) );
		$redirect_url = add_query_arg( 'logged_out', 'true', $login_page );
        wp_safe_redirect( $redirect_url );
        exit;
    }
}
