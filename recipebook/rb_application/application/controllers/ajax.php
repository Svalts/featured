<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

	//Searches the DB for recipes based on the user input on the homepage
	function autocomplete(){

		$this->load->model('recipes_model');
		$results = $this->recipes_model->autocomplete($_POST['search_input']);
		echo $results;
	}

	//Adds a user favorite when fav icon is clicked and user is logged in
	function addFavorite(){
		
		$this->load->model('recipes_model');
		$fav_data['user_id'] = $_POST['user_id'];
		$fav_data['recipe_id'] = $_POST['recipe_id'];
		$fav_data['num_favs'] = 1;
		$results = $this->recipes_model->addFavorite($fav_data);
		echo $results;
	}

	//Removes a logged in user's favorite when the fav icon is clicked
	function removeFavorite(){
		
		$this->load->model('recipes_model');
		$fav_data['user_id'] = $_POST['user_id'];
		$fav_data['recipe_id'] = $_POST['recipe_id'];
		$results = $this->recipes_model->removeFavorite($fav_data);
		echo $results;
	}

	//Adds a logged in users favorite to the favorite total when fav icon is clicked
	function addFavTotal(){

		$this->load->model('recipes_model');
		$results = $this->recipes_model->addFavTotal($_POST['recipe_id']);
		echo $results;
	}

	//Subtracts from recipe favorited total
	function subtractFavTotal(){

		$this->load->model('recipes_model');
		$results = $this->recipes_model->subFavTotal($_POST['recipe_id']);
		echo $results;
	}

	//Deletes a logged in user's recipe
	function deleteRecipe(){

		$this->load->model('recipes_model');
		$session = $this->session->userdata('logged_in');
		$rid = $_POST['recipe_id'];
		$uid = $session['uid'];
		
		$data = array(
				"recipe_id" => $rid,
				"user_id" => $uid
			);

		$results = $this->recipes_model->deleteRecipe($data);
		echo $results;

	}
}
?>