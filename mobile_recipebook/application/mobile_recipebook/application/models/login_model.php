<?php
Class Login_Model extends CI_Model{

	//Authenticates user
	function login($email, $password){

		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('email', $email);
		$this->db->where('password', MD5($password));
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