<?php
Class User_Model extends CI_Model{

	//Loads information for a user profile
	function getProfile($uid){

		$this->db->select('*');
		$this->db->from('users');
		$this->db->where('user_id', $uid);

		$query = $this->db->get();

		return $query->result();
	}

	//Gets the number of followers a user has - displayed in user profile
	function getNumFollowers($uid){

		$this->db->select('*');
		$this->db->from('followers');
		$this->db->where('user_id', $uid);

		$query = $this->db->get();

		return $query->result();
	}

	//Get the number of users a user is following
	function getNumFollowing($uid){

		$this->db->select('*');
		$this->db->from('followers');
		$this->db->where('follower_id', $uid);

		$query = $this->db->get();

		return $query->result();
	}

	//Gets a user's recipes
	function getUserRecipes($uid){

		$this->db->select('*');
		$this->db->from('user_recipes as ur');
		$this->db->join('recipes as r', 'ur.recipe_id=r.recipe_id');
		$this->db->where('user_id', $uid);

		$query = $this->db->get();

		return $query->result();
	}

	//Gets recipes that a user has favorited
	function getUserFavs($uid){

		$this->db->select('*');
		$this->db->from('favorites as f');
		$this->db->join('recipes as r', 'r.recipe_id=f.recipe_id');
		$this->db->where('user_id', $uid);

		$query = $this->db->get();

		return $query->result();
	}

	//Determines if the current user is a follower of another user
	//Used for viewing other profiles when logged in and determning if the button will
	//allow a user to follow or unfollow
	function is_follower($uid, $current_user){
		$this->db->select('*');
		$this->db->from('followers as f');
		$this->db->where('user_id', $uid);
		$this->db->where('follower_id', $current_user);

		$query = $this->db->get();

		return $query->result();
	}

	//Adds new follow
	function add_follower($data){

		$this->db->insert('followers', $data);
	}

	//Removes follow
	function delete_follower($data){

		$this->db->delete('followers', $data);
	}

	//Gets followers - used for populating dashboard feed
	function getFollowers($uid){

		$query = $this->db->query("SELECT u.user_id, f.date_added, u.username, u.user_img_path FROM users AS u JOIN followers AS f ON u.user_id = f.follower_id WHERE f.user_id = '". $uid . "' AND u.user_id IN (SELECT DISTINCT follower_id FROM followers AS f WHERE user_id = '" . $uid . "') ORDER BY f.date_added DESC");

		return $query->result();
	}

	//Gets followed user recipes - populates dashboard feed
	function getFollowerRecipes($uid){

		$query = $this->db->query("SELECT * FROM recipes AS r JOIN user_recipes AS ur ON r.recipe_id = ur.recipe_id JOIN users AS u ON ur.user_id = u.user_id WHERE r.recipe_id IN (SELECT recipe_id AS rid FROM user_recipes AS ur WHERE ur.user_id IN (SELECT DISTINCT user_id FROM followers AS f WHERE follower_id = '" . $uid . "'))");

		return $query->result();
	}

	//Update the last date a user has logged in - for profile info
	function updateLastLogin($uid,$data){

		$this->db->where('user_id', $uid);
		$this->db->update('users', $data);
	}


}
?>