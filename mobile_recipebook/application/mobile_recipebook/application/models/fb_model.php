<?php

Class Fb_Model extends CI_Model{

	//Function to insert Facebook user info into database - called from controller
	function addFbUser($fb_id, $name){

		$info = array(
			'fb_id' => $fb_id,
        	'fname' => $name
        	);

		$this->db->insert('users', $info);
	}

	//Checks for existing FB user
	function checkFbUser($fb_id){

		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('fb_id', $fb_id);
		$this->db->limit(1);

		$query = $this->db->get();

		$result = $query->result();

		if($query->num_rows() == 1){
			return true;
		}else{
			return false;
		}
	}

	//Returns user info - if already a fb user
	function getUserInfo($fb_id){
		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('fb_id', $fb_id);
		$this->db->limit(1);

		$query = $this->db->get();

		$result = $query->result();

		if($query->num_rows() == 1){
			return json_encode($result);
		}else{
			return false;
		}
	}
}

?>