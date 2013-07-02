<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register_User extends CI_Controller {

	//Register user functionality
	public function index()
	{
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->model('register_model', '', true);
		$this->load->model('user_model', '', true);
		$this->load->library('form_validation');

		//Form validation
		$this->form_validation->set_rules('r_email', 'Email', 'required|trim|xss_clean|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('r_password', 'Password', 'required|trim|xss_clean|md5');
		$this->form_validation->set_rules('r_username', 'Username', 'required|trim|xss_clean|is_unique[users.username]|min_length[3]');

		if($this->form_validation->run() == FALSE){

			$this->form_validation->set_message('r_email', 'Email Required');
			$this->form_validation->set_message('r_password', 'Password Required');
			$this->form_validation->set_message('r_username', 'Unique Username Required');

			//Registration errors
			$this->session->set_flashdata('register_error', validation_errors());
			redirect('home', 'refresh');

		}else{

			$r_email = $this->input->post('r_email');
			$r_password = $this->input->post('r_password');
			$r_username = $this->input->post('r_username');

			//Determines if user is connecting with FB
			if($this->input->post('fb_register_id')){

				$r_fb_id = $this->input->post('fb_register_id');
				$data = array(
					'email' => $r_email,
					'password' => md5($r_password),
					'username' => $r_username,
					'fb_id' => $r_fb_id
				);
				
			}else{

				$data = array(
					'email' => $r_email,
					'password' => md5($r_password),
					'username' => $r_username,
				);
			}


				$this->register_model->registration($data);
				
				$uid = $this->db->insert_id();
				
				//Starts new session
				$sess_array = array(
					'uid' => $uid,
					'uname' => $r_username
				);
				
				$this->session->set_userdata('logged_in', $sess_array);
				$session = $this->session->userdata('logged_in');

				$userData = array(
						"last_login" => now()
					);

				$this->user_model->updateLastLogin($session['uid'],$userData);

				redirect('home', 'refresh');
		}
	}
}
?>
