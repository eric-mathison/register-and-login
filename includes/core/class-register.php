<?php
namespace Register_and_Login\Core;
use function Register_and_Login\Utils\get_error_message;
use function Register_and_Login\Utils\get_template_html;
use function Register_and_Login\Utils\redirect_logged_in_user;

/**
 * Register
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class Register {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'login_form_register', array( $this, 'redirect_to_register' ) );
		add_action( 'login_form_register', array( $this, 'do_register_user' ) );
		add_shortcode( 'ral-register-form', array( $this, 'render_register_form' ) );
	}

	/**
     * Redirect the user to the login page instead of wp-login.php
     */
    public function redirect_to_register() {
		if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            if ( is_user_logged_in() ) {
                redirect_logged_in_user();
            } else {
				$register_page = get_permalink( get_page_by_title( 'Register' ) );
                wp_safe_redirect( $register_page );
            }
            exit;
        }
    }

	/**
     * A shortcode for rendering the new user registration form.
     */
    public function render_register_form( $attributes, $content = null ) {
        // Parse shortcode attributes
        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );

        // Retrieve possible errors from request parameters
        $attributes['errors'] = array();
        if ( isset( $_REQUEST['register-errors'] ) ) {
            $error_codes = explode( ',', $_REQUEST['register-errors'] );

            foreach ( $error_codes as $error_code ) {
                $attributes['errors'] []= get_error_message( $error_code );
            }
        }

        if ( is_user_logged_in() ) {
            return __( 'You are already signed in.', 'register-and-login' );
        } elseif ( !get_option( 'users_can_register' ) ) {
            return __( 'Registering is currently closed to new accounts.', 'register-and-login' );
        } else {
            return get_template_html( 'register_form', $attributes );
        }
    }

	/**
     * Handles the registration of a new user.
     *
     * Used through the action hook 'login_form_register' activated on wp-login.php
     * when accessed through the registration action.
     */
    public function do_register_user() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
            $redirect_url = get_permalink( get_page_by_title( 'Register' ) );
            $redirect_to = ! empty( $_REQUEST['redirect_to'] ) ? wp_validate_redirect( $_REQUEST['redirect_to'], '' ) : '';
			$options = get_option( 'register_and_login_general' );

            if ( !get_option( 'users_can_register' ) ) {
                //Registration closed, display error
                $redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );
            } else {
                $email = $_POST['email'];
                $first_name = sanitize_text_field( $_POST['first_name'] );
                $last_name = sanitize_text_field( $_POST['last_name'] );
                $password = $_POST['password'];
                $meter_value = $_POST['meter-value'];

                if ( !isset($meter_value) || $meter_value < 3 ) {
                    $errors = __( 'weak_password', 'register-and-login');
                    $redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
                    if ( !empty( $redirect_to ) ) {
                        $redirect_url = add_query_arg ( 'redirect_to', urlencode( $redirect_to ), $redirect_url );
                    }
                } else {
                    $result = $this->register_user( $email, $first_name, $last_name, $password );

                    if ( is_wp_error( $result ) ) {
                        // Parse errors into a string and append as parameter to redirect
                        $errors = join( ',', $result->get_error_codes() );
                        $redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
                        if ( !empty( $redirect_to ) ) {
                            $redirect_url = add_query_arg ( 'redirect_to', urlencode( $redirect_to ), $redirect_url );
                        }
                    } else {
                        // Success, send welcome email, autologin, and redirect to myaccount page or redirect.
                        $creds = array();
                        $creds['user_login'] = $email;
                        $creds['user_password'] = $password;
                        $creds['remember'] = false;
                        wp_signon($creds, false);

                        if ( $redirect_to != '' ) {
                            $redirect_url = $redirect_to;
                        } else {
                            if ( class_exists( 'woocommerce' ) && isset( $options['enable_woocommerce'] ) ) {
                                $account_page = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
                                $redirect_url = $account_page;
                            } else {
                                $redirect_url = isset( $options['enable_login_redirect'] ) ? get_page_link( $options['login_redirect_page'] ) : home_url('/wp-admin/profile.php');
                            }
                        }
                    }
                }
            }

            wp_safe_redirect( $redirect_url );
			exit;
        }
    }

	/**
     * Validates and then completes the new user signup process if successful
     */
    private function register_user( $email, $first_name, $last_name, $password ) {
        $errors = new \WP_Error();

        // Email address is used as both username and email. It is also the only parameter
        // we need to validate.
        if ( !is_email( $email ) ) {
            $errors->add( 'email', get_error_message( 'email' ) );
            return $errors;
        }

        if ( empty( $first_name ) ) {
            $errors->add( 'empty_firstname', get_error_message( 'empty_firstname' ) );
            return $errors;
        }

        if ( empty( $password ) ) {
            $errors->add( 'empty_password', get_error_message( 'empty_password' ) );
            return $errors;
        }

        if ( username_exists( $email ) || email_exists( $email ) ) {
            $errors->add( 'email_exists', get_error_message( 'email_exists') );
            return $errors;
        }

        $user_data = array(
            'user_login'    => $email,
            'user_email'    => $email,
            'user_pass'     => $password,
            'first_name'    => $first_name,
            'last_name'     => $last_name,
            'nickname'      => $first_name,
        );

        $user_id = wp_insert_user( $user_data );
        wp_new_user_notification( $user_id, null, 'user' );

        return $user_id;
    }
}
