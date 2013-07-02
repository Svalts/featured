        <?php if($this->session->flashdata('edit_errors')){
	        echo '<script type="text/javascript">$(document).ready(function(){
	            $(".fancybox_edit_recipe").click();
	            });</script>';
	    }?>
        <div class="container">
        	<?php if(isset($no_results)){ ?>
        		<p class="no_recipe_detail">No Recipe Found.</p>
        	<?php }else{ ?>

        		<?php if(isset($users_recipe)){ ?>
		        	<div class="delete_confirm">
		        		<p>Are you sure you want to delete this recipe?</p>
		        		<p class="delete_cancel">Cancel</p>
		        		<p class="btn_confirm_delete btn">Delete</p>
		        	</div>
		        	<p class="btn fancybox_edit_recipe btn_edit_recipe">Edit Recipe</p>
		        	<p class="btn btn_delete">Delete</p>
	        <div id="edit_recipe_section">
	            <div class="edit_recipe_header">
	                <h2>Edit Recipe</h2>
	            </div>
	            <div class="edit_errors"><?php if($errors = $this->session->flashdata('edit_errors')){
	                echo $errors;
	            } ?></div>

	            <?php   
	                $attrs = array('id' => 'edit_recipe_form');
	                echo form_open_multipart('recipe/edit_recipe', $attrs);
	            ?>
	                <div class="step" id="step1">
	                    <div>
	                        <ul class="recipe_opts">
	                            <li><label class="label_recipe_name">Recipe Name</label><input type="text" value='<?php echo $recipe->name; ?>' name="edit_recipe_name" class="add_recipe_name"/></li>
	                            <li><label class="label_cat">Category</label>
	                                <div class="styled_select">
	                                <select name="edit_category" class="add_category edit_category" data-id="<?php echo $recipe->category_id; ?>">
	                                    <option value="0">Category</option>
	                                    <option value="1">Breakfast</option>
	                                    <option value="2">Lunch</option>
	                                    <option value="3">Dinner</option>
	                                    <option value="4">Appetizer</option>
	                                    <option value="5">Dessert</option>
	                                    <option value="6">Snack</option>
	                                </select></div></li>
	                            <li><label class="label_diet">Diet</label>
	                                <div class="styled_select">
	                                <select name="edit_diet" class="add_diet edit_diet" data-id="<?php echo $recipe->diet_id; ?>">
	                                    <option value="0">None</option>
	                                    <option value="1">Vegetarian</option>
	                                    <option value="2">Vegan</option>
	                                    <option value="3">Pescetarian</option>
	                                </select></div></li>
	                        </ul>
	                    </div>
	                </div>
	                <div class="step" id="step2">
	                    <p>Add an image now, or press Next and you can add one later.</p>
	                    <p class="btn edit_image_btn">Add Image</p>
	                    <div class="img_area">
	                        <img src="" class="img_preview"/>
	                    </div>
	                    <input type="file" name="edit_userfile" id="edit_userfile" accept="image/jpeg,image/png,image/jpg"/>
	                    <input type="hidden" value="" id="edit_img_data" name="edit_img_data"/>
	                </div>
	                <div class="step" id="step3">
	                    <label class="label_desc">Description</label>
	                    <textarea name="edit_description" class="input_desc"><?php echo $recipe->description; ?></textarea>
	                </div>
	                <div class="step" id="step4">
	                    <p class="label_ing">Ingredients</p>
	                    <div class="edit_ing_list">
		                    	<?php if(!empty($ings)){ ?>

		                        <?php foreach($ings as $ing){ ?>
		                        	<input type="text" name="edit_ingredients[]" class="ingredients" placeholder="Ingredient (ex: 1c flour)" value="<?php echo $ing; ?>"/>
		                        <?php } ?>
		                    <?php } ?>
	                    </div><!--end ing_list-->
	                    <p class="edit_add_ing_btn">Add Another</p>
	                </div>
	                <div class="step submit_step" id="step5">
	                    <p class="label_directions">Directions</p>
	                    <textarea name="edit_directions" class="input_directions"><?php echo $recipe->directions; ?></textarea>
	                    <input type="hidden" value="<?php echo $recipe->recipe_id; ?>" id="edit_recipe_id" name="edit_recipe_id"/>
	                </div>

	                <div class="add_navigation">                            
	                    <input class="navigation_button btn back_btn" id="back" value="Back" type="reset" />
	                    <input class="navigation_button btn" id="next" value="Next" type="submit" />
	                </div>
	            </form>
        </div>
		        <?php } ?>

	        	<section class="recipe_info" data-id="<?php echo $recipe->recipe_id; ?>">
	                <div class="recipe_img_container">
	                    <img src="<?php echo base_url(); ?>recipe_img/<?php echo $recipe->image_path; ?>" class="recipe_img"/>
		    		</div>
	                <h1 class="recipe_name"><?php echo $recipe->name; ?></h1>
	                <p class="recipe_user">Recipe added by: <a href="<?php echo site_url(); ?>user/profile/<?php echo $recipe->user_id; ?>"><?php echo $recipe->username; ?></a></p>
	                <div class="recipe_rating" id="star_<?php echo $recipe->recipe_id; ?>"></div>

	                <?php
	                    echo '<script type="text/javascript">
	                        $("#star_'. $recipe->recipe_id . '").raty({
	                            starOff: "' .base_url().'/assets/img/star_off.png",
	                            starOn: "' .base_url().'/assets/img/star_on.png",
	                            width: "100%",
	                            readOnly: true, score:"'. $recipe->rating . '" 
	                        });</script>'; ?>

	                <p class="recipe_description"><?php echo $recipe->description; ?></p>
	                <ul class="social_list">
	                    <li id="fb-root"><img src="<?php echo base_url(); ?>assets/img/fb-share.gif" id="share_button"></li>
	                    <li><a href="//pinterest.com/pin/create/button/?url=<?php echo base_url();?>recipe/details/<?php echo $recipe->recipe_id; ?>&media=<?php echo base_url(); ?>recipe_img/<?php echo $recipe->image_path; ?>" data-pin-do="buttonPin" data-pin-config="none"></a></li>
	                </ul>
	        	</section>

	            <div class="recipe_details">
	                <section class="recipe_specifics">
	                    <h2>Ingredients</h2>
	                    <ul>
	                    	<?php if(!empty($ings)){ ?>

		                        <?php foreach($ings as $ing){ ?>
		                            <li><?php echo $ing; ?></li>
		                        <?php } ?>

		                    <?php }else{ ?>
		                    	<li>No Ingredients Added</li>
		                	<?php } ?>

	                    </ul>
	                    <h2>Instructions</h2>
	                    <p><?php echo $recipe->directions; ?></p>
	                </section>

	                <section class="comment_section">
	                    <div id="disqus_thread"></div>
					    <script type="text/javascript">
					        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
					        var disqus_shortname = 'recipe-book'; // required: replace example with your forum shortname
					
					        /* * * DON'T EDIT BELOW THIS LINE * * */
					        (function() {
					            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
					            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
					            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
					        })();
					    </script>
					    <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
					    <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
	                </section>
	            </div><!--end recipe_details-->
	        <?php } ?>
        </div><!--end container-->

			<script>
			window.fbAsyncInit = function() {
				FB.init({appId: '135722056595195', status: true, cookie: true,
				xfbml: true});
				};
				(function() {
				var e = document.createElement('script'); e.async = true;
				e.src = document.location.protocol +
				'//connect.facebook.net/en_US/all.js';
				document.getElementById('fb-root').appendChild(e);
			}());
			</script>
			
			<script type="text/javascript">
				$(document).ready(function(){
					$('#share_button').click(function(e){
						e.preventDefault();
						FB.ui(
						{
							method: 'feed',
							name: '<?php echo json_encode($recipe->name); ?>',
							link: '<?= base_url() . 'recipe/details/' . $recipe->recipe_id ?>',
							picture: '<?= base_url() . 'recipe_img/' . $recipe->image_path ?>',
							caption: 'Share and find your favourite recipes',
							description: '<?php echo json_encode($recipe->description); ?>',
							message: ''
						});
					});
				});
			</script>