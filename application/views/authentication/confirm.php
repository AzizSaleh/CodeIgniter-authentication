<?php
defined('BASEPATH') OR exit('No direct script access allowed');
echo form_open('authentication/confirm/' . urlencode($email));
?>
<div class="form">
    <div class="header">
        Confirm Account
    </div>
    <div class="input">
        <label>Confirmation Code</label>
        <?php echo form_error('confirm'); ?>
        <input type="text" name="confirm" id="confirm" value="<?php echo set_value('confirm'); ?>" />
    </div>
    <div class="action">
        <input type="submit" value="Confirm" />
        <?php if ($remember_me): ?>
        <br />
        <input type="checkbox" <?=set_checkbox('remember_me', '1');?> name="remember_me" id="remember_me" value="1" /> Check to remember
        <?php endif;?>
    </div>
</form>