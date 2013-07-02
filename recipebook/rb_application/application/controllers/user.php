<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class User extends CI_Controller {

	function __construct(){

		parent::__construct();
		$this->load->helper('url', 'form');

	}

	function index(){

		//Checks to see if user is logged in
		//Could be used to redirect user, but didnt need to do that now
		if($this->session->userdata('logged_in')){

			// redirect($this->session->userdata('url'));
			redirect('home/index', 'refresh');


		}else{

			redirect('home/index', 'refresh');
		}

	}

	//Load user profile information
	function profile(){

		$this->load->helper('url');
		$this->load->helper('date');
		$this->load->model('user_model', '', true);
		$this->load->model('recipes_model', '', true);

		//Loads user profile base on user id in url
		$uid = $this->uri->segment(3);
		$session_data = $this->session->userdata('logged_in');
		$current_user = $session_data['uid'];

		$profile = $this->user_model->getProfile($uid);
		$following = $this->user_model->getNumFollowing($uid);
		$followers = $this->user_model->getNumFollowers($uid);
		$recipes = $this->user_model->getUserRecipes($uid);
		$recipe_favs = $this->recipes_model->getFavs($current_user);
		$favs = $this->user_model->getUserFavs($uid);
		$is_follower = $this->user_model->is_follower($uid, $current_user);

		$d['results'] = $recipes;
		$d['favs'] = $recipe_favs;
		$d1['results'] = $favs;
		$d1['favs'] = $recipe_favs;
		$d1['user_favs'] = true;

		$timestamp = (int)$profile[0]->last_login;
		$timezone = 'UM5';
		$daylight_saving = TRUE;

		$time = unix_to_human(gmt_to_local($timestamp, $timezone, $daylight_saving));

		$data = array(
				'profile' => $profile,
				'profile_id' => $uid,
				'following' => count($following),
				'followers' => count($followers),
				'num_recipes' => count($recipes),
				'is_follower' => $is_follower,
				'tiles' => $this->load->view('recipe_tile', $d, TRUE),
				'favs' => $this->load->view('recipe_tile', $d1, TRUE),
				'last_login' => $time
			);

		$this->load->view('header');
		$this->load->view('profile', $data);
		$this->load->view('footer');

	}

	//Allows logged in user to follow another user
	function follow(){

		$this->load->helper('url');
		$this->load->helper('date');
		$this->load->model('user_model', '', true);

		$uid = $this->input->post('follow');
		$session_data = $this->session->userdata('logged_in');
		$current_user = $session_data['uid'];

		$data = array(
			'user_id' => $uid,
			'follower_id' => $current_user,
			'date_added' => now()
			);

		$add_follower = $this->user_model->add_follower($data);

		redirect('user/profile/'. $uid);
	}

	//Unfollow functionality
	function unfollow(){

		$this->load->helper('url');
		$this->load->model('user_model', '', true);

		$uid = $this->input->post('unfollow');
		$session_data = $this->session->userdata('logged_in');
		$current_user = $session_data['uid'];

		$data = array(
			'user_id' => $uid,
			'follower_id' => $current_user
			);

		$add_follower = $this->user_model->delete_follower($data);

		redirect('user/profile/'. $uid);
	}

	//Populates news feed on the dashboard
	function dashboard(){

		if($this->session->userdata('logged_in')){

			$this->load->model('user_model', '', true);

			$session_data = $this->session->userdata('logged_in');
			$uid = $session_data['uid'];

			//Get all of the user's followers and the follower's recipes
			$getFollowers = $this->user_model->getFollowers($uid);
			$getFolRecipes = $this->user_model->getFollowerRecipes($uid);

			//Types the data a follower or a recipe
			foreach ($getFollowers as $f){
				$f->type = "follower";
			};

			foreach ($getFolRecipes as $f){
				$f->type = "recipe";
			};

			//Merges teh two arrays - followers and follower recipes
			$result = array_merge($getFollowers,$getFolRecipes);

			//Sorts the merged array by the date timestamp to order them from most recent
			usort($result, function($a, $b){
			    return $a->date_added < $b->date_added;
			});			

			$data['results'] = $result;
			
			$this->load->view('header');
			$this->load->view('dashboard', $data);
			$this->load->view('footer');

		}else{

			redirect('home/index', 'refresh');
		}
	}

	//Populates the user's recipes in the dashboard
	function recipes(){

		$this->load->helper('url');
		$this->load->model('recipes_model', '', true);
		$this->load->model('user_model', '', true);

		$session_data = $this->session->userdata('logged_in');
		$uid = $session_data['uid'];

		$recipes = $this->user_model->getUserRecipes($uid);
		$d['results'] = $recipes;

		$data['tiles'] = $this->load->view('recipe_tile', $d, TRUE);
		

		$this->load->view('header');
		$this->load->view('users_recipes', $data);
		$this->load->view('footer');

	}


}
?>