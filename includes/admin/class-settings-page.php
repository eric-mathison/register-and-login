<?php
namespace Register_and_Login\Admin;

/**
 * Settings Page
 *
 * @package Register_and_Login
 * @since 1.0.0
 * @copyright Copyright (c) 2021, Eric Mathison
 * @license GPL-2.0+
 */

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class Settings_Page {
    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    /**
     * Store settings options
     */
    private $options;

    /**
     * Register settings and options
     */
    public function register_settings() {
        // Register settings
        register_setting( 'register_and_login_general_group', 'register_and_login_general' );

        // Get options
        $this->options = get_option( 'register_and_login_general' );

        // Register general settings section
        add_settings_section(
            'general-settings',
            __( 'General Settings', 'register-and-login' ),
            false,
            'register-and-login'
        );

        // Add general settings fields
		add_settings_field(
			'enable_login_redirect',
			__( 'Redirect on Login', 'register-and-login' ),
			array( $this, 'render_redirect_on_login' ),
			'register-and-login',
			'general-settings'
		);

		add_settings_field(
			'login_redirect_page',
			__( 'Login Redirect Page', 'register-and-login' ),
			array( $this, 'render_login_redirect' ),
			'register-and-login',
			'general-settings'
		);

		add_settings_field(
			'disable_password_change_email',
			__( 'Disable Password Change Email', 'register-and-login' ),
			array( $this, 'render_disable_password_change_email' ),
			'register-and-login',
			'general-settings'
		);

        add_settings_field(
            'enable_woocommerce',
            __( 'WooCommerce Support', 'register-and-login' ),
            array( $this, 'render_enable_woocommerce_support' ),
            'register-and-login',
            'general-settings'
        );

		add_settings_field(
            'enable_force_woocommerce_checkout',
            __( '', 'register-and-login' ),
            array( $this, 'render_enable_force_woocommerce_checkout' ),
            'register-and-login',
            'general-settings'
        );

		add_settings_field(
			'enable_user_logs',
			__( 'User Login Logging', 'register-and-login' ),
			array( $this, 'render_enable_user_logs' ),
			'register-and-login',
			'general-settings'
        );

		add_settings_field(
			'enable_user_switching',
			__( 'User Switching', 'register-and-login' ),
			array( $this, 'render_enable_user_switching' ),
			'register-and-login',
			'general-settings'
		);

        add_settings_field(
            'delete_user_logs',
            __( 'Delete User Login Logs on Uninstall', 'register-and-login' ),
            array( $this, 'render_delete_user_logs' ),
            'register-and-login',
            'general-settings'
        );
    }

	/**
	 * Render redirect on login field
	 */
	public function render_redirect_on_login() {
		$value = isset( $this->options['enable_login_redirect'] ) ? $this->options['enable_login_redirect'] : false;
		echo '<label for="enable_login_redirect"><input type="checkbox" id="enable_login_redirect" name="register_and_login_general[enable_login_redirect]" value="1" ' . checked( 1, $value, false ) . ' />Redirect users to a specific URL after login</label>';
	}

	/**
	 * Render login redirect url field
	 */
	public function render_login_redirect() {
		$value = isset( $this->options['login_redirect_page'] ) ? $this->options['login_redirect_page'] : 0;
		wp_dropdown_pages(
			array(
				'name' => 'register_and_login_general[login_redirect_page]',
				'echo' => 1,
				'show_option_none' => __( '&mdash; Select &mdash;', 'register-and-login' ),
             	'option_none_value' => '0',
				'selected' => $value,
			)
		);
	}

	/**
	 * Render disable password changed emails field
	 */
	public function render_disable_password_change_email() {
		$value = isset( $this->options['disable_password_change_email'] ) ? $this->options['disable_password_change_email'] : false;
		echo '<label for="disable_password_change_email"><input type="checkbox" id="disable_password_change_email" name="register_and_login_general[disable_password_change_email]" value="1" ' . checked( 1, $value, false ) . ' />Disable user password change notifications</label>';
	}

    /**
     * Render enable woocommerce field
     */
    public function render_enable_woocommerce_support() {
        $value = isset( $this->options['enable_woocommerce'] ) ? $this->options['enable_woocommerce'] : false;
        echo '<label for="enable_woocommerce"><input type="checkbox" id="enable_woocommerce" name="register_and_login_general[enable_woocommerce]" value="1" ' . checked( 1, $value, false ) . ' />Ensure WooCommerce uses these login and register forms</label>';
    }

	/**
     * Render enable force woocommerce checkout field
     */
    public function render_enable_force_woocommerce_checkout() {
        $value = isset( $this->options['enable_force_woocommerce_checkout'] ) ? $this->options['enable_force_woocommerce_checkout'] : false;
        echo '<label for="enable_force_woocommerce_checkout"><input type="checkbox" id="enable_force_woocommerce_checkout" name="register_and_login_general[enable_force_woocommerce_checkout]" value="1" ' . checked( 1, $value, false ) . ' />Force a user to login or register before checkout</label>';
    }

	/**
	 * Render enable user logs field
	 */
	public function render_enable_user_logs() {
        $value = isset( $this->options['enable_user_logs'] ) ? $this->options['enable_user_logs'] : false;
        echo '<label for="enable_user_logs"><input type="checkbox" id="enable_user_logs" name="register_and_login_general[enable_user_logs]" value="1" ' . checked( 1, $value, false ) . ' />See the last time users logged in</label>';
	}

    /**
     * Render enable user switching field
     */
    public function render_enable_user_switching() {
        $value = isset( $this->options['enable_user_switching'] ) ? $this->options['enable_user_switching'] : false;
        echo '<label for="enable_user_switching"><input type="checkbox" id="enable_user_switching" name="register_and_login_general[enable_user_switching]" value="1" ' . checked(1, $value, false ) . ' />Allow an admin to switch to a user account</label>';
    }


    /**
     * Render delete user logs field
     */
    public function render_delete_user_logs() {
        $value = isset( $this->options['delete_user_logs'] ) ? $this->options['delete_user_logs'] : false;
        echo '<label for="delete_user_logs"><input type="checkbox" id="delete_user_logs" name="register_and_login_general[delete_user_logs]" value="1" ' . checked( 1, $value, false ) . ' />Delete all logs and information stored after deactivating this plugin</label>';
    }

    /**
     * Add Settings Page
     */
    public function add_settings_page() {
        add_submenu_page(
            'options-general.php',
            __( 'Register and Login', 'register-and-login' ),
            __( 'Register and Login', 'register-and-login' ),
            'manage_options',
            'register-and-login',
            array($this, 'display_settings_page')
        );
    }

    /**
     * Display Settings Page
     */
    public function display_settings_page() {
        // check user capabilities
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

            <form action="options.php" method="post">
                <?php
                    settings_fields( 'register_and_login_general_group' );
                    do_settings_sections( 'register-and-login' );
                	submit_button( 'Save Settings' );
                ?>
            </form>
        </div>
        <?php
    }
}
