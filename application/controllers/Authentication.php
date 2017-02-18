<?php
/**
 * This controllers handles user authentication
 *
 * @author    Aziz S. Hussain <azizsaleh@gmail.com>
 * @copyright GPL license
 * @license   http://www.gnu.org/copyleft/gpl.html
 * @link      http://www.AzizSaleh.com
 * @using     Codeigniter 3.1.2
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Authentication
 *
 * Authentication controller class
 *
 * @author    Aziz S. Hussain <azizsaleh@gmail.com>
 * @copyright GPL license
 * @license   http://www.gnu.org/copyleft/gpl.html
 * @link      http://www.AzizSaleh.com
 * @extends   MY_Controller
 */
class Authentication extends MY_Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->helper('url');

        // Set error message div
        $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
        $this->config->load('forms', true);
    }

    /**
     * Account page
     */
    public function admin()
    {
        // User check
        $user = $this->get_user();
        if (empty($user) || $user->id != 1) {
            header('Location: ' . site_url('authentication/account'));
            exit();
        }

        $data = array(
            'page_title' => 'Admin : Manage Users',
        );

        // Load pagination
        $config = array();
        $offset = $this->uri->segment(3, 0);
        $config['per_page'] = 10;
        $data['status'] = $this->input->post('status');
        $data['search'] = $this->input->post('search');

        // Get users
        $data['users'] = $this->model_users->get_users($config['per_page'], $offset,
            $data['status'], $data['search']);
        $config['total_rows'] = $this->model_users->get_count();


        $this->load->library('pagination');
        $config['base_url']     = site_url('authentication/admin');
        $this->pagination->initialize($config);
        $data['links'] = $this->pagination->create_links();
        $data['total_rows'] = $config['total_rows'];

        $data['body'] = $this->load->view('authentication/admin', $data, true);
        $this->load->view('template', $data);
    }

    /**
     * Manage user
     */
    public function manage()
    {
        // User check
        $user = $this->get_user();
        if (empty($user) || $user->id != 1) {
            header('Location: ' . site_url('authentication/account'));
            exit();
        }

        $data = array(
            'page_title' => 'Manage User',
        );

        $user_id = (int) $this->uri->segment(3, 0);
        $user = $this->model_users->get_user($user_id);

        if (empty($user)) {
            $this->session->set_flashdata('last_action', 'Invalid action.');
            header('Location: ' . site_url('authentication/admin'));
            exit();
        }

        if ($this->input->method() == 'post') {
            $validation = $this->config->item('forms');
            $this->form_validation->set_rules($validation['authentication/manage']);

            $password = $this->input->post('password');
            if (!empty($password)) {
                $this->form_validation->set_rules('password_confirm', 'password confirmation',
                'required');
            }

            if ($this->form_validation->run() != false) {

                $username   = $this->input->post('username');
                $email      = $this->input->post('email');
                $status     = $this->input->post('status');

                // Are we changing the username?
                $updates = array();
                if ($username != $user->user_name) {
                    // Make sure it doesn't exist
                    if ($this->model_users->field_exists('user_name', $username)) {
                        $data['message_main'] = 'This username is already in use, please select another.';
                    } else {
                        $updates['user_name'] = $username;
                    }
                }

                if (!empty($password)) {
                    $updates['user_salt'] = uniqid('', true);
                    $updates['user_pass'] = password_hash(
                        $password . $this->configs['hash'] .
                        $updates['user_salt'], PASSWORD_BCRYPT
                    );
                }

                if ($email != $user->user_email) {
                    // Make sure it doesn't exist
                    if ($this->model_users->field_exists('user_email', $email)) {
                        $data['message_main'] = 'This email is already in use, please select another.';
                    } else {
                        $updates['user_email'] = $email;
                    }
                }

                if ($status != $user->user_status) {
                    // Make sure it doesn't exist
                    if (!in_array($status, array('active', 'disabled', 'confirm'))) {
                        $data['message_main'] = 'Invalid status.';
                    } else {
                        $updates['user_status'] = $status;
                    }
                }

                if (!empty($updates)) {
                    $res = $this->model_users->update_user($user->id, $updates);
                    if ($res) {
                        $data['message_main'] = 'User account settings have been updated.';
                    } else {
                        $data['message_main'] = 'Internal error. Please try again.';
                    }
                }
            }
        }

        $data['user'] = $this->model_users->get_user($user_id);

        $data['body'] = $this->load->view('authentication/manage', $data, true);
        $this->load->view('template', $data);
    }

    /**
     * Delete user
     */
    public function delete()
    {
        // User check
        $user = $this->get_user();
        if (empty($user) || $user->id != 1) {
            header('Location: ' . site_url('authentication/account'));
            exit();
        }

        $user_id = (int) $this->uri->segment(3, 0);

        if (!empty($user_id) && $user_id == $this->uri->segment(3, 0)) {
            $this->model_users->db->delete($this->model_users->table, array('id' => $user_id));
        }

        $this->session->set_flashdata('last_action', "User #{$user_id} Deleted.");
        header('Location: ' . site_url('authentication/admin'));
        exit();
    }

    /**
     * User Registration
     *
     * @access    /index.php/authentication/register
     */
    public function register()
    {
        // User check
        $user = $this->get_user();
        if (!empty($user)) {
            header('Location: ' . site_url('authentication/account'));
            exit();
        }

        $data = array(
            'page_title'    => 'Create an account',
            'remember_me'   => $this->configs['remember_me'] && !$this->configs['force_confirm'],
            'show_resend'   => $this->configs['force_confirm'] || $this->configs['force_confirm_pt']
        );

        if ($this->input->method() == 'post') {
            $validation = $this->config->item('forms');
            $this->form_validation->set_rules($validation['authentication/register']);

            if ($this->form_validation->run() != false) {
                $username   = $this->input->post('username');
                $email      = $this->input->post('email');
                $password   = $this->input->post('password');

                // User if username/email exists
                if ($this->model_users->field_exists('user_name', $username)) {
                    $data['message_main'] = 'This username is already in use, please select another.';
                } else if ($this->model_users->field_exists('user_email', $email)) {
                    $data['message_main'] = 'This email is already in use, please select another.';
                } else if (!$user = $this->model_users->create_user($username, $email,
                    $password, $this->configs, $this->get_ip())) {
                    $data['message_main'] = 'Internal error. Please try again. Contact us if the problem persists.';
                } else {
                    // Redirect to confirm
                    if ($this->configs['force_confirm']) {
                        // Send confirm email out
                        $this->load->library('ah_email', 'ah_email');
                        $this->ah_email->send($email, 'confirm', array(
                            'confirm_code'  => $user['code'],
                            'email'         => $email,
                            'username'      => $username,
                            'url'           => site_url('/authentication/confirm/' . urlencode($email)),
                        ));
                        $this->session->set_flashdata('last_action',
                            "Please enter confirmation code that was just sent to your email.");
                        header('Location: ' . site_url('authentication/confirm/' . urlencode($email)));
                        exit();
                    }

                    // Login user
                    $this->_log_user($user['id']);
                    $this->session->set_flashdata('last_action', "Your account has been setup.");
                    header('Location: ' . site_url('authentication/account'));
                    exit();
                }
            }
        }

        $data['body'] = $this->load->view('authentication/register', $data, true);
        $this->load->view('template', $data);
    }

    /**
     * Confirm account
     *
     * @access    /index.php/authentication/confirm/{EMAIL}
     */
    public function confirm($email)
    {
        if (empty($email)) {
            header('Location: ' . site_url('authentication/account'));
            exit();
        }

        // User check
        $user = $this->get_user();
        if (!empty($user)) {
            header('Location: ' . site_url('authentication/account'));
            exit();
        }

        $data = array(
            'page_title'    => 'Confirm account',
            'email'         => urldecode($email),
            'remember_me'   => $this->configs['remember_me'] && $this->configs['force_confirm']
        );

        if ($this->input->method() == 'post') {
            $validation = $this->config->item('forms');
            $this->form_validation->set_rules($validation['authentication/confirm']);

            if ($this->form_validation->run() != false) {
                $confirm = $this->input->post('confirm');

                // User if username/email exists
                if (!$user_id = $this->model_users->confirm_user($confirm, $data['email'])) {
                    $data['message_main'] = 'This confirmation code is invalid.';
                } else {
                    // Login user
                    $this->_log_user($user_id);
                    $this->session->set_flashdata('last_action', "Account confirmed.");
                    header('Location: ' . site_url('authentication/account'));
                    exit();
                }
            }
        }

        $data['body'] = $this->load->view('authentication/confirm', $data, true);
        $this->load->view('template', $data);
    }

    /**
     * User Login
     *
     * @access    /index.php/authentication/login
     */
    public function login()
    {
        // User check
        $user = $this->get_user();
        if (!empty($user)) {
            header('Location: ' . site_url('authentication/account'));
            exit();
        }

        $data = array(
            'page_title'    => 'Login',
            'remember_me'   => $this->configs['remember_me'],
            'show_resend'   => $this->configs['force_confirm'] || $this->configs['force_confirm_pt']
        );

        if ($this->input->method() == 'post') {
            $validation = $this->config->item('forms');
            $this->form_validation->set_rules($validation['authentication/login']);

            if ($this->form_validation->run() != false) {
                $login = $this->input->post('login');
                $password = $this->input->post('password');

                $user = $this->model_users->get_hash($login);

                if (!empty($user)) {
                    // Is user de-activated && user un-delete allowed?
                    if ($user->user_status == 'disabled' && $this->configs['allow_undelete']) {
                        // Reset status to active
                        $this->model_users->set_status($user->id, 'active');
                        $user = $this->model_users->get_hash($login);
                    }

                    if ($user->user_status == 'disabled') {
                        $data['message_main'] = 'Your account was deleted. ' .
                            'Please contact us for more info.';
                    } else {
                        if (!password_verify(
                            $password . $this->configs['hash'] . $user->user_salt,
                                $user->user_pass)) {
                                    $data['message_main'] = 'Invalid login and/or password.';
                        } else {
                            $this->_log_user($user->id);
                            header('Location: ' . site_url('authentication/account'));
                            exit();
                        }
                    }
                } else {
                    $data['message_main'] = 'Invalid login and/or password.';
                }
            }
        }

        $data['body'] = $this->load->view('authentication/login', $data, true);
        $this->load->view('template', $data);
    }

    /**
     * User Logoff
     *
     * @param boolean $redirect
     *
     * @access    /index.php/authentication/logout
     */
    public function logout($redirect = true)
    {
        // User check
        $user = $this->get_user();
        if (empty($user) && $redirect) {
            header('Location: ' . site_url('authentication/login'));
            exit();
        }

        // Remove User Session Info
        session_unset($this->configs['name']);
        setcookie($this->configs['cookie_name'], '', time() - 3600, '/', $this->configs['domain']);
        $this->model_users->clear_sessions($user->id);
        if ($redirect) {
            $this->session->set_flashdata('last_action', "Your have been logged off.");
            header('Location: ' . site_url('authentication/login'));
            exit();
        }
    }

    /**
     * User Forgot Pass
     *
     * @access    /index.php/authentication/forgot
     */
    public function forgot()
    {
        // User check
        $user = $this->get_user();
        if (!empty($user)) {
            header('Location: ' . site_url('authentication/account'));
            exit();
        }

        $data = array(
            'page_title'    => 'Forgot Password',
            'remember_me'   => $this->configs['remember_me'],
            'show_resend'   => $this->configs['force_confirm'] || $this->configs['force_confirm_pt']
        );

        if ($this->input->method() == 'post') {
            $validation = $this->config->item('forms');
            $this->form_validation->set_rules($validation['authentication/forgot']);

            if ($this->form_validation->run() != false) {
                $login = $this->input->post('login');

                $user = $this->model_users->reset_token($login);

                if (!empty($user->reset_pass)) {
                    // Email token to user
                    $this->load->library('ah_email', 'ah_email');
                    $this->ah_email->send($user->user_email, 'reset', array(
                        'username'      => $user->user_name,
                        'url'           => site_url('/authentication/reset/' .
                            $user->reset_pass . '/' . $user->id),
                    ));
                }

                $data['message_main'] = 'Password reset link has been emailed.';
            }
        }

        $data['body'] = $this->load->view('authentication/forgot', $data, true);
        $this->load->view('template', $data);
    }

    /**
     * Re-send confirmation code
     *
     * @access    /index.php/authentication/resend
     */
    public function resend()
    {
        // User check
        $user = $this->get_user();
        if (!empty($user)) {
            header('Location: ' . site_url('authentication/account'));
            exit();
        }

        $data = array(
            'page_title'    => 'Resend Confirmation Code'
        );

        if ($this->input->method() == 'post') {
            $validation = $this->config->item('forms');
            $this->form_validation->set_rules($validation['authentication/forgot']);

            if ($this->form_validation->run() != false) {
                $login = $this->input->post('login');

                $user = $this->model_users->get_confirm_code($login);

                if (empty($user)) {
                    $data['message_main'] = 'Invalid login or login is already confirmed.';
                } else {
                    // Send confirm email out
                    $this->load->library('ah_email', 'ah_email');
                    $this->ah_email->send($user->user_email,
                        'confirm',
                        array(
                            'confirm_code'  => $user->confirm_code,
                            'email'         => $user->user_email,
                            'username'      => $user->user_name,
                            'url'           => site_url('/authentication/confirm/' . urlencode($user->user_email)),
                        )
                    );
                    $this->session->set_flashdata('last_action',
                            "Please enter confirmation code that was just sent to your email.");
                    header('Location: ' . site_url('authentication/confirm/' . urlencode($user->user_email)));
                    exit();
                }
            }
        }

        $data['body'] = $this->load->view('authentication/resend', $data, true);
        $this->load->view('template', $data);
    }

    /**
     * Reset Password
     *
     * @access    /index.php/authentication/reset
     */
    public function reset($code, $user_id)
    {
        if (empty($code) || empty($user_id)) {
            header('Location: ' . site_url('authentication/forgot'));
            exit();
        }

        // User check
        $user = $this->get_user();
        if (!empty($user)) {
            header('Location: ' . site_url('authentication/account'));
            exit();
        }

        $data = array(
            'page_title'    => 'Reset Password',
            'remember_me'   => $this->configs['remember_me'],
            'show_resend'   => $this->configs['force_confirm'] || $this->configs['force_confirm_pt']
        );

        $info = $this->model_users->generate_new_pass($code, $user_id, $this->configs);

        if (!empty($info['pass'])) {
            // Email password
            $this->load->library('ah_email', 'ah_email');
            $this->ah_email->send($info['user']->user_email, 'password', array(
                'username'      => $info['user']->user_name,
                'password'      => $info['pass'],
            ));
        }

        $data['message_main'] = 'Your new password has been emailed to you.';

        $data['body'] = $this->load->view('authentication/login', $data, true);
        $this->load->view('template', $data);
    }

    /**
     * Delete/Deactivate account
     *
     * @access    /index.php/authentication/deactivate
     */
    public function deactivate()
    {
        // User check
        $user = $this->get_user();
        if (empty($user)) {
            header('Location: ' . site_url('authentication/login'));
            exit();
        }

        if ($user->id == '1') {
            $this->session->set_flashdata('last_action', 'Invalid action');
            header('Location: ' . site_url('authentication/account'));
            exit();
        }

        $data = array(
            'page_title'    => 'De-activate Account',
            'configs'       => $this->configs,
        );

        if ($this->input->post('delete') == '1' && $this->configs['allow_delete'] == true) {
            $this->model_users->set_status($user->id, 'disabled');
            $this->logout();
        }

        $data['body'] = $this->load->view('authentication/deactivate', $data, true);
        $this->load->view('template', $data);
    }

    /**
     * Account page
     */
    public function account()
    {
        // User check
        $user = $this->get_user();
        if (empty($user)) {
            header('Location: ' . site_url('authentication/login'));
            exit();
        }

        $data = array(
            'page_title'        => 'Account Settings',
            'force_confirm_pt'  => $this->configs['force_confirm_pt'],
            'allow_delete'      => $this->configs['allow_delete'],
            'user_id'           => $user->id,
        );

        if ($this->input->method() == 'post') {
            $validation = $this->config->item('forms');
            $this->form_validation->set_rules($validation['authentication/account']);

            $password = $this->input->post('password');
            if (!empty($password)) {
                $this->form_validation->set_rules('password_confirm', 'password confirmation',
                'required');
            }

            if ($this->form_validation->run() != false) {

                // Check current password
                if (!password_verify(
                    $this->input->post('current_password') . $this->configs['hash'] . $user->user_salt,
                        $user->user_pass)) {
                    $data['message_main'] = 'Current password is incorrect';
                } else {
                    $username   = $this->input->post('username');
                    $email      = $this->input->post('email');

                    // Are we changing the username?
                    $updates = array();
                    if ($username != $user->user_name) {
                        // Make sure it doesn't exist
                        if ($this->model_users->field_exists('user_name', $username)) {
                            $data['message_main'] = 'This username is already in use, please select another.';
                        } else {
                            $updates['user_name'] = $username;
                        }
                    }
                    if (!empty($password)) {
                        $updates['user_salt'] = uniqid('', true);
                        $updates['user_pass'] = password_hash(
                            $password . $this->configs['hash'] .
                            $updates['user_salt'], PASSWORD_BCRYPT
                        );
                    }

                    if ($email != $user->user_email) {
                        // Make sure it doesn't exist
                        if ($this->model_users->field_exists('user_email', $email)) {
                            $data['message_main'] = 'This email is already in use, please select another.';
                        } else {
                            $updates['user_email'] = $email;
                            if ($this->configs['force_confirm_pt']) {
                                $updates['user_status'] = 'confirm';
                                $updates['user_confirmed'] = 'no';
                                $updates['confirm_code'] = password_hash(
                                    isset($updates['user_salt']) ? $updates['user_salt'] : $user->user_salt,
                                    PASSWORD_BCRYPT
                                );
                            }
                        }
                    }

                    if (!empty($updates)) {
                        $res = $this->model_users->update_user($user->id, $updates);

                        // Redirect to confirm
                        if ($res && $this->configs['force_confirm_pt'] && !empty($updates['user_email'])) {
                            // Send confirm email out
                            $this->load->library('ah_email', 'ah_email');
                            $this->ah_email->send(isset($email) ? $email : $user->email,
                                'confirm',
                                array(
                                    'confirm_code'  => $updates['confirm_code'],
                                    'email'         => $email,
                                    'username'      => isset($username) ? $username : $user->user_name,
                                    'url'           => site_url('/authentication/confirm/' . urlencode($email)),
                                )
                            );
                            $this->logout(false);
                            $this->session->set_flashdata('last_action',
                                "Please enter confirmation code that was just sent to your email.");
                            header('Location: ' . site_url('authentication/confirm/' . urlencode($email)));
                            exit();
                        }

                        if ($res) {
                            $data['message_main'] = 'Your account settings have been updated.';
                        } else {
                            $data['message_main'] = 'Internal error. Please try again. Contact us if the problem persists.';
                        }
                    }
                }
            }

            $user = $this->get_user();
        }

        $_POST['username'] = $user->user_name;
        $_POST['email'] = $user->user_email;

        $data['body'] = $this->load->view('authentication/account', $data, true);
        $this->load->view('template', $data);
    }

    /**
     * Cleanup cronjob - Should run daily
     *
     * 0 1 * * * /usr/local/bin/php /{SITE_ROOT}/index.php crons/cleanup
     *
     * @return void
     */
    public function cleanup()
    {
        // Make sure this is cli
        if (!is_cli()) {
            exit(1);
        }

        // Do we need to cleanup de-activated accounts?
        if ($this->configs['remove_deleted']) {
            $rows = $this->model_users->remove_accounts('disabled', $this->configs['delete_time']);
            echo "Removed {$rows} De-activated accounts" . PHP_EOL;
        }

        // Do we need to cleanup un-confirmed accounts?
        if ($this->configs['remove_stale']) {
            $rows = $this->model_users->remove_accounts('confirm', $this->configs['stale_time']);
            echo "Removed {$rows} Un-confirmed accounts" . PHP_EOL;
        }

        echo 'Operation completed' . PHP_EOL;
    }

    /**
     * Log user in, create session and store it
     *
     * @param int $user_id
     */
    protected function _log_user($user_id)
    {
        $remember = false;
        if ($this->input->post('remember_me') == '1' && $this->configs['remember_me'] == true) {
            $remember = true;
        }

        // Create session
        $session_id = $this->model_users->create_session($user_id, $this->get_ip());
        $_SESSION[$this->configs['name']] = $session_id;

        // Remember me set?
        if ($remember) {
            setcookie($this->configs['cookie_name'], $session_id, $expire, '/', $this->configs['domain']);
        }
    }

    /**
     * Get user IP
     *
     * @param boolean $raw If true, will not check static IP
     *
     * @return boolean|string
     */
    public function get_ip($raw = false)
    {
        static $ip_address;
        if (!empty($ip_address) && !$raw) {
            return $ip_address;
        }

        $ip_address = false;
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } else if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if(isset($_SERVER['HTTP_X_FORWARDED'])) {
            $ip_address = $_SERVER['HTTP_X_FORWARDED'];
        } else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
            $ip_address = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        } else if(isset($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if(isset($_SERVER['HTTP_FORWARDED'])) {
            $ip_address = $_SERVER['HTTP_FORWARDED'];
        } else if(isset($_SERVER['REMOTE_ADDR'])) {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }

        if (strstr($ip_address, ',')) {
            $tmp = explode(',', $ip_address);
            $ip_address = trim($tmp[0]);
        }

        return $ip_address;
    }
}