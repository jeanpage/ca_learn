<?php
if(isset($_POST['mokis_social_data_left'])){
	update_option('mokis_social_data_left', $_POST['mokis_social_data_left']);
	update_option('mokis_social_data_top', $_POST['mokis_social_data_top']);
	update_option('mokis_social_data_left_pages', $_POST['mokis_social_data_left_pages']);
	update_option('mokis_social_data_top_pages', $_POST['mokis_social_data_top_pages']);
	update_option('mokis_social_data_left_home', $_POST['mokis_social_data_left_home']);
	update_option('mokis_social_data_top_home', $_POST['mokis_social_data_top_home']);
	update_option('mokis_show_googleplus', $_POST['mokis_show_googleplus']);
	update_option('mokis_show_digg', $_POST['mokis_show_digg']);
	update_option('mokis_show_twitter', $_POST['mokis_show_twitter']);
	update_option('mokis_show_facebook', $_POST['mokis_show_facebook']);
	update_option('mokis_show_stumble', $_POST['mokis_show_stumble']);
	update_option('mokis_show_linkedin', $_POST['mokis_show_linkedin']);
	update_option('mokis_show_pinterest', $_POST['mokis_show_pinterest']);
	update_option('mokis_show_reddit', $_POST['mokis_show_reddit']);
	update_option('mokis_social_data_posts', $_POST['mokis_social_data_posts']);
	update_option('mokis_social_data_pages', $_POST['mokis_social_data_pages']);
	update_option('mokis_social_data_home', $_POST['mokis_social_data_home']);
	update_option('mokis_social_bgcolor', $_POST['mokis_social_bgcolor']);
	update_option('mokis_social_border_color', $_POST['mokis_social_border_color']);
	update_option('mokis_social_ignore_posts', $_POST['mokis_social_ignore_posts']);
	update_option('mokis_social_ignore_pages', $_POST['mokis_social_ignore_pages']);
	update_option('mokis_social_static_homepage', $_POST['mokis_social_static_homepage']);
	
	$mokisfloatsuccess = "Settings Updated Successfully!";
}
?>

<iframe src="http://www.android-advice.com/plugin-news.php" name="right" scrolling="auto" frameborder="0" height = "150px" width = "800px">
</iframe>

<h3 style="color:green;"><?php echo $mokisfloatsuccess; ?></h3>

<form method="post" action="">
<h2>AA's Social Floater Plugin &raquo; Settings</h2>
<table class="widefat">
	<thead>
	&nbsp;&nbsp;&nbsp;<tr>
		 <th><input type="submit" name="submit" value="<?php esc_attr_e('Save Settings'); ?>" class="button-primary" style="padding:8px;" /></th>
	   </tr>
	</thead>
	<tfoot>
	   <tr>
		 <th><input type="submit" name="submit" value="<?php esc_attr_e('Save Settings'); ?>" class="button-primary" style="padding:8px;" /></th>
	   </tr>
	</tfoot>
	<tbody>
	   <tr>
		 <td style="padding:25px;font-family:Verdana, Geneva, sans-serif;color:#666;">
		 	<h2>Post/Page Location Settings:</h2>
		 	<h3 style="padding:0;margin:0;">Posts - location on posts</h3>
             <label for="msf_float_left">
                 <p><input type="text" name="mokis_social_data_left" value="<?php echo get_option('mokis_social_data_left'); ?>" style="width:40px;" /> :<b>From Center of Page (pixels)</b> - Higher number moves bar to the left lower number to the right.</p>
             </label>
             <label for="mokis_social_data_top">
                 <p><input type="text" name="mokis_social_data_top" value="<?php echo get_option('mokis_social_data_top'); ?>" style="width:40px;" /> :<b>From Top of Page (pixels)</b> - Higher number moves bar down lower number up.</p>
             </label>
             <br />
             <h3 style="padding:0;margin:0;">Pages - location on pages</h3>
             <label for="msf_float_left_pages">
                 <p><input type="text" name="mokis_social_data_left_pages" value="<?php echo get_option('mokis_social_data_left_pages'); ?>" style="width:40px;" /> :<b>From Center of Page (pixels)</b> - Higher number moves bar to the left lower number to the right.</p>
             </label>
             <label for="mokis_social_data_top_pages">
                 <p><input type="text" name="mokis_social_data_top_pages" value="<?php echo get_option('mokis_social_data_top_pages'); ?>" style="width:40px;" /> :<b>From Top of Page (pixels)</b> - Higher number moves bar down lower number up.</p>
             </label>
             <br />
             <h3 style="padding:0;margin:0;">Home Page - location on homepage</h3>
             <label for="msf_float_left_pages">
                 <p><input type="text" name="mokis_social_data_left_home" value="<?php echo get_option('mokis_social_data_left_home'); ?>" style="width:40px;" /> :<b>From Center of Page (pixels)</b> - Higher number moves bar to the left lower number to the right.</p>
             </label>
             <label for="mokis_social_data_top_pages">
                 <p><input type="text" name="mokis_social_data_top_home" value="<?php echo get_option('mokis_social_data_top_home'); ?>" style="width:40px;" /> :<b>From Top of Page (pixels)</b> - Higher number moves bar down lower number up.</p>
             </label>
             
             <hr />
             
             <h2>Custom Style Settings:</h2>
             <label for="mokis_social_bgcolor">
                 <p><input type="text" name="mokis_social_bgcolor" value="<?php echo get_option('mokis_social_bgcolor'); ?>" style="width:100px;" /> :<b>Floating Bar Background Color (HEX i.e #FFFFFF for white)</b></p>
             </label>
             <label for="mokis_social_border_color">
                 <p><input type="text" name="mokis_social_border_color" value="<?php echo get_option('mokis_social_border_color'); ?>" style="width:100px;" /> :<b>Floating Bar Border Color (HEX i.e #999999 for gray)</b></p>
             </label>
             <hr />
             
             <?php
             if(get_option('mokis_show_googleplus') == 1) {$gpluschecked = checked;}
			 if(get_option('mokis_show_digg') == 1) {$diggchecked = checked;}
			 if(get_option('mokis_show_twitter') == 1) {$twitterchecked = checked;}
			 if(get_option('mokis_show_facebook') == 1) {$facebookchecked = checked;}
			 if(get_option('mokis_show_stumble') == 1) {$stumblechecked = checked;}
			 if(get_option('mokis_show_linkedin') == 1) {$linkedinchecked = checked;}
			 if(get_option('mokis_show_pinterest') == 1) {$pinterestchecked = checked;}
			 if(get_option('mokis_show_reddit') == 1) {$redditchecked = checked;}
			 if(get_option('mokis_social_data_posts') == 1) {$postsshowchecked = checked;}
			 if(get_option('mokis_social_data_pages') == 1) {$pagesshowchecked = checked;}
			 if(get_option('mokis_social_data_home') == 1) {$homeshowchecked = checked;}
			 ?>
             
             <h2>Social Network Display Settings:</h2>
             <h3>We recommend no more than 5 sharing options for SEO although to each his own :)</h3>
             <label for="mokis_show_googleplus">
                 <p><input type="checkbox" name="mokis_show_googleplus" value="1" <?php echo $gpluschecked; ?> /> :<b>Google Plus</b> - Check this box to show Google Plus on your Floating bar.</p>
             </label>
             <label for="mokis_show_digg">
                 <p><input type="checkbox" name="mokis_show_digg" value="1" <?php echo $diggchecked; ?> /> :<b>Digg</b> - Check this box to show Digg on your Floating bar.</p>
             </label>
             <label for="mokis_show_twitter">
                 <p><input type="checkbox" name="mokis_show_twitter" value="1" <?php echo $twitterchecked; ?> /> :<b>Twitter</b> - Check this box to show Twitter on your Floating bar.</p>
             </label>
             <label for="mokis_show_facebook">
                 <p><input type="checkbox" name="mokis_show_facebook" value="1" <?php echo $facebookchecked; ?> /> :<b>Facebook</b> - Check this box to show Facebook on your Floating bar.</p>
             </label>
             <label for="mokis_show_stumble">
                 <p><input type="checkbox" name="mokis_show_stumble" value="1" <?php echo $stumblechecked; ?> /> :<b>Stumble</b> - Check this box to show Stumble on your Floating bar.</p>
             </label>
             <label for="mokis_show_linkedin">
                 <p><input type="checkbox" name="mokis_show_linkedin" value="1" <?php echo $linkedinchecked; ?> /> :<b>LinkedIn</b> - Check this box to show LinkedIn on your Floating bar.</p>
             </label>
             <label for="mokis_show_pinterest">
                 <p><input type="checkbox" name="mokis_show_pinterest" value="1" <?php echo $pinterestchecked; ?> /> :<b>Pinterest</b> - Check this box to show Pinterest on your Floating bar.</p>
             </label>
             <label for="mokis_show_reddit">
                 <p><input type="checkbox" name="mokis_show_reddit" value="1" <?php echo $redditchecked; ?> /> :<b>Reddit</b> - Check this box to show Reddit on your Floating bar.</p>
             </label>
             
             <hr />
             
             <h2>Where to Show the Floating Bar:</h2>
             <label for="mokis_social_data_posts">
                 <p><input type="checkbox" name="mokis_social_data_posts" value="1" <?php echo $postsshowchecked; ?> /> :<b>Posts</b> - Check this box to show the floating social bar on posts.</p>
             </label>
             <label for="mokis_social_ignore_posts">
                 <p><input type="text" name="mokis_social_ignore_posts" value="<?php echo get_option('mokis_social_ignore_posts'); ?>" style="width:200px;" /> :<b>Posts to Ignore (post id)</b> - seperate ids with commas i.e. 1,2,3</p>
             </label>
             <label for="mokis_social_data_pages">
                 <p><input type="checkbox" name="mokis_social_data_pages" value="1" <?php echo $pagesshowchecked; ?> /> :<b>Pages</b> - Check this box to show the floating social bar on pages.</p>
             </label>
             <label for="mokis_social_ignore_pages">
                 <p><input type="text" name="mokis_social_ignore_pages" value="<?php echo get_option('mokis_social_ignore_pages'); ?>" style="width:200px;" /> :<b>Pages to Ignore (page id)</b> - seperate ids with commas i.e. 1,2,3</p>
             </label>
             <label for="mokis_social_data_home">
                 <p><input type="checkbox" name="mokis_social_data_home" value="1" <?php echo $homeshowchecked; ?> /> :<b>Homepage</b> - Check this box to show the floating social bar on homepage.</p>
             </label>
             <label for="mokis_social_static_homepage">
                 <p><input type="text" name="mokis_social_static_homepage" value="<?php echo get_option('mokis_social_static_homepage'); ?>" style="width:40px;" /> :<b>Homepage's page ID if using Static Home Page</b> - if not using set to -1 or all pages will show the bar (just for you pwizard :)</p>
             </label>
             
             <hr /><br />
             <a href="http://www.brandonorndorff.com/wordpress/plugins/aas-digg-digg-alternative-wordpress-plugin/" title="Digg Digg Alternative Social Toolbar" target="_blank">AA's Social Toolbar</a> - This is where you can request features, report bugs, or just talk about the plugin in general.
         </td>
	   </tr>
	</tbody>
</table>
</form>