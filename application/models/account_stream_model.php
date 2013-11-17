<?php

class Account_stream_model extends CI_Model {

    public function insert_new($account_id, $stream_id, $rank) {
        $sql = 'INSERT INTO account_stream(account_id, stream_id, rank) VALUES (?, ?, ?)';
        $this->db->query($sql, array($account_id, $stream_id, $rank));
        return $this->get_id($account_id, $stream_id);
    }

    public function get_id($account_id, $stream_id) {
        $sql = 'SELECT account_stream_id FROM account_stream WHERE account_id = ? AND stream_id = ?';
        $query = $this->db->query($sql, array($account_id, $stream_id));
        if ($query->row() !== false) {
            return $query->row()->account_stream_id;
        }
        return false;
    }

    public function fetch_asc($user_id) {
        $sql = 'SELECT * FROM account NATURAL JOIN account_stream NATURAL JOIN stream WHERE user_id = ? ORDER BY rank';
        $query = $this->db->query($sql, array($user_id));
        return $query->result();
    }

    public function fetch_one($account_stream_id) {
        $sql = 'SELECT * FROM account NATURAL JOIN account_stream NATURAL JOIN stream WHERE account_stream_id = ? LIMIT 1';
        $query = $this->db->query($sql, array($account_stream_id));
        return $query->row();
    }
    
    public function get_max_rank($user_id) {
        $sql = 'SELECT MAX(rank) FROM account_stream NATURAL JOIN account WHERE user_id = ?';
        $query = $this->db->query($sql, array($user_id));
        return $query->result_array()[0]['MAX(rank)'];
    }

}
