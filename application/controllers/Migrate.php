<?php
/**
 * This controllers handles DB migrations
 *
 * @author    Aziz S. Hussain <azizsaleh@gmail.com>
 * @copyright GPL license 
 * @license   http://www.gnu.org/copyleft/gpl.html 
 * @link      http://www.AzizSaleh.com
 * @using     Codeigniter 3.1.2
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Migrate
 * 
 * DB Migrations
 *
 * @author    Aziz S. Hussain <azizsaleh@gmail.com>
 * @copyright GPL license 
 * @license   http://www.gnu.org/copyleft/gpl.html 
 * @link      http://www.AzizSaleh.com
 * @extends   CI_Controller
 */
class Migrate extends CI_Controller 
{
    /**
     * Configs
     */
    public $configs;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * User Registration
     *
     * @access    /index.php/migrate/index
     */
    public function index()
    {
        if (!is_cli()) {
            exit(1);
        }

        $this->load->library('migration');

        if ($this->migration->current() === false) {
            show_error($this->migration->error_string());
        } else {
            echo 'Database setup complete' . PHP_EOL;
        }
    }
}