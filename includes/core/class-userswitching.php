<?php
namespace Register_and_Login\Core;

/**
 * User Switching
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class UserSwitching {
	private $options;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->options = get_option( 'register_and_login_general' );
		add_filter( 'user_row_actions', array( $this, 'show_switch_user_link' ), 10, 2 );
		add_action( 'admin_init', array ( $this, 'switch_to_user_account' ) );
	}

	/**
	 * Add user switching option to user list
	 * Should only work if admin
	 */
	public function show_switch_user_link( $actions, $user ) {
		$current_user_id = get_current_user_id();
		if ($current_user_id != $user->ID && current_user_can( 'manage_options' ) && isset( $this->options['enable_user_switching'] ) ) {
			$actions['switch_user'] = "<a class='switch_user' href='" . wp_nonce_url(admin_url( "users.php?&action=switch_user&amp;user=$user->ID" ),'switch_user') . "'>" . esc_html__( 'Switch to this user', 'register-and-login' ) . "</a>";
		}
		return $actions;
	}

	/**
	 * Switch to the user account
	*/
	public function switch_to_user_account() {
		global $pagenow;
		if ( $pagenow == 'users.php' ) {
			if ( isset($_GET["action"]) && $_GET["action"] == 'switch_user' ) {
				if ( isset( $_REQUEST['_wpnonce'] ) && wp_verify_nonce( $_REQUEST['_wpnonce'], 'switch_user' ) ) {
					$userID = $_GET["user"];
					wp_clear_auth_cookie();
					wp_set_current_user ( $userID );
					wp_set_auth_cookie  ( $userID );
					$redirect_to = user_admin_url();
					wp_safe_redirect( $redirect_to );
					exit();
				} else {
					add_action( 'admin_notices', 'Register_and_Login\\Utils\\user_switching_error' );
				}
			}
		}
	}
}
