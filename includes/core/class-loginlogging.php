<?php
namespace Register_and_Login\Core;

/**
 * Login Logging
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class LoginLogging {
	private $options;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->options = get_option( 'register_and_login_general' );
		add_action( 'wp_login', array( $this, 'capture_login_time' ), 10, 2 );
		add_filter('manage_users_columns', array( $this, 'add_custom_user_columns' ) );
		add_action('manage_users_custom_column', array( $this, 'add_user_custom_column_values' ), 10, 3 );
		add_filter('manage_users_sortable_columns', array( $this, 'add_users_sortable_columns' ) );
		add_action('pre_get_users', array( $this, 'user_column_orderby' ) );
	}

	/**
	 * Capture current time user logs on
	 */
	public function capture_login_time($user_login, $user) {
		if ( isset( $this->options['enable_user_logs'] ) ) {
			update_user_meta($user->ID, 'last_login', gmdate('Y-m-d H:i:s'));
		}
	}

	/**
	 * Add user custom columns
	 */
	public function add_custom_user_columns($columns) {
		if ( isset( $this->options['enable_woocommerce'] ) ) {
			$columns['orders'] = __( 'Orders', 'register-and-login' );
		}
		$columns['register_date'] = __( 'Register Date', 'register-and-login' );
		$columns['last_login'] = __( 'Last Login', 'register-and-login' );
		return $columns;
	}

	/**
	 * Add user custom column values
	 */
	public function add_user_custom_column_values($value, $column_name, $user_id) {
		if ('orders' == $column_name) {
			return wc_get_customer_order_count($user_id);
			// return get_user_meta($user_id, '_order_count', true) > 0 ? get_user_meta($user_id, '_order_count', true) : '0';
		}
		if ('last_login' == $column_name) {
			return $this->get_user_last_login($user_id, false);
		}
		if ('register_date' == $column_name) {
			return $this->get_user_register_date($user_id, false);
		}

		return $value;
	}

	/**
	 * Enable sorting for custom user columns
	 */
	public function add_users_sortable_columns($columns) {
		if ( isset( $this->options['enable_woocommerce'] ) ) {
			$columns['orders'] = 'orders';
		}
		$columns['register_date'] = 'registerdate';
		$columns['last_login'] = 'lastlogin';
		return $columns;
	}

	/**
	 * Queries for column sorting
	 */
	public function user_column_orderby($query) {
		if (!is_admin()) {
			return;
		}
		if ('orders' == $query->get('orderby')) {
			$query->set('meta_key', '_order_count');
			$query->set('orderby', 'meta_value_num');
			$query->set( 'meta_query', array(
				'relation' => 'OR',
				array(
					'key' => '_order_count',
					'compare' => 'NOT EXISTS'
				),
				array(
					'key' => '_order_count',
					'compare' => 'EXISTS'
				)
			) );
		}
		if ('registerdate' == $query->get('orderby')) {
			$query->set('orderby', 'registered');
		}
		if ('lastlogin' == $query->get('orderby')) {
			$query->set('meta_key', 'last_login');
			$query->set('orderby', 'meta_value');
			$query->set( 'meta_query', array(
				'relation' => 'OR',
				array(
					'key' => 'last_login',
					'compare' => 'NOT EXISTS'
				),
				array(
					'key' => 'last_login',
					'compare' => 'EXISTS'
				)
			) );
		}
	}

	/**
	 * Gets user last login time date
	 */
	private function get_user_last_login($user_id, $echo = true) {
		$last_login = get_user_meta($user_id, 'last_login', true);
		if (!empty($last_login)) {
			return get_date_from_gmt( $last_login );
		} else {
			return '--';
		}
	}

	/**
	 * Gets user registration date
	 */
	private function get_user_register_date($user_id, $echo =true) {
		$register_date = get_userdata($user_id)->user_registered;
		if (!empty($register_date)) {
			return get_date_from_gmt( $register_date );
		} else {
			return '--';
		}
	}
}
