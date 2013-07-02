<?php
Class Register_Model extends CI_Model{

	//Adds new standard user
	function addUser($fname, $email, $password){

		$data = array(
			'fname' => $fname,
			'email' => $email,
			'password' => $password
		);

		$this->db->insert('users', $data);

		$recipe_id = $this->db->insert_id();

		return json_encode(array("id" => $recipe_id));
	}

	//Adds new user that is connecting with fb - adds fb id
	function addUserFb($fname, $email, $password, $fb_id){

		$data = array(
			'fname' => $fname,
			'email' => $email,
			'password' => $password,
			'fb_id' => $fb_id
		);

		$this->db->insert('users', $data);

		$recipe_id = $this->db->insert_id();

		return json_encode(array("id" => $recipe_id));
	}
}

?>