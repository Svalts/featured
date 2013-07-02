<?php
class Ajax extends CI_Controller {


	//Ajax call from JS to enter FB user into database - calls model
	function addFbUser(){
		$this->load->model('fb_model');
		$fb_model = $this->fb_model->addFbUser($_POST['fb_id'],$_POST['name']);
		echo $fb_model;
	}

	//Checks to see if user has logged in with FB before
	function checkFbUser(){
		$this->load->model('fb_model');
		$fb_model = $this->fb_model->checkFbUser($_POST['fb_id']);
		echo $fb_model;
	}

	//Gets FB user info
	function getUserInfo(){
		$this->load->model('fb_model');
		$fb_model = $this->fb_model->getUserInfo($_POST['fb_id']);
		echo $fb_model;
	}

	function uploadPhoto(){
		$this->load->model('recipes_model');
		$recipes_model = $this->recipes_model->uploadPhoto($_POST['image']);
		echo $recipes_model;
	}

	//Login authorization
	function login(){
		$this->load->model('login_model');
		$login_model = $this->login_model->login($_POST['email'], $_POST['password']);
		echo $login_model;
	}

	//Adds a standard user and form validation
	function addUser(){

		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('r_fname', 'First Name', 'required|trim|xss_clean|alpha');
		$this->form_validation->set_rules('r_email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('r_pass', 'Password', 'trim|required|matches[r_confirm]|min_length[5]|md5');
		$this->form_validation->set_rules('r_confirm', 'Confirm', 'trim|required|xss_clean');

		if ($this->form_validation->run() == FALSE){

			$this->form_validation->set_message('r_fname', 'Invalid First Name');
			$this->form_validation->set_message('r_email', 'Invalid Email');
			$this->form_validation->set_message('r_pass', 'Invalid Password');
			$this->form_validation->set_message('r_confirm', 'Confirm Password Required');
			echo json_encode(validation_errors());

		}else{

			$this->load->model('register_model');
			$register_model = $this->register_model->addUser($_POST['r_fname'], $_POST['r_email'], $_POST['r_pass']);
			echo $register_model;
		}
	}

	//Adds a user that is connecting with FB - includes FB id
	function addUserFb(){

		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('r_fname', 'First Name', 'required|trim|xss_clean|alpha');
		$this->form_validation->set_rules('r_email', 'Email', 'trim|required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('r_pass', 'Password', 'trim|required|matches[r_confirm]|min_length[5]|md5');
		$this->form_validation->set_rules('r_confirm', 'Confirm', 'trim|required|xss_clean');

		if ($this->form_validation->run() == FALSE){

			$this->form_validation->set_message('r_fname', 'Invalid First Name');
			$this->form_validation->set_message('r_email', 'Invalid Email');
			$this->form_validation->set_message('r_pass', 'Invalid Password');
			$this->form_validation->set_message('r_confirm', 'Confirm Password Required');
			echo json_encode(validation_errors());

		}else{

			$this->load->model('register_model');
			$register_model = $this->register_model->addUserFb($_POST['r_fname'], $_POST['r_email'], $_POST['r_pass'], $_POST['fb_id']);
			echo $register_model;
		}
	}

	//Loads all recipes
	function loadRecipes(){
		$this->load->model('recipes_model');
		$recipes_model = $this->recipes_model->getAllRecipes($_POST['uid']);
		echo $recipes_model;
	}

	//Loads single recipe detail based on recipe and user id's
	function getRecipe(){
		$this->load->model('recipes_model');
		$recipes_model = $this->recipes_model->getRecipe($_POST['uid'], $_POST['recipe_id']);
		echo $recipes_model;
	}

	//Gets the ingredients for recipe details based on recipe id
	function getRecipeIngredients(){
		$this->load->model('recipes_model');
		$recipes_model = $this->recipes_model->getRecipeIngredients($_POST['recipe_id']);
		echo $recipes_model;
	}

	//Delete recipe - based on user and recipe id
	function deleteRecipe(){
		$this->load->model('recipes_model');
		$recipes_model = $this->recipes_model->deleteRecipe($_POST['uid'], $_POST['recipe_id']);
		echo $recipes_model;
	}

	//Update recipe - validation
	function updateRecipe(){
		$this->load->model('recipes_model');
		$this->load->helper('form');
		$this->load->library('form_validation');


		$this->form_validation->set_rules('add_recipe_name', 'Recipe Name', 'required|trim|xss_clean');
		$this->form_validation->set_rules('add_description', 'Description', 'trim|xss_clean');
		$this->form_validation->set_rules('add_directions', 'Directions', 'required|trim|xss_clean');

		if ($this->form_validation->run() == FALSE)
		{
			$this->form_validation->set_message('add_recipe_name', 'Recipe Name Required');
			$this->form_validation->set_message('add_directions', 'Directions Required');
			echo json_encode(validation_errors());
		}
		else
		{
			$this->load->model('recipes_model');
			$recipes_model = $this->recipes_model->updateRecipe($_POST['uid'], $_POST['recipe_id'], $_POST['add_recipe_name'], $_POST['add_category'], $_POST['add_description'], $_POST["add_ingredients"], $_POST['add_directions']);
			echo $recipes_model;
		}
	}

	//Update recipe that has an image
	function updateRecipeImage(){
		$this->load->model('recipes_model');
		$this->load->helper('form');
		$this->load->library('form_validation');


		$this->form_validation->set_rules('add_recipe_name', 'Recipe Name', 'required|trim|xss_clean');
		$this->form_validation->set_rules('add_description', 'Description', 'trim|xss_clean');
		$this->form_validation->set_rules('add_directions', 'Directions', 'required|trim|xss_clean');

		if ($this->form_validation->run() == FALSE)
		{
			$this->form_validation->set_message('add_recipe_name', 'Recipe Name Required');
			$this->form_validation->set_message('add_directions', 'Directions Required');
			echo json_encode(validation_errors());
		}
		else
		{
			$this->load->model('recipes_model');
			$recipes_model = $this->recipes_model->updateRecipeImage($_POST['uid'], $_POST['recipe_id'], $_POST['add_recipe_name'], $_POST['add_category'], $_POST['add_description'], $_POST["add_ingredients"], $_POST['add_directions'], $_POST['add_image']);
			echo $recipes_model;
		}
	}

	//Add a recipe and validation
	function addRecipe(){

		$this->load->model('recipes_model');
		$this->load->helper('form');
		$this->load->library('form_validation');


		$this->form_validation->set_rules('add_recipe_name', 'Recipe Name', 'required|trim|xss_clean');
		$this->form_validation->set_rules('add_description', 'Description', 'trim|xss_clean');
		$this->form_validation->set_rules('add_directions', 'Directions', 'required|trim|xss_clean');

		if ($this->form_validation->run() == FALSE)
		{
			$this->form_validation->set_message('add_recipe_name', 'Recipe Name Required');
			$this->form_validation->set_message('add_directions', 'Directions Required');
			echo json_encode(validation_errors());
		}
		else
		{
			$this->load->model('recipes_model');
			$recipes_model = $this->recipes_model->addRecipe($_POST['uid'], $_POST['add_recipe_name'], $_POST['add_category'], $_POST['add_description'], $_POST['add_ingredients'], $_POST['add_directions']);
			echo $recipes_model;
		}
	}

	//Add a recipe that has an image and validation
	function addRecipeImage(){

		$this->load->model('recipes_model');
		$this->load->helper('form');
		$this->load->library('form_validation');


		$this->form_validation->set_rules('add_recipe_name', 'Recipe Name', 'required|trim|xss_clean');
		$this->form_validation->set_rules('add_description', 'Description', 'trim|xss_clean');
		$this->form_validation->set_rules('add_directions', 'Directions', 'required|trim|xss_clean');

		if ($this->form_validation->run() == FALSE)
		{
			$this->form_validation->set_message('add_recipe_name', 'Recipe Name Required');
			$this->form_validation->set_message('add_directions', 'Directions Required');
			echo json_encode(validation_errors());
		}
		else
		{
			$this->load->model('recipes_model');
			$recipes_model = $this->recipes_model->addRecipeImage($_POST['uid'], $_POST['add_recipe_name'], $_POST['add_category'], $_POST['add_description'], $_POST['add_ingredients'], $_POST['add_directions'], $_POST['add_image']);
			echo $recipes_model;
		}
	}
}

?>