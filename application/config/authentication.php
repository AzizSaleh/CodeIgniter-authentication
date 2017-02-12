<?php
/**
 * This config handles authentication functionality
 *
 * @author    Aziz S. Hussain <azizsaleh@gmail.com>
 * @copyright GPL license 
 * @license   http://www.gnu.org/copyleft/gpl.html 
 * @link      http://www.AzizSaleh.com
 * @using     Codeigniter 3.1.2
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/*
|-----------------------------
| General
|-----------------------------
|
| 'hash'  = Site hash. Please change to make it custom to your site.
|           Leaving it the same will make you vurenable to hacking.
*/

$config['hash'] = 'LLHUdsaohd_&#$(&!^+_/.asd9y797834jhasd8y@';

/*
|-----------------------------
| Handler Names
|-----------------------------
|
| 'name' = Session Name
*/

$config['name'] = '__ID';

/*
|-----------------------------
| Remember Me
|-----------------------------
|
| 'remember_me'     = Enable "Remember Me" checkbox, if false will not implement.
| 'cookie_name'     = Cookie name to use
| 'cookie_domain'   = Cookie domain
| 'remember_expire' = How long to remember. Value in seconds, defaults to 4 weeks.
*/

$config['remember_me']      = true;
$config['cookie_name']      = '__ID';
$config['cookie_domain']    = '';
$config['remember_expire']  = 604800 * 4;

/*
|-----------------------------
| User Options
|-----------------------------
|
| Important Notice:
|-------------------
| For accounts to be automatically delete, you need to set a cronjob @ crontab -e:
| 0 1 * * * /usr/local/bin/php /{SITE_ROOT}/index.php crons/cleanup
|
|
| 'force_confirm'    = Force users who register to confirm their email address before they log in.
| 'force_confirm_pt' = Force users who change their email to confirm their email address before they log back in.
| 'allow_delete'     = If enabled, users would have the ability to de-activate their accounts.
| 'allow_undelete'   = If enabled, users can re-login to re-activates their accounts.
|
| 'remove_deleted' = If true, this will remove de-activated accounts after allotted time.
| 'delete_time'    = Number of days to delete de-activated accounts.
|
| 'remove_stale'   = If true, this will remove accounts not confirmed after allotted time.
| 'stale_time'     = Number of days to delete un-confirmed accounts.
*/

$config['force_confirm']    = true;
$config['force_confirm_pt'] = true;
$config['allow_delete']     = true;
$config['allow_undelete']   = true;

$config['remove_deleted']   = true;
$config['delete_time']      = 30;

$config['remove_stale']     = true;
$config['stale_time']       = 30;