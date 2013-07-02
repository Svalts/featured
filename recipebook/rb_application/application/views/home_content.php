    	<section class="cta">
    		<div class="cta_img">
	    		<img src="<?php echo base_url(); ?>assets/img/cta_img.png" width="1400" height="380"/>
	    	</div>
	    	
    		<div class="container">
	    		<h1>Explore. Discover. Share.</h1>
    			<?php
    				$attrs = array('class' => 'search_form', 'method' => 'get');
    				echo form_open('search/', $attrs); 
    			?>
    				<div class="ui-widget">
	    				<input type="text" name="q" class="cta_search" id="cta_search" placeholder="Search Recipes or Ingredients"/>
	    				<input type="submit" class="btn_search btn" value="Explore"/>
	    			</div>
	    		</form>
	    	</div>
    	</section>

    	<section>
    		<div class="container main_content">
	    		<h2>Featured Recipes</h2>
	    		<ul class="recipe_list">
	    			<?php echo $tiles; ?>
	    		</ul>
	    	</div>
    	</section>