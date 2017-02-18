<?php
/**
 * This is the core MY_Controller.php file. All your controllers
 * should extend this, makes it easier to handle authentication.
 *
 * @author    Aziz S. Hussain <azizsaleh@gmail.com>
 * @copyright GPL license 
 * @license   http://www.gnu.org/copyleft/gpl.html 
 * @link      http://www.AzizSaleh.com
 * @using     Codeigniter 3.1.2
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * MY_Controller
 * 
 * @author    Aziz S. Hussain <azizsaleh@gmail.com>
 * @copyright GPL license 
 * @license   http://www.gnu.org/copyleft/gpl.html 
 * @link      http://www.AzizSaleh.com
 * @extends   CI_Controller
 */
class MY_Controller extends CI_Controller
{
	/**
     * Configs
     */
    public $configs;

	/**
	 * Load master/slave objects, set it to master by default
	 */
	public function __construct()
	{
		parent::__construct();

        $this->load->config('authentication', true);
        $this->configs = $this->config->config['authentication'];
        $this->load->library('session');

        $this->load->model('model_users');

        // Start session if needed
        if ($this->is_session_started() === false) {
            session_start();
        }
	}

	/**
     * Get user
     *
     * @return false|Model_User
     */
    public function get_user()
    {
        // Do we have a cookie
        $cookie = isset($_SESSION[$this->configs['name']]) ? $_SESSION[$this->configs['name']] : false;
        if ($cookie === false) {
            // Do we have a remember me?
            $cookie = isset($_COOKIE[$this->configs['cookie_name']]) ? $_COOKIE[$this->configs['cookie_name']] : false;

            if ($cookie === false) {
                return false;
            }
        }

        // Get session
        return $this->model_users->get_session($cookie);
    }

	/**
     * Check for an existing user session
     *
     * @author coder.ua@gmail.com
     * @source http://www.php.net/manual/en/function.session-status.php
     *
     * @return boolean
     */
    public function is_session_started()
    {
        if (!is_cli()) {
            if (version_compare(phpversion(), '5.4.0', '>=')) {
                return session_status() === PHP_SESSION_ACTIVE ? true : false;
            } else {
                return session_id() === '' ? false : true;
            }
        }

        return false;
    }
}