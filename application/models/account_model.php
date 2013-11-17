<?php

class Account_model extends CI_Model {

    public function insert_new($user_id, $social_name, $sn_user_id, $avatar_url, $token1, $token2) {
        $sql = 'INSERT INTO account(user_id, social_name, sn_user_id, token1, token2, avatar_url) VALUES (?, ?, ?, ?, ?)';
        $this->db->query($sql, array($user_id, $social_name, $sn_user_id, $token1, $token2, $avatar_url));
        return $this->get_id($user_id, $social_name, $sn_user_id);
    }

    public function get_id($user_id, $social_name, $sn_user_id) {
        $sql = 'SELECT account_id FROM account WHERE user_id = ? AND social_name = ? AND sn_user_id = ?';
        $query = $this->db->query($sql, array($user_id, $social_name, $sn_user_id));
        if ($query->row() != false) {
            return $query->row()->account_id;
        }
        return false;
    }

    public function update($user_id, $social_name, $sn_user_id, $avatar_url, $token1, $token2) {
        $sql = 'UPDATE account SET token1 = ?, token2 = ?, avatar_url = ? where user_id = ? AND social_name = ? AND sn_user_id = ?';
        return $this->db->query($sql, array($token1, $token2, $avatar_url, $user_id, $social_name, $sn_user_id));
    }

    public function get_accounts($user_id) {
        $sql = 'SELECT * FROM account WHERE user_id = ?';
        $query = $this->db->query($sql, array($user_id));
        return $query;
    }

    public function get_add_stream_options($user_id) {
        $result = array();
        $sql = 'SELECT * FROM account WHERE user_id = ?';
        $query = $this->db->query($sql, array($user_id));
        if ($query->num_rows() > 0) {
            $accounts = $query->result();
            foreach($accounts as $account) {
                $sql2 = 'SELECT * from stream WHERE social_name = ?';
                $query2 = $this->db->query($sql2, array($account->social_name));
                if ($query2->num_rows() > 0) {
                    array_push($result, array(
                        'account' => $account, 
                        'stream_options' => $query2->result()
                    ));
                }
            }
        }
        return $result;
    }
}
