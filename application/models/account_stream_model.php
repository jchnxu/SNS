<?php

class Account_stream_model extends CI_Model {

    public function insert_new($account_id, $stream_id, $rank) {
        $sql = 'INSERT INTO account_stream(account_id, stream_id, rank) VALUES (?, ?, ?)';
        return $this->db->query($sql, array($account_id, $stream_id, $rank));
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

}
