	    			<?php
	    				$favs_array = array();
	    				if($results){
	    					foreach($results as $r){ 
	    						if(isset($favs)){ 
	    							foreach($favs as $f){
	    								array_push($favs_array, $f->recipe_id);
	    							}
	    						} ?>

			    			<li class="recipe_tile">
			    				<a href="<?php echo site_url(); ?>recipe/details/<?php echo $r->recipe_id; ?>">
			    					<div class="recipe_tile_img_container">
					    				<img src="<?php echo base_url(); ?>recipe_img/<?php echo $r->image_path; ?>" class="recipe_tile_img"/>
					    			</div>
					    			<p class="tile_name"><?php echo $r->name; ?></p>
					    			<p class="tile_desc"><?php echo $r->description; ?></p>
				    			</a>
				    			<?php if(in_array($r->recipe_id, $favs_array)){ ?>
				    						<div class="tile_fav_icon selected_fav"></div>
				    			<?php }else{ ?>
				    						<div class="tile_fav_icon"></div>
				    			<?php } ?>			

				    			<p class="tile_fav_num"><?php echo $r->favorites; ?></p>
				    			<?php if(isset($user_favs)){ ?>
				    				<div class="tile_stars" id="star_<?php echo $r->recipe_id; ?>_uf"></div>
				    			<?php }else{ ?>
				    				<div class="tile_stars" id="star_<?php echo $r->recipe_id; ?>"></div>
				    			<?php } ?>
				    			<input type="hidden" value="<?php echo $r->recipe_id; ?>" id="recipe_id" name="recipe_id"/>
				    		</li>

				    		<?php
				    		if(isset($user_favs)){
					    		echo '<script type="text/javascript">
					    			$("#star_'. $r->recipe_id . '_uf").raty({
										starOff: "' .base_url().'/assets/img/star_off.png",
										starOn: "' .base_url().'/assets/img/star_on.png",
										readOnly: true, score:"'. $r->rating . '" 
									});</script>';
							}else{
								echo '<script type="text/javascript">
					    			$("#star_'. $r->recipe_id . '").raty({
										starOff: "' .base_url().'/assets/img/star_off.png",
										starOn:"' .base_url().'assets/img/star_on.png",
										readOnly: true, score:"'. $r->rating . '" 
									});</script>';
							}

								?>
			    	<?php }} ?>