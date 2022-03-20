<?php
/**
 * Autoloader
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

spl_autoload_register('register_and_login_autoloader');

function register_and_login_autoloader ( $class_name ) {
    // Set static variables
    $parent_namespace = 'Register_and_Login';
    $classes_dir = 'includes';

    if ( false !== strpos( $class_name, $parent_namespace ) ) {
        $classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . '/' . $classes_dir . '/';

        // Project namespace
        $project_namespace = $parent_namespace . '\\';
        $length = strlen( $project_namespace );

        // Remove top level namespace
        $class_file = substr( $class_name, $length );

        // Swap underscores for dashes and lowercase
        $class_file = str_replace( '_', '-', strtolower( $class_file) );

        // Prepend 'class-' to the filename
        $class_parts = explode( '\\', $class_file );
        $last_index = count( $class_parts ) - 1;
        $class_parts[ $last_index ] = 'class-' . $class_parts[ $last_index ];

        $class_file = implode( '/', $class_parts ) . '.php';
        $location = $classes_dir . $class_file;

        if ( !is_file( $location ) ) {
            return;
        }

        require_once $location;
    }
}
