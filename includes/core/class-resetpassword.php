<?php
namespace Register_and_Login\Core;
use function Register_and_Login\Utils\get_error_message;
use function Register_and_Login\Utils\get_template_html;

/**
 * ResetPassword
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class ResetPassword {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'login_form_rp', array( $this, 'redirect_to_password_reset' ) );
		add_action( 'login_form_resetpass', array( $this, 'redirect_to_password_reset' ) );
		add_shortcode( 'ral-reset-password-form', array( $this, 'render_password_reset_form' ) );
		add_action( 'login_form_rp', array( $this, 'do_password_reset' ) );
        add_action( 'login_form_resetpass', array( $this, 'do_password_reset' ) );
	}

	/**
     * Redirects to the password reset page, or the login page
     * if there are errors.
     */
    public function redirect_to_password_reset() {
        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {

            // Verify key / login combo
            if ( isset( $_REQUEST['key'] ) ) {
                $user = check_password_reset_key( $_REQUEST['key'], $_REQUEST['login'] );
				$login_page = get_permalink( get_page_by_title( 'Login' ) );
                if ( !$user || is_wp_error( $user ) ) {
                    if ( $user && $user->get_error_code() === 'expired_key' ) {
						$login_url = add_query_arg( 'login', 'expiredkey', $login_page );
                        wp_redirect( $login_url );
                    } else {
                        $login_url = add_query_arg( 'login', 'invalidkey', $login_page );
                        wp_redirect( $login_url );
                    }
                    exit;
                }
                $redirect_url = get_permalink( get_page_by_title( 'Reset Password' ) );
                $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
                $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );

                wp_redirect( $redirect_url );
                exit;
            }
        }
    }

	/**
     * A shortcode for rendering the form used to reset a user's password.
     */
    public function render_password_reset_form( $attributes, $content = null ) {
        // Parse shortcode attributes
        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );

        if ( is_user_logged_in() ) {
            return __( 'You are already signed in.', 'register-and-login' );
        } else {
            if ( isset( $_REQUEST['login'] ) && isset( $_REQUEST['key'] ) ) {
                $attributes['login']  = $_REQUEST['login'];
                $attributes['key'] = $_REQUEST['key'];

                // Error messages
                $errors = array();
                if ( isset( $_REQUEST['error'] ) ) {
                    $error_codes = explode( ',', $_REQUEST['error'] );

                    foreach ( $error_codes as $code ) {
                        $errors []= get_error_message( $code );
                    }
                }
                $attributes['errors'] = $errors;

                return get_template_html( 'password_reset_form', $attributes );
            } else {
                return __( 'Invalid password reset link.', 'register-and-login' );
            }
        }
    }

	/**
     * Resets the user's password if the password reset form was submitted.
     */
    public function do_password_reset() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
            $rp_key = $_REQUEST['rp_key'];
            $rp_login = $_REQUEST['rp_login'];

            $user = check_password_reset_key( $rp_key, $rp_login );
			$login_page = get_permalink( get_page_by_title( 'Login' ) );

			if ( !$user || is_wp_error( $user ) ) {
				if ( $user && $user->get_error_code() === 'expired_key' ) {
					$login_url = add_query_arg( 'login', 'expiredkey', $login_page );
					wp_redirect( $login_url );
				} else {
					$login_url = add_query_arg( 'login', 'invalidkey', $login_page );
					wp_redirect( $login_url );
				}
				exit;
			}

            if ( isset( $_POST['password'] ) && !empty( $_POST['password'] ) ) {
                if ( $_POST['password'] != $_POST['pass2'] ) {
                    // Passwords don't match
                    $redirect_url = get_permalink( get_page_by_title( 'Reset Password' ) );

                    $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                    $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                    $redirect_url = add_query_arg( 'error', 'password_reset_mismatch', $redirect_url );

                    wp_redirect( $redirect_url );
                    exit;
                }
                if ( !isset( $_POST['meter-value'] ) || $_POST['meter-value'] < 3 ){
                    // Weak Password
                    $redirect_url = get_permalink( get_page_by_title( 'Reset Password' ) );

                    $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                    $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                    $redirect_url = add_query_arg( 'error', 'weak_password', $redirect_url );

                    wp_redirect( $redirect_url );
                    exit;
                }

                // Parameter checks ok, reset password
                reset_password( $user, $_POST['password'] );
				$pw_changed_url = add_query_arg( 'password', 'changed', $login_page );
                wp_redirect( $pw_changed_url );
            } else {
                // Passwords empty
                $redirect_url = get_permalink( get_page_by_title( 'Reset Password' ) );

                $redirect_url = add_query_arg( 'key', $rp_key, $redirect_url );
                $redirect_url = add_query_arg( 'login', $rp_login, $redirect_url );
                $redirect_url = add_query_arg( 'error', 'password_reset_empty', $redirect_url );

                wp_redirect( $redirect_url );
                exit;
            }
            exit;
        }
    }
}
