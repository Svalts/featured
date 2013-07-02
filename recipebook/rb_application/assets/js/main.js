(function($){

	var base_url = "http://localhost:8888/rb_application/index.php?/",
		autocomplete_array = [],
		filter_list_array = [];

/*----------LOGIN FUNCTIONS-------------*/

$('.link_login').on('click', function(e){
	e.preventDefault();
	$('#user_action').val('login');
	$('#header_user_form').submit();
});

$('.link_sign_up').on('click', function(e){
	e.preventDefault();
	$('#user_action').val('register');
	$('#header_user_form').submit();
});

/*----------FANCYBOX FUNCTIONS-------------*/

$(".fancybox_signup").fancybox({
	closeBtn: true,
	href: "#signup_form",
	helpers: {
		overlay:{
			css:{
				'background': 'rgba(255,255,255,.5)'
			}
		}
	}
});

$(".fancybox_login").fancybox({
	closeBtn: true,
	href: "#login_form",
	beforeShow: function(){
		$(".login_input").val("");
	},
	leftRation: 0,
	helpers: {
		overlay:{
			css:{
				'background': 'rgba(255,255,255,.5)'
			}
		}
	}
});

$(".fancybox_add_recipe").fancybox({
	closeBtn: true,
	href: "#add_recipe_section",
	height: 400,
	leftRation: 0,
	afterClose: function(){
		$("#add_recipe_form").formwizard("reset"); 
	},
	helpers: {
		overlay:{
			css:{
				'background': 'rgba(255,255,255,.5)'
			}
		}
	}
});

$(".fancybox_edit_recipe").fancybox({
	closeBtn: true,
	href: "#edit_recipe_section",
	beforeShow: function(){
		var cat = $('.edit_category').attr('data-id'),
			diet = $('.edit_diet').attr('data-id');
		$('.edit_category').val(cat);
		$('.edit_diet').val(diet);
	},
	height: 400,
	leftRation: 0,
	afterClose: function(){
		$("#edit_recipe_form").formwizard("reset"); 
	},
	helpers: {
		overlay:{
			css:{
				'background': 'rgba(255,255,255,.5)'
			}
		}
	}
});





//This is the function for the Raty star rating system
//this is used dynamically within the php - this is for reference
// $('.star').raty({
// 	starOff: "/assets/img/star_off.png",
// 	starOn: "/assets/img/star_on.png",
// 	readOnly: true, score: 3 
// });


/*---------FUNCTIONS FOR TRUNCATING-------------*/
//Truncates Recipe Name in Recipe Tile
$('.tile_name').each(function(){
	if($(this).html().length >= 35){
		var truncated_name = $(this).html().substring(0,35).trim(this) + "...";
		$(this).html(truncated_name);
	}

});

//Truncates Recipe Description in Recipe Tile
$('.tile_desc').each(function(){
	if($(this).html().length >= 135){
		var truncated_name = $(this).html().substring(0,135).trim(this) + "...";
		$(this).html(truncated_name);		
	}
});

/*--------- AUTOCOMPLETE -------------*/
$('#cta_search').on('keyup', function(e){
	var query = $(this).val(),
		sorted_array = [];
	autocomplete_array = [];
	sorted_array = [];
	if($(this).val() != ""){
		$.ajax({
			url: base_url + "ajax/autocomplete",
			type: 'POST',
			data: {search_input: query},
			dataType: 'json',
			success: function(response){
				var i = 0
				$(response).each(function(){
					autocomplete_array.push(response[i].name);
					i++;
				});
				sorted_array = autocomplete_array.sort();

				$('#cta_search').autocomplete({ 
					source: sorted_array,
					minLength: 3
				});
			},
			error: function(){

			}
		});
	}

});

/*--------- FILTER RESULTS FUNCTIONS -------------*/

$("#filter_breakfast, #filter_lunch, #filter_dinner, #filter_appetizer, #filter_snack, #filter_dessert, #filter_vegetarian, #filter_vegan, #filter_pescetarian").on('click', function(){
	$('#filters_form').submit();
});

$('.btn_add_ing').on('click', function(){
	var ing = $('.input_add_ing').val(),
		li_ing = '<li><img src="http://placehold.it/15x15" class="btn_remove_ing"/>' + ing + '<input type="hidden" value="' + ing + '" name="ingredient[]"/></li>';

	if(ing != ""){
		$('.filter_ing').append(li_ing);
		$('.input_add_ing').val("");

		$('#filters_form').submit();
	}
});

$('.filter_ing').on('click', '.btn_remove_ing', function(){
	$(this).parent().remove();
	$('#filters_form').submit();
});


/*--------- FAVORITE RECIPE FUNCTIONS -------------*/
$('.tile_fav_icon').on('click', function(){

	var rid = $(this).parent().find('#recipe_id').val(),
	fav_icon = $(this);

	if($('.header_user').length != 0){

		var uid = $('.header_user').attr('data-id');

		if(!($(this).hasClass('selected_fav'))){

				$.ajax({
					url: base_url + "ajax/addFavorite",
					type: 'POST',
					data: {recipe_id: rid,
							user_id: uid},
					success: function(response){
						$.ajax({
							url: base_url + "ajax/addFavTotal",
							type: 'POST',
							data: {recipe_id: rid},
							success: function(response){
								var val = parseInt(fav_icon.parent().find('.tile_fav_num').text());
								val += 1;
								fav_icon.parent().find('.tile_fav_num').text(val);
							},
							error: function(response){
							}
						});
					},
					error: function(a,b,c){

					}
				});

				$(this).addClass('selected_fav');
		}else{

			$.ajax({
				url: base_url + "ajax/removeFavorite",
				type: 'POST',
				data: {recipe_id: rid,
						user_id: uid},
				success: function(response){

					$.ajax({
						url: base_url + "ajax/subtractFavTotal",
						type: 'POST',
						data: {recipe_id: rid},
						success: function(response){
							var val = parseInt(fav_icon.parent().find('.tile_fav_num').text());
							val -= 1;
							fav_icon.parent().find('.tile_fav_num').text(val);
						},
						error: function(response){
						}
					});
				},
				error: function(){

				}
			});

			$(this).removeClass('selected_fav');
		}

	}else{

		$(this).addClass('fancybox_login');

		$(".fancybox_login").fancybox({
			closeBtn: true,
			href: "#login_form",
			beforeShow: function(){
				$(".login_input").val("");
			},
			leftRation: 0,
			helpers: {
				overlay:{
					css:{
						'background': 'rgba(255,255,255,.5)'
					}
				}
			}
		});
	}

});

/*--------- USER PROFILE FUNCTIONS -------------*/

$('.user_added_recipes').each(function(){
	$('li:gt(3)', this).hide();
});

if($('.user_added_recipes li').length > 4){
	$('.user_recipes_show_all').show();
}else{
	$('.user_recipes_show_all').hide();
}

$('.user_recipes_show_all').on('click', function(){

	if($(this).hasClass('visible')){
		$('.user_added_recipes').each(function(){
			$("li:gt(3)", this).slideUp(500);
		});
		$(this).text('Show All').removeClass('visible');
	}else{
		$('.user_added_recipes li').slideDown(500);
		$(this).text('Hide').addClass('visible');
	}
});

$('.user_fav_recipes').each(function(){
	$('li:gt(3)', this).hide();
});

if($('.user_fav_recipes li').length > 4){
	$('.fav_show_all').show();
}else{
	$('.fav_show_all').hide();
}

$('.fav_total').text("(" + $('.user_fav_recipes li').length + ")");

$('.fav_show_all').on('click', function(){

	if($(this).hasClass('visible')){
		$('.user_fav_recipes').each(function(){
			$('li:gt(3)', this).slideUp(500);
		});
		$(this).text('Show All').removeClass('visible');
	}else{
		$('.user_fav_recipes li').slideDown(500);
		$(this).text('Hide').addClass('visible');
	}
});

$('.user_recipe_search').on('keyup', function(){
	var recipe_names = [],
		recipe_tiles = [],
		term = $(this).val();

	$('.user_added_recipes > .recipe_tile').each(function(){
		if($(this).find('.tile_name').text().match(term)){
			recipe_names.push($(this).find('.tile_name').text());
			recipe_tiles.push($(this));
			// $('.user_added_recipes').html(recipe_tiles);
		}

		// recipe_tiles.each(function(){
			// console.log(recipe_tiles);
		// });

	});

});


/*--------- ADD RECIPE FUNCTIONS -------------*/
$('.add_ing_btn').on('click', function(e){

	e.preventDefault();

	var empty = true;
	var ing_list = $(this).parents().find('.ing_list'),
		new_field = '<input type="text" name="ingredients[]" class="ingredients" placeholder="Ingredient (ex: 1c flour)"/>';

		$('.ingredients').each(function(){

			if($(this).val() == ""){
				empty = true;
			}else{
				empty = false;
			}
		});

	if(empty == false){
		ing_list.append(new_field);
	}
	return false;
});

$('#userfile').on('change', function(){

	if(this.files && this.files[0] && ("image/jpeg" || "image/png" || "image/jpg")){

		var reader = new FileReader();

		reader.onload = function(e){
			add_image = e.target.result;
			if($('.evroneCropCanvas').length){
				$('.evroneCropCanvas').remove();
				$('.img_preview').attr('src', add_image);
				// .evroneCrop({
				// 	setSelect: 'center',
		  //     		ratio: 23/20
				// });

			}else{
				$('.img_preview').attr('src', add_image);
				// .evroneCrop({
				// 	setSelect: 'center',
		  //     		ratio: 23/20
				// });
			}

			// $('.img_preview').css('width', '100%').css('height', '100%');

		}

		reader.onloadend = function(e){

		}

		reader.readAsDataURL(this.files[0]);
		
	}
});

$('.add_image_btn').on('click', function(){
	$('#userfile').click();
});

$('#add_recipe_form').formwizard({
    formPluginEnabled: false,
    validationEnabled: false,
    focusFirstInput : true,
    disableUIStyles : true,	
    formOptions : {
        success: function(data){

        },
        dataType: 'json',
        resetForm: true
    }
});

/*--------- DELETE RECIPE FUNCTIONS -------------*/
$('.btn_delete').on('click', function(){
	$('.delete_confirm').slideDown(500);
});

$('.delete_cancel').on('click', function(){
	$('.delete_confirm').slideUp(500);
});

$('.btn_confirm_delete').on('click', function(){
	var rid = $('.recipe_info').attr('data-id');

	$.ajax({
		url: base_url + "ajax/deleteRecipe",
		type: 'POST',
		data: {recipe_id: rid},
		success: function(response){

		},
		error: function(response){
		}
	});

});

/*--------- EDIT RECIPE FUNCTIONS -------------*/

$('#edit_recipe_form').formwizard({
    formPluginEnabled: false,
    validationEnabled: false,
    focusFirstInput : true,
    disableUIStyles : true,	
    formOptions : {
        success: function(data){

        },
        dataType: 'json',
        resetForm: true
    }
});

$('#edit_userfile').on('change', function(){

	if(this.files && this.files[0] && ("image/jpeg" || "image/png" || "image/jpg")){

		var reader = new FileReader();

		reader.onload = function(e){
			add_image = e.target.result;
			if($('.evroneCropCanvas').length){
				$('.evroneCropCanvas').remove();
				$('.img_preview').attr('src', add_image);
				// .evroneCrop({
				// 	setSelect: 'center',
		  //     		ratio: 23/20
				// });

			}else{
				$('.img_preview').attr('src', add_image);
				// .evroneCrop({
				// 	setSelect: 'center',
		  //     		ratio: 23/20
				// });
			}

			// $('.img_preview').css('width', '100%').css('height', '100%');

		}

		reader.onloadend = function(e){

		}

		reader.readAsDataURL(this.files[0]);
		
	}
});

$('.edit_image_btn').on('click', function(){
	$('#edit_userfile').click();
});

$('.edit_add_ing_btn').on('click', function(e){

	e.preventDefault();

	var empty = true;
	var ing_list = $(this).parents().find('.edit_ing_list'),
		new_field = '<input type="text" name="edit_ingredients[]" class="ingredients" placeholder="Ingredient (ex: 1c flour)"/>';

		$('.ingredients').each(function(){

			if($(this).val() == ""){
				empty = true;
			}else{
				empty = false;
			}
		});

	if(empty == false){
		ing_list.append(new_field);
	}
	return false;
});




})(jQuery);