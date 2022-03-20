<?php
namespace Register_and_Login;

/**
 * Init
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class Init {
    /**
     * Constructor
     */
    public function __construct() {
        $settings_page = new \Register_and_Login\Admin\Settings_Page();
		$login = new \Register_and_Login\Core\Login();
		$lost_password = new \Register_and_Login\Core\LostPassword();
		$authentication = new \Register_and_Login\Core\Authentication();
		$emails = new \Register_and_Login\Core\Emails();
		$reset_password = new \Register_and_Login\Core\ResetPassword();
		$logout = new \Register_and_Login\Core\Logout();
		$register = new \Register_and_Login\Core\Register();
		$woocommerce = new \Register_and_Login\Core\Woocommerce();
		$loginlogging = new \Register_and_Login\Core\LoginLogging();
		$userswitching = new \Register_and_Login\Core\UserSwitching();

		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts') );
		// add_filter( 'block_categories', array( $this, 'register_block_category' ), 10, 2 );
    }

	public function register_scripts() {
		if (is_page( array( 'Login', 'Register', 'Lost Password', 'Reset Password' ) ) ) {
			wp_enqueue_style( 'register-and-login-css', plugins_url( '/css/register-and-login.css', __FILE__ ), false, REGISTER_AND_LOGIN_VERSION, 'all');
			wp_enqueue_script( 'register-and-login-js', plugins_url( '/js/register-and-login.js', __FILE__ ), array('jquery'), REGISTER_AND_LOGIN_VERSION, true );
			wp_localize_script( 'register-and-login-js', 'ral_data', array( 'siteUrl' => site_url() ));
		}
	}

	public function register_block_category($categories, $post) {
		return array_merge(
			$categories,
			array(
				array(
					'slug' => 'register-and-login-blocks',
					'title' => __( 'Register and Login Blocks', 'register-and-login' ),
				),
			)
		);
	}
}
