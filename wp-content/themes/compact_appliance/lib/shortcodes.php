<?php

function lc_recentpost($atts, $content=null){

$getpost = get_posts( array('number' => 1) );

$getpost = $getpost[0];

$return = $getpost->post_title . "<br />" . $getpost->$content . "…";

$return .= "<br /><a href='" . get_permalink($getpost->ID) . "'><em>read more →</em></a>";

return $return;

}
add_shortcode('newestpost', 'lc_recentpost');

function promotion_func( $atts ) {
	remove_filter('the_content', 'wpautop');
    $a = shortcode_atts( array(
        'title' => '',
		'link' => '',
        'description' => '',
		'promocode' => '',
        'expiration' => '',
		'disclaimer' => '',
    ), $atts );
    return "<div class='large-6 columns'>
    	<div class='scissors'>&nbsp;</div>
        <div class='coupon'>
		<span class='fold'></span>
        <span class='title'><a href='{$a['link']}' title='Shop Now'> {$a['title']}</a></span>
        <span class='desc'>{$a['description']}</span>
		<span class='promocode'>{$a['promocode']}</span>
        <div class='row'>
            <div class='large-7 medium-7 columns'>
                <div class='fineprint'>
                    <div class='expiration'>{$a['expiration']}</div>
                    <div class='disclaimer'>{$a['disclaimer']}</div>
                </div>
            </div>
            <div class='large-5 medium-5 columns'>   
                <div class='shop'><a href='{$a['link']}' title='Shop Now'>Shop Now</a></div>
            </div>    
        </div>    
    </div>
   		<div class='shadow'>&nbsp;</div>
    </div>  ";
	
}

add_shortcode( 'promotion', 'promotion_func' );

function disable_autop() {
	global $post;
	$disable_autop_var = get_post_meta($post->ID, 'disable_autop', TRUE);
		if ( !empty( $disable_autop_var ) ) {
		remove_filter('the_content', 'wpautop');
		}
	}
add_action ('loop_start', 'disable_autop');
