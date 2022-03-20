<div class='ral-wrapper'>
    <div class='login-form-container'>
        <?php if ( $attributes['show_title'] ) : ?>
            <h2><?php _e( 'Sign In', 'register-and-login' ); ?></h2>
        <?php endif; ?>

        <!-- Show errors -->
        <?php if ( count( $attributes['errors'] ) > 0 ) :
            foreach ( $attributes['errors'] as $error ) : ?>
                <p class='login-error'>
					<span><img src="<?php echo REGISTER_AND_LOGIN_URI . 'assets/svg/exclamation-circle.svg' ?>" /></span>
                    <?php echo $error; ?>
                </p>
        <?php endforeach;
        endif; ?>

        <?php if ( $attributes['lost_password_sent'] ) : ?>
            <p class="login-info">
                <?php _e( 'Check your email for a link to reset your password.', 'register-and-login' ); ?>
            </p>
        <?php endif; ?>

        <?php if ( $attributes['password_updated'] ) : ?>
            <p class='login-info'>
                <?php _e( 'Your password has been changed. Please sign in using your new password.', 'register-and-login' ); ?>
            </p>
        <?php endif; ?>

        <!-- Show logged out message if user just logged out -->
        <?php if ( $attributes['logged_out'] ) : ?>
            <p class="login-info">
                <?php _e( 'You have signed out.', 'register-and-login' ); ?>
            </p>
        <?php endif; ?>

        <?php
            wp_login_form(
                array(
                    'label_username' => __( 'Email', 'register-and-login' ),
                    'label_log_in' => __( 'Sign In', 'register-and-login' ),
                    'redirect' => $attributes['redirect'],
                )
            );
        ?>

        <a class='forgot-password' href='<?php echo wp_lostpassword_url(); ?>'>
            <?php _e( 'Forgot your password?', 'register-and-login' ); ?>
        </a>
    </div>

	<?php
		// If the user can register, show a link for them to do so.
		if ( get_option( 'users_can_register' ) ) {
		?>
			<div class='new-registration-container'>
				<h3><span>New to <?php echo bloginfo( 'name' ); ?>?</span></h3>
				<div class='button-wrapper'>
					<a class='button' href="<?php
					$register_page = get_permalink( get_page_by_title( 'Register' ) );
					if ( !empty( $_REQUEST['redirect_to'] ) ) {
						$validate_redirect = wp_validate_redirect( $_REQUEST['redirect_to'], '' );
						$redirect = urlencode( $validate_redirect );
						$register_url = add_query_arg( 'redirect_to', $redirect, $register_page );
						echo $register_url;
					} else {
						echo $register_page;
					}
					?>">Create an account</a>
				</div>
			</div>
		<?php
		}
		?>
</div>
