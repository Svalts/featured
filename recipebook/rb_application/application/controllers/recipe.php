<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Recipe extends CI_Controller {

	//Loads detailed recipe information
	function details(){
		$this->load->helper('form');
		$this->load->model('recipes_model', '', true);

		$session = $this->session->userdata('logged_in');

		//Gets recipe id from the url segment
		$rid = $this->uri->segment(3);

		$result = $this->recipes_model->recipe_detail($rid);

		//Create array of ingredients for the recipe
		$ing_list = array();

		foreach($result as $r){
			array_push($ing_list, $r->ingredient);
		}

		if(empty($result)){

			$data['no_results'] = $result;

		}else{

			if($result[0]->user_id == $session['uid']){
				$data['users_recipe'] = TRUE;
			}

			$data['recipe'] = $result[0];
			$data['ings'] = $ing_list;
		}

		$this->load->view('header');
		$this->load->view('details', $data);
		$this->load->view('footer');

	}
	
	//Add recipe functionality
	function add_recipe(){
		$this->load->helper('form');
		$this->load->helper('date');
		$this->load->model('recipes_model', '', true);
		$this->load->library('form_validation');

		//Validation for add recipe form fields
		$this->form_validation->set_rules('recipe_name', 'Name', 'required|trim|xss_clean');
		$this->form_validation->set_rules('description', 'Description', 'required|trim|xss_clean');
		$this->form_validation->set_rules('directions', 'Directions', 'required|trim|xss_clean');
		$this->form_validation->set_rules('ingredients[]', 'Ingredients', 'required|trim|xss_clean');

		if($this->form_validation->run() == FALSE){

			$this->form_validation->set_message('recipe_name', 'Name Required');
			$this->form_validation->set_message('description', 'Description Required');
			$this->form_validation->set_message('ingredients[]', 'Ingredients Required');
			$this->form_validation->set_message('directions', 'Directions Required');

			//Shows earrors if validation fails
			$this->session->set_flashdata('add_errors', validation_errors());

			redirect('home', 'refresh');

		}else{

			//Determines if user is trying to upload an image
			if(!empty($_FILES['userfile']['name'])){

				$dir = './recipe_img';

				if(!is_dir($dir)){
					$upload_path = mkdir('./recipe_img', 0777);
				}

				//File upload configuration
				$config['upload_path'] = $dir;
				$config['allowed_types'] = 'jpg|jpeg|png';
				$config['max_size']	= '1000000';
				$config['max_width']  = '1500';
				$config['max_height']  = '1000';
				$config['remove_spaces'] = true;
				$config['file_name']  = uniqid();

				$this->load->library('upload', $config);

				if(!$this->upload->do_upload()){

					$this->session->set_flashdata('add_errors', 'There was a problem with your upload.');

					redirect('home', 'refresh');

				}else{

					$image_data = array('upload_data' => $this->upload->data());
					$img_name = $image_data['upload_data']['file_name'];
					$data['image_path'] = $img_name;
					$image_path = $image_data['upload_data']['full_path'];
					$this->imageResize($image_path);
				}
			}else{
				$data['image_path'] = "default_recipe_img.png";
			}

			$r_name = $this->input->post('recipe_name');
			$r_description = $this->input->post('description');
			$r_diet = $this->input->post('diet');
			$r_category = $this->input->post('category');
			$r_directions = $this->input->post('directions');
			$r_ingredients = $this->input->post('ingredients');

			//Was using this for base64 uploads - no longer needed - just for reference
			// $img = $this->input->post('add_img_data');
			// $img = str_replace('data:image/png;base64,', '', $img);
			// $img = str_replace(' ', '+', $img);
			// $data = base64_decode($img);
			// $img_name = uniqid() . '.png';
			// $img_file = 'recipe_img/' . $img_name;
			// $success = file_put_contents($img_file, $data);

			$data['name'] = $r_name;
			$data['description'] = $r_description;
			$data['diet_id'] = $r_diet;
			$data['category_id'] = $r_category;
			$data['directions'] = $r_directions;
			$data['date_added'] = now();

			$this->recipes_model->create_recipe($data);
			
			$session_data = $this->session->userdata('logged_in');
			
			$uid = $session_data['uid'];
				
			$rid = $this->db->insert_id();
			
			$recipe_array = array(
				'recipe_id' => $rid,
				'user_id' => $uid
			);
			
			$this->recipes_model->user_recipe($recipe_array);

			foreach($r_ingredients as $ing){
				$ings = array(
					'recipe_id' => $rid,
					'ingredient' => $ing
				);
				$this->recipes_model->add_recipe_ings($ings);
			}
			
			redirect('home');
				
		
		}
	}

	//Edit recipe functionality
	function edit_recipe(){

		$this->load->helper('form');
		$this->load->model('recipes_model', '', true);
		$this->load->library('form_validation');

		$this->form_validation->set_rules('edit_recipe_name', 'Name', 'required|trim|xss_clean');
		$this->form_validation->set_rules('edit_description', 'Description', 'required|trim|xss_clean');
		$this->form_validation->set_rules('edit_directions', 'Directions', 'required|trim|xss_clean');
		$this->form_validation->set_rules('edit_ingredients[]', 'Ingredients', 'required|trim|xss_clean');

		if($this->form_validation->run() == FALSE){

			$this->form_validation->set_message('edit_recipe_name', 'Name Required');
			$this->form_validation->set_message('edit_description', 'Description Required');
			$this->form_validation->set_message('edit_ingredients[]', 'Ingredients Required');
			$this->form_validation->set_message('edit_directions', 'Directions Required');

			$this->session->set_flashdata('edit_errors', validation_errors());

			redirect('home', 'refresh');

		}else{


			if(!empty($_FILES['edit_userfile']['name'])){

				$dir = './recipe_img';

				if(!is_dir($dir)){
					$upload_path = mkdir('./recipe_img', 0777);
				}

				$config1['upload_path'] = $dir;
				$config1['allowed_types'] = 'jpg|jpeg|png';
				$config1['max_size']	= '1000000';
				$config1['max_width']  = '1500';
				$config1['max_height']  = '1000';
				$config1['remove_spaces'] = true;
				$config1['file_name']  = uniqid();

				$this->load->library('upload', $config1);

				if(!$this->upload->do_upload('edit_userfile')){

					$this->session->set_flashdata('edit_errors', 'There was a problem with your upload.');

					redirect('home', 'refresh');

				}else{

					$image_data = array('upload_data' => $this->upload->data());
					$img_name = $image_data['upload_data']['file_name'];
					$data['image_path'] = $img_name;
					$image_path = $image_data['upload_data']['full_path'];
					$this->imageResize($image_path);
				}
			}else{
				$data['image_path'] = "default_recipe_img.png";
			}

			$session_data = $this->session->userdata('logged_in');
			
			$uid = $session_data['uid'];

			$rid = $this->input->post('edit_recipe_id');
			$r_name = $this->input->post('edit_recipe_name');
			$r_description = $this->input->post('edit_description');
			$r_diet = $this->input->post('edit_diet');
			$r_category = $this->input->post('edit_category');
			$r_directions = $this->input->post('edit_directions');
			$r_ingredients = $this->input->post('edit_ingredients');

			$data['name'] = $r_name;
			$data['description'] = $r_description;
			$data['diet_id'] = $r_diet;
			$data['category_id'] = $r_category;
			$data['directions'] = $r_directions;



			if($this->recipes_model->verifyUserRecipe($rid,$uid)){

				$this->recipes_model->update_recipe($rid, $data);

				$d1 = array(
						"recipe_id" => $rid
					);

				$this->recipes_model->update_ings($d1);

				foreach($r_ingredients as $ing){

					$ings = array(
						'recipe_id' => $rid,
						'ingredient' => $ing
					);

					$this->recipes_model->add_recipe_ings($ings);
				}
			
				redirect(base_url()."recipe/details/" .$rid);

			}
		}
	}

	//Resizes the uploaded recipe image
	function imageResize($image_path){

		$this->load->library('image_lib');

		//New image configuration
		//Resizes to be 230x200 but maintain the ration
		$config['source_image']	= $image_path;
		$config['maintain_ratio'] = TRUE;
		$config['width']	 = 230;
		$config['height']	= 200;
		$config['master_dim'] = 'width';

		$this->image_lib->initialize($config); 
		$this->image_lib->resize();
	}
}
?>