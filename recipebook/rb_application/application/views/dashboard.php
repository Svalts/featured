<?php if($session_data = $this->session->userdata('logged_in')){} ?>
        <div class="container">
            <aside class="dashboard_aside">
                <img src="<?php echo base_url();?>assets/img/user_img.png" class="dash_img" width="200" height="200"/>
                <p class="dash_welcome">Hello <?php echo $session_data['uname']; ?></p>
                <p class="dash_edit_profile"><a href="#">Edit Profile</a></p>
                <div class="dash_quick_links">
                    <p>Quick Links</p>
                    <ul>
                        <li><a href="#" class="fancybox_add_recipe">Add Recipe</a></li>
                        <li><a href="#">Help/FAQ</a></li>
                        <li><a href="index/home">Find Recipes</a></li>
                    </ul>
                </div>
            </aside>

            <nav class="dashboard_nav">
                <ul>
                    <li><a href="<?php echo base_url();?>user/dashboard" class="active_dash">Dashboard</a></li>
                    <li><a href="<?php echo base_url();?>user/profile/<?php echo $session_data['uid']; ?>">Profile</a></li>
                    <li><a href="<?php echo base_url();?>user/recipes">Your Recipes</a></li>
                    <li><a href="#">Account</a></li>
                </ul>
            </nav>

            <section class="user_feed">
                <?php if(isset($results)){
                    foreach($results as $r){
                        if($r->type == "follower"){ ?>
                            <div class="user_feed_follower">
                                <img src="<?php echo base_url();?>assets/img/user_img.png" width="45" height="45"/>
                                <p><span class="follower_name"><a href="<?php echo base_url(); ?>user/profile/<?php echo $r->user_id; ?>"><?php echo $r->username; ?></a></span> started following you.</p>
                            </div>
                        <?php }elseif($r->type == "recipe"){ ?>
                            <div class="user_feed_added">
                                <div class="dash_img_container">
                                    <img src="../recipe_img/<?php echo $r->image_path; ?>" width="115"/>
                                </div>
                                <p class="uf_username"><a href="<?php echo base_url(); ?>user/profile/<?php echo $r->user_id; ?>"><?php echo $r->username; ?></a> posted a new recipe.</p>
                                <p class="uf_recipe_name"><?php echo $r->name; ?></p>
                                <p class="uf_recipe_description"><?php echo $r->description ?></p>
                                <p class="uf_recipe_link"><a href="<?php echo base_url(); ?>recipe/details/<?php echo $r->recipe_id; ?>">View Recipe</a></p>
                            </div>
                        <?php }
                    }
                } ?>


            </section>

        </div><!--end container-->