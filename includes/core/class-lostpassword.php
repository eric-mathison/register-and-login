<?php
namespace Register_and_Login\Core;
use function Register_and_Login\Utils\get_error_message;
use function Register_and_Login\Utils\get_template_html;
use function Register_and_Login\Utils\redirect_logged_in_user;

/**
 * LostPassword
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class LostPassword {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'login_form_lostpassword', array( $this, 'redirect_to_lostpassword' ) );
		add_action( 'login_form_lostpassword', array( $this, 'do_password_lost' ) );
		add_filter( 'lostpassword_user_data', array( $this, 'check_last_reset' ), 10, 2 );
		add_shortcode( 'ral-lost-password-form', array( $this, 'render_password_lost_form' ) );
	}

	/**
     * Redirects the user to the lost password page instead of
     * wp-login.php?action=lostpassword.
     */
    public function redirect_to_lostpassword() {
        if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
            if ( is_user_logged_in() ) {
                redirect_logged_in_user();
                exit;
            }

			$lost_password_page = get_permalink( get_page_by_title( 'Lost Password' ) );
            wp_safe_redirect( $lost_password_page );
            exit;
        }
    }

	/**
     * Initiates password reset
     */
    public function do_password_lost() {
        if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
            $errors = retrieve_password();
            if ( is_wp_error( $errors ) ) {
                // Errors found
                $redirect_url = get_permalink( get_page_by_title( 'Lost Password' ) );
                $redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
            } else {
                // Email Sent
                $redirect_url = get_permalink( get_page_by_title( 'Login' ) );
                $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
            }
            wp_safe_redirect( $redirect_url );
            exit;
        }
    }

	/**
	 * Checks the last time a lost password was requested
	 */
	public function check_last_reset($user_data, $errors) {
		if ( !empty( $user_data) ) {
			$last_reset = get_user_meta( $user_data->ID, 'last_reset', true );
			if ( !empty( $last_reset ) ) {
				$past_date = date_create( $last_reset );
				$cur_time = date_create( gmdate( 'Y-m-d H:i:s' ) );
				if ( date_diff( $past_date, $cur_time )->h < 1 ) {
					// Send message too soon to reset pw again
					$errors->add( 'too_many_attempts', __( '<strong>Error</strong>: You recently requested a password reset. Please check your email for instructions on how to reset your password.', 'register-and-login' ) );
					return $errors;
				} else {
					update_user_meta($user_data->ID, 'last_reset', gmdate('Y-m-d H:i:s'));
					return $user_data;
				}
			} else {
				update_user_meta($user_data->ID, 'last_reset', gmdate('Y-m-d H:i:s'));
				return $user_data;
			}
		}
	}

	/**
     * A shortcode for rendering the form used to initiate the password reset.
     */
    public function render_password_lost_form( $attributes, $content = null ) {
        // Parse shortcode attributes
        $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts( $default_attributes, $attributes );

        // Retrieve possible errors from request parameters
        $attributes['errors'] = array();
        if ( isset( $_REQUEST['errors'] ) ) {
            $error_codes = explode( ',', $_REQUEST['errors'] );

            foreach ( $error_codes as $error_code ) {
                $attributes['errors'] []= get_error_message( $error_code );
            }
        }

        if ( is_user_logged_in() ) {
            return __( 'You are already signed in.', 'register-and-login' );
        } else {
            return get_template_html( 'password_lost_form', $attributes );
        }
    }
}
