<?php
namespace Register_and_Login\Core;
use function Register_and_Login\Utils\get_error_message;
use function Register_and_Login\Utils\get_template_html;
use function Register_and_Login\Utils\redirect_logged_in_user;

/**
 * Login
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class Login {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'login_form_login', array( $this, 'redirect_to_login' ) );
		add_shortcode( 'ral-login-form', array( $this, 'render_login_form' ) );
		add_filter( 'login_redirect', array( $this, 'redirect_after_login' ), 10, 3 );
	}

	/**
     * Redirect the user to the login page instead of wp-login.php
     */
    public function redirect_to_login() {
        if ( $_SERVER['REQUEST_METHOD'] == 'GET' ) {
            $redirect_to = isset( $_REQUEST['redirect_to'] ) ? wp_validate_redirect( $_REQUEST['redirect_to'], '' ) : '';

            // Encode URL
            $encoded_redirect_to = urlencode( $redirect_to );

            if ( is_user_logged_in() ) {
                redirect_logged_in_user( $encoded_redirect_to );
                exit;
            }

            // The rest are redirected to the login page
			$login_page = get_permalink( get_page_by_title( 'Login' ) );
            if ( !empty( $encoded_redirect_to ) ) {
                $login_page = add_query_arg( 'redirect_to', $encoded_redirect_to, $login_page );
            }
            wp_safe_redirect( $login_page );
            exit;
        }
    }

	/**
     * Shortcode for rendering the login form.
     */
    public function render_login_form( $attributes, $content = null ) {
        // Parse shortcode attributes
        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );
        $show_title = $attributes['show_title'];

        if ( is_user_logged_in() ) {
            return __( 'You are already signed in.', 'register-and-login' );
        }

        // Pass the redirect parameter to the Wordpress login functionality
        // by default don't specify a redirect, but if a valid redirect URL has been passed
        // as a request parameter, use it.
        $attributes['redirect'] = '';
        if ( isset( $_REQUEST['redirect_to'] ) ) {
            $attributes['redirect'] = wp_validate_redirect( $_REQUEST['redirect_to'], $attributes['redirect'] );
        }

        // Check if the user just registered
        $attributes['registered'] = isset( $_REQUEST['registered'] );

        // Check if the user just requested a new password
        $attributes['lost_password_sent'] = isset( $_REQUEST['checkemail'] ) && $_REQUEST['checkemail'] == 'confirm';

        // Check if user just updated password
        $attributes['password_updated'] = isset( $_REQUEST['password'] ) && $_REQUEST['password'] == 'changed';

        // Error Messages
        $errors = array();
        if ( isset( $_REQUEST['login'] ) ) {
            $error_codes = explode( ',', $_REQUEST['login'] );
            foreach ( $error_codes as $code ) {
                $errors [] = get_error_message( $code );
            }
        }
        $attributes['errors'] = $errors;

        // Check if user just logged out
        $attributes['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;

        // Render the login form using an external template
        return get_template_html( 'login_form', $attributes );
    }

	/**
     * Returns the URL to which the user should be redirected after login.
     */
    public function redirect_after_login( $redirect_to, $requested_redirect_to, $user ) {
		$options = get_option( 'register_and_login_general' );
        $redirect_url = home_url();

        if ( !isset( $user->ID ) ) {
            return $redirect_url;
        }

        if ( user_can( $user, 'manage_options' ) ) {
            // Use the redirect_to parameter if set, otherwise redirect
            if ( $requested_redirect_to == '' ) {
                $redirect_url = admin_url();
            } else {
                $redirect_url = $requested_redirect_to;
            }
        } else {
            // Non-admin users
            if ( $redirect_to != '' ) {
                $redirect_url = $redirect_to;
            } else {
                if ( class_exists( 'woocommerce' ) && isset( $options['enable_woocommerce'] ) ) {
                    $account_page = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
                    $redirect_url = $account_page;
                } else {
                    $redirect_url = isset( $options['enable_login_redirect'] ) ? get_page_link( $options['login_redirect_page'] ) : '';
                }
            }
        }

        return wp_validate_redirect( $redirect_url, home_url() );
    }
}
