<div class='ral-wrapper'>
    <div id='register-form' class='widecolumn'>
        <?php if ( $attributes['show_title'] ) : ?>
            <h3><?php _e( 'Register', 'register-and-login'); ?></h3>
        <?php endif; ?>

        <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
            <?php foreach ( $attributes['errors'] as $error ) : ?>
            <p class='register-error'>
			<span><img src="<?php echo REGISTER_AND_LOGIN_URI . 'assets/svg/exclamation-circle.svg' ?>" /></span>
                    <?php echo $error; ?>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>

        <form id="signupform" action="<?php echo ! empty( $_REQUEST['redirect_to'] ) ? home_url( 'wp-login.php?action=register&redirect_to=' . urlencode( wp_validate_redirect( $_REQUEST['redirect_to'], '' ) ) ) : home_url( 'wp-login.php?action=register' ); ?>" method="post">
            <p class='form-row'>
                <label for="email"><?php _e( 'Email', 'register-and-login' ); ?> <strong>*</strong></label>
                <input type="text" name="email" id="email">
            </p>

            <p class="form-row">
                <label for="first_name"><?php _e( 'First Name', 'register-and-login' ); ?> <strong>*</strong></label>
                <input type="text" name="first_name" id="first-name">
            </p>

            <p class="form-row">
                <label for="last_name"><?php _e( 'Last Name', 'register-and-login' ); ?></label>
                <input type="text" name="last_name" id="last-name">
            </p>

            <p class="form-row">
                <label for="password"><?php _e( 'Password', 'register-and-login' ); ?> <strong>*</strong></label>
                <input type="password" name="password" id="password">
                <span toggle="#password" class="field-icon toggle-password"><img src="<?php echo REGISTER_AND_LOGIN_URI . 'assets/svg/eye.svg' ?>" /></span>
                <meter max="4" id="password-strength-meter"></meter>
                <span id="password-strength-text"></span>
            </p>

            <input type="hidden" name="meter-value" id="meter-value">

            <p class="signup-submit">
                <input type="submit" name="submit" class="register-button disabled"
                    value="<?php _e( 'Register', 'register-and-login' ); ?>" disabled/>
            </p>
        </form>
    </div>

    <div class='existing-account-container'>
        <h3><span>Already have an account?</span></h3>
        <div class='button-wrapper'>
            <a class='button' href="<?php
			$login_page = get_permalink( get_page_by_title( 'Login' ) );
			if ( !empty( $_REQUEST['redirect_to'] ) ) {
				$validate_redirect = wp_validate_redirect( $_REQUEST['redirect_to'], '' );
				$redirect = urlencode( $validate_redirect );
				$login_url = add_query_arg( 'redirect_to', $redirect, $login_page );
				echo $login_url;
			} else {
				echo $login_page;
			}
			 ?>">Login</a>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
