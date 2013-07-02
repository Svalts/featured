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
                    <li><a href="<?php echo base_url();?>user/dashboard">Dashboard</a></li>
                    <li><a href="<?php echo base_url();?>user/profile/<?php echo $session_data['uid']; ?>">Profile</a></li>
                    <li><a href="<?php echo base_url();?>user/recipes" class="active_dash">Your Recipes</a></li>
                    <li><a href="#">Account</a></li>
                </ul>
            </nav>

            <section class="user_recipes">
                <ul>
                    <?php echo $tiles; ?>
                </ul>
            </section>

        </div><!--end container-->