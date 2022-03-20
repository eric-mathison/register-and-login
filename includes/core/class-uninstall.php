<?php
namespace Register_and_Login\Core;

/**
 * Uninstall
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class Uninstall {
    /**
     * Uninstall
     */
    public static function uninstall() {
			$options = get_option( 'register_and_login_general' );
			if ( isset( $options['delete_user_logs'] ) ) {
				delete_option( 'register_and_login_general' );
				delete_metadata( 'user', 0, 'last_login', '', true );
			}
    }
}
