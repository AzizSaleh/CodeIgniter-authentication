<?php
defined('BASEPATH') OR exit('No direct script access allowed');
echo form_open('authentication/deactivate');
?>
<div class="form danger">
    <div class="header">
        <?php echo ($configs['allow_undelete']) ? 'Deactivate' : 'Delete';?> Account
    </div>
    <div class="action">
        <?php
        if (!$configs['allow_delete']): ?>
            We do not allow account deletion. Please contact us for more information.
        <?php
        else:
            if ($configs['allow_undelete']): ?>
                Once your account is deactivated, you have <?=$configs['delete_time'];?> days to change your mind and re-login to re-activate your account. Once that time passes you account will be deleted.
                <br />
                <input type="submit" value="Deactivate Account" />
                <br />
                <input type="checkbox" <?=set_checkbox('delete', '1');?> name="delete" id="delete" value="1" /> Deactivate
            <?php else: ?>
                This action in Not Reversible. Once an account is Deleted you will not get it back. All data will be erased.
                <br />
                <input type="submit" value="DELETE Account" />
                <br />
                <input type="checkbox" <?=set_checkbox('delete', '1');?> name="delete" id="delete" value="1" /> DELETE
            <?php endif;
        endif; ?>
    </div>

    <div class="links">     
        <div class="link">
            <a href="<?=site_url('authentication/account');?>">Account</a>
        </div>
        <div class="link">
            <a href="<?=site_url('authentication/logout');?>">Logout</a>
        </div>
    </div>
</form>