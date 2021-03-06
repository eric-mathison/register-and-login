<div class='ral-wrapper'>
    <?php if ( $attributes['show_title'] ) : ?>
        <h3><?php _e( 'Forgot Your Password?', 'register-and-login'); ?></h3>
    <?php endif; ?>

    <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
        <?php foreach ( $attributes['errors'] as $error ) : ?>
        <p class='lost-pass-error'>
			<span><img src="<?php echo REGISTER_AND_LOGIN_URI . 'assets/svg/exclamation-circle.svg' ?>" /></span>
            <?php echo $error; ?>
        </p>
        <?php endforeach; ?>
    <?php endif; ?>

    <p>
        <?php
            _e(
                "Enter your email address and we'll send you a link you can use to reset your password.",
                "register-and-login"
            );
        ?>
    </p>

    <form id='lostpasswordform' action="<?php echo home_url( 'wp-login.php?action=lostpassword' ); ?>" method="post">
        <p class='form-row'>
            <label for="user_login"><?php _e( 'Email', 'register-and-login' ); ?></label>
            <input type="text" name="user_login" id="user_login">
        </p>

        <p class='lostpassword-submit'>
            <input type="submit" name="submit" class="lostpassword-button"
            value="<?php _e( 'Reset Password', 'register-and-login'); ?>"/>
        </p>
    </form>
</div>
