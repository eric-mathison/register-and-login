<?php
namespace Register_and_Login\Core;

/**
 * Woocommerce
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class Woocommerce {
	private $options;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->options = get_option( 'register_and_login_general' );
		add_filter( 'lostpassword_url', array( $this, 'return_passwordlost_url' ), 101, 2 );
		add_action( 'wp', array( $this, 'woocommerce_redirect_to_login' ) );
		add_action( 'wp', array( $this, 'woocommerce_lost_password_redirect' ) );
		add_action( 'woocommerce_before_checkout_form', array( $this, 'render_before_checkout_form' ), 2 );
	}

	/**
     * Returns lost password page incase another plugin is overriding like Woocommerce.
     */
    public function return_passwordlost_url( $lostpassword_url, $redirect ) {
		if ( class_exists( 'woocommerce' ) && isset( $this->options['enable_woocommerce'] ) ) {
        	return get_permalink( get_page_by_title( 'Lost Password' ) );
		}
    }

	/**
     * Redirects My Account and Checkout Pages to custom login page if not logged in.
     */
    public function woocommerce_redirect_to_login() {
        if ( class_exists( 'woocommerce' ) && isset( $this->options['enable_woocommerce'] ) ) {
            $account_id = get_option( 'woocommerce_myaccount_page_id' );
            $checkout_id = get_option( 'woocommerce_checkout_page_id' );
            $account_page = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
            global $woocommerce;
            $checkout_page = wc_get_checkout_url();
            if ( !is_user_logged_in() && is_page( $account_id ) ) {
                $redirect_url = get_permalink( get_page_by_title( 'Login' ) );
                $redirect_url = add_query_arg( 'redirect_to', urlencode( $account_page ), $redirect_url );
                wp_safe_redirect( $redirect_url );
				exit;
            }
			else {
                if ( !is_user_logged_in() && is_page( $checkout_id ) && isset( $this->options['enable_force_woocommerce_checkout']) ) {
                    $redirect_url = get_permalink( get_page_by_title( 'Login' ) );
                    $redirect_url = add_query_arg( 'redirect_to', urlencode( $checkout_page ), $redirect_url );
                    wp_safe_redirect( $redirect_url );
					exit;
                }
            }
        }
    }

	/**
	 * Redirect Woocommerce password reset url from new account email
	 */
	public function woocommerce_lost_password_redirect() {
		if ( is_page( 'Lost Password' ) && isset( $this->options['enable_woocommerce'] ) ) {
			if ( isset( $_REQUEST['key'] ) ) {
				$redirect_url = home_url( 'wp-login.php?action=rp');
                $redirect_url = add_query_arg( 'login', esc_attr( $_REQUEST['login'] ), $redirect_url );
                $redirect_url = add_query_arg( 'key', esc_attr( $_REQUEST['key'] ), $redirect_url );

                wp_safe_redirect( $redirect_url );
                exit;
			}
		}
	}

	/**
	 * Change the checkout login form to use our own.
	 */
	public function render_before_checkout_form() {
		if( ! is_user_logged_in() ) {
			remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form' );
			remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form' );
			add_action( 'woocommerce_before_checkout_form', array( $this, 'custom_checkout_login_form' ) );
			add_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 20 );
		}
	}

	public function custom_checkout_login_form() {
		$checkout_page = wc_get_checkout_url();
		$redirect_url = get_permalink( get_page_by_title( 'Login' ) );
		$redirect_url = add_query_arg( 'redirect_to', urlencode( $checkout_page ), $redirect_url );
		echo '<div class="woocommerce-info">Already have an account? <a href="'.$redirect_url.'">Click here to login</a></div>';
	}
}
