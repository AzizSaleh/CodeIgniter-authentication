<?php
/**
 * This model handles the user table
 *
 * @author    Aziz S. Hussain <azizsaleh@gmail.com>
 * @copyright GPL license 
 * @license   http://www.gnu.org/copyleft/gpl.html 
 * @link      http://www.AzizSaleh.com
 * @using     Codeigniter 3.1.2
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model_Users
 * 
 * SQL Table: users
 *
 * @author    Aziz S. Hussain <azizsaleh@gmail.com>
 * @copyright GPL license 
 * @license   http://www.gnu.org/copyleft/gpl.html 
 * @link      http://www.AzizSaleh.com
 * @extends   MY_Model
 */
class Model_Users extends MY_Model
{
    /**
     * Table name
     *
     * @param string
     */
    public $table = 'users';

    /**
     * Get user password & hash
     *
     * @param string $login
     *
     * @return stdClass|boolean
     */
    public function get_hash($login)
    {
        $this->db->select('id, user_pass, user_salt, user_status');
        $this->db->from($this->table);
        $this->db->where('user_name', $login);
        $this->db->or_where('user_email', $login);
        $res = $this->db->get()->result();
        return !empty($res) ? $res[0] : false;
    }

    /**
     * Check if a row with field exists
     *
     * @param string $field
     * @param string $value
     *
     * @return boolean
     */
    public function field_exists($field, $value)
    {
        $this->db->select($field);
        $this->db->from($this->table);
        $this->db->where($field, $value);
        $res = $this->db->get()->result();
        return !empty($res) ? $res[0] : false;
    }

    /**
     * Create new user
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @param array $configs
     * @param string $ip
     *
     * @return boolean|array
     */
    public function create_user($username, $email, $password, $configs, $ip)
    {
        $user_salt  = uniqid('', true);
        $password   = password_hash($password . $configs['hash'] . $user_salt, PASSWORD_BCRYPT);

        $data = array(
            'user_name'     => $username,
            'user_email'    => $email,
            'user_pass'     => $password,
            'user_salt'     => $user_salt,
            'user_status'   => 'active',
            'created_at'    => date("Y-m-d H:i:s"),
            'created_ip'    => $ip,
        );

        if ($configs['force_confirm']) {
            $data['user_status']  = 'confirm';
            $data['confirm_code'] = password_hash($user_salt, PASSWORD_BCRYPT);
        }

        $this->db->insert($this->table, $data);

        $id = $this->db->insert_id();

        return empty($id) ? false : array('id' => $id, 'code' => $data['confirm_code']);
    }

    /**
     * Create user session
     *
     * @param int $user_id
     * @param string $user_ip
     *
     * @return string
     */
    public function create_session($user_id, $user_ip)
    {
        $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'unknown';
        $session_id = password_hash($user_id . $user_ip . $agent, PASSWORD_BCRYPT);

        $sql = "REPLACE INTO user_sessions (user_id, session_id, created_ip, created_at)
        VALUES (?, ?, ?, ?)";
        $this->db->query($sql, array($user_id, $session_id, $user_ip, date("Y-m-d H:i:s")));        

        return $session_id;
    }

    /**
     * Confirm user
     *
     * @param string $code
     * @param string $email
     *
     * @return boolean|int
     */
    public function confirm_user($code, $email)
    {
        $this->db->select('id');
        $this->db->from($this->table);
        $this->db->where('confirm_code', $code);
        $this->db->where('user_email', $email);
        $this->db->where('user_confirmed', 'no');
        $res = $this->db->get()->result();

        if (!isset($res[0])) {
            return false;
        }

        $date = date("Y-m-d H:i:s");

        $this->db->query("UPDATE {$this->table} 
        SET user_status = 'active',user_confirmed = 'yes',
        confirm_code = NULL, confirmed_at = ?
        WHERE id = ? LIMIT 1", array($date, $res[0]->id));
        
        return $res[0]->id;
    }

    /**
     * Reset user password
     *
     * @param string $login
     *
     * @return boolean|stdClass
     */
    public function reset_token($login)
    {
        $reset = uniqid('', true);
        $this->db->query("UPDATE {$this->table} SET reset_pass = ? 
            WHERE (user_email = ? or user_name = ?) and user_status = ?", 
            array($reset, $login, $login, 'active'));

        if ($this->db->affected_rows() > 0) {
            $this->db->select('id, user_name, user_email, reset_pass');
            $this->db->from($this->table);
            $this->db->where('reset_pass', $reset);
            $res = $this->db->get()->result();

            return $res[0];
        }

        return false;
    }

    /**
     * Generate new password
     *
     * @param string $code
     * @param int $user_id
     * @param array $configs
     *
     * @return boolean|array
     */
    public function generate_new_pass($code, $user_id, $configs)
    {
        $this->db->select('user_name, user_email');
        $this->db->from($this->table);
        $this->db->where('reset_pass', $code);
        $this->db->where('id', $user_id);
        $this->db->where('user_status', 'active');
        $res = $this->db->get()->result();

        if (!empty($res[0])) {
            $new_pass   = uniqid('', true);
            $user_salt  = uniqid('', true);
            $password   = password_hash($new_pass . $configs['hash'] . $user_salt, PASSWORD_BCRYPT);

            $this->db->query("UPDATE {$this->table} 
                SET user_pass = ?, reset_pass = NULL, updated_at = ?,
                user_salt = ?
                WHERE id = ? LIMIT 1", array($password, date("Y-m-d H:i:s"), $user_salt, $user_id));

            if ($this->db->affected_rows() > 0) {
                return array('pass' => $new_pass, 'user' => $res[0]);
            }
        }

        return false;       
    }

    /**
     * Update user status
     *
     * @param int $id
     * @param string $status
     *
     * @return boolean
     */
    public function set_status($id, $status)
    {
        return $this->db->update($this->table, array('user_status' => $status), array('id' => $id));
    }

    /**
     * Get user's session
     *
     * @param string $session_id
     *
     * @return false|object
     */
    public function get_session($session_id)
    {
        $this->db->select('users.*');
        $this->db->from('user_sessions');
        $this->db->join('users', 'users.id = user_sessions.user_id');
        $this->db->where('user_sessions.session_id', $session_id);
        $this->db->where('users.user_status', 'active');

        $res = $this->db->get()->result();

        if (!empty($res[0])) {
            return $res[0];
        }

        return false;
    }

    /**
     * Delete user session
     *
     * @param int $user_id
     *
     * @return boolean
     */
    public function clear_sessions($user_id)
    {
        return $this->db->delete('user_sessions', array('user_id', $user_id));
    }

    /**
     * Update user
     *
     * @param int $user_id
     * @param array $updates
     *
     * @return boolean
     */
    public function update_user($user_id, $updates)
    {
        return $this->db->update($this->table, $updates, array('id' => $user_id));
    }

    /**
     * Remove disabled OR un-confirmed accounts from the system
     * which haven't been updated since x days
     *
     * @param enum type [disabled, confirm]
     * @param int $days
     *
     * @return int
     */
    public function remove_accounts($type, $days)
    {
        $days = (int) $days;
        if (!in_array($type, array('disabled', 'confirm'))) {
            exit(1);
        }

        $delete_query = "users WHERE user_status = '{$type}' 
        AND (updated_at <= DATE(NOW() - INTERVAL {$days} DAY)) OR
        (updated_at IS NULL AND created_at <= DATE(NOW() - INTERVAL {$days} DAY))";

        // Remove sessions first
        $this->db->query("DELETE FROM user_sessions WHERE user_id IN 
            (SELECT id FROM {$delete_query})");

        // Then remove accounts
        $this->db->query("DELETE FROM {$delete_query}");

        return $this->db->affected_rows();
    }

    /**
     * Get confirmation code
     *
     * @param string $login
     *
     * @return object|false
     */
    public function get_confirm_code($login)
    {
        $this->db->select('user_email, user_name, confirm_code');
        $this->db->from($this->table);
        $this->db->where('user_confirmed', 'no');
        $this->db->where('user_status', 'confirm');
        $login = $this->db->escape($login);
        $this->db->where("(user_name = $login OR user_email = $login)");

        $res = $this->db->get()->result();

        if (!empty($res[0])) {
            return $res[0];
        }

        return false;
    }
}