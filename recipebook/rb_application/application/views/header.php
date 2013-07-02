<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>RecipeBook</title>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Rokkitt:400,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fancybox/source/jquery.fancybox.css?v=2.1.4" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui-1.10.2.custom.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/main.css">
        <link rel="author" href="humans.txt">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/raty/jquery.raty.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/formwizard/jquery.form.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/formwizard/jquery.form.wizard.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/formwizard/jquery.validate.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/fancybox/source/jquery.fancybox.pack.js?v=2.1.4"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.5"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
        <script type="text/javascript">
			(function(d){
			  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
			  p.type = 'text/javascript';
			  p.async = true;
			  p.src = '//assets.pinterest.com/js/pinit.js';
			  f.parentNode.insertBefore(p, f);
			}(document));
		</script>
    </head>
    <body>
        <?php if(!($this->session->userdata('logged_in'))){ ?>
            <?php if($this->session->flashdata('login_error')){
                echo '<script type="text/javascript">$(document).ready(function(){
                    $(".fancybox_login").click();
                    });</script>';
            }?>
            <?php if($this->session->flashdata('register_error')){
                echo '<script type="text/javascript">$(document).ready(function(){
                    $(".fancybox_signup").click();
                    });</script>';
            }?>
    	<header>
    		<a href="<?php echo base_url(); ?>" class="logo_header"><img src="<?php echo base_url(); ?>assets/img/logo.png" alt="RecipeBook"/></a>
            <ul class="header_actions">
                <?php
                    $attrs = array('id' => 'header_user_form');
                    echo form_open('home/user', $attrs); 
                ?>
    			<li><a href="#" class="link link_sign_up">Sign Up</a></li>
    			<li><button class="btn_login btn link_login">Login</button></li>
    		</ul>
            <a href="#" class="fancybox_signup hide"></a>
            <a href="#" class="fancybox_login hide"></a>
            <input type="hidden" name="user_action" id="user_action" value=""/>
        </form>
    	</header>
        <div id="login_form">
            <div class="add_errors"><?php if($errors = $this->session->flashdata('login_error')){
                echo $errors;
            } ?></div>
            <?php if(isset($url)){ ?>
                <a href="<?php echo $url; ?>"><img src="<?php echo base_url();?>assets/img/facebook_icon.png"/></a>
            <?php } ?>

            <?php echo form_open('verify_login'); ?>
                <p>-or-</p>
                <input type="text" placeholder="Email Address" name="login_email" class="login_input"/>
                <input type="password" placeholder="Password" name="login_password" class="login_input"/>
                <input type="submit" class="btn_login btn" value="Log In"/>
            </form>
        </div>
        <div id="signup_form">
            <div class="add_errors"><?php if($errors = $this->session->flashdata('register_error')){
                echo $errors;
            } ?></div>
            <?php echo form_open('register_user'); ?>
            <?php if($this->session->flashdata('fb_register')){ ?>
                <p>This is your first time logging in with Facebook. Please create an account.</p>
                <input type="hidden" name="fb_register_id" value="<?php echo $this->session->flashdata('fb_register'); ?>"/>
            <?php } ?>
                <input type="text" placeholder="Email Address" name="r_email" class="signup_input"/>
                <input type="password" placeholder="Password" name="r_password" class="signup_input"/>
                <input type="text" placeholder="Username" name="r_username" class="signup_input"/>
                <input type="submit" class="btn_signup btn" value="Sign Up"/>
            </form>
        </div>

        <?php }elseif(($session_data = $this->session->userdata('logged_in'))){ ?>

        <?php if($this->session->flashdata('add_errors')){
            echo '<script type="text/javascript">$(document).ready(function(){
                $(".fancybox_add_recipe").click();
                });</script>';
        }?>

        <header>
            <a href="<?php echo base_url(); ?>" class="logo_header"><img src="<?php echo base_url(); ?>assets/img/logo.png" alt="RecipeBook"/></a>
            
            <?php if(site_url() == current_url() || (site_url(). "home") == current_url()){ ?>


            <?php }else{ ?>
                <?php
                    $attrs = array('class' => 'header_search_form', 'method' => 'get');
                    echo form_open('search/', $attrs); 
                ?>
                    <input type="text" placeholder="Search Recipes or Ingredients" name="q" class="header_search"/>
                </form>
            <?php } ?>  

            <ul class="alt_header_actions">
                <li><img src="<?php echo base_url();?>assets/img/user_img.png" class="header_image" width="40" height="40"/></li>
                <li class="header_user" data-id="<?php echo $session_data['uid']; ?>"><a href="<?php echo base_url();?>user/dashboard"><?php echo $session_data['uname']; ?></a></li>
                <li><button class="btn_add_recipe fancybox_add_recipe">Add Recipe</button></li>
                <?php if(isset($logout_url)){ ?>
                    <a href="<?php echo $logout_url; ?>" id="logout_btn">Logout</a>
                <?php }else{ ?>
                    <a href="<?php echo base_url(); ?>verify_login/logout" id="logout_btn">Logout</a>
                <?php } ?>
            </ul>
        </header>
        <div id="add_recipe_section">
            <div class="add_recipe_header">
                <h2>Add Recipe</h2>
            </div>
            <div class="add_errors"><?php if($errors = $this->session->flashdata('add_errors')){
                echo $errors;
            } ?></div>

            <?php   
                $attrs = array('id' => 'add_recipe_form');
                echo form_open_multipart('recipe/add_recipe', $attrs);
            ?>
                <div class="step" id="step1">
                    <div>
                        <ul class="recipe_opts">
                            <li><label class="label_recipe_name">Recipe Name</label><input type="text" value='<?php echo set_value('name'); ?>' name="recipe_name" class="add_recipe_name"/></li>
                            <li><label class="label_cat">Category</label>
                                <div class="styled_select">
                                <select name="category" class="add_category">
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
                                <select name="diet" class="add_diet">
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
                    <p class="add_image_btn btn">Add Image</p>
                    <div class="img_area">
                        <img src="" class="img_preview"/>
                    </div>
                    <input type="file" name="userfile" id="userfile" accept="image/jpeg,image/png,image/jpg"/>
                    <input type="hidden" value="" id="add_img_data" name="add_img_data"/>
                </div>
                <div class="step" id="step3">
                    <label class="label_desc">Description</label>
                    <textarea name="description" class="input_desc"><?php echo set_value('description'); ?></textarea>
                </div>
                <div class="step" id="step4">
                    <p class="label_ing">Ingredients</p>
                    <div class="ing_list">
                        <input type="text" name="ingredients[]" class="ingredients" placeholder="Ingredient (ex: 1c flour)"/>
                    </div><!--end ing_list-->
                    <p class="add_ing_btn">Add Another</p>
                </div>
                <div class="step submit_step" id="step5">
                    <p class="label_directions">Directions</p>
                    <textarea name="directions" class="input_directions"><?php echo set_value('directions'); ?></textarea>
                </div>

                <div class="add_navigation">                            
                    <input class="navigation_button btn back_btn" id="back" value="Back" type="reset" />
                    <input class="navigation_button btn" id="next" value="Next" type="submit" />
                </div>
                <!-- <input type="submit" name="add_recipe" id="add_recipe_btn" class="button" value="Add Recipe"/> -->
            </form>
        </div>
        <?php } ?>
        <?php if($this->session->flashdata('user_action') == 'login'){
            echo '<script type="text/javascript">$(document).ready(function(){
                    $(".fancybox_login").click();
                    });</script>';
            }elseif($this->session->flashdata('user_action') == 'register'){
            echo '<script type="text/javascript">$(document).ready(function(){
                        $(".fancybox_signup").click();
                    });</script>' ; } ?>
        <?php if($this->session->flashdata('fb_register')){
            echo '<script type="text/javascript">$(document).ready(function(){
                        $(".fancybox_signup").click();
                    });</script>' ; } ?>