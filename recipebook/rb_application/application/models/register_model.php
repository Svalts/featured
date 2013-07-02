<?php
Class Register_Model extends CI_Model{


	//Creates a new user
	function registration($data){

		$this->db->insert('users', $data);
		
	}
}
?>