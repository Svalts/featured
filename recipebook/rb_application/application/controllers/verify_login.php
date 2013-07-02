<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Verify_Login extends CI_Controller {

	public function index(){
		
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->model('login_model', '', true);
		$this->load->model('user_model', '', true);
		$this->load->library('form_validation');

		//Form validation for login
		$this->form_validation->set_rules('login_email', 'Email', 'required|trim|xss_clean');
		$this->form_validation->set_rules('login_password', 'Password', 'required|trim|xss_clean|md5');

		if($this->form_validation->run() == FALSE){

			$this->form_validation->set_message('login_email', 'Email Required');
			$this->form_validation->set_message('login_password', 'Password Required');

			//Displays validation errors
			$this->session->set_flashdata('login_error', validation_errors());
			redirect('home', 'refresh');

		}else{

			$email = $this->input->post('login_email');
			$password = $this->input->post('login_password');

			//Verify existing user
			$result = $this->login_model->login($email, md5($password));

			//If user, login and start session
			if($result){

				$session_data = $this->session->userdata('logged_in');

				foreach($result as $r){
					$sess_array = array(
						'uid' => $r->user_id,
						'uname' => $r->username
						);
				}

				
				$this->session->set_userdata('logged_in', $sess_array);
				$session = $this->session->userdata('logged_in');

				$userData = array(
						"last_login" => now()
					);

				$this->user_model->updateLastLogin($session['uid'],$userData);

				redirect('home', 'refresh');

				return true;

			}else{

				$this->session->set_flashdata('login_error', 'Invalid Username or Password');
				redirect('home', 'refresh');

				return false;

			}

		}
	}

	//FB login function
	public function fb(){

		$this->load->helper('date');
		$this->load->model('login_model', '', true);
		$this->load->model('user_model', '', true);

		$user = $this->session->flashdata('fb');
		$fb_id = $user['id'];

		//Checks to see if FB id matches a current user
		$result = $this->login_model->fb_login($fb_id);

		//If FB user exists, log in and start session
		if($result){

				$session_data = $this->session->userdata('logged_in');

				foreach($result as $r){
					$sess_array = array(
						'uid' => $r->user_id,
						'uname' => $r->username,
						'fb' => $r->fb_id
						);
				}

				
				$this->session->set_userdata('logged_in', $sess_array);

				$session = $this->session->userdata('logged_in');

				$userData = array(
						"last_login" => now()
					);

				$this->user_model->updateLastLogin($session['uid'],$userData);

				redirect('home', 'refresh');

				return true;
		}else{
				//If user has not connected with FB, they will be asked to register and the FB id will be saved for
				//the next time they log in
				$this->session->set_flashdata('fb_register', $fb_id);
				redirect('home', 'location');
				return false;
		}

	}
	
	//Logs user out of the application and out of FB
	public function logout(){

    	$this->load->library('Facebook');
    	$this->facebook->destroySession();
		$this->session->unset_userdata('logged_in');
		$this->session->sess_destroy();
		
		//Redirects user back to homepage.
		$this->load->helper('url');
		redirect('home', 'refresh');
	}
}
?>
