# Codeigniter User Authentication System

This plug and play - just copy/paste entire directory into your codeigniter directory, over writing existing files to use.

# Uses - Included in package

1. CodeIgniter-master-slave: https://github.com/AzizSaleh/CodeIgniter-master-slave

2. CodeIgniter-email: https://github.com/AzizSaleh/CodeIgniter-email

# Barbone Functionalities

1. Registration

2. Login

3. Email confirmation (if enabled)

4. Forgot password

5. Account settings page

6. Account de-activate/deletion (if enabled)


# Installation

1. Update your database configs: `application/configs/database.php`:
2. Update your email configs: `application/configs/email.php`:
3. Install the required database via migration: `php index.php migrate/index`
4. You can now access the system by going to: `http://application/index.php/authentication/register`

# How to use

Make sure that your controller extends `MY_Controller` instead of the default `CI_Controller`. Then you can use the `get_user` method to check if the user is logged in or not. For example:

```
$user = $this->get_user();
if (empty($user)) {
    echo 'User is not logged in';
} else {
    echo 'Welcome: ' . $user->user_name;
}
```

# Config customization

Configs are located at `application/configs/authentication.php` the following things should be taken care of:

Change your hash. Keeping the default one isn't as secure. Just put in random numbers+symbols+lowercase+uppercase characters.

```
$config['hash'] = 'LLHUdsaohd_&#$(&!^+_/.asd9y797834jhasd8y@';
```

All other configs are pretty much explained within the configs, but the following need some attention:

```
 'force_confirm'    = Force users who register to confirm their email address before they log in.
 'force_confirm_pt' = Force users who change their email to confirm their email address before they log back in.
 'allow_delete'     = If enabled, users would have the ability to de-activate their accounts.
 'allow_undelete'   = If enabled, users can re-login to re-activates their accounts.

 'remove_deleted' = If true, this will remove de-activated accounts after allotted time.
 'delete_time'    = Number of days to delete de-activated accounts.

 'remove_stale'   = If true, this will remove accounts not confirmed after allotted time.
 'stale_time'     = Number of days to delete un-confirmed accounts.
```

Keep in mind that if you have remove_deleted/remove_stale enabled you need to enable the following cronjob to run daily to actually delete them from the system:

```
0 1 * * * /usr/local/bin/php /{SITE_ROOT}/index.php crons/cleanup
```