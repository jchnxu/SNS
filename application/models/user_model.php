<?php

class User_model extends CI_Model {
    /*
     * return false if login fails, otherwise user info
     */
    public function get_user_by_id($user_id) {
        $sql = 'SELECT * from user where user_id = ? limit 1';
        $query = $this->db->query($sql, array($user_id));
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    public function get_nickname($user_id) {
        $row = $this->get_user_by_id($user_id);
        if ($row != false) {
            return $row->nickname;
        }
        return false;
    }


    
    /*
     * return false if login fails, otherwise user info
     */
    public function check_login($email_address, $password) {
        $sql = 'SELECT * from user where email_address = ? and password = ? limit 1';
        $query = $this->db->query($sql, array($email_address, $password));
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    public function insert_new($email_address, $password) {
        $sql = 'INSERT INTO user(email_address, password, nickname) VALUES (?, ?, ?)';
        return $this->db->query($sql, array($email_address, $password, ''));
    }

    public function check_password($user_id, $password) {
        $row = $this->get_user_by_id($user_id);
        if ($row != false) {
            if ($row->password === sha1($password)) {
                return true;
            }
        }
        return false;
    }

    public function update_nickname($user_id, $new_nickname) {
        $sql = 'UPDATE user SET nickname = ? WHERE user_id = ?';
        return $this->db->query($sql, array($new_nickname, $user_id));
    }

    public function update_password($user_id, $new_password) {
        $sql = 'UPDATE user SET password = ? WHERE user_id = ?';
        return $this->db->query($sql, array(sha1($new_password), $user_id));
    }

    public function update_avatar_url($user_id, $new_url) {
        $sql = 'UPDATE user SET avatar_url = ? WHERE user_id = ?';
        return $this->db->query($sql, array($new_url, $user_id));
    }
}
