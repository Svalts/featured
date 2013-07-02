<?php
Class Login_Model extends CI_Model{

	//Verify login credentials with email and password
	function login($email, $password){

		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('email', $email);
		$this->db->where('password', $password);
		$this->db->limit(1);

		$query = $this->db->get();

		if($query->num_rows() == 1){
			return $query->result();
		}else{
			return false;
		}
	}

	//Verify login credentials when connecting with FB
	//Checks to see if FB id exists in DB
	function fb_login($fb_id){

		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('fb_id', $fb_id);

		$query = $this->db->get();

		if($query->num_rows() == 1){
			return $query->result();
		}else{
			return false;
		}

	}
}
?>