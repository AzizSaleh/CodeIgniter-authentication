<?php
defined('BASEPATH') OR exit('No direct script access allowed');
echo form_open('authentication/forgot');
?>
<div class="form">
    <div class="header">
        Forgot Password
    </div>
    <div class="input">
        <label>Username or Email</label>
        <?php echo form_error('login'); ?>
        <input type="text" name="login" id="login" value="<?php echo set_value('login'); ?>" />
    </div>
    <div class="action">
        <input type="submit" value="Email me reset link" />
    </div>
    <?php echo form_error('g-recaptcha-response'); ?>
    <?php echo set_captcha('<div class="captcha">', '</div>');?>
    <div class="links">     
        <div class="link">
            <a href="<?=site_url('authentication/register');?>">Register</a>
        </div>
        <div class="link">
            <a href="<?=site_url('authentication/login');?>">Login</a>
        </div>
        <?php if ($show_resend):?>
        <div class="link">
            <a href="<?=site_url('authentication/resend');?>">Resend Confirmation Code</a>
        </div>
        <?php endif;?>
    </div>
</form>