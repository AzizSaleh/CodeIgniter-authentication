<?php
/**
 * This file contains form validation rules
 *
 * @author    Aziz S. Hussain <azizsaleh@gmail.com>
 * @copyright GPL license 
 * @license   http://www.gnu.org/copyleft/gpl.html 
 * @link      http://www.AzizSaleh.com
 * @using     Codeigniter 3.1.2
 */

defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    'authentication/login' => array(
        array(
            'field' => 'login',
            'label' => 'Login',
            'rules' => 'required',
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required'
        ),
    ),
    'authentication/register' => array(
        array(
            'field' => 'username',
            'label' => 'User Name',
             /*
              * Important:
              *-----------
              * The regex_match rule is important here
              * since the username can not contain @
              * if you change it to allow @ then the forgot
              * functionality should be limited to either username OR email
              * and not both otherwise you could potentially reset someone
              * else's passwords
              */
            'rules' => 'required|regex_match[/^[^@]+$/]',
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|valid_email'
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required'
        ),
        array(
            'field' => 'password_confirm',
            'label' => 'Confirm Password',
            'rules' => 'required|matches[password]'
        ),
    ),
    'authentication/confirm' => array(
        array(
            'field' => 'confirm',
            'label' => 'Confirm',
            'rules' => 'required',
        ),
    ),
    'authentication/forgot' => array(
        array(
            'field' => 'login',
            'label' => 'Forgot',
            'rules' => 'required',
        ),
    ),
    'authentication/account' => array(
        array(
            'field' => 'username',
            'label' => 'User Name',
            /*
              * Important:
              *-----------
              * The regex_match rule is important here
              * since the username can not contain @
              * if you change it to allow @ then the forgot
              * functionality should be limited to either username OR email
              * and not both otherwise you could potentially reset someone
              * else's passwords
              */
            'rules' => 'required|regex_match[/^[^@]+$/]',
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|valid_email'
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
        ),
        array(
            'field' => 'current_password',
            'label' => 'Current Password',
            'rules' => 'required'
        ),
    ),
);