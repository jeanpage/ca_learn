<?php

// includes
include_once('lib/backend.php');
include_once('lib/shortcodes.php');
include_once('lib/simple_html_dom.php');
include_once('lib/acf/advanced-custom-fields/acf.php' );
include_once('lib/acf/acf-repeater/acf-repeater.php');
include_once('lib/acf/acf-options-page/acf-options-page.php');
include_once('lib/ca-custom-fields.php');
include_once('lib/ca-filters.php');


if( class_exists( 'C_Photocrati_Resource_Manager' ) ) {
	define( 'NGG_DISABLE_RESOURCE_MANAGER', true );
}


add_action('genesis_setup','child_theme_setup', 15);
function child_theme_setup() {

	define( 'CHILD_THEME_VERSION', filemtime( get_stylesheet_directory() . '/style.css' ) );

	remove_action( 'genesis_meta', 'genesis_load_stylesheet' );

	// Enable HTML5 markup
	add_theme_support( 'html5' );
	// Add Viewport meta tag for mobile browsers
	add_theme_support( 'genesis-responsive-viewport' );

	add_action( 'init', 'ca_enqueue_styles', 4 );
	add_action( 'init', 'ca_enqueue_scripts', 8 );

	add_action('genesis_doctype','ca_ie_support', 20);

	add_action('genesis_before', 'ca_fb_sdk');

	add_action( 'wp_head', 'ca_header_vars' );

	add_action( 'genesis_before', 'ca_network_header' );

	add_action( 'genesis_header', 'ca_site_header' );

	add_action( 'genesis_header', 'ca_mobile_header' );

	add_action( 'genesis_before', 'ca_remove_header' );

	add_action( 'genesis_after_header', 'genesis_do_nav' );

	add_action( 'genesis_after_header', 'ca_header_banner' );

	add_action( 'genesis_after', 'ca_modal_box' );

	add_action('get_header','ca_change_genesis_sidebar');

	add_action('genesis_before_sidebar_widget_area', 'ca_contact_callout');

	add_action( 'genesis_before', 'ca_remove_breadcrumb' );

	add_action( 'genesis_before', 'ca_move_breadcrumb' );

	add_action( 'genesis_before_content_sidebar_wrap', 'ca_search_area' );

	add_action( 'genesis_after_header', 'ca_social_shares' );

	add_action( 'genesis_before_content_sidebar_wrap', 'ca_mega_menu' );

	add_action( 'genesis_before', 'ca_remove_footer' );

	add_action( 'genesis_footer', 'ca_add_footer' );

	add_action( 'genesis_footer', 'ca_network_footer' );

	add_action( 'genesis_footer', 'ca_mobile_footer' );

	add_action( 'genesis_after_content_sidebar_wrap', 'ca_home_columns' );

	add_action( 'genesis_after_loop', 'ca_page_slider' );

	add_action('genesis_before_loop', 'ca_category_heading');

	add_action('genesis_before_loop', 'ca_show_cat_slider');

	add_action( 'genesis_before', 'ca_remove_meta_on_categories' );

	add_filter('excerpt_more', 'ca_get_read_more_link');

	add_filter( 'the_content_more_link', 'ca_get_read_more_link' );

	add_action( 'genesis_before', 'ca_remove_pagination' );

	add_action( 'genesis_before_loop', 'ca_top_pagination' );

	add_action( 'genesis_after_loop', 'ca_bottom_pagination' );

	add_action( 'genesis_entry_header', 'ca_post_comment_flag' );

	add_filter('body_class','mc_browser_body_class');


}



// global scripts
function ca_enqueue_styles() {

	if ( !is_admin() ) {
		$IE8 = ( ereg( 'MSIE 8',$_SERVER['HTTP_USER_AGENT'] ) ) ? true : false;
		global $is_IE;

		// css
		wp_enqueue_style( 'ca-style', get_stylesheet_uri(), array(), CHILD_THEME_VERSION );

		if ( $IE8 == 1 ) {
			wp_deregister_style( 'ca-style' );
			wp_enqueue_style( 'ca-ie8-style', get_stylesheet_directory_uri() .'/ie8.css', array(), CHILD_THEME_VERSION );
			wp_enqueue_style( 'ca-ie8-print-style', get_stylesheet_directory_uri() .'/ie8print.css', array( 'ca-ie8-style' ), CHILD_THEME_VERSION );

		}
	}

}
// global scripts
function ca_enqueue_scripts() {

	if ( !is_admin() ) {
		$IE8 = ( ereg( 'MSIE 8',$_SERVER['HTTP_USER_AGENT'] ) ) ? true : false;
		global $is_IE;

		// javascripts
		if( $is_IE ) {

			wp_enqueue_script( 'html5shiv', "http://html5shiv.googlecode.com/svn/trunk/html5.js", false );
			wp_enqueue_script( 'respond',  get_stylesheet_directory_uri().'/js/respond.js', false );
			wp_enqueue_script( 'selectivizr',  get_stylesheet_directory_uri().'/js/selectivizr-min.js', array('jquery'), false );

		}

		wp_enqueue_script( 'modernizr', get_stylesheet_directory_uri().'/js/modernizr.custom.js', false );
		wp_enqueue_script( 'ecom', 'http://demandware.edgesuite.net/aabh_prd/on/demandware.static/Sites-Appliance-Site/-/default/js/app.js', false );
		wp_enqueue_script( 'frontend', get_stylesheet_directory_uri().'/js/min/frontend.min.js', array('jquery'),'',true );

	}
}

/*function ca_script_checks() {
	var_export( wp_style_is( 'acx-widget-si-style', 'enqueued' ) );
	$badnextgen = wp_script_is( 'ngg-ajax', 'registered' );
	var_export( $badnextgen );
}
*/
// adding ie support to head
function ca_ie_support() {
	echo '<!--[if lt IE 7]><html class="lt-ie9 lt-ie8 lt-ie7" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"><![endif]-->';
	echo '<!--[if IE 7]><html class="lt-ie9 lt-ie8" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"><![endif]-->';
	echo '<!--[if IE 8]><html class="lt-ie9" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"><![endif]-->';
	echo '<!--[if gt IE 8]><!--><html xmlns="http://www.w3.org/1999/xhtml"><!--<![endif]-->';
}

// add fb sdk after body tag
function ca_fb_sdk() {
	echo "<div id='fb-root'></div>";
	echo "<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = 'http://connect.facebook.net/en_US/all.js#xfbml=1&appId=293127030793987';
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>";
}

if ( !defined('DESKTOPSERVER') ){
	define( 'ACF_LITE' , true );
}

/** Header Functions */
// header vars js
function ca_header_vars(){
	$html = file_get_html('http://www.compactappliance.com/');
	foreach($html->find('#headerVars script') as $element)
	   echo $element;
}
//Remove Header
function ca_remove_header(){
	remove_action( 'genesis_header', 'genesis_do_header' );
}
add_action( 'genesis_before', 'ca_remove_header' );


// network header
function ca_network_header(){
	get_template_part('partials/network', 'header');
}


// site header
function ca_site_header(){
	get_template_part('partials/site', 'header');
}

// mobile header
function ca_mobile_header(){
	get_template_part('partials/mobile', 'header');
}

function mobile_search_form() {?>
	<form id="mobile-searchform" action="<?php echo home_url('/'); ?>" method="get" role="search">
		<section class="row collapse">
			<section class="small-10 columns">
				<input type="text" id="mobile-search" placeholder="Search Learning Center" name="s" value="<?php the_search_query(); ?>" />
			</section>
			<section class="small-2 columns">
				<button type="submit" id="mobile-search-button" class="postfix button">
					<i class="icon-search"></i>
				</button>
			</section>
		</section>
	</form>
<?php }

//moving genesis menu
function ca_remove_menu() {
	remove_action( 'genesis_after_header', 'genesis_do_nav' );
}

//header banner
function ca_header_banner(){
	$html = file_get_html('http://www.compactappliance.com/');
	foreach($html->find('#headerBanner') as $element)
	   echo $element;
}

//modal box
function ca_modal_box() {
echo' <div id="HeaderModal" class="reveal-modal large" data-reveal>';
	the_field('lightbox','option');
  echo '<a class="close-reveal-modal">&#215;</a>';
echo '</div>';
}

/** Sidebar Functions */
//remove sidebars
function ca_remove_sidebars(){
	unregister_sidebar( 'header-right' );
	unregister_sidebar( 'sidebar-alt' );
}

// Register home sidebar
function ca_new_sidebars() {
	genesis_register_sidebar(
		array(
			'id' => 'home-sidebar',
			'name' => 'Home Sidebar',
			'description' => 'This is the sidebar for the home page.',
		)
	);
}
add_action('init', 'ca_remove_sidebars');
add_action('init', 'ca_new_sidebars');


function ca_change_genesis_sidebar() {
	if ( is_front_page()) {
		remove_action( 'genesis_sidebar', 'genesis_do_sidebar' ); //remove the default genesis sidebar
		add_action( 'genesis_sidebar', 'ca_do_sidebar' ); //add an action hook to call the function for my custom sidebar
	}
}

//Function to output my custom sidebar
function ca_do_sidebar() {
	dynamic_sidebar( 'home-sidebar' );
}

//callout before sidebar
function ca_contact_callout() {
	echo '<section id="contact-callout">';
				echo '<section class="user"></section>';
		echo '<section class="content">';
			_e('<p class="expert">Need an Expert? Call:</p>');
			_e('<p class="phone"><span><a href="tel:+18002976076">1-800-297-6076</a></span></p>');
		echo '</section>';
	echo '</section>';
}

/** Before Content/Sidebar Wrap */
// moving breadcrumbs
function ca_remove_breadcrumb(){
	remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );
}

function ca_move_breadcrumb(){
	add_action( 'genesis_after_header', 'genesis_do_breadcrumbs' );
}

//search area above content
function ca_search_area() {?>
<section id="search-area">
	<section class="search-wrap">
		<section class="learn-share">
			<p><?php _e('Learn & Share');?></p>
			<a href="#" id="catdrop" class="small button dropdown">by <span>Category</span></a><br>
		</section>
		<?php echo body_search_form();?>
	</section>
</section>
<?php  }

function body_search_form() {?>
	<form id="searchform" action="<?php echo home_url('/'); ?>" method="get" role="search">
		<section class="row collapse">
			<section class="large-1 columns hide-for-small">
				<div id="search-prefix"><?php _e('Search');?></div>
			</section>
			<section id="input-wrap" class="small-8 large-7 columns">
				<input type="text" id="search" placeholder="Learn something new today..." name="s" value="<?php the_search_query(); ?>" />
			</section>
			<section class="small-4 large-4 columns">
				<button type="submit" id="search-button" class="button radius">
					<i class="icon-search"></i><?php _e('Search');?>
				</button>
			</section>
		</section>
	</form>
<?php }

//social share buttons
function ca_social_shares() {
	echo '<section id="social-wrap">';
	if( function_exists('ADDTOANY_SHARE_SAVE_KIT') ) { ADDTOANY_SHARE_SAVE_KIT(); }
	echo '</section>';
}

// mega menu/home page columns
function ca_mega_columns(){
	$ca_columns = _get_field( 'ca_column','option' );
	$colcount = count( $ca_columns );
	if ( $ca_columns ) : ?>
		<ul class="small-block-grid-1 large-block-grid-<?php echo $colcount;?> top-set">
			<?php
			foreach ( $ca_columns as $column ) : ?>
				<li class="megacol">
					<?php
					$col_head = $column['column_heading'];
					$col_sub = $column['column_subhead'];
					$see_all_link = $column['see_all_link'];
					$see_all_label = $column['see_all_label']; ?>

				 	<h4><?php echo $col_head; ?></h4>
					<h5><?php echo $col_sub; ?></h5>
					<?php $col_links = $column['column_link']; ?>
					<ul>
						<?php
						foreach( $col_links as $link ) :
							if ( $link ) :
								$link_url = $link['link_url'];
								$link_text = $link['link_text']; ?>
								<li><a href="<?php echo $link_url;?>"><?php echo $link_text; ?></a></li>
							<?php
							endif;
						endforeach;
						?>
					</ul>
					<?php if ( $see_all_link ) : ?>
					<p class="see-all arrow"><a href="<?php echo $see_all_link; ?>"><?php echo $see_all_label; ?></a></p>
					<?php endif; ?>
				</li>
				<?php //var_export( $column ); ?>
			<?php endforeach; ?>
		</ul>
	<?php
	endif;

/*		if( have_rows('columns','option') ):
		echo '<ul class="small-block-grid-1 large-block-grid-3 top-set">';
			 while ( have_rows('columns','option') ) : the_row();
			 echo '<li>';
				echo '<h4>'; the_sub_field('heading','option'); echo '</h4>';
				echo '<section class="sub-content">'; the_sub_field('content','option'); echo '</section>';
				echo '<h6>'; the_sub_field('sub_heading','option'); echo '</h6>';
			 echo '</li>';
			 endwhile;
		echo '</ul>';
		endif;

		echo '<ul class="small-block-grid-1 large-block-grid-3 bottom-set">';
			echo '<li>';
				if( have_rows('product_articles','option')):
				echo '<section class="product-articles">';
				while (have_rows('product_articles','option') ) : the_row();
					$articles = get_sub_field('article','option');
					foreach ($articles as $article) {
						$title = get_the_title($article->ID);
						$permalink = get_permalink($article->ID);
						echo '<p>';
						echo '<a href="'.$permalink.'">'.$title.'</a>';
						echo '</p>';
					}
				endwhile;
				echo '</section>';
				endif;

				echo '<p>Or choose from Featured Category</p>';
				if( have_rows('product_category','option') ): while ( have_rows('product_category','option') ) : the_row();
					$product_id = get_sub_field('category_name','option');
					$product_name = get_cat_name( $product_id );
					$product_link = get_sub_field('category_link','option');
					echo '<p><a class="arrow" href="'.$product_link.'">'.$product_name.'</a></p>';
				endwhile; endif;
			echo '</li>';

			echo '<li>';
				if( have_rows('lifestyle_articles','option')):
				echo '<section class="lifestyle-articles">';
				while (have_rows('lifestyle_articles','option') ) : the_row();
					$articles = get_sub_field('article','option');
					foreach ($articles as $article) {
						$title = get_the_title($article->ID);
						$permalink = get_permalink($article->ID);
						echo '<p>';
						echo '<a href="'.$permalink.'">'.$title.'</a>';
						echo '</p>';
					}
				endwhile;
				echo '</section>';
				endif;

				$lifestyle = get_category_by_slug('lifestyle');
				$lifestyle_id = $lifestyle->term_id;
				$lifestyle_url = get_category_link( $lifestyle_id);
				echo '<a class="see-all arrow" href="'.$lifestyle_url.'">See All Articles</a>';
			echo '</li>';

			echo '<li>';
				echo ca_mega_news();
				$news = get_category_by_slug('news-events');
				$news_id = $news->term_id;
				$news_url = get_category_link( $news_id);
				echo '<a class="arrow see-all" href="'.$news_url.'">See All News</a>';
			echo '</li>';

		echo '</ul>';
*/
}

function ca_mega_news() {
	$args = array(
		'posts_per_page' => 5,
		'no_found_rows' => true, // counts posts, remove if pagination required
		'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
		'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
	);
	$loop = new WP_Query( $args );
	echo '<section class="news">';
		if ($loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post();
			$permalink = get_permalink(); $title = get_the_title();
			echo '<p><a href="'.$permalink.'">'.$title.'</a></p>';
		endwhile;  endif; wp_reset_query();
	echo '</section>';
}

function ca_mobile_news() {
	$args = array(
		'posts_per_page' => 5,
		'no_found_rows' => true, // counts posts, remove if pagination required
		'update_post_term_cache' => false, // grabs terms, remove if terms required (category, tag...)
		'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
	);
	$loop = new WP_Query( $args );
	echo '<section class="news">';
		if ($loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post();
			$permalink = get_permalink(); $title = get_the_title();
			echo '<a href="'.$permalink.'"><p>'.$title.'</p></a>';
		endwhile;  endif; wp_reset_query();
	echo '</section>';
}

// displaying mega columns as a drop down menu
function ca_mega_menu() {
	echo '<section id="mega-menu">';
		echo ca_mega_columns();
	echo '</section>';
}
/** Footer Functions */
// Remove the Footer
function ca_remove_footer(){
	remove_action( 'genesis_footer', 'genesis_do_footer' );
}

// Add our Footer
function ca_add_footer(){
	echo '<h4 class="special-header">';
		_e('popular articles');
	echo '</h4>';

	echo '<section id="footer-posts">';
		ca_footer_posts();
	echo '</section>';

	echo '<section id="footer-mailing">';
		echo '<div class="mailer-box">';
		_e('<p class="small-12 large-7 columns"><span>Want More?</span> Get the latest deals, news and subscribe today.</p>');
		echo '<form method="post" action="http://www.compactappliance.com/on/demandware.store/Sites-Appliance-Site/default/Newsletter-SubscribedFooter">';
			echo '<div class="small-12 large-5 columns">';
				echo '<div class="row collapse">';
					echo '<div class="large-7 columns">';
						echo '<input type="text" name="Email" id="control_EMAIL" label="Email" value="" placeholder="Email Address">';
					echo '</div>';
					echo '<div class="large-5 columns">';
						echo '<button class="small button radius">Subscribe</button>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</form>';
		echo '</div>';
	echo '</section>';


	echo '<section id="footer-social">';
		ca_footer_social_widgets();
	echo '</section>';

}

function ca_footer_posts() {
	$args = array(
		'posts_per_page' => 6,
		'no_found_rows' => true, // counts posts, remove if pagination required
		'update_post_meta_cache' => false, // grabs post meta, remove if post meta required
	);
	echo '<ul id="posts-wrap" class="small-block-grid-1 large-block-grid-3">';
		$loop = new WP_Query( $args ); if ($loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post();
		$excerpt = get_the_excerpt();
		$content = get_the_content();
		$permalink = get_permalink();
		$trimmed_excerpt = wp_trim_words( $excerpt,20, '' );
		$trimmed_content = wp_trim_words( $content,12, '' );
		echo '<li>';
		echo '<a href="'.$permalink.'">';
			echo '<div>';
				echo the_post_thumbnail();
				echo the_title('<h5 class="entry-title">','</h5>');
				echo '<section class="entry-content">';
				if(has_excerpt()) { echo($trimmed_excerpt); } else { echo($trimmed_content),"<a class=\"read-more\" href='". get_permalink( get_the_ID() ) . "'><span>...</span> Read More</a>"; }
			echo '</section>';
			echo '</div>';
		echo '</a>';
		echo '</li>';
		endwhile;  endif;
	echo '</ul>';
}

function ca_footer_social_widgets(){
	echo '<ul id="footer-social-widgets" class="small-block-grid-1 large-block-grid-3">';
		echo '<li>'.do_shortcode(get_field('footer-left', 'option')).'</li>';
		echo '<li>'.do_shortcode(get_field('footer-center', 'option')).'</li>';
		echo '<li>'.do_shortcode(get_field('footer-right', 'option')).'</li>';
	echo '</ul>';
}


//network footer
function ca_network_footer() {
	get_template_part('partials/network', 'footer');
}

// mobile footer
function ca_mobile_footer(){
	get_template_part('partials/mobile', 'footer');
}

/** Home Function */
// home mega columns
function ca_home_columns() {
	if(is_front_page()) {
		echo '<section id="home-columns-wrap">';
			echo ca_mega_columns();
		echo '</section>';
	}
}

/** Page Functions */
// page slider
function ca_page_slider(){
	if(is_page()) {
		if( have_rows('slide') ):
		echo '<div id="page-slider" class="royalSlider rsDefault">';
		while ( have_rows('slide') ) : the_row();
			$url = get_sub_field('slide_link');
			$image = get_sub_field('slide_image');
			$description = get_sub_field('slide_description');
			echo '<div>';
				echo '<a href="'.$url.'">';
					echo '<img src="'.$image.'"/>';
					echo '<div class="rsABlock">'.$description.'</div>';
				echo '</a>';
			echo '</div>';
		 endwhile;
		 echo '</div>';
		endif; wp_reset_query();
	}
}

/** single article functions */


/** category functions */
// add category title and subtitle
function ca_category_heading() {
	if(is_category()) {
		$queried_object = get_queried_object();
		$taxonomy = $queried_object->taxonomy;
		$term_id = $queried_object->term_id;

		$format = '<h1 class="entry-title">%s <small> -  %s</small></h1>';
		$title = single_cat_title('',  false);
		$subtitle = get_field('category_subtitle', $queried_object);
		$description = category_description();

		echo sprintf($format, $title, $subtitle);
		echo '<section class="cat-desc">'.category_description().'</section>';
	}
}



// featured category slider
function ca_show_cat_slider(){
	$queried_object = get_queried_object();
	$featuredcat = get_field('featured_category', $queried_object);
	if(is_category() && $featuredcat == true) {
		echo ca_cat_slider();
	}

}

function ca_cat_slider() {
	$queried_object = get_queried_object();
	if( have_rows('category_slider', $queried_object) ):
		echo '<div id="cat-slider" class="royalSlider rsDefault">';
		while ( have_rows('category_slider', $queried_object) ) : the_row();
			$url = get_sub_field('slide_link');
			$image = get_sub_field('slide_image');
			$description = get_sub_field('slide_description');
			echo '<div>';
				echo '<a href="'.$url.'">';
					echo '<img src="'.$image.'"/>';
					echo '<div class="rsABlock">'.$description.'</div>';
				echo '</a>';
			echo '</div>';
		 endwhile;  wp_reset_query();
		 echo '</div>';
	endif;
}

// remove entry header meta and entry meta footer if not a single post
function ca_remove_meta_on_categories(){
	if(!is_single()) {
		remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
		remove_action( 'genesis_entry_footer', 'genesis_post_meta' );
	}
}

// only show 5 posts
function ca_category_posts_counts( $query ) {
	$queried_object = get_queried_object();
	 if ( !is_admin() && $query->is_main_query() && is_category()) {
		if(get_field('featured_category', $queried_object) == true) {
			$query->set( 'posts_per_page', 3);
			} else {
				$query->set( 'posts_per_page', 5);
			}
		return;
	}
}
add_action( 'pre_get_posts', 'ca_category_posts_counts', 1 );

// Add Read More Link to Excerpts
function ca_get_read_more_link() {
   return '&nbsp;<a href="' . get_permalink() . '" class="read-more">... Read&nbsp;More</a>';
}
// remove genesis pagination
function ca_remove_pagination() {
	remove_action( 'genesis_after_endwhile', 'genesis_posts_nav' );
}

// add our pagination
function ca_top_pagination() {
	if(is_category()) :
		echo '<p class="pagination">';
			echo 'Latest Articles | ';
			echo '<span class="pagination-heading">Page</span>';
			echo ca_category_pagination();
		echo '</p>';
	endif;
}

function ca_bottom_pagination() {
	if(is_category()) :
		echo '<p class="pagination">';
			echo '<span class="pagination-heading">Page</span>';
			echo ca_category_pagination();
		echo '</p>';
	endif;
}

function ca_category_pagination() {
	global $wp_query;

	$big = 999999999; // need an unlikely integer

	echo paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'current' => max( 1, get_query_var('paged') ),
		'total' => $wp_query->max_num_pages,
		'prev_next' => false,
		'show_all' => true
	) );
}

// add comment count
function ca_post_comment_flag() {
	if(is_category() || is_search() || is_home()) :
		echo '<section class="post_comment_flag hide-for-small">';
			echo '<a href="'.get_comments_link().'">';
			echo comments_number('0', '1', '%' );
			echo '</a>';
		echo '</section>';
	endif;
}

function mc_browser_body_class($classes) {
		global $is_lynx, $is_gecko, $is_IE, $is_opera, $is_NS4, $is_safari, $is_chrome, $is_iphone;
		if($is_lynx) $classes[] = 'lynx';
		elseif($is_gecko) $classes[] = 'gecko';
		elseif($is_opera) $classes[] = 'opera';
		elseif($is_NS4) $classes[] = 'ns4';
		elseif($is_safari) $classes[] = 'safari';
		elseif($is_chrome) $classes[] = 'chrome';
		elseif($is_IE) {
			$classes[] = 'ie';
			if(preg_match('/MSIE ([0-9]+)([a-zA-Z0-9.]+)/', $_SERVER['HTTP_USER_AGENT'], $browser_version))
			$classes[] = 'ie'.$browser_version[1];
		} else $classes[] = 'unknown';
		if($is_iphone) $classes[] = 'iphone';
		if ( stristr( $_SERVER['HTTP_USER_AGENT'],"mac") ) {
			$classes[] = 'osx';
		} elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"linux") ) {
			$classes[] = 'linux';
		} elseif ( stristr( $_SERVER['HTTP_USER_AGENT'],"windows") ) {
			$classes[] = 'windows';
		}
		return $classes;
}


/**
 * Return a custom field stored by the Advanced Custom Fields plugin
 *
 * @global $post
 * @param str $key The key to look for
 * @param mixed $id The post ID (int|str, defaults to $post->ID)
 * @param mixed $default Value to return if get_field() returns nothing
 * @return mixed
 * @uses get_field()
 */
function _get_field( $key, $id=false, $default='' ) {
  global $post;
  $key = trim( filter_var( $key, FILTER_SANITIZE_STRING ) );
  $result = '';

  if ( function_exists( 'get_field' ) ) {
	if ( isset( $post->ID ) && !$id )
	  $result = get_field( $key );
	else
	  $result = get_field( $key, $id );

	if ( $result == '' ) // If ACF enabled but key is undefined, return default
	  $result = $default;

  } else {
	$result = $default;
  }

  return $result;
}

// add wrap to deal w/ IE8 not recognizing "main" tag
function shim_div_open() {
	echo '<div class="content">';
}

function shim_div_close() {
	echo '</div><!-- close .content div -->';
}

if($is_IE) {
	add_action( 'genesis_before_content','shim_div_open' );
	add_action( 'genesis_after_content','shim_div_close' );
}