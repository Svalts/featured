<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct(){

    	//Instantiates and configures facebook library
        parent::__construct();
        parse_str( $_SERVER['QUERY_STRING'], $_REQUEST );
        $CI = & get_instance();
        $CI->config->load("facebook",TRUE);
        $config = $CI->config->item('facebook');
        $this->load->library('Facebook', $config);
    }

    //Loads the home page
	public function index(){

		$this->load->model('recipes_model', '', true);

        // Try to get the user's id on Facebook
        $userId = $this->facebook->getUser();
 
        // If user is not yet authenticated, the id will be zero
        if($userId == 0){

            // Generate a login url
            $header_data['url'] = $this->facebook->getLoginUrl(array('scope'=>'email'));

        } else {

        	//If user is logged in, loads the user's favorites and populates logout button with FB logout
        	if($session_data = $this->session->userdata('logged_in')){
				$uid = $session_data['uid'];
				$favs = $this->recipes_model->getFavs($uid);
				$data['favs'] = $favs;
				$next = site_url() . "verify_login/logout";
	            $header_data['logout_url'] = $this->facebook->getLogoutUrl(array('next' => $next));
        	}else{
	            // Get user's data and print it
	            $user = $this->facebook->api('/me');
	            $this->session->set_flashdata('fb', $user);

	            if(!($this->session->flashdata('fb_register'))){
	            	redirect('verify_login/fb/', 'location');
	            }
	            
        	}

        }

        //Loads featured recipes for home page
		$featured_recipes = $this->recipes_model->getFeatured();

		//If user is logged in, loads the user's favorites
		if($session_data = $this->session->userdata('logged_in')){

			$uid = $session_data['uid'];

			$favs = $this->recipes_model->getFavs($uid);
			$data['favs'] = $favs;
		}

		$data['results'] = $featured_recipes;

		//Loads recipes tiles with feature recipes
		$recipes['tiles'] = $this->load->view('recipe_tile', $data, TRUE);

		//Checks to see if user is connecting with FB to populate the FB login url
		if(isset($header_data)){
			$this->load->view('header', $header_data);
		}else{
			$this->load->view('header');
		}
		
		$this->load->view('home_content', $recipes);
		$this->load->view('footer');
	}

	//Used to reopen modal panel after login/register action
	public function user(){
		
		$action = $this->input->post('user_action');
		$this->session->set_flashdata('user_action', $action);
		redirect('home');
	}
}
?>
