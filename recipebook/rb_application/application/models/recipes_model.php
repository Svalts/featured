<?php
Class Recipes_Model extends CI_Model{

	//Gets featured recipes for home page
	//Gets recipes with ratings of 4 or 5 and only returns 8 results
	function getFeatured(){

		$this->db->select('*');
		$this->db->from('recipes as r');
		$this->db->join('user_recipes as ur', 'r.recipe_id=ur.recipe_id');
		$this->db->where('r.rating', 5);
		$this->db->or_where('r.rating', 4);
		$this->db->limit(8);

		$query = $this->db->get();

		return $query->result();
	}

	//Searches recipe names and ingredients to populate the autocomplete
	function autocomplete($search){
		//work in progress
		$this->db->select('name as name');
		$this->db->from('recipes as r');
		$this->db->distinct();
		$this->db->like('r.name', $search);
		$this->db->group_by('r.name');
		$this->db->limit(8);
		$query1 = $this->db->get()->result();

		$this->db->select('ingredient as name');
		$this->db->from('ingredients as i');
		$this->db->distinct();
		$this->db->like('i.ingredient', $search);
		$this->db->group_by('i.ingredient');
		$this->db->limit(8);
		$query2 = $this->db->get()->result();

		$query = array_merge($query1, $query2);

		$json = json_encode($query);

		return $json;
	}

	//Searches recipes and ingredients for any match to the search term
	function search($term){
		$this->db->select('*');
		$this->db->from('recipes as r');
		$this->db->join('ingredients as i', 'r.recipe_id=i.recipe_id');
		$this->db->like('r.name', $term);
		$this->db->or_like('i.ingredient', $term);
		$this->db->group_by('r.name');

		$query = $this->db->get();

		return $query->result();
	}

	//Filters results based on term, and category and diet
	function filter($category_filters, $diet_filters, $term){

		$temp = implode("','", $category_filters);
		$temp2 = implode("','", $diet_filters);
		$query = $this->db->query("SELECT * FROM recipes AS r JOIN ingredients AS i ON r.recipe_id=i.recipe_id JOIN category AS c ON c.category_id=r.category_id JOIN diets AS d ON d.diet_id=r.diet_id WHERE r.name LIKE '%" . $term . "%' OR i.ingredient LIKE '%" . $term . "%' AND c.cat_name IN ('" . $temp . "') AND d.diet_name IN ('" . $temp2 . "') GROUP BY r.name");

		return $query->result();
	}

	//Filters results when ingredients are added to filter by
	//*********Work in progress*********
	function filter_ings($ings, $category_filters, $diet_filters, $term){

		$temp = implode("','", $category_filters);
		$temp2 = implode("','", $diet_filters);
		$temp3 = implode("'AND'", $ings);
		$query = $this->db->query("SELECT * FROM recipes AS r JOIN ingredients AS i ON r.recipe_id=i.recipe_id JOIN category AS c ON c.category_id=r.category_id JOIN diets AS d ON d.diet_id=r.diet_id WHERE r.name LIKE '%" . $term . "%' OR i.ingredient LIKE '%" . $term . "%' AND c.cat_name IN ('" . $temp . "') AND d.diet_name IN ('" . $temp2 . "') AND i.ingredient IN ('" . $temp3 . "') GROUP BY r.name");

		return $query->result();
	}

	//Returns detailed recipe information
	function recipe_detail($r_id){

		$this->db->select('*');
		$this->db->from('recipes as r');
		$this->db->join('ingredients as i', 'r.recipe_id=i.recipe_id');
		$this->db->join('user_recipes as ur', 'r.recipe_id=ur.recipe_id');
		$this->db->join('users as u', 'ur.user_id=u.user_id');
		$this->db->where('r.recipe_id', $r_id);

		$query = $this->db->get();

		return $query->result();
	}
	
	//Add new recipe to DB
	function create_recipe($data){
	
		$this->db->insert('recipes', $data);
	}

	//Update a user's recipe
	function update_recipe($rid,$data){

		$this->db->where('recipe_id', $rid);
		$this->db->update('recipes', $data);

	}

	//Verify's the recipe belongs to current user - for delete and edit options
	function verifyUserRecipe($rid,$uid){

		$this->db->select('*');
		$this->db->from('user_recipes');
		$this->db->where('recipe_id', $rid);
		$this->db->where('user_id', $uid);

		$query = $this->db->get();

		return $query->result();
	}
	
	//Pairs a recipe and user in the user_recipes table
	function user_recipe($recipe_array){
	
		$this->db->insert('user_recipes', $recipe_array);
	}

	//Update ingredients
	function update_ings($data){

		$this->db->delete('ingredients', $data);
	}

	//Add ingredients from a new recipe to ingredients table
	function add_recipe_ings($ings){
		
		$this->db->insert('ingredients', $ings);
	}


	//Loads the user's favorites based on user id
	function getFavs($uid){

		$this->db->select('*');
		$this->db->from('favorites as f');
		$this->db->where('user_id', $uid);

		$query = $this->db->get();

		return $query->result();
	}

	//Adds a new user favorite
	function addFavorite($fav_data){
		
		$this->db->insert('favorites', $fav_data);
	}

	//Removes a user favorite
	function removeFavorite($fav_data){

		$this->db->delete('favorites', $fav_data);
	}

	//Adds to the total times a recipe is favorited
	function addFavTotal($rid){
		
		$this->db->where('recipe_id', $rid);
		$this->db->set('favorites', 'favorites+1', FALSE);
		$this->db->update('recipes');
	}

	//Subtracts from total times favorited
	function subFavTotal($rid){
		
		$this->db->where('recipe_id', $rid);
		$this->db->set('favorites', 'favorites-1', FALSE);
		$this->db->update('recipes');
	}

	//Delete recipe form user recipes table
	function deleteRecipe($data){

		$this->db->delete('user_recipes', $data);

		$d2 = array(
				"recipe_id" => $data['recipe_id']
			);

		$this->deleteFavs($d2);		
	}

	//Deletes the recipe entry in the recipes table
	function deleteRecipeEntry($data){

		$this->db->delete('recipes', $data);

	}

	//Deletes the ingredients associated with a recipe
	function deleteIngs($data){

		$this->db->delete('ingredients', $data);
		$this->deleteRecipeEntry($data);
	}

	//Deletes the favorites associated with a deleted recipe
	function deleteFavs($data){

		$this->db->delete('favorites', $data);
		$this->deleteIngs($data);

	}

}
?>