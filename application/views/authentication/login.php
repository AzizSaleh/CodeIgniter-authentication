<?php
defined('BASEPATH') OR exit('No direct script access allowed');
echo form_open('authentication/login');
?>
<div class="form">
    <div class="header">
        User Login
    </div>
    <div class="input">
        <label>Login</label>
        <?php echo form_error('login'); ?>
        <input type="text" name="login" id="login" value="<?php echo set_value('login'); ?>" size="50" />
    </div>
    <div class="input">
        <label>Password</label>
        <?php echo form_error('password'); ?>
        <input type="password" name="password" id="password" size="50" />
    </div>
    <div class="action">
        <input type="submit" value="Login" />
        <?php if ($remember_me): ?>
        <br />
        <input type="checkbox" <?=set_checkbox('remember_me', '1');?> name="remember_me" id="remember_me" value="1" /> Check to remember
        <?php endif;?>
    </div>
    <?php echo form_error('g-recaptcha-response'); ?>
    <?php echo set_captcha('<div class="captcha">', '</div>');?>
    <div class="links">
        <div class="link">
            <a href="<?=site_url('authentication/register');?>">Register</a>
        </div>
        <div class="link">
            <a href="<?=site_url('authentication/forgot');?>">Forgot Password</a>
        </div>
        <?php if ($show_resend):?>
        <div class="link">
            <a href="<?=site_url('authentication/resend');?>">Resend Confirmation Code</a>
        </div>
        <?php endif;?>
    </div>
</form>