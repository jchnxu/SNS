<?php

class User_model extends CI_Model {
    
    public function check_login($email_address, $password) {
        $sql = 'SELECT * from user where email_address = ? and password = ? limit 1';
        $query = $this->db->query($sql, array($email_address, $password));
        if ($query->num_rows() > 0) {
            return $query->row();
        }
        return false;
    }

    public function insert_new($email_address, $password) {
        $sql = 'INSERT INTO user(email_address, password) VALUES (?, ?)';
        $result = $this->db->query($sql, array($email_address, $password));
    }
}
