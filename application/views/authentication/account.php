<?php
defined('BASEPATH') OR exit('No direct script access allowed');
echo form_open('authentication/account');
?>
<div class="form">
    <div class="header">
        Account Settings
    </div>
    <div class="input">
        <label>Current Password</label>
        <?php echo form_error('current_password'); ?>
        <input type="password" name="current_password" id="current_password" />
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
        <?php if ($force_confirm_pt): ?>
        <br />
        <div class="notice">Changing your emaill will log you out and require you to confirm your account again before you can login.</div>
        <?php endif;?>
    </div>
    <div class="input">
        <label>Change Password</label>
        <?php echo form_error('password'); ?>
        <input type="password" name="password" id="password" />
    </div>
    <div class="input">
        <label>Confirm Password</label>
        <?php echo form_error('password_confirm'); ?>
        <input type="password" name="password_confirm" id="password_confirm" />
    </div>
    <div class="action">
        <input type="submit" value="Update" />
    </div>
    <div class="links">     
        <?php if ($allow_delete): ?>
        <div class="link">
            <a href="<?=site_url('authentication/deactivate');?>">De-activate account</a>
        </div>
        <?php endif;?>
        <div class="link">
            <a href="<?=site_url('authentication/logout');?>">Log off</a>
        </div>
    </div>
</form>