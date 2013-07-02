    <?php if($profile){
        foreach ($profile as $p){ ?>

        <div class="container">
            <section class="profile_info">
                <img src="<?php echo base_url();?>assets/img/user_img.png" class="profile_image" width="100" height="100"/>
                <ul>
                    <li class="profile_name"><?php echo $p->first_name; ?></li>
                    <li class="profile_username"><?php echo $p->username; ?></li>
                    <li class="profile_city">User City</li>
                </ul>
            </section>

            <section class="profile_details">
                <div class="profile_top_section">
                    <p class="profile_login">Last Login: <?php echo $last_login; ?></p>
                    <?php if($session_data = $this->session->userdata('logged_in')){ 
                        if($p->user_id == $session_data['uid']){}else{ ?>
                            <?php if(!empty($is_follower)){
                                echo form_open('user/unfollow') ?>
                                    <button class="profile_follow unfollow">Unfollow</button>
                                    <input type="hidden" name="unfollow" value="<?php echo $profile_id ?>"/>
                                </form>
                            <?php }else{
                                echo form_open('user/follow') ?>
                                <button class="profile_follow">Follow</button>
                                <input type="hidden" name="follow" value="<?php echo $profile_id ?>"/>
                            </form>
                    <?php }}} ?>

                        

                    
                </div>
                <ul class="profile_followers">
                    <li>Followers<span><?php echo $followers; ?></span></li>
                    <li>Following<span><?php echo $following; ?></span></li>
                    <li>Recipes Added<span><?php echo $num_recipes; ?></span></li>
                    <li>Overall Rating<span><img src="<?php echo base_url();?>assets/img/default_stars.png"/></span></li>
                </ul>
            </section>
    <?php } } ?>

        	<section class="profile_recipes">
        		<h2>Added Recipes</h2>
                <p class="user_recipes_show_all">Show All</p>
                <ul class="recipe_list user_added_recipes">
                    <?php echo $tiles; ?>
                </ul>
                </section>
                <section class="profile_favorites">
                    <h2>Favorite Recipes</h2>
                    <p class="fav_total">(rec total)</p>
                    <p class="fav_show_all">Show All</p>
                    <ul class="recipe_list user_fav_recipes">
                         <?php echo $favs; ?>
                    </ul>
        	</section>
        </div><!--end container-->