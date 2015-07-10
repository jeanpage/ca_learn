<?php

// change post title to include post sub title
 function ca_page_title_output( $title ) {
	if ( is_page())
		$title = sprintf( '<h1 class="entry-title">%s<small> - %s</small></h1>', apply_filters( 'genesis_post_title_text', get_the_title()), get_field('page_subtitle'));
	return $title;
}
add_filter( 'genesis_post_title_output', 'ca_page_title_output', 15 );

// Remove 'You are here' from the front of breadcrumb trail
add_filter( 'genesis_breadcrumb_args', 'ca_prefix_breadcrumb' );
function ca_prefix_breadcrumb( $args ) {
  $args['labels']['prefix'] = '';
  return $args;
}

// change "Home" to "Learning Center" in bread crumbs
add_filter('genesis_breadcrumb_args', 'ca_modify_home_text_breadcrumbs');
function ca_modify_home_text_breadcrumbs($args) {
    $args['home'] = 'Learning Center';
    return $args;
}


// Change the breadcrumb separator
add_filter( 'genesis_breadcrumb_args', 'ca_change_separator_breadcrumb' );
function ca_change_separator_breadcrumb( $args ) {
 	$args['sep'] = ' &raquo; ';
  return $args;
}

// Customize the entry meta in the entry header
add_filter( 'genesis_post_info', 'ca_post_entry_filter' );
function ca_post_entry_filter($post_info) {
	$post_info = '[post_date] [post_author_posts_link before="by: "] [post_comments zero="0" one="1" more="% "]';
	return $post_info;
}

// Customize the entry meta in the entry footer
add_filter( 'genesis_post_meta', 'ca_post_meta_filter' );
function ca_post_meta_filter($post_meta) {
	$post_meta = '[post_tags before="Tagged: "]';
	return $post_meta;
}

//enable author box
add_filter( 'get_the_author_genesis_author_box_single', '__return_true' );
add_filter( 'get_the_author_genesis_author_box_archive', '__return_true' );


// author box
add_filter( 'genesis_author_box', 'ca_author_box', 10, 6 );
function ca_author_box( $output, $context, $pattern, $gravatar, $title, $description ) {
	$output = '';

	// Author box on single post
	if( 'single' == $context ) {
		$output .= '<div class="author-box">';
			$output .= '<div class="author-image">';
				$output .= get_avatar( get_the_author_meta( 'email' ), 200 );
			$output .= '</div>';
			$output .= '<div class="author-info">';
				$output .= '<h4 class="title">About Author</h4>';
				$output .= '<p class="desc">' . get_the_author_meta( 'description' ) . '</p>';
			$output .= '</div>';
		$output .= '</div><!-- .author-box -->';

	} else {
		$output .= '<div class="author-box">';
			$output .= '<div class="author-image">';
				$output .= get_avatar( get_the_author_meta( 'email' ), 200 );
			$output .= '</div>';
			$output .= '<div class="author-info">';
				if( !empty( $title ) )
					$output .= '<h4 class="title">'. $title .'</h4>';
				$output .= '<p class="desc">' . get_the_author_meta( 'description' ) . '</p>';
			$output .= '</div>';
		$output .= '</div><!-- .author-box -->';
	}

	return $output;
}
