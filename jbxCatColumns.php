<?php
/*
 Plugin Name: JBX Category Columns
 Plugin URI: http://www.jaybuddy.com/
 Description: This plugin displays x number of posts in each category in columns of 2, 3, 4, 5, or 6.
 Version: 1.0
 Author: Jay Pedersen
 Author URI: http://www.jaybuddy.com/
 */

/*
 This file is part of JBX Category Columns
 JBX Cat Posts is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.
 JBX Cat Posts is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with JBX Category Columns.  If not, see <http://www.gnu.org/licenses/>.
 */

class jbxCatColumns {
	
	var $optionsPage = 'jbxCatColumnsOptions';
	
	function __construct() {
		add_action( 'admin_menu', array( $this, 'adminPage' ) );
	}
	
	function loadOptionsPage() {
		require_once ('jbxCatColumnsOptions.php');
	}
	
	function adminPage() {
		add_options_page( 'JBX Category Columns', 'JBX Category Columns', 'manage_options', 'jbxCatColumnsOptions', array($this, 'loadOptionsPage') );
	}
	
	function jbx_activate() {
		
		if (get_option('jbxCatColumns_Archive')) {
			$options = unserialize(get_option('jbxCatColumns_Archive'));
			
			//Get the archived options and put them back where they belong//
			update_option('jbxCatColumns_cols', $options['jbxCatColumns_cols']);
			update_option('jbxCatColumns_feat', $options['jbxCatColumns_feat']);
			update_option('jbxCatColumns_cats', $options['jbxCatColumns_cats']);
			
			delete_option('jbxCatColumns_Archive');
		}
		
	}
	
	function jbx_deactivate() {
		
		//Get all the plugin options and store them in an array/
		$options = array();
		$options['jbxCatColumns_cols'] = get_option('jbxCatColumns_cols');
		$options['jbxCatColumns_feat'] = get_option('jbxCatColumns_feat');
		$options['jbxCatColumns_cats'] = get_option('jbxCatColumns_cats');
		
		//Save all the individual options in 1 record, incase plugin is reactivated//
		update_option('jbxCatColumns_Archive', serialize($options));
		
		//Now Delete all the individual options since we have them stored as 1 record//
		delete_option('jbxCatColumns_cols');
		delete_option('jbxCatColumns_feat');
		delete_option('jbxCatColumns_cats');
	}
	
	/* Upon delete, get rid of the settings data */
	function jbxPlugin_delete() {
		delete_option('jbxCatColumns_Archive');
	}

	function getNumCols() {
		if (get_option('jbxCatColumns_cols') != false) {
			$field = get_option('jbxCatColumns_cols');
			return $field;
		} else {
			return '';
		}
	}
	
	function getFeat() {
		if (get_option('jbxCatColumns_feat') != false) {
			$field = get_option('jbxCatColumns_feat');
			return $field;
		} else {
			return '';
		}
	}
	
	function getSelectedCats() {
		if (get_option('jbxCatColumns_cats') != false) {
			$field = get_option('jbxCatColumns_cats');
			return $field;
		} else {
			return '';
		}
	}
	
	function display($n) {
		
		//Grab options from the DB//
		$cols = get_option('jbxCatColumns_cols');
		$feat = get_option('jbxCatColumns_feat');
		$cats = unserialize(get_option('jbxCatColumns_cats'));
		
		//Get setup the column classes//
		switch ($cols) {
			case 2: $col_class = 'jbx_one_half'; break;
			case 3: $col_class = 'jbx_one_third'; break;
			case 4: $col_class = 'jbx_one_forth'; break;
			case 5: $col_class = 'jbx_one_fifth'; break;
			case 6: $col_class = 'jbx_one_sixth'; break;
		}
		
		$num_cats = count($cats);
		
		//Setup Column HTML//
		$html = "";
		
		$html .="<div id='jbx-wrap'>";
		for ($i=1; $i<=$num_cats; $i++) { 
			$cat_name = get_category($cats[$i-1]);
			
			$posts = get_posts(array('posts_per_page' => $n, 'category' => $cats[$i-1]));
			
			
			$html .="<div class='".$col_class." jbx-block'>";
				$html .="<h3><a href=".get_category_link($cat_name->term_id).">".$cat_name->name."</a></h3>";
				
				foreach ($posts as $p) {
					$html .="<div class='jbx-item-wrap'>";
					if ($feat == 1) {
						if ($n == 0) {
							if (has_post_thumbnail( $p->ID ) ) {
								$image = wp_get_attachment_image_src( get_post_thumbnail_id( $p->ID ), 'single-post-thumbnail' ); 
								$html .="<div class='jbx-feat-img'><img src='".$image[0]."' alt='alt' /></div>";
							}
						}
					} elseif ($feat == 2) {
						
						if (has_post_thumbnail( $p->ID ) ) {
							$image = wp_get_attachment_image_src( get_post_thumbnail_id( $p->ID ), 'single-post-thumbnail' ); 
							$html .="<div class='jbx-feat-img'><img src='".$image[0]."' alt='alt' /></div>";
						}
					}
					$html .="<div class='jbx-date'>".date('F d, Y', strtotime($p->post_date))."</div>";
					$html .="<div class='jbx-title'><a href='".get_permalink($p->ID)."'>".$p->post_title."</a></div>";
					$html .="</div>";
				}
				
			$html .="</div>";
			if ($i % $cols == 0)  {
				$html .="<div class='jbx-clear'></div>";
			}
			
		} 
		$html .="<div class='jbx-clear'></div>";
		$html .="</div>";
					
		echo $html;
	
	}
	
}


$jbxCatColumns = new jbxCatColumns();

function display_jbxCatColumns($n = 4) {
	Global $jbxCatColumns;
	$jbxCatColumns->display($n);
}

/* Register activate/deactivate functions */
register_activation_hook(__FILE__, array($jbxCatColumns, 'jbx_activate'));
register_deactivation_hook(__FILE__, array($jbxCatColumns, 'jbx_deactivate'));
register_uninstall_hook(__FILE__, array($jbxCatColumns, 'jbxPlugin_delete'));

function jbxCatColumnsStyles() {
    wp_enqueue_style( 'jbxCatColumnsStyles', plugin_dir_url( __FILE__ ) . 'jbxCatColumns-style.css', array(), '1.0', 'screen' );
}

add_action( 'wp_enqueue_scripts', 'jbxCatColumnsStyles' );
