<?php
namespace Register_and_Login\Utils;

/**
 * Get Error Message
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Finds and Returns a matching error message for a given error code.
 */
function get_error_message( $error_code ) {
	$lostpassword_url = '<a href="'. get_permalink( get_page_by_title( 'Lost Password' ) ) .'">Reset Password</a>';
	switch ( $error_code ) {
		case 'empty_username':
			return __( 'Please provide an email address.', 'register-and-login' );
		case 'empty_password':
			return __( 'Please provide a password.', 'register-and-login' );
		case 'invalid_username':
			return __( 'Incorrect username. Please try again.', 'register-and-login' );
		case 'incorrect_password':
			return __( 'Incorrect password. Please try again.', 'register-and-login' );
		case 'email':
			return __( 'The email address you entered is not valid.', 'register-and-login' );
		case 'empty_firstname':
			return __( 'You need to enter your first name.', 'register-and-login' );
		case 'empty_password':
			return __( 'You need to enter a password.', 'register-and-login' );
		case 'email_exists':
			return __( 'An account already exists with this email address. You can reset your password here: ', 'register-and-login' ) . $lostpassword_url;
		case 'closed':
			return __( 'Registering is currently closed to new accounts.', 'register-and-login' );
		case 'empty_username':
			return __( 'You need to enter your email address to continue.', 'register-and-login' );
		case 'invalid_email':
		case 'invalidcombo':
			return __( 'There are no registered accounts with this email address.', 'register-and-login' );
		case 'expiredkey':
		case 'invalidkey':
			return __( 'The password reset link you used is not valid anymore.', 'register-and-login' );
		case 'password_reset_mismatch':
			return __( "The passwords you entered don't match.", 'register-and-login' );
		case 'password_reset_empty':
			return __( "Your password can't be blank.", 'register-and-login' );
		case 'weak_password':
			return __( "Your password is too weak. Please make a stronger password.", 'register-and-login' );
		case 'too_many_attempts':
			return __( "You recently requested a password reset. Please check your email for instructions on how to reset your password.", 'register-and-login' );
		default:
			break;
	}

	return __( 'An unknown error occured. Please try again.', 'register-and-login' );
}
