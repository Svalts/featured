/*
Project RecipeBook
authors: Brad Cerny and Simon Figueroa
February 2013
*/
(function($){

	var fb_id,
		fb_name,
		fb_email,
		access_token,
		page = $('html');

window.fbAsyncInit = function(){
    FB.init({
		appId      : '135722056595195', // App ID
		channelUrl : 'http://localhost:8888/m.recipebook/', // Channel File
		status     : true, // check login status
		cookie     : true, // enable cookies to allow the server to access the session
		xfbml      : true  // parse XFBML
    });


	//Facebook login on click
	$('#fb_login').on('click', function(){
		FB.getLoginStatus(function(response){
			if(response.status === 'connected'){
				// connected
				testAPI();
			}else if(response.status === 'not_authorized'){
				// not_authorized
				login();
			}else{
				// not_logged_in
				login();
			}
		});
	});

	page.on('click', '#fb_share', function(){
		$.ajax({
			url: ajax_url + "uploadPhoto",
			type: 'POST',
			data: {image: recipeObject[0].image},
			dataType: 'json',
			success: function(response){

			FB.ui(
			{
				access_token: access_token,
				method: 'feed',
				name: recipeObject[0].name,
				picture: "http://www.m-recipebook.bradcerny.com/mobile_recipebook/uploads/" + response + ".jpg",
				caption: recipeObject[0].cat_name,
				description: recipeObject[0].description
			},
			
			function(response) {
				
				if (response && response.post_id){
					// alert('Post was published.');
				}else{
					// alert('Post was not published.');
				}
			});

			},
			error: function(xhr, statusText, errorThrown){
				alert(errorThrown);
			}
		});
	});

	//Log user out of FB - also logs out of app - main js
	page.on('click', '#logoutBtn', function(){
		FB.logout(function(response) {
		  // user is now logged out
		});
	});

	//Prepopulates fields on sign-up page with FB user information - happens for users that are using FB the first time
	$('html').on('pageinit', '#signup', function(){
		if(localStorage.fb_id){
			$('#fb_sign_up_msg').show();
			$('#r_fname').val(fb_name);
			if(fb_email != "undefined"){
				$('#r_email').val(fb_email);
			}
		}

	});
};

	//Authorizes user
	function login() {
		FB.login(function(response) {
			if(response.authResponse){
				// connected
				testAPI();
				localStorage.fb_id = response.authResponse.userID;
			}else{
				// cancelled
			}
		});
	};

	//Returns response when user is logged in to Facebook
	function testAPI() {
		FB.api('/me', function(response) {

			localStorage.fb_id = response.id;
			localStorage.fb_name = response.first_name;
			localStorage.email = response.email;

			fb_id = localStorage.getItem('fb_id');
			fb_name = localStorage.getItem('fb_name');
			fb_email = localStorage.getItem('email');

			//Check to see if FB user exists. Then determine if the user is new (needs to create account) or existing	
			$.ajax({
				url: ajax_url + "checkFbUser",
				type: 'POST',
				data: {fb_id: fb_id},
				dataType: 'json',
				success: function(response){
					if(response == 1){
						$.mobile.changePage('list_view.html');
					}else{
						$.mobile.changePage('signup.html');
					}
				},
				error: function(xhr, statusText, errorThrown){
					alert(errorThrown);
				}
			});
		});
	};

	// Load the SDK Asynchronously
	(function(d){
		var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
		if (d.getElementById(id)) {return;}
			js = d.createElement('script'); js.id = id; js.async = true;
			js.src = "//connect.facebook.net/en_US/all.js";
			ref.parentNode.insertBefore(js, ref);
	}(document));
})(jQuery);