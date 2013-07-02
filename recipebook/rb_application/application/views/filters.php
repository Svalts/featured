      <div class="filters_page">
            <div class="container">
                <aside class="recipe_filters">
                    <?php
                        $attrs = array('id' => 'filters_form');
                        echo form_open('search/filter', $attrs); 
                    ?>
                        <h2>Filters</h2>
                        <p class="reset_filters">Reset</p>
                        <h3>Ingredients</h3>
                            <input type="text" placeholder="Add Ingredient" class="input_add_ing"/>
                            <img src="<?php echo base_url();?>assets/img/add_ing_icon.png" class="btn_add_ing"/>
                            <ul class="filter_ing">
                                <?php 
                                if(!empty($ing_list)){
                                    foreach ($ing_list as $ing) { ?>
                                        <li><img src="<?php echo base_url();?>assets/img/remove_ing_icon.png" class="btn_remove_ing"/><?php echo $ing; ?><input type="hidden" value="<?php echo $ing; ?>" name="ingredient[]"/></li>
                                <?php }} ?>
                            </ul>
                        <h3>Courses</h3>
                            <ul>
                                <li><input type="checkbox" name="breakfast" id="filter_breakfast" value="breakfast" <?php echo set_checkbox('breakfast', 'breakfast'); ?>/>Breakfast</li>
                                <li><input type="checkbox" name="lunch" id="filter_lunch" value="lunch" <?php echo set_checkbox('lunch', 'lunch'); ?>/>Lunch</li>
                                <li><input type="checkbox" name="dinner" id="filter_dinner" value="dinner" <?php echo set_checkbox('dinner', 'dinner'); ?>/>Dinner</li>
                                <li><input type="checkbox" name="snack" id="filter_snack" value="snack" <?php echo set_checkbox('snack', 'snack'); ?>/>Snack</li>
                                <li><input type="checkbox" name="appetizer" id="filter_appetizer" value="appetizer" <?php echo set_checkbox('appetizer', 'appetizer'); ?>/>Appetizer</li>
                                <li><input type="checkbox" name="dessert" id="filter_dessert" value="dessert" <?php echo set_checkbox('dessert', 'dessert'); ?>/>Dessert</li>
                            </ul>
                        <h3>Diet</h3>
                            <ul>
                                <li><input type="checkbox" name="vegetarian" id="filter_vegetarian" value="vegetarian" <?php echo set_checkbox('vegetarian', 'vegetarian'); ?>/>Vegetarian</li>
                                <li><input type="checkbox" name="vegan" id="filter_vegan" value="vegan" <?php echo set_checkbox('vegan', 'vegan'); ?>/>Vegan</li>
                                <li><input type="checkbox" name="pescetarian" id="filter_pescetarian" value="pescetarian" <?php echo set_checkbox('pescetarian', 'pescetarian'); ?>/>Pescetarian</li>
                            </ul>
                    </form>
                </aside>
