<?php
namespace Register_and_Login\Core;

/**
 * Emails
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class Emails {
	public function __construct() {
        add_filter( 'wp_mail_content_type', array( $this, 'set_email_content_type' ) );
		add_filter( 'retrieve_password_message', array( $this, 'replace_retrieve_password_email' ), 10, 4 );
		add_filter( 'wp_new_user_notification_email', array( $this, 'replace_wp_new_user_notification_email' ), 10, 3 );
	}

    /**
     * Change default plain text emails to support html
     */
    public function set_email_content_type( $email_content_type ) {
        return 'text/html';
    }

	/**
     * Returns the message body for the password reset email.
     * Called through the retrieve_password_message filter.
    */
    public function replace_retrieve_password_email( $message, $key, $user_login, $user_data ) {
        // Create new message
        $msg = __( 'Hello!', 'register-and-login' ) . '<br /><br />';
        $msg .= sprintf( __( 'You asked us to reset your password for your account using the email address %s.', 'register-and-login' ), $user_login ) . '<br /><br />';
        $msg .= __( "If this was a mistake, or if you didn't ask for a password reset, just ignore this email and nothing will happen.", 'register-and-login' ) . '<br /><br />';
        $msg .= __( 'To reset your password, visit the following link:', 'register-and-login' ) . '<br />';
        $msg .= '<a href=' . site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . '>Reset Password</a>' . '<br /><br />';
        $msg .= __( 'Thanks!', 'register-and-login' ) . '<br />';

        return $msg;
    }

	/**
     * Customize the new user welcome email
     */
    public function replace_wp_new_user_notification_email( $wp_new_user_notification_email, $user, $blogname ) {
        $user_login = stripslashes( $user->user_login );
        $user_email = stripslashes( $user->user_email );
        $login_url  = get_permalink( get_page_by_title( 'Login' ) );
        $rp_url = get_permalink( get_page_by_title( 'Lost Password' ) );

        $message = sprintf( __( 'Welcome to %s!', 'register-and-login' ), get_option('blogname') ) . '<br /><br />';
        $message .= sprintf( __( 'To access to your new account, visit this link: <a href="%s">Login</a>', 'register-and-login' ), $login_url ) . '<br />';
        $message .= sprintf( __( 'Your Username is: %s', 'register-and-login' ), $user_login ) . '<br />';
        $message .= sprintf(__( 'If you have forgotten your password, you can reset it here: <a href="%s">Reset Password</a>', 'register-and-login' ), $rp_url ) . '<br /><br />';
        $message .= __( 'Thank you for joining!', 'register-and-login' );

        $wp_new_user_notification_email['subject'] = sprintf( 'Welcome to %s!', $blogname );
        $wp_new_user_notification_email['headers'] = array('Content-Type: text/html; charset=UTF-8');
        $wp_new_user_notification_email['message'] = $message;

        return $wp_new_user_notification_email;
    }
}
