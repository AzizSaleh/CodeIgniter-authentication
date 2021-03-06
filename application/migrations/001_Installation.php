<?php
/**
 * Setting up the database
 *
 * @author    Aziz S. Hussain <azizsaleh@gmail.com>
 * @copyright GPL license 
 * @license   http://www.gnu.org/copyleft/gpl.html 
 * @link      http://www.AzizSaleh.com
 * @using     Codeigniter 3.1.2
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Install Migration_Installation
 * 
 * Database installation
 *
 * @author    Aziz S. Hussain <azizsaleh@gmail.com>
 * @copyright GPL license 
 * @license   http://www.gnu.org/copyleft/gpl.html 
 * @link      http://www.AzizSaleh.com
 * @extends   CI_Migration 
 */
class Migration_Installation extends CI_Migration  
{
    /**
     * Migrate up
     */
    public function up()
    {
        if ($_SERVER['argc'] < 5) {
            echo 'Usage: php index.php migrate/index admin_username adminemail%gmail.com admin_password.';
            echo ' Please note the email, use % for @.' . PHP_EOL;
            exit(1);
        }

        $username = $_SERVER['argv'][2];
        $email    = str_replace('%', '@', $_SERVER['argv'][3]);
        $password = $_SERVER['argv'][4];

        // Check email format
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo 'Invalid email: ' . $email;
            exit(1);
        }

        if (strpos($username, '@') !== false) {
            echo 'Username can not contain @';
            exit(1);
        }

        $this->db->query("CREATE TABLE `users` (
          `id` bigint(20) UNSIGNED NOT NULL,
          `user_name` varchar(50) NOT NULL,
          `user_email` varchar(256) NOT NULL,
          `user_pass` varchar(255) NOT NULL,
          `user_salt` varchar(23) NOT NULL,
          `user_status` enum('active','confirm','disabled') NOT NULL DEFAULT 'active',
          `reset_pass` varchar(23) DEFAULT NULL,
          `user_confirmed` enum('no','yes') NOT NULL DEFAULT 'no',
          `confirm_code` varchar(60) DEFAULT NULL,
          `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `created_ip` varchar(55) DEFAULT NULL,
          `confirmed_at` datetime NOT NULL,
          `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

        $this->db->query("ALTER TABLE `users`
            ADD PRIMARY KEY (`id`),
            ADD UNIQUE KEY `user_name` (`user_name`),
            ADD KEY `user_email` (`user_email`(255));");

        $this->db->query("ALTER TABLE `users`
            MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;");

        $this->db->query("CREATE TABLE `user_sessions` (
          `user_id` bigint(20) NOT NULL,
          `session_id` varchar(60) NOT NULL,
          `created_ip` varchar(55) NOT NULL,
          `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='User login sessions';");

        $this->db->query("ALTER TABLE `user_sessions`
        ADD PRIMARY KEY (`user_id`,`session_id`);");

        // Add admin user
        $this->load->config('authentication', true);
        $configs = $this->config->config['authentication'];

        $salt = uniqid('', true);
        $password = password_hash(
            $password . $configs['hash'] . 
            $salt, PASSWORD_BCRYPT
        );
        $this->db->query("INSERT INTO `users` 
          (`id`, `user_name`, `user_email`, `user_pass`, `user_salt`, `user_status`, `reset_pass`, `user_confirmed`, `confirm_code`, `created_at`, `created_ip`, `confirmed_at`, `updated_at`) VALUES
          (1, ?, ?, ?, ?, 'active', NULL, 'yes', NULL, NOW(), '::1', NOW(), NULL)",
          array($username, $email, $password, $salt));
    }

    /**
     * Migrate down
     */
    public function down()
    {
        $this->dbforge->drop_table('users');
        $this->dbforge->drop_table('user_sessions');
    }
}