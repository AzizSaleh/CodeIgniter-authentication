<?php
defined('BASEPATH') OR exit('No direct script access allowed');
echo form_open('authentication/register');
?>
<div class="form">
    <div class="header">
        Register
    </div>
    <div class="input">
        <label>Login</label>
        <?php echo form_error('username'); ?>
        <input type="text" name="username" id="username" value="<?php echo set_value('username'); ?>" />
    </div>
    <div class="input">
        <label>Email</label>
        <?php echo form_error('email'); ?>
        <input type="text" name="email" id="email" value="<?php echo set_value('email'); ?>" />
    </div>
    <div class="input">
        <label>Password</label>
        <?php echo form_error('password'); ?>
        <input type="password" name="password" id="password" />
    </div>
    <div class="input">
        <label>Confirm Password</label>
        <?php echo form_error('password_confirm'); ?>
        <input type="password" name="password_confirm" id="password_confirm" />
    </div>
    <div class="action">
        <input type="submit" value="Register" />
        <?php if ($remember_me): ?>
        <br />
        <input type="checkbox" <?=set_checkbox('remember_me', '1');?> name="remember_me" id="remember_me" value="1" /> Check to remember
        <?php endif;?>
    </div>
    <div class="links">     
        <div class="link">
            <a href="<?=site_url('authentication/login');?>">Login</a>
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