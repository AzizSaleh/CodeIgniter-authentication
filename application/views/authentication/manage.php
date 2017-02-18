<?php
defined('BASEPATH') OR exit('No direct script access allowed');
echo form_open('authentication/manage/' . $user->id);
?>
<div class="form">
    <div class="header">
        Account Settings For <?php
        echo strlen($user->user_name) > 15 ? substr($user->user_name, 0, 15) . '...' : $user->user_name;
        ?>
    </div>
    <div class="input">
        <label>Username</label>
        <?php echo form_error('username'); ?>
        <input type="text" name="username" id="username" value="<?=$user->user_name;?>" />
    </div>
    <div class="input">
        <label>User email</label>
        <?php echo form_error('email'); ?>
        <input type="text" name="email" id="email" value="<?=$user->user_email;?>" />
    </div>
    <div class="input">
        <label>User Status</label>
        <?php echo form_error('status'); ?>
        <?php echo trim(form_dropdown('status', array(
                'active'    => 'Active',
                'confirm'   => 'Pending Confirmation',
                'disabled'  => 'Disabled',
            ), $user->user_status, array('id' => 'status')));?>
    </div>
    <div class="input">
        <label>Change Password</label>
        <?php echo form_error('password'); ?>
        <input type="password" name="password" id="password" />
        <br />
        <div class="notice">Leave this and next input empty to keep the same.</div>
    </div>
    <div class="input">
        <label>Confirm New Password</label>
        <?php echo form_error('password_confirm'); ?>
        <input type="password" name="password_confirm" id="password_confirm" />
    </div>
    <div class="action">
        <input type="submit" value="Update" />
    </div>
    <div class="links">
        <div class="link">
            <a href="<?=site_url('authentication/admin');?>">Manage Users</a>
        </div>
        <div class="link">
            <a href="<?=site_url('authentication/account');?>">My Account</a>
        </div>
        <div class="link">
            <a href="<?=site_url('authentication/logout');?>">Log off</a>
        </div>
    </div>
</form>