=== Plugin Name ===
Plugin Name: JBX Category Columns
Plugin URI: http://www.jaybuddy.com
Author URI: http://www.jaybuddy.com/
Author: Jay Pedersen
Contributors: thejaybuddy
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=LVMLAE6L2U8V2&lc=US&item_name=Jaybuddy%2c%20Inc&item_number=1234&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: category, category posts, category columns, columns, thejaybuddy, post columns
Requires at least: 3.4.0
Tested up to: 4.1
Stable tag: trunk
Version: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

JBX Category Columns displays latest posts from selected categories in 2-6 columns.

== Description ==

JBX Category Columns displays any number latest posts from selected categories in a column format. You can choose to display 2-6 columns. You also have the ability to display a featured image with either the first post, all posts, or no posts.  

== Installation ==

1. Upload all the `jbx-catcolumns` folder and all its contents to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Place `<?php display_jbxCatColumns(); ?>` in your template, outside the loop, where ever you would like the contents to be displayed.

== Usage ==

Add the following tag to any template file. Keep the tag outside the loop. 

`<?php display_jbxCatColumns(); ?>`

The default number of posts per category the plugin will show is 4. You can specify the number of posts to show in each category by passing a number to to the function. See below:

`<?php display_jbxCatColumns(3); ?>` // Displays 3 posts
`<?php display_jbxCatColumns(7); ?>` // Displays 7 posts

Styles can be edited in the stylesheet included in the jbx-catcolumns plugin folder. The file is entitled `jbxCatColumns-style.css`.

== Screenshots == 

1. Admin options
2. Frontend example


== Changelog ==

= 1.0 =
* Initial plugin release.

