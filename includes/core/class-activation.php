<?php
namespace Register_and_Login\Core;

/**
 * Activation
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class Activation {
    /**
     * Activate
     */
    public static function activate() {
        $pages = \Register_and_Login\Admin\Pages::setup_pages();
    }
}
