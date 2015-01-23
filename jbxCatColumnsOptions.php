<?php

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if(!wp_verify_nonce($_POST['save_options_field'], 'save_options')){
			die("Sorry, but this request is invalid");
		}
		
		/* Process the columns option */
		if (isset($_POST['num_cols'])) {
			update_option('jbxCatColumns_cols', $_POST['num_cols']);
		}

		/* Process the featured image option */
		if (isset($_POST['feat_img'])) {
			update_option('jbxCatColumns_feat', $_POST['feat_img']);
		}

		/* Process the categories option */
		update_option('jbxCatColumns_cats', serialize($_POST['post_category']));
		
	}
	
?>

<div class="wrap">
	<div id="icon-options-general" class="icon32">
		<br />
	</div>
	<form method="post" action="<?php echo $GLOBALS['PHP_SELF'] . '?page=' . $this->optionsPage; ?>" id="jbxcpoptions">
		<h2><?php _e('JBX Category Columns', 'jbxcatcolumns');?></h2>
		
		Select which categories to display.
		
		<ul class='categorychecklist form-no-clear'>
		<?php 
			$sel_cats = unserialize($this->getSelectedCats());
			$all_cats = get_categories();
			
			foreach ($all_cats as $v) { ?>
				<li class='popular-category'>
					<label class='selectit'>
						<input <?php echo (in_array($v->term_id, $sel_cats)) ? 'checked="checked"' : ''; ?> type='checkbox' name='post_category[]' value='<?php echo $v->term_id; ?>' /> <?php echo $v->name; ?>
					</label>
				</li>
			<?php } ?>
		</ul>
		
		How many columns would you like to display?
		<br><br>
		<?php $cols = $this->getNumCols(); ?>
		<select name='num_cols'>
			<option value='2' <?php echo ($cols == 2) ? 'selected="selected"' : ''; ?>>2</option>
			<option value='3' <?php echo ($cols == 3) ? 'selected="selected"' : ''; ?>>3</option>
			<option value='4' <?php echo ($cols == 4) ? 'selected="selected"' : ''; ?>>4</option>
			<option value='5' <?php echo ($cols == 5) ? 'selected="selected"' : ''; ?>>5</option>
			<option value='6' <?php echo ($cols == 6) ? 'selected="selected"' : ''; ?>>6</option>
		</select>
		<br><br>
		How would you like to display featured images?
		<br><br>
		<?php $feat = $this->getFeat(); ?>
		<select name='feat_img'>
			<option value='0' <?php echo ($feat == 0) ? 'selected="selected"' : ''; ?>>Do not display featured images.</option>
			<option value='1' <?php echo ($feat == 1) ? 'selected="selected"' : ''; ?>>Display the featured image for the first post only</option>
			<option value='2' <?php echo ($feat == 2) ? 'selected="selected"' : ''; ?>>Display the featured image for all posts</option>
		</select>
		<br><br>
		<?php wp_nonce_field('save_options','save_options_field'); ?>
		<input type="submit" name="Submit" class="button-primary" value="<?php _e('Save Changes', 'jbxCatColumns'); ?>" id="submitChanges" />
	</form>
</div>