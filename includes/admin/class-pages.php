<?php
namespace Register_and_Login\Admin;

/**
 * Pages
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class Pages {
     /**
     * Setup Pages
     */
    public static function setup_pages() {
        $pages = array(
            'login' => array(
                'title' => __('Login', 'register-and-login'),
                'content' => '[ral-login-form]'
            ),
            'register' => array(
                'title' => __('Register', 'register-and-login'),
                'content' => '[ral-register-form]'
            ),
            'lost-password' => array(
                'title' => __('Lost Password', 'register-and-login'),
                'content' => '[ral-lost-password-form]'
            ),
            'reset-password' => array(
                'title' => __('Reset Password', 'register-and-login'),
                'content' => '[ral-reset-password-form]'
            ),
        );

        foreach ( $pages as $slug => $page ) {
            // Check that the page doesn't exist
            $query = new \WP_Query( array( 'pagename' => $slug ) );
            if ( !$query->have_posts() ) {
                // Insert the page
                wp_insert_post(
                    array(
                        'post_title'     => $page['title'],
                        'post_content'   => $page['content'],
                        'post_status'    => 'publish',
                        'post_type'      => 'page',
                        'post_name'      => $slug,
                        'ping_status'    => 'closed',
                        'comment_status' => 'closed',
                    )
                );
            }
        }
    }
}
