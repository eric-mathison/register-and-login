<?php
/**
 * Plugin Name: Register and Login
 * Description: Enable visitors to register and login to your site easily and securely.
 * Version: 0.0.0-development
 * Author: Eric Mathison
 * Text Domain: register-and-login
 * Domain Path: /languages
 * License: GPL-2.0-or-later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * GitHub Plugin URI: eric-mathison/register-and-login
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

// Definitons.
define( 'REGISTER_AND_LOGIN_URI', plugin_dir_url( __FILE__ ) );
define( 'REGISTER_AND_LOGIN_VERSION' , '0.0.0-development' );

require_once( plugin_dir_path(__FILE__) . 'autoload.php' );
require_once( plugin_dir_path(__FILE__) . 'includes/utils/get_error_message.php' );
require_once( plugin_dir_path(__FILE__) . 'includes/utils/get_template_html.php' );
require_once( plugin_dir_path(__FILE__) . 'includes/utils/display_notices.php' );

/**
 * Load plugin files.
 */
function register_and_login_load_text_domain() {
    load_plugin_textdomain( 'register-and-login', false, dirname( plugin_basename( __FILE__ ) ) .'/languages/' );
}
add_action( 'plugins_loaded', 'register_and_login_load_text_domain' );

/**
 * Register Blocks.
 */
function create_block_register_and_login_block_init() {
    register_block_type( plugin_dir_path( __FILE__ ) . 'blocks/login-form/' );
}
// add_action( 'init', 'create_block_register_and_login_block_init' );

/**
 * Disable password change email for admin
 */
$options = get_option( 'register_and_login_general' );

if ( isset( $options['disable_password_change_email'] ) ) {
	if ( !function_exists( 'wp_password_change_notification' ) ) {
		function wp_password_change_notification () {}
	}
}

// Plugin activation
register_activation_hook( __FILE__, array( 'Register_and_Login\Core\Activation', 'activate' ) );

// Plugin uninstall
register_uninstall_hook( __FILE__, array( 'Register_and_Login\Core\Uninstall', 'uninstall' ) );

// Start up plugin
function register_and_login() {
    $plugin = new Register_and_Login\Init();
}

register_and_login();
