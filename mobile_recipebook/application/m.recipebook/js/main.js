/*
Project RecipeBook
authors: Brad Cerny and Simon Figueroa
February 2013
*/

//Global variable used to pass data to Facebook JS
var recipeObject = {},
	base_url = "http://localhost:8888/m.recipebook/",
	ajax_url = "/mobile_recipebook/index.php?/ajax/";

(function($){

	var fname,
	uid,
	login_email,
	login_password,
	temp_recipe_id,
	add_name,
	add_category,
	add_description,
	add_ingredients,
	add_directions,
	add_cat_label,
	add_image,
	page = $('html'),
	width = $(window).width();

	//Verify login with database using AJAX
	//Loads new view and stores name and user id in variables
	page.on('click', '#login', function(e){
		e.preventDefault();

		var email = $("#email").val(),
			password = $("#password").val();

			$.ajax({
				url: ajax_url + "login",
				type: 'POST',
				data: {email: email,
					   password: password},
				dataType: 'json',
				success: function(response){

					if(response){
						//clears local storage if exists(used if user goes back to sign up after trying FB)
						delete localStorage.fb_id;
						fname = response[0].fname;
						uid = response[0].user_id;

						//setup local storage and store user id
						localStorage.uid = uid;
						localStorage.fname = fname;
						localStorage.loggedIn = 1;

						$.mobile.changePage('list_view.html');
						
					}else{
						alert('Email or Password Invalid.');
					}
				},
				error: function(xhr, statusText, errorThrown){
					alert(errorThrown);
				}
			});
	});

	//Loads sign up page, clears local storage in case user tried to login thru FB and then decides not to
	//Stores values of fields in case user needs to sign up
	page.on('click', '#sign_up', function(){
		login_email = $('#email').val();
		login_password = $('#password').val();
		$.mobile.changePage('signup.html');
		localStorage.clear();
	});

	//Populates sign up form fields if user had entered them at the login then clicked sign-up
	page.on('pageinit', '#signup', function(){
		$('#r_email').val(login_email);
		$('#r_password').val(login_password);
	});


	//Uses AJAX to add a user to the database
	page.on('click', '#join', function(){
		var fname = $('#r_fname').val(),
			email = $('#r_email').val(),
			password = $('#r_password').val(),
			confirm = $('#r_confirm').val();

			
		//If the user has connected via FB the user info will be added to the DB
		if(localStorage.fb_id){
			$.ajax({
				url: ajax_url + "addUserFb",
				type: 'POST',
				data: {r_fname: fname, 
					r_email: email, 
					r_pass: password,
					r_confirm: confirm,
					fb_id: localStorage.fb_id},
				dataType: 'json',
				success: function(response){
					//response returns <p> of errors from the server
					//errors are displayed in a div within the html page
					if(response.id){
						$.mobile.changePage('list_view.html');
					}else if(response){
						$("#form_errors").html(response);
					}
				},
				error: function(xhr, statusText, errorThrown){
					alert(errorThrown);
				}
			});
		}else{
			//Adds user that is not connected via FB
			$.ajax({
				url: ajax_url + "addUser",
				type: 'POST',
				data: {r_fname: fname, 
					r_email: email, 
					r_pass: password,
					r_confirm: confirm},
				dataType: 'json',
				success: function(response){
					//response returns <p> of errors from the server
					//errors are displayed in a div within the html page
					if(response.id){

						uid = response.id;

						//setup local storage and store user id
						localStorage.uid = uid;
						localStorage.fname = fname;
						localStorage.loggedIn = 1;

						$.mobile.changePage('list_view.html');

					}else if(response){
						$("#form_errors").html(response);
					}
				},
				error: function(xhr, statusText, errorThrown){
					alert(errorThrown);
				}
			});

		}
	});

	//Redirects user to home page if they cancel sign up
	page.on('click', '#cancel_signup', function(e){
		e.preventDefault();
		window.location.replace(base_url);
	});

	page.on('click', '.home_link', function(e){
		e.preventDefault();
		window.location.replace(base_url);
	});

	//Calls function to dynamically generate details view
	page.on('click', '.list_item', function(e){
		e.preventDefault();
		temp_recipe_id = $(this).attr('data-id');
		localStorage.tempRecipeId = temp_recipe_id;
	});

	////////////////// NAV FUNCTIONS ///////////////////////
	page.on('click', '#home_btn', function(){
		$.mobile.changePage('list_view.html');
		add_name = "";
		add_category ="";
		add_description = "";
		add_ingredients = [];
		add_directions = "";
		add_cat_label = "";
		add_image = "";		
	});

	page.on('click', '#addRecipeBtn', function(e){
		e.preventDefault;
		$.mobile.changePage('addrecipe_one.html');
		$('.wrapper').trigger('create');
	});

	page.on('click', '#myRecipesBtn', function(){
		$.mobile.changePage('list_view.html');
	});

	//Logout user and clear local storage
	//FB Logout is done thru facebook JS
	page.on('click', '#logoutBtn', function(){
		localStorage.clear();
		window.location.replace(base_url);
	});

	//////////////////////////////////////////////////////////


	//Redirects user to home page if they are not logged in
	if(localStorage.getItem('loggedIn') != '1' && window.location.href != base_url && window.location.href != base_url + "signup.html"){
		window.location.replace(base_url);
	};

	//Runs on list view page load
	//Gets user info and stores in local storage and loads list items
	page.on('pageinit', '#list_view', function(){

		if(fb_id = localStorage.fb_id){
			$.ajax({
				url: ajax_url + "getUserInfo",
				type: 'POST',
				data: {fb_id: fb_id},
				dataType: 'json',
				success: function(response){
					localStorage.uid = response[0].user_id;
					localStorage.loggedIn = 1;
					uid = localStorage.getItem('uid');
					loadItems();
				},
				error: function(xhr, statusText, errorThrown){
					alert(errorThrown);
				}
			});
		}else{
			loadItems();
		}
	});

	//Uses tempRecipeId to load recipe detail page
	page.on('pageinit', '#details', function(){
		temp_recipe_id = localStorage.getItem('tempRecipeId');
		loadRecipeDetail();
	});

	//Edit recipe - prepopulates fields with recipe data
	page.on('click', '#edit_btn', function(){
		var recipe_id = localStorage.getItem('tempRecipeId');

		$.ajax({
			url: ajax_url + "getRecipe",
			type: "POST",
			data: {uid: uid,
					recipe_id: recipe_id},
			dataType: 'JSON',
			success: function(response){

				$.ajax({
					url: ajax_url + "getRecipeIngredients",
					type: "POST",
					data: {recipe_id: recipe_id},
					dataType: "JSON",
					success: function(response){
						var i = 0,
							newData = "";
						//creates ingredient fields based on number of ingredients
						$(response).each(function(){
							newData += '<input type="text" placeholder="Ingredient (ex: 1c flour)" class="edit_ingredient" value="' + response[i].ingredient + '"/>';
							i++;
						});
						$('#edit_ingredients').append(newData).trigger('create');
					},
					error: function(xhr, statusText, errorThrown){
						alert(errorThrown);
					}					
				});

				$('#edit_name').val(response[0].name);
				$('#edit_description').val(response[0].description);
				$('#edit_directions').val(response[0].directions);

			},
			error: function(xhr, statusText, errorThrown){
				alert(errorThrown);
			}			
		});
	});

	//Update click function - stores new values in variables used by updateRecipe()
	page.on('click', '#update_btn', function(e){

		add_name = $('#edit_name').val();
		add_category = $('#edit_category').val();
		add_description = $('#edit_description').val();
		add_directions = $('#edit_directions').val();

		ing_value = [];
		$('.edit_ingredient').each(function(){
			if($(this).val() != ""){
				ing_value.push($(this).val());
			}
		});
		add_ingredients = ing_value;

		updateRecipe();
	});

	//Delete recipe function
	page.on('click', '#delete_btn', function(){

		var recipe_id = localStorage.getItem('tempRecipeId');

		$.ajax({
			url: ajax_url + "deleteRecipe",
			type: 'POST',
			data: {uid: uid,
					recipe_id: recipe_id},
			dataType: 'JSON',
			success: function(response){
				$.mobile.changePage('list_view.html');
			},
			error: function(xhr, statusText, errorThrown){
				alert(errorThrown);
			}
		});
	});

	//Add ingredients button on edit page - prevents excess amount of ingredients - checks that
	// ingredient has been added before adding additional fields
	page.on('click', '#edit_add_ingredients_btn', function(){
		var empty = true;
		var ing_form = $(this).parents().find('#edit_ingredients'),
			new_field = '<input type="text" name="edit_ingredient" class="edit_ingredient" placeholder="Ingredient (ex: 1c flour)"/>';

			$('.edit_ingredient').each(function(){
				if($(this).val() == ""){
					empty = true;
				}else{
					empty = false;
				}
			});

		if(empty == false){
			ing_form.append(new_field).trigger('create');
		}
	});


// ///////////////////ADD RECIPE FUNCTIONS /////////////////////////
	//Checks for ingredients before adding additional fields
	page.on('click', '#add_ingredients_btn', function(){
		var empty = true;
		var ing_form = $(this).parents().find('#ingredients_form'),
			new_field = '<input type="text" name="r_ingredients" class="r_ingredients" placeholder="Ingredient (ex: 1c flour)"/>';

			$('.r_ingredients').each(function(){
				if($(this).val() == ""){
					empty = true;
				}else{
					empty = false;
				}
			});

		if(empty == false){
			ing_form.append(new_field).trigger('create');
		}
	});

	page.on('change', '#r_category', function(){
		add_cat_label = $('#r_category option:selected').attr('name');
	});

	page.on('click', '#next_btn_one', function(){
		add_name = $('#r_name').val();
		add_category = $('#r_category').val();
	});

	page.on('click', '#next_btn_two', function(){
		add_description = $('#r_description').val();
	});

	page.on('click', '#next_btn_three', function(){
		ing_value = [];
		$('.r_ingredients').each(function(){
			if($(this).val() != ""){
				ing_value.push($(this).val());
			}
		});
		add_ingredients = ing_value;
	});

	page.on('click', '#next_btn_four', function(){
		add_directions = $('#r_directions').val();
	});

	page.on('pageinit', '#one', function(){
		$('#r_name').val(add_name);
	});

	page.on('pageinit', '#two', function(){
		$('#r_description').val(add_description);
	});

	//Loads ingredients in separate input fields
	page.on('pageinit', '#three', function(){
		var ing_form = $(this).parents().find('#ingredients_form');

		if(!add_ingredients || add_ingredients.length < 1){
			ing_form.append('<input type="text" name="r_ingredients" class="r_ingredients" placeholder="Ingredient (ex: 1c flour)"/>').trigger('create');
		}else{
			$(add_ingredients).each(function(){
				var new_field = '<input type="text" name="r_ingredients" class="r_ingredients" placeholder="Ingredient (ex: 1c flour)" value="' + this + '"/>';
				ing_form.append(new_field).trigger('create');
			});
		}
	});

	page.on('pageinit', '#four', function(){
		$('#r_directions').val(add_directions);
	});

	//Creates a review page based on user input that has been stored in variables
	page.on('pageinit', '#five', function(){
		var i = 0;
		var listData = "";
		$(add_ingredients).each(function(){
			listData += '<li>' + add_ingredients[i] + '</li>';
			i++;
		});

		newData = '<p><span>Recipe Name: </span>' + add_name + '</p><p><span>Category: </span>' + add_cat_label + '<p><span>Description: </span>' + add_description + '</p><p><span>Ingredients: </span><ul>' + listData + '</ul></p><p><span>Directions: </span>' + add_directions + '</p>';
		$('#review_list').append(newData);
	});

	//Runs addRecipe function
	page.on('click', '#add_finish', function(e){
		e.preventDefault();
		addRecipe();
	});

	//File input for add recipe - loads file and checks for file type
	page.on('change', '#userfile', function(){
		if(this.files && this.files[0] && ("image/jpeg" || "image/png" || "image/jpg")){

			var reader = new FileReader();

			reader.onload = function(e){
				add_image = e.target.result;
			}
			reader.readAsDataURL(this.files[0]);
			
		}
	});

	//File input for edit recipe 
	page.on('change', '#update_file', function(){
		if(this.files && this.files[0] && ("image/jpeg" || "image/png" || "image/jpg")){

			var reader = new FileReader();

			reader.onload = function(e){
				add_image = e.target.result;
			}
			reader.readAsDataURL(this.files[0]);
			
		}
	});


////////////////////////////////////////////////////////////////////////

	//Dynamically creates list of user recipes based on user id variable
	function loadItems(){

			uid = localStorage.getItem('uid');
			$('.wrapper').trigger('refresh');
			$.ajax({
				url: ajax_url + "loadRecipes",
				type: 'POST',
				data: {uid: uid },
				dataType: 'json',
				success: function(response){
					//Determines if the user has recipes. If not displays a message
					//Dynamic content - list items based on database.
					if(response.length != 0){

						var listData = '';
						var newData = '';
						var i = 0;

						$(response).each(function(){
							var image = "";
							//Assigns a default image if user image is non-existent
							if(this.image == null || this.image == ""){
								image = "img/lg_default_img.png";
							}else{
								image = this.image;
							}

							listData +='<li class="list_item" data-id="' + response[i].recipe_id + '"><a href="details.html"><img src="' + image + '" /><h3>' + this.name + '</h3><p>' + this.cat_name + '</p></a></li>';
							i++;
						});

						$("#list_items").append(listData);
						$("#list_items").listview('refresh');

					}else{
						var temp = "<p>You have no recipes. Add one by clicking the button below.</p>";
						$('#list_items').html(temp);
					}

				},
				error: function(xhr, statusText, errorThrown){
					alert(errorThrown);
				}
			});
		};

	//Dynamically create the detail recipe view
	//Uses user id variable and recipe id from data-id in the list item to make an AJAX call
	function loadRecipeDetail(){

		temp_recipe_id = localStorage.getItem('tempRecipeId');
		uid = localStorage.getItem('uid');
		$('.wrapper').trigger('refresh');
		var listData = "";
		$.ajax({
			url: ajax_url + "getRecipeIngredients",
			type: "POST",
			data: {recipe_id: temp_recipe_id},
			dataType: "JSON",
			success: function(response){

				var i = 0;
				$(response).each(function(){
					listData += '<li>' + response[i].ingredient + '</li>';
					i++;
				});

				$.ajax({
					url: ajax_url + "getRecipe",
					type: 'POST',
					data: {uid: uid, 
						recipe_id: temp_recipe_id},
					dataType: 'json',
					success: function(response){

					recipeObject = response;
					localStorage.tempRecipeId = response[0].recipe_id;

					var newData = '';
					var image = "";

					//Assigns a default image if user image is non-existent
					if(response[0].image == null || response[0].image == ""){
						image = "img/lg_default_img.png";
					}else{
						image = response[0].image;
					}

					//Determines if user is using a phone or tablet - if tablet the recipe detail accordions will default to open
					if(width <= 700) {
						newData = '<img src="' + image + '"/><p id="fb_share"><img src="img/fb_share.png"/></p><h2 class="recipe_name">' + response[0].name + '</h2><h2 class="category">' + response[0].cat_name + '</h2><div class="accordion"><div data-role="collapsible" data-iconpos="right" data-inset="false"><h3>Desciption</h3><p>' + response[0].description + '</p></div><div data-role="collapsible" data-iconpos="right" data-inset="false"><h3>Ingredients</h3><ul>' + listData + '</ul></div><div data-role="collapsible" data-iconpos="right" data-inset="false"><h3>Directions</h3><p>' + response[0].directions + '</p></div></div><div class="btn_holder"><p class="edit_btn button"><a href="editrecipe.html" data-id="' + response[0].recipe_id + '"data-role="button" data-theme="a" id="edit_btn">Edit Recipe</a></p><p class="delete_btn button"><a href="conf_del_dialog.html" data-id="' + response[0].recipe_id + '"data-role="button" data-rel="dialog" data-transition="pop">Delete Recipe</a></p></div>';
					}
					else {
						newData = '<img src="' + image + '"/><p id="fb_share"><img src="img/fb_share.png"/></p><h2 class="recipe_name">' + response[0].name + '</h2><h2 class="category">' + response[0].cat_name + '</h2><div class="accordion"><div data-role="collapsible" data-iconpos="right" data-inset="false" data-collapsed="false"><h3>Desciption</h3><p>' + response[0].description + '</p></div><div data-role="collapsible" data-iconpos="right" data-inset="false" data-collapsed="false"><h3>Ingredients</h3><ul>' + listData + '</ul></div><div data-role="collapsible" data-iconpos="right" data-inset="false" data-collapsed="false"><h3>Directions</h3><p>' + response[0].directions + '</p></div></div><div class="btn_holder"><p class="edit_btn button"><a href="editrecipe.html" data-id="' + response[0].recipe_id + '"data-role="button" data-theme="a" id="edit_btn">Edit Recipe</a></p><p class="delete_btn button"><a href="conf_del_dialog.html" data-id="' + response[0].recipe_id + '"data-role="button" data-rel="dialog" data-transition="pop">Delete Recipe</a></p></div>';
					}

					$("#details_page").append(newData);
					$("#details_page").trigger('create');

					},
					error: function(xhr, statusText, errorThrown){
						alert(errorThrown);
					}
				});
			},
			error: function(xhr, statusText, errorThrown){
				alert(errorThrown);
			}
		});

	};

	//Function to add recipe - called from nav bar
	function addRecipe(){

		uid = localStorage.getItem('uid');

		//Checks to see if user has provided image
		if($('#userfile').val() != ''){
			//Ajax for a recipe with an image
			$.ajax({
				url: ajax_url + "addRecipeImage",
				type: 'POST',
				data: { uid: uid,
						add_recipe_name: add_name,
						add_category: add_category,
						add_description: add_description,
						add_ingredients: add_ingredients,
						add_directions: add_directions,
						add_image: add_image},
				dataType: 'json',
				success: function(response){
					if(response){
						$("#add_errors").html(response).trigger('create').show();
					}else{
						$.mobile.changePage('list_view.html');
						add_name = "";
						add_category ="";
						add_description = "";
						add_ingredients = [];
						add_directions = "";
						add_cat_label = "";
						add_image = "";
					}
				},
				error: function(xhr, statusText, errorThrown){
					alert(errorThrown);
				}				
			});

		}else{
			//Ajax for a recipe without an image
			$.ajax({
				url: ajax_url + "addRecipe",
				type: 'POST',
				data: { uid: uid,
						add_recipe_name: add_name,
						add_category: add_category,
						add_description: add_description,
						add_ingredients: add_ingredients,
						add_directions: add_directions},
				dataType: 'json',
				success: function(response){
					if(response){
						$("#add_errors").html(response).trigger('create').show();
					}else{
						$.mobile.changePage('list_view.html');
						add_name = "";
						add_category ="";
						add_description = "";
						add_ingredients = [];
						add_directions = "";
						add_cat_label = "";
						add_image = "";
					}
				},
				error: function(xhr, statusText, errorThrown){
					alert(errorThrown);
				}
		});

		}
	};

	//Function to update recipes - called from update btn on details page
	function updateRecipe(){

		uid = localStorage.getItem('uid');
		//checks to see if image is supplies
		if($('#update_file').val() != ''){
			//update with image
			$.ajax({
				url: ajax_url + "updateRecipeImage",
				type: 'POST',
				data: { uid: uid,
						recipe_id: temp_recipe_id,
						add_recipe_name: add_name,
						add_category: add_category,
						add_description: add_description,
						add_ingredients: add_ingredients,
						add_directions: add_directions,
						add_image: add_image},
				dataType: 'json',
				success: function(response){
					$.mobile.changePage('details.html');
					add_name = "";
					add_category ="";
					add_description = "";
					add_ingredients = [];
					add_directions = "";
					add_cat_label = "";
					add_image = "";
				},
				error: function(xhr, statusText, errorThrown){
					alert(errorThrown);
				}
			});

		}else{
			//update without image
			$.ajax({
				url: ajax_url + "updateRecipe",
				type: 'POST',
				data: { uid: uid,
						recipe_id: temp_recipe_id,
						add_recipe_name: add_name,
						add_category: add_category,
						add_description: add_description,
						add_ingredients: add_ingredients,
						add_directions: add_directions},
				dataType: 'json',
				success: function(response){
					$.mobile.changePage('details.html');
					add_name = "";
					add_category ="";
					add_description = "";
					add_ingredients = [];
					add_directions = "";
					add_cat_label = "";
					add_image = "";
				},
				error: function(xhr, statusText, errorThrown){
					alert(errorThrown);
				}
			});
		}
	};
	
})(jQuery);