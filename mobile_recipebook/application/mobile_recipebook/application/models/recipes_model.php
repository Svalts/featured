<?php
Class Recipes_Model extends CI_Model{

	//Returns all user's recipe - list view
	function getAllRecipes($uid){

		$this->db->select('*');
		$this->db->from('user_recipes as ur');
		$this->db->join('recipes as r', 'ur.recipe_id=r.recipe_id');
		$this->db->join('category as c', 'r.category_id=c.category_id');
		$this->db->where('user_id', $uid);

		$query = $this->db->get();

		return json_encode($query->result());
	}

	//Adds new recipe and calls function to assign the user to the recipe
	function addRecipe($uid, $r_name, $r_cat, $r_desc, $r_ing, $r_dir){

		$data = array(
			'name' => $r_name,
			'category_id' => $r_cat,
			'description' => $r_desc,
			'directions' => $r_dir
		);

		$this->db->insert('recipes', $data);

		$recipe_id = $this->db->insert_id();

		$userRecipeData = array(
			'user_id' => $uid,
			'recipe_id' => $recipe_id
		);

		foreach ($r_ing as $ing){
			$ingData = array(
				'ingredient' => $ing,
				'recipe_id' => $recipe_id
			);
			$this->db->insert('ingredients', $ingData);
		};

		$this->assignRecipeUser($userRecipeData);
	}

	//Adds new recipe with an image and calls function to assign the user the recipe
	function addRecipeImage($uid, $r_name, $r_cat, $r_desc, $r_ing, $r_dir, $r_img){

		$data = array(
			'name' => $r_name,
			'category_id' => $r_cat,
			'description' => $r_desc,
			'directions' => $r_dir,
			'image' => $r_img
		);

		$this->db->insert('recipes', $data);

		$recipe_id = $this->db->insert_id();

		$userRecipeData = array(
			'user_id' => $uid,
			'recipe_id' => $recipe_id
		);

		foreach ($r_ing as $ing){
			$ingData = array(
				'ingredient' => $ing,
				'recipe_id' => $recipe_id
			);
			$this->db->insert('ingredients', $ingData);
		};

		$this->assignRecipeUser($userRecipeData);
	}

	//Assigns the user to new recipe for the user_recipes table
	function assignRecipeUser($data){

		$this->db->insert('user_recipes', $data);

	}

	//Gets data for a single recipe
	function getRecipe($uid, $recipe_id){

		$this->db->select('*');
		$this->db->from('user_recipes as ur');
		$this->db->join('recipes as r', 'ur.recipe_id=r.recipe_id');
		$this->db->join('category as c', 'r.category_id=c.category_id');
		$this->db->where('user_id', $uid);
		$this->db->where('r.recipe_id', $recipe_id);

		$query = $this->db->get();
		
		return json_encode($query->result());
	}

	//Gets ingredients for an individual recipe - from ingredients table
	function getRecipeIngredients($recipe_id){
		$this->db->select('*');
		$this->db->from('recipes as r');
		$this->db->join('ingredients as i', 'i.recipe_id=r.recipe_id');
		$this->db->where('r.recipe_id', $recipe_id);

		$query = $this->db->get();
		
		return json_encode($query->result());
	}

	//Deletes single recipe from user_recipes table - calls to remove actual recipe and ingredients
	function deleteRecipe($uid, $recipe_id){

		$this->db->where('user_id', $uid);
		$this->db->where('recipe_id', $recipe_id);
		$del = $this->db->delete('user_recipes');

		$this->removeIngredients($recipe_id);
		$this->removeRecipeEntry($recipe_id);

		return (bool)$del; 

	}

	//Deletes actual recipe from recipes table
	function removeRecipeEntry($recipe_id){

		$this->db->where('recipe_id', $recipe_id);
		$this->db->delete('recipes');

	}

	//Deletes single recipe ingredients from ingredients table
	function removeIngredients($recipe_id){

		$this->db->where('recipe_id', $recipe_id);
		$this->db->delete('ingredients');
	}

	//Updates a single recipe
	function updateRecipe($uid, $recipe_id, $r_name, $r_cat, $r_desc, $r_ing, $r_dir){

		$data = array(
			'name' => $r_name,
			'category_id' => $r_cat,
			'description' => $r_desc,
			'directions' => $r_dir
		);

		$this->db->where('user_id', $uid);
		$this->db->where('r.recipe_id', $recipe_id);
		$this->db->update('recipes r join user_recipes ur on r.recipe_id=ur.recipe_id', $data);

		$this->updateIngredients($r_ing, $recipe_id);

		return $this->db->affected_rows();
	}

	//Updates a single recipe with an image
	function updateRecipeImage($uid, $recipe_id, $r_name, $r_cat, $r_desc, $r_ing, $r_dir, $r_img){

		$data = array(
			'name' => $r_name,
			'category_id' => $r_cat,
			'description' => $r_desc,
			'directions' => $r_dir,
			'image' => $r_img
		);

		$this->db->where('user_id', $uid);
		$this->db->where('r.recipe_id', $recipe_id);
		$this->db->update('recipes r join user_recipes ur on r.recipe_id=ur.recipe_id', $data);

		$this->updateIngredients($r_ing, $recipe_id);

		return $this->db->affected_rows();
	}

	//Updates a single recipe ingredients - ingredients table
	function updateIngredients($r_ing, $recipe_id){

		$this->db->where('recipe_id', $recipe_id);
		$del = $this->db->delete('ingredients');

		foreach ($r_ing as $ing){
			$ingData = array(
				'ingredient' => $ing,
				'recipe_id' => $recipe_id
			);
			$this->db->insert('ingredients', $ingData);
		};
	}

	//Uploads base64 photo to uploads folder when a user shares via facebook
	function uploadPhoto($image){

		$dir = './uploads/';

		if(!is_dir($dir)){
			$upload_path = mkdir('./uploads/', 0777);
		}

		$config['upload_path'] = $dir;
		$config['allowed_types'] = 'jpg|jpeg';
		$config['max_size']	= '1000000';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';
		$config['remove_spaces'] = true;
		$config['file_name']  = uniqid();

		$this->load->library('upload', $config);

		$img = $image;
		$img_name = uniqid();
		$img = str_replace('data:image/jpeg;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$file = $dir . $img_name . '.jpg';
		$success = file_put_contents($file, $data);

		return json_encode($img_name);
	}


}



?>