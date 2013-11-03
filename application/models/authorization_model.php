<?php

class Authorization_model extends CI_Model {

	public function insert_account($user_id,$accessToken,$social_name){
		$sql = 'SELECT * from account where user_id = ? and social_name = ? and access_token = ?';
        $query = $this->db->query($sql, array($user_id, $social_name,$accessToken));
        if ($query->num_rows() > 0) {
            return;
        }
		$sql = 'INSERT INTO account(user_id, social_name,access_token) VALUES (?,?, ?)';
		return $this->db->query($sql, array($user_id, $social_name,$accessToken));
	}
	
	public function update_accesstoken($user_id,$accessToken,$social_name){
		$sql = 'UPDATE account SET access_token = ? WHERE user_id = ? and social_name = ?';
        return $this->db->query($sql, array($accessToken, $user_id,$social_name));
	}
	
	
	
}