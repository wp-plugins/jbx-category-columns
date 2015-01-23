<?php
/*
 Plugin Name: JBX Category Columns
 Plugin URI: http://www.jaybuddy.com/
 Description: This plugin displays x number of posts in each category in 2-6 columns.
 Version: 1.1
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

	function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'jbxCatColumnsStyles' ) );
		add_shortcode( 'jbx', array( $this, 'jbx_shortcode' ) );
	}

	function jbxCatColumnsStyles() {
    	wp_enqueue_style( 'jbxCatColumnsStyles', plugin_dir_url( __FILE__ ) . 'jbxCatColumns-style.css', array(), '1.1', 'screen' );
	}

	// [jbx columns='3' names='name1, name2, name3' numberposts='5' images='true'] //
	function jbx_shortcode( $atts ) {
		$a = shortcode_atts( array(
			'columns' => '3',
			'names' => '',
			'numberposts' => '3',
			'images' => 'true'
		), $atts);

		$cats = explode( ',', str_replace( " " , "", $a['names'] ) );

		//Check to make sure we have the same number of categories as columns//
		$cat_count = count($cats);

		//If we have more columns than categories, adjust the columns to be equal to categories.//
		if ( $a['columns'] > $cat_count ) {
			$a['columns'] = $cat_count;
		}
		
		switch ($a['columns']) {
			case 2: $col_class = 'jbx_one_half'; break;
			case 3: $col_class = 'jbx_one_third'; break;
			case 4: $col_class = 'jbx_one_forth'; break;
			case 5: $col_class = 'jbx_one_fifth'; break;
			case 6: $col_class = 'jbx_one_sixth'; break;
		}
		
		$html = "";
		$html .="<div id='jbx-wrap'>";

		for ($i=1; $i<=$a['columns']; $i++) { 

			$cat_ID = get_cat_ID( $cats[$i-1] );
			$cat = get_category( $cat_ID );
			
			$posts = get_posts( array( 'posts_per_page' => $a['numberposts'], 'category_name' => $cat->name ) );
			
			$html .="<div class='".$col_class." jbx-block'>";
				$html .="<h3><a href=".get_category_link($cat->term_id).">".$cat->name."</a></h3>";
				
				foreach ($posts as $p) {
					$html .="<div class='jbx-item-wrap'>";
					if ($feat == 'true') {
						if ($n == 0) {
							if (has_post_thumbnail( $p->ID ) ) {
								$image = wp_get_attachment_image_src( get_post_thumbnail_id( $p->ID ), 'single-post-thumbnail' ); 
								$html .="<div class='jbx-feat-img'><img src='".$image[0]."' alt='alt' /></div>";
							}
						}
					} else {
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
			if ($i % $a['columns'] == 0)  {
				$html .="<div class='jbx-clear'></div>";
			}
			
		} 
		$html .="<div class='jbx-clear'></div>";
		$html .="</div>";
					
		return $html;
	}
	
}

$jbxCatColumns = new jbxCatColumns();