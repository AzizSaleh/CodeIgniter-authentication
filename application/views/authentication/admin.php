<?php
defined('BASEPATH') OR exit('No direct script access allowed');
echo form_open('authentication/admin');
?>
<div class="form">
    <div class="header">
        Manage Users

        <div class="options">
            <input type="textbox" id="search" name="search"
              value="<?=set_value('search');?>" placeholder="Enter id, name or email"
              /><?php echo trim(form_dropdown('status', array(
                'all'       => 'All Accounts',
                'active'    => 'Active Accounts',
                'confirm'   => 'Pending Confirmation',
                'disabled'  => 'Disabled Accounts',
            ), set_value('status'), array('id' => 'status')));?><input
            type="submit" id="submit" value="&check;" />
        </div>
    </div>

    <table style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="5" align="center">There are no users in the system.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $user):?>
                    <tr>
                        <td><?=$user->id;?></td>
                        <td><?php
                        echo strlen($user->user_name) > 15 ? substr($user->user_name, 0, 15) . '...' : $user->user_name;
                        ?></td>
                        <td><?php
                        echo strlen($user->user_email) > 30 ? substr($user->user_email, 0, 30) . '...' : $user->user_email;
                        ?></td>
                        <td><?=ucfirst($user->user_status);?></td>
                        <td class="actions"> <a href="<?php
                            echo site_url('authentication/manage/' . $user->id);
                        ?>">EDIT</a> | <a href="<?php
                            echo site_url('authentication/delete/' . $user->id);
                        ?>">DELETE</a></td>
                    </tr>
                <?php endforeach;?>
            <?php endif;?>
        </tbody>
    </table>
    <div class="numbers" style="float:right">Total: <?=number_format($total_rows);?></div>
    <div class="pagination"><?=$links;?></div>
    <div class="links">
        <div class="link">
            <a href="<?=site_url('authentication/account');?>">My Account</a>
        </div>
        <div class="link">
            <a href="<?=site_url('authentication/logout');?>">Log off</a>
        </div>
    </div>
</form>