<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller {

	function __construct(){

		parent::__construct();
		$this->load->helper('url', 'form');
	}

	function index(){

		$this->load->helper('form');
		$this->load->model('recipes_model', '', true);

		//GET search data
		$term = $this->input->get('q', TRUE);
		$this->session->set_userdata('search', $term);

		//DB query
		$result = $this->recipes_model->search($term);

		//Determines if user is logged in and their favorites
		if($session_data = $this->session->userdata('logged_in')){

			$uid = $session_data['uid'];

			$favs = $this->recipes_model->getFavs($uid);
			$data['favs'] = $favs;
		}

		//Determines if there are any matches to the search term
		//If so loads results  (recipe tiles) and the total
		if($result){

			$data['results'] = $result;

			$recipes['num'] = count($result);
			$recipes['tiles'] = $this->load->view('recipe_tile', $data, TRUE);

			$this->load->view('header');
			$this->load->view('filters');
			$this->load->view('results', $recipes);
			$this->load->view('footer');

		}else{

			$recipes['tiles'] = "<p>No Results Found</p>";
			$recipes['num'] = 0;

			$this->load->view('header');
			$this->load->view('filters');
			$this->load->view('results', $recipes);
			$this->load->view('footer');
		}


	}

	//Recipe search filter functionality
	function filter(){

		$this->load->helper('form');
		$this->load->model('recipes_model', '', true);

		//Create an array for the different search parameters
		$category_filters = array();
		$diet_filters = array();

		//Search term is being stored in the session
		//***********Not the best way to do this...will fix later**********
		$term = $this->session->userdata('search');

		$ings = $this->input->post('ingredient');

		$breakfast = $this->input->post('breakfast');
		$lunch = $this->input->post('lunch');
		$dinner = $this->input->post('dinner');
		$snack = $this->input->post('snack');
		$appetizer = $this->input->post('appetizer');
		$dessert = $this->input->post('dessert');

		$vegetarian = $this->input->post('vegetarian');
		$vegan = $this->input->post('vegan');
		$pescetarian = $this->input->post('pescetarian');

		//These if statements determine if the category/diet filters are selected
		//If a category/diet is selected it adds the value to the array created above
		if($breakfast){
			array_push($category_filters, $breakfast);
		}

		if($lunch){
			array_push($category_filters, $lunch);
		}

		if($dinner){
			array_push($category_filters, $dinner);
		}

		if($snack){
			array_push($category_filters, $snack);
		}

		if($appetizer){
			array_push($category_filters, $appetizer);
		}

		if($dessert){
			array_push($category_filters, $dessert);
		}

		if($vegetarian){
			array_push($diet_filters, $vegetarian);
		}

		if($vegan){
			array_push($diet_filters, $vegan);
		}

		if($pescetarian){
			array_push($diet_filters, $pescetarian);
		}


		//Determines which filters are selected and which DB query should be used
		if(count($category_filters) != 0 || count($diet_filters) != 0){

			if(count($category_filters) == 0){
				array_push($category_filters, 'no category', 'breakfast', 'lunch', 'dinner', 'snack', 'appetizer', 'dessert');
			}

			if(count($diet_filters) == 0){
				array_push($diet_filters, 'none', 'vegetarian', 'vegan', 'pescetarian');
			}

			if(empty($ings)){
				$result = $this->recipes_model->filter($category_filters, $diet_filters, $term);
			}else{
				// $result = $this->recipes_model->filter_ings($ings, $category_filters, $diet_filters, $term);
				$result = $this->recipes_model->filter($category_filters, $diet_filters, $term);
			}

		}else{

			$category_filters = array('no category', 'breakfast', 'lunch', 'dinner', 'snack', 'appetizer', 'dessert');
			$diet_filters = array('none', 'vegetarian', 'vegan', 'pescetarian');

			if(empty($ings)){
				$result = $this->recipes_model->filter($category_filters, $diet_filters, $term);
			}else{
				// $result = $this->recipes_model->filter_ings($ings, $category_filters, $diet_filters, $term);
				$result = $this->recipes_model->filter($category_filters, $diet_filters, $term);
			}
		}

		$data = array(
			'results' => $result
		);

		$filter_data = array(
			'ing_list' => $ings
		);

		$recipes['tiles'] = $this->load->view('recipe_tile', $data, TRUE);
		$recipes['num'] = count($result);

		$this->load->view('header');
		$this->load->view('filters', $filter_data);
		$this->load->view('results', $recipes);
		$this->load->view('footer');
	}

}	
?>