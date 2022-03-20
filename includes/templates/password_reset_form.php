<div class='ral-wrapper'>
    <?php if ( $attributes['show_title'] ) : ?>
        <h3><?php _e( 'Pick a New Password', 'register-and-login' ); ?></h3>
    <?php endif; ?>

    <form name="resetpassform" id="resetpassform" action="<?php echo site_url( 'wp-login.php?action=resetpass' ); ?>" method='post' autocomplete='off'>
        <input type="hidden" id="user_login" name="rp_login" value="<?php echo esc_attr( $attributes['login'] ); ?>" autocomplete='off' />
        <input type="hidden" name="rp_key" value="<?php echo esc_attr( $attributes['key'] ); ?>" />

        <?php if ( count( $attributes['errors'] ) > 0 ) : ?>
            <?php foreach ( $attributes['errors'] as $error ) : ?>
            <p class='reset-pass-error'>
                <span><img src="<?php echo REGISTER_AND_LOGIN_URI . 'assets/svg/exclamation-circle.svg' ?>" /></span>
                <?php echo $error; ?>
            </p>
            <?php endforeach; ?>
        <?php endif; ?>

        <p>
            <label for="password"><?php _e( 'New password', 'register-and-login' ); ?></label>
            <input type="password" name='password' id='password' class='input' size='20' value='' autocomplete='off' />
            <span toggle="#password" class="field-icon toggle-password"><img src="<?php echo REGISTER_AND_LOGIN_URI . 'assets/svg/eye.svg' ?>" /></span>
            <meter max="4" id="password-strength-meter"></meter>
        </p>

        <p>
            <label for="pass2"><?php _e( 'Repeat new password', 'register-and-login' ); ?></label>
            <input type="password" name='pass2' id='pass2' class='input' size='20' value='' autocomplete='off' />
            <span toggle="#pass2" class="field-icon toggle-password"><img src="<?php echo REGISTER_AND_LOGIN_URI . 'assets/svg/eye.svg' ?>" /></span>
        </p>

        <input type="hidden" name="meter-value" id="meter-value">


        <div class="validation"><span id="password-strength-text"></span><span id="password-match"></span></div>

        <p class='resetpass-submit'>
            <input type="submit" name='submit' id='reset-button' class='button disabled'
            value="<?php _e( 'Reset Password', 'register-and-login' ); ?>" disabled />
        </p>
    </form>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
