<?php
namespace Register_and_Login\Core;

/**
 * Authentication
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class Authentication {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );
	}

	/**
     * Redirect the user after authentication if there were any errors/
     */
    function maybe_redirect_at_authenticate( $user, $username, $password ) {
        // Check if the earlier authenticate filter functions have errors
        if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            if ( !isset($_SERVER['HTTP_REFERER']) ) {
                header("HTTP/1.1 401 Unauthorized");
                exit;
            } elseif ( is_wp_error( $user ) ) {
                $error_codes = join( ',', $user->get_error_codes() );
				$redirect_to = isset( $_REQUEST['redirect_to'] ) ? wp_validate_redirect( $_REQUEST['redirect_to'], '' ) : '';

                $login_url = get_permalink( get_page_by_title( 'Login' ) );
                $login_url = add_query_arg( 'login', $error_codes, $login_url );
				$login_url = add_query_arg( 'redirect_to', $redirect_to, $login_url );

                if ( isset( $_COOKIE['_rallogin'] ) && htmlspecialchars($_COOKIE['_rallogin']) > 4 ) {
                    setcookie('_rallogin', '0');
                    $redirect_url = get_permalink( get_page_by_title( 'Lost Password' ) );
                    wp_safe_redirect( $redirect_url );
                    exit;
                }

				if ( isset( $_COOKIE['_rallogin'] ) ) {
					$tries = htmlspecialchars($_COOKIE['_rallogin']);
					setcookie('_rallogin', $tries + 1);
				} else {
					setcookie('_rallogin', '1');
				}

                wp_safe_redirect( $login_url );
                exit;
            }
        }
        setcookie('_rallogin', '0');
        return $user;
    }
}
