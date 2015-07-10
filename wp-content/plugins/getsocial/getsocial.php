<?php
/*
  Plugin Name: GetSocial
  Plugin URI: http://socialmetricspro.com/getsocial/
  Description: GetSocial adds an intelligent, lightweight, quick to setup floating social media sharing box on your blog posts.
  Author: Riyaz
  Version: 2.0.1
  Author URI: http://www.riyaz.net
  License: GPL2
 */
/*
 * @author  Riyaz Sayyad
 * @license GPL-2.0+
 * @link    http://socialmetricspro.com/getsocial/
 *
 * Copyright 2014 Riyaz Sayyad  (email: riyaz@riyaz.net)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */
/*
 * Feature Credits **
 *
 * Name: Farbtastic: jQuery color picker plug-in
 * URI: http://acko.net/dev/farbtastic
 * Description: Adds color picker widgets into a page
 * Version: 1.2
 * Author: Steven Wittens
 * Author URI: http://acko.net/dev/farbtastic
 *
 * Name: Custom Write Panel
 * URI: http://wefunction.com/2008/10/tutorial-create-custom-write-panels-in-wordpress
 * Description: Allows custom fields to be added to the WordPress Post Page
 * Version: 1.0
 * Author: Spencer
 * Author URI: http://wefunction.com
 */

if ( !class_exists( "GetSocial" ) ) :

	class GetSocial {

		var $_getsocial_settings = '_getsocial_settings';
		var $_main_settings = array( );
		var $_getsocial_settings_transient = '_getsocial_settings_transient';
		var $_getsocial_meta_boxes = array( "getsocial_hide" => array(
				"name" => "getsocial_hide",
				"std" => "",
				"title" => "Hide GetSocial box on this post/page",
				"description" => "Hide GetSocial box on this post/page" )
		);

		function GetSocial() {
			return $this->gs_construct();
		}

		function gs_construct() {
			$this->define_constants();
			$this->add_actions();
			$this->add_filters();
			$this->initialize();
		}

		function define_constants() {
			define( 'GETSOCIAL_VERSION', '2.0' );
			define( 'GETSOCIAL_DB_VERSION', '2.0' );
			define( 'GETSOCIAL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
			define( 'GETSOCIAL_PLUGIN_SLUG', basename( dirname( __FILE__ ) ) );

			define( 'GETSOCIAL_DIR', plugin_dir_path( __FILE__ ) );
			define( 'GETSOCIAL_URL', plugin_dir_url( __FILE__ ) );
			define( 'GETSOCIAL_LIB_URL', GETSOCIAL_URL . 'lib/' );
			define( 'GETSOCIAL_IMAGES_URL', GETSOCIAL_URL . 'images/' );
		}

		function add_actions() {
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'admin_init', array( $this, 'process_posted_data' ) );
			add_action( 'wp_head', array( $this, 'add_css_styles' ) );

			$settings = $this->get_settings();
			if ( 'true' == $settings[ 'main' ][ 'scripts_in_footer' ] ) {
				add_action( 'wp_footer', array( $this, 'add_scripts' ) );
				add_action( 'wp_footer', array( $this, 'add_getsocial_box' ) );
			} else {
				add_action( 'wp_head', array( $this, 'add_scripts' ) );
				add_action( 'wp_footer', array( $this, 'add_getsocial_box' ) );
			}

			add_action( 'admin_enqueue_scripts', array( $this, 'add_admin_scripts' ) );
			add_action( 'wp_print_scripts', array( $this, 'add_admin_css_styles' ) );

			add_action( 'admin_menu', array( $this, 'add_menu_item' ) );

			add_action( 'admin_menu', array( $this, 'add_inpost_meta' ) );
			add_action( 'save_post', array( $this, 'save_inpost_meta' ) );

//			add_action( 'admin_notices', array( $this, 'show_admin_notice' ) );
		}

		function add_filters() {
			add_filter( 'the_content', array( &$this, 'add_getsocial_div' ), 30 );
		}

		function initialize() {

		}

		function init() {
			//* map settings from older versions
			$old_set = get_option( 'getsocial_order', false );
			if ( false != $old_set ) {
				$this->map_settings();
			}

			add_post_type_support( 'post', 'getsocial-meta' );
			add_post_type_support( 'page', 'getsocial-meta' );
		}

		function add_css_styles() {
			if ( $this->showbox() ) {
				$settings = $this->get_settings();
				$back_color = trim( esc_attr( $settings[ 'main' ][ 'background_color' ] ), "#" );
				$border_color = trim( esc_attr( $settings[ 'main' ][ 'border_color' ] ), "#" );
//				$strip_color = esc_attr( $settings[ 'main' ][ 'background_image' ] );
				$preload_hide = esc_attr( $settings[ 'main' ][ 'preload_hide' ] );
				if ( $preload_hide == 'yes' )
					$preload_hide = 'display:none;';
				else
					$preload_hide = '';



				$bar_width = esc_attr( $settings[ 'main' ][ 'bar_width' ] );
				$rounded_corners = esc_attr( $settings[ 'main' ][ 'rounded_corners' ] );

				if ( "yes" == $rounded_corners ) {
					$rounded_corners = 'border-radius: 3px 3px 3px 3px;-moz-border-radius: 3px 3px 3px 3px;-webkit-border-top-left-radius: 3px;-webkit-border-bottom-left-radius: 3px;-webkit-border-top-right-radius: 3px;-webkit-border-bottom-right-radius: 3px;border-top-left-radius: 3px 3px;border-bottom-left-radius: 3px 3px;border-top-right-radius: 3px 3px;border-bottom-right-radius: 3px 3px;';
				} else {
					$rounded_corners = '';
				}

				$font_color = $this->inversehex( $back_color );
//				echo '<link rel="stylesheet" type="text/css" href="' . GETSOCIAL_LIB_URL . 'getsocialstyles.php?color=' . $back_color . '&border=' . $border_color . '&w=' . $bar_width . '&strip=' . $strip_color . '&prehide=' . $preload_hide . '&rc=' . $rounded_corners . '&stralign=' . $strip_align . '" >';
				//echo '<link rel="stylesheet" type="text/css" href="' . GETSOCIAL_LIB_URL . 'getsocialstyles.php?color=' . $back_color . '&border=' . $border_color . '&w=' . $bar_width . '&prehide=' . $preload_hide . '&rc=' . $rounded_corners . '" >';
				echo '<link rel="stylesheet" type="text/css" href="' . GETSOCIAL_LIB_URL . 'getsocial.css' . '" >';
				echo '<style>';
				echo '.getsocial{width:' . $bar_width . 'px;background:#' . $back_color . ';border:1px solid #' . $border_color . ';' . $preload_hide . $rounded_corners . '}';
				echo '.sharebutton.sharefooter,.sharebutton.sharefooter a{color:#' . $font_color . ';}';
				echo '</style>';
			}
		}

		function add_scripts() {
			$getsocial_js = "";
			$settings = $this->get_settings();
			if ( $this->showbox() ) {
				$getsocial_js = '<script type="text/javascript">'
					. 'var $opt_initial_top = ' . $settings[ 'main' ][ 'initial_top' ] . ';'
					. 'var $opt_scrolled_top = ' . $settings[ 'main' ][ 'scrolled_top' ] . ';'
					. 'var $opt_browser_width = ' . $settings[ 'main' ][ 'browser_width' ] . ';'
					. 'var $opt_box_left = ' . $settings[ 'main' ][ 'offset_left' ] . ';'
					. '</script>'
					. '<script type="text/javascript" src="' . GETSOCIAL_LIB_URL . 'getsocial.js' . '"></script>';
			}
			echo $getsocial_js;
		}

		function add_admin_css_styles() {
			if ( (is_admin() && isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'getsocial/getsocial.php' ) ) {
				wp_deregister_style( 'farbtastic' );
				wp_register_style( 'farbtastic', GETSOCIAL_LIB_URL . 'farbtastic.css' );
				wp_enqueue_style( 'farbtastic' );

				wp_register_style( 'getsocial-admin', GETSOCIAL_LIB_URL . 'adminstyles.css' );
				wp_enqueue_style( 'getsocial-admin' );
			}
		}

		function add_admin_scripts() {
			if ( (is_admin() && isset( $_GET[ 'page' ] ) && $_GET[ 'page' ] == 'getsocial/getsocial.php' ) ) {

				wp_deregister_script( 'jquery' );
				wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js' );
				wp_enqueue_script( 'jquery' );

				wp_register_script( 'jquery-ui', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js', 'jquery' );
				wp_enqueue_script( 'jquery-ui' );

				wp_deregister_script( 'farbtastic' );
				wp_register_script( 'farbtastic', GETSOCIAL_LIB_URL . 'farbtastic.js' );
				wp_enqueue_script( 'farbtastic' );
//				wp_enqueue_script( 'jquery-ui-sortable' );
			}
		}

		function add_menu_item() {
			//create new top-level menu
			add_menu_page( 'GetSocial Settings', 'GetSocial', 'administrator', __FILE__, array( $this, 'display_settings_page' ), GETSOCIAL_IMAGES_URL . 'riyaznet.ico' );
		}

		//		function register_settings() {
		//
					//		}

		/**
		 * Map settings from older versions and remove old settings
		 *
		 */
		function map_settings() {
			$settings = $this->get_settings();

			$settings[ 'main' ][ 'initial_top' ] = get_option( 'getsocial_initial_top', 200 );
			$settings[ 'main' ][ 'scrolled_top' ] = get_option( 'getsocial_scrolled_top', 20 );
			$settings[ 'main' ][ 'offset_left' ] = get_option( 'getsocial_offset_left', 88 );
			$settings[ 'main' ][ 'browser_width' ] = get_option( 'getsocial_browser_width', 1140 );
			$settings[ 'main' ][ 'bar_width' ] = get_option( 'getsocial_bar_width', 68 );
			$settings[ 'main' ][ 'show_on_posts' ] = get_option( 'getsocial_getsocial_show_on_posts', true );
			$settings[ 'main' ][ 'show_on_pages' ] = get_option( 'getsocial_show_on_pages', false );
			$settings[ 'main' ][ 'show_on_homepage' ] = get_option( 'getsocial_show_on_homepage', false );
			$settings[ 'main' ][ 'background_color' ] = get_option( 'getsocial_background_color', '#FFFFFF' );
			$settings[ 'main' ][ 'border_color' ] = get_option( 'getsocial_border_color', '#DDDDDD' );
			//$settings[ 'main' ][ 'background_image' ] = get_option( 'getsocial_background_image', 'dark' );
			$settings[ 'main' ][ 'rounded_corners' ] = get_option( 'getsocial_rounded_corners', 'yes' );
			//$settings[ 'main' ][ 'strip_align' ] = get_option( 'getsocial_strip_align', 'right' );
			$settings[ 'main' ][ 'preload_hide' ] = get_option( 'getsocial_preload_hide', 'yes' );
			$settings[ 'main' ][ 'order' ] = get_option( 'getsocial_order', 'rtw,rbf,rfb,rgp,rsu,rli,rpi,rcu,rab' );
			$settings[ 'main' ][ 'order' ] = str_replace( 'rdg', 'rcu', $settings[ 'main' ][ 'order' ] );
			$settings[ 'main' ][ 'twitter' ] = get_option( 'getsocial_twitter', 'vertical' );
			$settings[ 'main' ][ 'buffer' ] = get_option( 'getsocial_buffer', 'vertical' );
			$settings[ 'main' ][ 'twitter_username' ] = get_option( 'getsocial_twitter_username', '' );
			$settings[ 'main' ][ 'facebook' ] = get_option( 'getsocial_facebook', 'box_count_send' );
			$settings[ 'main' ][ 'googleplusone' ] = get_option( 'getsocial_googleplusone', 'vertical' );
			$settings[ 'main' ][ 'stumbleupon' ] = get_option( 'getsocial_stumbleupon', 'hide' );
			$settings[ 'main' ][ 'centup' ] = 'hide';
			$settings[ 'main' ][ 'linkedin' ] = get_option( 'getsocial_linkedin', 'hide' );
			$settings[ 'main' ][ 'pinterest' ] = get_option( 'getsocial_pinterest', 'hide' );
			$settings[ 'main' ][ 'additional' ] = get_option( 'getsocial_additional', '' );
			$settings[ 'main' ][ 'linkluv' ] = get_option( 'getsocial_linkluv', true );
			$settings[ 'main' ][ 'scripts_in_footer' ] = get_option( 'getsocial_scripts_in_footer', 'true' );

			$this->update_settings( $settings );

			delete_option( 'getsocial_initial_top' );
			delete_option( 'getsocial_scrolled_top' );
			delete_option( 'getsocial_left' );
			delete_option( 'getsocial_browser_width' );
			delete_option( 'getsocial_bar_width' );
			delete_option( 'getsocial_show_on_posts' );
			delete_option( 'getsocial_show_on_pages' );
			delete_option( 'getsocial_show_on_homepage' );
			delete_option( 'getsocial_background_color' );
			delete_option( 'getsocial_border_color' );
			delete_option( 'getsocial_background_image' );
			delete_option( 'getsocial_rounded_corners' );
			delete_option( 'getsocial_strip_align' );
			delete_option( 'getsocial_preload_hide' );
			delete_option( 'getsocial_order' );
			delete_option( 'getsocial_twitter' );
			delete_option( 'getsocial_buffer' );
			delete_option( 'getsocial_twitter_username' );
			delete_option( 'getsocial_facebook' );
			delete_option( 'getsocial_googleplusone' );
			delete_option( 'getsocial_stumbleupon' );
			delete_option( 'getsocial_digg' );
			delete_option( 'getsocial_linkedin' );
			delete_option( 'getsocial_pinterest' );
			delete_option( 'getsocial_blendit' );
			delete_option( 'getsocial_additional' );
			delete_option( 'getsocial_linkluv' );
			delete_option( 'getsocial_scripts_in_footer' );
		}

		/**
		 * Get plugin settings
		 *
		 * @return array $settings
		 *
		 */
		function get_settings() {
			$settings = wp_cache_get( $this->_getsocial_settings );
			if ( !$settings || !is_array( $settings ) ) {
				$settings = get_option( $this->_getsocial_settings, array( ) );
				if ( !is_array( $settings ) )
					$settings = array( );
				if ( !isset( $settings[ 'main' ] ) || !is_array( $settings[ 'main' ] ) ) {
					$settings[ 'main' ] = array( );
					$settings[ 'main' ][ 'initial_top' ] = 200;
					$settings[ 'main' ][ 'scrolled_top' ] = 20;
					$settings[ 'main' ][ 'offset_left' ] = 88;
					$settings[ 'main' ][ 'browser_width' ] = 1140;
					$settings[ 'main' ][ 'bar_width' ] = 68;
					$settings[ 'main' ][ 'show_on_posts' ] = true;
					$settings[ 'main' ][ 'show_on_pages' ] = false;
					$settings[ 'main' ][ 'show_on_homepage' ] = false;
					$settings[ 'main' ][ 'background_color' ] = '#FFFFFF';
					$settings[ 'main' ][ 'border_color' ] = '#DDDDDD';
					//$settings[ 'main' ][ 'background_image' ] = 'hide';
					$settings[ 'main' ][ 'rounded_corners' ] = 'yes';
					//$settings[ 'main' ][ 'strip_align' ] = 'right';
					$settings[ 'main' ][ 'preload_hide' ] = 'yes';
					$settings[ 'main' ][ 'order' ] = 'rtw,rbf,rfb,rgp,rsu,rli,rpi,rcu,rab';
					$settings[ 'main' ][ 'twitter' ] = 'vertical';
					$settings[ 'main' ][ 'buffer' ] = 'hide';
					$settings[ 'main' ][ 'twitter_username' ] = '';
					$settings[ 'main' ][ 'facebook' ] = 'box_count';
					$settings[ 'main' ][ 'googleplusone' ] = 'vertical';
					$settings[ 'main' ][ 'stumbleupon' ] = 'hide';
					$settings[ 'main' ][ 'centup' ] = 'hide';
					$settings[ 'main' ][ 'linkedin' ] = 'hide';
					$settings[ 'main' ][ 'pinterest' ] = 'hide';
					$settings[ 'main' ][ 'additional' ] = '';
					$settings[ 'main' ][ 'linkluv' ] = 'true';
					$settings[ 'main' ][ 'scripts_in_footer' ] = 'true';
				}

				wp_cache_set( $this->_getsocial_settings, $settings, null, time() + 24 * 60 * 60 );
			}
			return $settings;
		}

		/**
		 *
		 * Update plugin settings
		 *
		 * @param array $settings
		 *
		 */
		function update_settings( $settings ) {
			if ( !is_array( $settings ) )
				return;

			update_option( $this->_getsocial_settings, $settings );
			wp_cache_set( $this->_getsocial_settings, $settings, null, time() + 24 * 60 * 60 );
		}

		/**
		 *
		 * Update plugin settings submitted via settings page HTTP POST
		 *
		 */
		function update_getsocial_settings() {

			$new_settings = stripslashes_deep( $_POST );

			$new_settings = $new_settings[ 'getsocial' ];
			$old_settings = $this->get_settings();

			$settings[ 'main' ] = array_merge( $old_settings[ 'main' ], $new_settings[ 'main' ] );

			$settings[ 'main' ][ 'initial_top' ] = intval( esc_attr( $settings[ 'main' ][ 'initial_top' ] ) );
			$settings[ 'main' ][ 'offset_left' ] = intval( esc_attr( $settings[ 'main' ][ 'offset_left' ] ) );
			$settings[ 'main' ][ 'browser_width' ] = intval( esc_attr( $settings[ 'main' ][ 'browser_width' ] ) );
			$settings[ 'main' ][ 'scrolled_top' ] = intval( esc_attr( $settings[ 'main' ][ 'scrolled_top' ] ) );
			$settings[ 'main' ][ 'bar_width' ] = intval( esc_attr( $settings[ 'main' ][ 'bar_width' ] ) );

			$errors = array( );
			$this->update_settings( $settings );

			set_transient( $this->_getsocial_settings_transient, array( 'updates' => array( __( 'Settings Saved.', 'getsocial' ) ), 'errors' => $errors ), 10 );
		}

		function showbox() {
			global $post;
			$post_type = get_post_type();
			$settings = $this->get_settings();
			$hide_box_value = get_post_meta( $post->ID, 'getsocial_hide_value', true );

			if ( is_single() && ( 'post' == $post_type ) && true == $settings[ 'main' ][ 'show_on_posts' ] && $hide_box_value != "true" )
				return true;
			elseif ( is_page() && true == $settings[ 'main' ][ 'show_on_pages' ] && $hide_box_value != "true" )
				return true;
			elseif ( ( is_home() || is_front_page() ) && true == $settings[ 'main' ][ 'show_on_homepage' ] )
				return true;
			else
				return false;
		}

		function getsocial_get_images( $post_id, $size = 'full' ) {

			$images = get_children( array( 'post_parent' => $post_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID' ) );
			$results = array( );

			if ( $images ) {
				foreach ( $images as $image ) {
					$results[ ] = wp_get_attachment_image_src( $image->ID, $size );
				}
			}
			return $results;
		}

		function add_getsocial_box() {
			echo $this->get_getsocial_box();
		}

		function get_getsocial_box() {
			$getsocial_html = "";
			/* global $post; */
			/* $permalink = get_permalink($post->ID); */

			$settings = $this->get_settings();
			$this->_main_settings = $settings[ 'main' ];
			if ( $this->showbox() ) {
				$getsocial_html = '<div class="getsocial"><div class="gs-wrap">';
				$settings = $this->get_settings();
				$order = $settings[ 'main' ][ 'order' ];
				$order = trim( $order, ',' );

				$rows = explode( ",", $order );
				foreach ( $rows as $row ) {
					$getsocial_html .= $this->getsocial_button_code_get( $row, 'display' );
				}

				if ( "true" == $settings[ 'main' ][ 'linkluv' ] ) {
					$getsocial_html .= '<div class="sharebutton sharefooter"><a href="http://socialmetricspro.com/getsocial/" title="GetSocial Plugin by Social Metrics Pro">GetSocial</a></div>';
				}

				$getsocial_html .= '<div class="gsclear"></div>';
				$getsocial_html .= '</div></div>';
			}
			return $getsocial_html;
		}

		function add_getsocial_div( $content ) {
			global $post;
			global $gs_url, $gs_image_url, $gs_title;
			if ( $this->showbox() ) {
				$gs_image_url = "";
				$gs_title = get_the_title( $post->ID );
				$gs_url = get_permalink( $post->ID );

				$full_image_url = $this->getsocial_get_images( $post->ID );
				if ( $full_image_url ) {
					$gs_image_url = $full_image_url[ 0 ][ 0 ];
				}

				if ( function_exists( 'has_post_thumbnail' ) ) {
					if ( has_post_thumbnail( $post->ID ) ) {
						$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
						$gs_image_url = $full_image_url[ 0 ];
					}
				}

				$content = '<div id="getsocialmain">' . $content . '</div><!-- #getsocialmain -->';
			}
			return $content;
		}

		function display_settings_page() {
			$settings = $this->get_settings();
			$main = $settings[ 'main' ];
			$this->_main_settings = $settings[ 'main' ];
			include_once(GETSOCIAL_DIR . 'settings.php');
		}

		function process_posted_data() {
			if ( isset( $_POST[ 'option_page' ] ) && ('getsocial-settings-group' == $_POST[ 'option_page' ]) && wp_verify_nonce( $_POST[ 'save-getsocial-settings-nonce' ], 'save-getsocial-settings' ) ) {
				$this->update_getsocial_settings();
			}
		}

		function add_inpost_meta() {

			//			add_meta_box( 'new-meta-boxes', 'GetSocial Settings', 'getsocial_meta_boxes', 'page', 'normal', 'high' );
			//			add_meta_box( 'new-meta-boxes', 'GetSocial Settings', 'getsocial_meta_boxes', 'post', 'normal', 'high' );

			foreach ( ( array ) get_post_types( array( 'public' => true ) ) as $type ) {
				if ( post_type_supports( $type, 'getsocial-meta' ) )
					add_meta_box( 'new-meta-boxes', 'GetSocial Settings', array( $this, 'render_inpost_meta_box' ), $type, 'normal', 'high' );
			}
		}

		function render_inpost_meta_box() {
			global $post;

			foreach ( $this->_getsocial_meta_boxes as $meta_box ) {
				$meta_box_value = get_post_meta( $post->ID, $meta_box[ 'name' ] . '_value', true );

				if ( $meta_box_value == "" )
					$meta_box_value = $meta_box[ 'std' ];

				echo'<input type="hidden" name="' . $meta_box[ 'name' ] . '_noncename" id="' . $meta_box[ 'name' ] . '_noncename" value="' . wp_create_nonce( GETSOCIAL_PLUGIN_BASENAME ) . '" />';

				echo'' . $meta_box[ 'title' ] . ' ';

				if ( $meta_box_value == true )
					echo'<input type="checkbox" name="' . $meta_box[ 'name' ] . '_value" value="true" checked="checked" /><br />';
				else
					echo'<input type="checkbox" name="' . $meta_box[ 'name' ] . '_value" value="true" /><br />';
			}
		}

		function save_inpost_meta( $post_id ) {
			global $post;

			foreach ( $this->_getsocial_meta_boxes as $meta_box ) {
				// Verify
				if ( !wp_verify_nonce( $_POST[ $meta_box[ 'name' ] . '_noncename' ], GETSOCIAL_PLUGIN_BASENAME ) ) {
					return $post_id;
				}

				if ( 'page' == $_POST[ 'post_type' ] ) {
					if ( !current_user_can( 'edit_page', $post_id ) )
						return $post_id;
				} else if ( 'post' == $_POST[ 'post_type' ] ) {
					if ( !current_user_can( 'edit_post', $post_id ) )
						return $post_id;
				}

				$data = $_POST[ $meta_box[ 'name' ] . '_value' ];

				if ( get_post_meta( $post_id, $meta_box[ 'name' ] . '_value' ) == "" )
					add_post_meta( $post_id, $meta_box[ 'name' ] . '_value', $data, true );
				elseif ( $data != get_post_meta( $post_id, $meta_box[ 'name' ] . '_value', true ) )
					update_post_meta( $post_id, $meta_box[ 'name' ] . '_value', $data );
				elseif ( $data == "" )
					delete_post_meta( $post_id, $meta_box[ 'name' ] . '_value', get_post_meta( $post_id, $meta_box[ 'name' ] . '_value', true ) );
			}
		}

		function gs_get_page_image_url( $url ) {
			$result = 'http://s.wordpress.com/mshots/v1/' . urlencode( $url ) . '?w=600';
			return $result;
		}

		function gs_get_page_url() {
			$pageURL = 'http';
			if ( isset( $_SERVER[ "HTTPS" ] ) && "on" == $_SERVER[ "HTTPS" ] ) {
				$pageURL .= "s";
			}
			$pageURL .= "://";
			if ( $_SERVER[ "SERVER_PORT" ] != "80" ) {
				$pageURL .= $_SERVER[ "SERVER_NAME" ] . ":" . $_SERVER[ "SERVER_PORT" ] . $_SERVER[ "REQUEST_URI" ];
			} else {
				$pageURL .= $_SERVER[ "SERVER_NAME" ] . $_SERVER[ "REQUEST_URI" ];
			}
			return $pageURL;
		}

		function getsocial_button_code_get( $service, $context = 'display' ) {

			$main = $this->_main_settings;
			$html = '';
			global $gs_url, $gs_title, $gs_image_url;
			if ( empty( $gs_url ) || is_home() || is_front_page() ) {
				$permalink = $this->gs_get_page_url();
			} else {
				$permalink = $gs_url;
			}

			if ( empty( $gs_image_url ) || is_home() || is_front_page() ) {
				$gs_image_url = $this->gs_get_page_image_url( $permalink );
			}

			if ( empty( $gs_title ) || is_home() || is_front_page() ) {
				$gs_title = get_bloginfo( 'name' );
			}

			switch ( $service ) {
				case 'rtw':
					if ( $context == 'display' ) {
						if ( "hide" != $main[ 'twitter' ] ) {
							$html = '<div class="sharebutton">'
								. '<a href="http://twitter.com/share" class="twitter-share-button" data-count="' . $main[ 'twitter' ] . '" data-via="' . $main[ 'twitter_username' ] . '" data-related="' . $main[ 'twitter_username' ] . '">Tweet</a>'
								. '<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script><div class="gsclear"></div></div>';
						}
					}
					if ( $context == 'options' ) {
						?><li class="ui-state-default" id="rtw">
							<div class="sortth">Twitter Tweet Button</div>
							<div class="sorttd"><div id="radio-tc">
									<input type="radio" name="getsocial[main][twitter]" id="gstver" value="vertical" <?php
									$twitter = esc_attr( $main[ 'twitter' ] );

									if ( "vertical" == $twitter ) {
										_e( 'checked = "checked"', "getsocial_twitter" );
									}
									?> /><label for="gstver">Button + Count</label>
									<input type="radio" name="getsocial[main][twitter]" id="gstnc" value="none" <?php
									if ( "none" == $twitter ) {
										_e( 'checked = "checked"', "getsocial_twitter" );
									}
									?> /><label for="gstnc">No Count</label>
									<input type="radio" name="getsocial[main][twitter]" id="gsthide" value="hide" <?php
									if ( "hide" == $twitter ) {
										_e( 'checked = "checked"', "getsocial_twitter" );
									}
									?> /><label for="gsthide">Hide</label>
								</div></div>
						</li><?php
					}
					break;
				case 'rbf':
					if ( $context == 'display' ) {
						if ( "hide" != $main[ 'buffer' ] ) {
							$html = '<div class = "sharebutton">'
								. '<a href = "http://bufferapp.com/add" class = "buffer-add-button" data-count = "' . $main[ 'buffer' ] . '" data-via = "' . $main[ 'twitter_username' ] . '" data-related = "' . $main[ 'twitter_username' ] . '">Buffer</a>'
								. '<script type = "text/javascript" src = "http://static.bufferapp.com/js/button.js"></script><div class="gsclear"></div></div>';
						}
					}
					if ( $context == 'options' ) {
						?><li class="ui-state-default" id="rbf">
							<div class="sortth">Buffer Button</div>
							<div class="sorttd"><div id="radio-bc">
									<input type="radio" name="getsocial[main][buffer]" id="gsbver" value="vertical" <?php
									$buffer = esc_attr( $main[ 'buffer' ] );
									if ( "vertical" == $buffer ) {
										_e( 'checked="checked"', "getsocial_buffer" );
									}
									?> /><label for="gsbver">Button + Count</label>
									<input type="radio" name="getsocial[main][buffer]" id="gsbnc" value="none" <?php
									if ( "none" == $buffer ) {
										_e( 'checked="checked"', "getsocial_buffer" );
									}
									?> /><label for="gsbnc">No Count</label>
									<input type="radio" name="getsocial[main][buffer]" id="gsbhide" value="hide" <?php
									if ( "hide" == $buffer ) {
										_e( 'checked="checked"', "getsocial_buffer" );
									}
									?> /><label for="gsbhide">Hide</label>
								</div></div>
						</li><?php
					}
					break;
				case 'rfb':
					if ( $context == 'display' ) {
						if ( "hide" != $main[ 'facebook' ] ) {
//							$bar_width = $main[ 'bar_width' ] - 33;
							$fb_send = "false";
							if ( "box_count_send" == $main[ 'facebook' ] )
								$fb_send = "true";

							if ( "true" == $fb_send ) {
								$box_height = 88;
							} else {
								$box_height = 62;
							}
							$html = '<div class="sharebutton">'
								. '<div id="fb-root"></div>'
								. '<script>(function(d, s, id) {'
								. '  var js, fjs = d.getElementsByTagName(s)[0];'
								. '  if (d.getElementById(id)) return;'
								. '  js = d.createElement(s); js.id = id;'
								. '  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";'
								. '  fjs.parentNode.insertBefore(js, fjs);'
								. "}(document, 'script', 'facebook-jssdk'));</script>"
								// .'<div class="fb-like" data-href="' . urlencode($permalink) . '" data-send="' . $fb_send . '" data-layout="box_count" data-width="'. $bar_width .'" data-show-faces="false"></div>'
//								. '<div class="fb-like" data-href="' . $permalink . '" data-share="' . $fb_send . '" data-layout="box_count" data-width="' . $bar_width . '" data-show-faces="false"></div>'
								. '<div class="fb-like" data-href="' . $permalink . '" data-width="50" data-height="' . $box_height . '" data-layout="box_count" data-action="like" data-show-faces="false" data-share="' . $fb_send . '"></div>'
								. '<div class="gsclear"></div></div>';
//							$html = '<div class="sharebutton">'
//								. '<iframe src="http://www.facebook.com/plugins/like.php?href='
//								. urlencode( $permalink )
//								. '&width=50&layout=box_count&action=like&show_faces=false&share='
//								. $fb_send
//								. '&height=35">'
//								. '</iframe>'
//								. '</div>';
//							$html = '<div class="sharebutton">'
//								. '<iframe src="//www.facebook.com/plugins/like.php?href=' . $permalink
//								. '&amp;width=50&amp;layout=box_count&amp;action=like&amp;show_faces=false&amp;share=' . $fb_send
//								. '&amp;height=' . $box_height . '" scrolling="no" frameborder="0" style="border:none;overflow:hidden;height:' . $box_height . 'px;width:50px;" allowTransparency="true"></iframe>'
//								. '<div class="gsclear"></div></div>';
						}
					}
					if ( $context == 'options' ) {
						?><li class="ui-state-default" id="rfb">
							<div class="sortth">Facebook Like Button</div>
							<div class="sorttd"><div id="radio-fc">
									<input type="radio" name="getsocial[main][facebook]" id="gsfver" value="box_count" <?php
									$facebook = esc_attr( $main[ 'facebook' ] );
									if ( "box_count" == $facebook ) {
										_e( 'checked="checked"', "getsocial_facebook" );
									}
									?> /><label for="gsfver">Like Button</label>
									<input type="radio" name="getsocial[main][facebook]" id="gsfversend" value="box_count_send" <?php
									if ( "box_count_send" == $facebook ) {
										_e( 'checked="checked"', "getsocial_facebook" );
									}
									?> /><label for="gsfversend">Like + Share</label>
									<input type="radio" name="getsocial[main][facebook]" id="gsfhide" value="hide" <?php
									if ( "hide" == $facebook ) {
										_e( 'checked="checked"', "getsocial_facebook" );
									}
									?> /><label for="gsfhide">Hide</label>
								</div></div>
						</li><?php
					}
					break;
				case 'rgp':
					if ( $context == 'display' ) {
						if ( "hide" != $main[ 'googleplusone' ] ) {
							$po_style = ( "vertical" == $main[ 'googleplusone' ] ) ? '' : 'annotation="none"';
							$html = '<div class="sharebutton">'
								. '<script type="text/javascript" src="http://apis.google.com/js/plusone.js"></script>'
								. '<g:plusone size="tall" ' . $po_style . ' href="' . $permalink . '"></g:plusone><div class="gsclear"></div></div>';
						}
					}
					if ( $context == 'options' ) {
						?><li class="ui-state-default" id="rgp">
							<div class="sortth">Google +1 Button</div>
							<div class="sorttd"><div id="radio-gc">
									<input type="radio" name="getsocial[main][googleplusone]" id="gsgver" value="vertical" <?php
									$googleplusone = esc_attr( $main[ 'googleplusone' ] );
									if ( "vertical" == $googleplusone ) {
										_e( 'checked="checked"', "getsocial_googleplusone" );
									}
									?> /><label for="gsgver">Button + Count</label>
									<input type="radio" name="getsocial[main][googleplusone]" id="gsgnc" value="none" <?php
									if ( "none" == $googleplusone ) {
										_e( 'checked="checked"', "getsocial_googleplusone" );
									}
									?> /><label for="gsgnc">No Count</label>
									<input type="radio" name="getsocial[main][googleplusone]" id="gsghide" value="hide" <?php
									if ( "hide" == $googleplusone ) {
										_e( 'checked="checked"', "getsocial_googleplusone" );
									}
									?> /><label for="gsghide">Hide</label>
								</div></div>
						</li><?php
					}
					break;
				case 'rsu':
					if ( $context == 'display' ) {
						if ( "hide" != $main[ 'stumbleupon' ] ) {
							$su_style = ( "vertical" == $main[ 'stumbleupon' ] ) ? '5' : '6';
							$html = '<div class="sharebutton">'
								. '<su:badge layout="' . $su_style . '"></su:badge>'
								. ' <script type="text/javascript">'
								. " (function() { "
								. "     var li = document.createElement('script'); li.type = 'text/javascript'; li.async = true;"
								. "     li.src = window.location.protocol + '//platform.stumbleupon.com/1/widgets.js'; "
								. "     var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(li, s);"
								. " })();"
								. ' </script>'
								. '<div class="gsclear"></div></div>';
						}
					}
					if ( $context == 'options' ) {
						?><li class="ui-state-default" id="rsu">
							<div class="sortth">StumbleUpon Button</div>
							<div class="sorttd"><div id="radio-sc">
									<input type="radio" name="getsocial[main][stumbleupon]" id="gssver" value="vertical" <?php
									$stumbleupon = esc_attr( $main[ 'stumbleupon' ] );
									if ( "vertical" == $stumbleupon ) {
										_e( 'checked="checked"', "getsocial_stumbleupon" );
									}
									?> /><label for="gssver">Button + Count</label>
									<input type="radio" name="getsocial[main][stumbleupon]" id="gssnc" value="none" <?php
									if ( "none" == $stumbleupon ) {
										_e( 'checked="checked"', "getsocial_stumbleupon" );
									}
									?> /><label for="gssnc">No Count</label>
									<input type="radio" name="getsocial[main][stumbleupon]" id="gsshide" value="hide" <?php
									if ( "hide" == $stumbleupon ) {
										_e( 'checked="checked"', "getsocial_stumbleupon" );
									}
									?> /><label for="gsshide">Hide</label>
								</div></div>
						</li><?php
					}
					break;
				case 'rcu':
					if ( $context == 'display' ) {
						if ( "hide" != $main[ 'centup' ] ) {
							$html = '<div class="sharebutton">'
								. '<div class="centup-top" data-url="' . $permalink . '" data-title="' . $gs_title . '"></div>'
								. '<script src="https://www.centup.org/button.js"></script>'
								. '<div class="gsclear"></div></div>';
						}
					}
					if ( $context == 'options' ) {
						?><li class="ui-state-default" id="rcu">
							<div class="sortth">CentUp Button</div>
							<div class="sorttd"><div id="radio-dc">
									<input type="radio" name="getsocial[main][centup]" id="gscver" value="vertical" <?php
									$centup = esc_attr( $main[ 'centup' ] );
									if ( "vertical" == $centup ) {
										_e( 'checked="checked"', "getsocial_centup" );
									}
									?> /><label for="gscver">Button + Count</label>
									<input type="radio" name="getsocial[main][centup]" id="gschide" value="hide" <?php
									if ( "hide" == $centup ) {
										_e( 'checked="checked"', "getsocial_centup" );
									}
									?> /><label for="gschide">Hide</label>
								</div></div>
						</li><?php
					}
					break;
				case 'rli':
					if ( $context == 'display' ) {
						if ( "hide" != $main[ 'linkedin' ] ) {
							$li_style = ( "vertical" == $main[ 'linkedin' ] ) ? ' data-counter="top"' : '';
							$html = '<div class="sharebutton">'
								. '<script src="http://platform.linkedin.com/in.js" type="text/javascript"></script>'
								. '<script type="IN/Share"' . $li_style . '></script>'
								. '<div class="gsclear"></div>'
								. '</div>';
						}
					}
					if ( $context == 'options' ) {
						?><li class="ui-state-default" id="rli">
							<div class="sortth">LinkedIn Button</div>
							<div class="sorttd"><div id="radio-lc">
									<input type="radio" name="getsocial[main][linkedin]" id="gslver" value="vertical" <?php
									$linkedin = esc_attr( $main[ 'linkedin' ] );
									if ( "vertical" == $linkedin ) {
										_e( 'checked="checked"', "getsocial_linkedin" );
									}
									?> /><label for="gslver">Button + Count</label>
									<input type="radio" name="getsocial[main][linkedin]" id="gslnc" value="none" <?php
									if ( "none" == $linkedin ) {
										_e( 'checked="checked"', "getsocial_linkedin" );
									}
									?> /><label for="gslnc">No Count</label>
									<input type="radio" name="getsocial[main][linkedin]" id="gslhide" value="hide" <?php
									if ( "hide" == $linkedin ) {
										_e( 'checked="checked"', "getsocial_linkedin" );
									}
									?> /><label for="gslhide">Hide</label>
								</div></div>
						</li><?php
					}
					break;
				case 'rpi':
					if ( $context == 'display' ) {
						if ( "hide" != $main[ 'pinterest' ] && !empty( $gs_image_url ) ) {
							$html = '<div class="sharebutton" ';
							if ( "vertical" == $main[ 'pinterest' ] )
								$html .= 'style="margin-top:30px;"';
							$html .= '>'
								. '<a href="http://pinterest.com/pin/create/button/?url=' . urlencode( $permalink ) . '&media=' . urlencode( $gs_image_url ) . '&description=' . $gs_title . '" class="pin-it-button" count-layout="' . $main[ 'pinterest' ] . '"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>'
								. '<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>'
								. '<div class="gsclear"></div>'
								. '</div>';
						}
					}
					if ( $context == 'options' ) {
						?><li class="ui-state-default" id="rpi">
							<div class="sortth">Pinterest Pin it Button</div>
							<div class="sorttd"><div id="radio-pc">
									<input type="radio" name="getsocial[main][pinterest]" id="gspver" value="vertical" <?php
									$pinterest = esc_attr( $main[ 'pinterest' ] );
									if ( "vertical" == $pinterest ) {
										_e( 'checked="checked"', "getsocial_pinterest" );
									}
									?> /><label for="gspver">Button + Count</label>
									<input type="radio" name="getsocial[main][pinterest]" id="gspnc" value="none" <?php
									if ( "none" == $pinterest ) {
										_e( 'checked="checked"', "getsocial_pinterest" );
									}
									?> /><label for="gspnc">No Count</label>
									<input type="radio" name="getsocial[main][pinterest]" id="gsphide" value="hide" <?php
									if ( "hide" == $pinterest ) {
										_e( 'checked="checked"', "getsocial_pinterest" );
									}
									?> /><label for="gsphide">Hide</label>
								</div></div>
						</li><?php
					}
					break;
				case 'rab':
					if ( $context == 'display' ) {
						$getsocial_additional = $main[ 'additional' ];
						if ( $getsocial_additional != "" ) {
							$html = '<div class="sharebutton">' . $getsocial_additional . '<div class="gsclear"></div></div>';
						}
					}
					if ( $context == 'options' ) {
						?><li class="ui-state-default" id="rab">
							<div class="sortth">Additional buttons</div>
							<div class="sorttd">
								<textarea name="getsocial[main][additional]" id="getsocial_additional" cols="45" rows="5"><?php echo esc_textarea( $main[ 'additional' ] ); ?></textarea>
							</div>
						</li><?php
					}
					break;
				default :
					break;
			}
			return $html;
		}

		function show_admin_notice() {
			if ( !isset( $_GET[ 'gsmsg' ] ) ) {
				return;
			}

			if ( 'dismiss' == $_GET[ 'gsmsg' ] ) {
				update_option( 'getsocial_show_remote_massage', 'dismiss' );
				wp_cache_delete( 'getsocial_show_remote_massage' );
			}
			if ( 'remind' == $_GET[ 'gsmsg' ] ) {
				update_option( 'getsocial_show_remote_massage', time() + ( 7 * 24 * 60 * 60) );
				wp_cache_delete( 'getsocial_show_remote_massage' );
			}

			$show_msg = get_option( 'getsocial_show_remote_massage', 'show' );

			if ( is_numeric( $show_msg ) ) {
				$diff = time() - intval( $show_msg );
				if ( $diff > 0 )
					$show_msg = "show";
			}

			if ( $show_msg == "show" ) {
				$msg = wp_remote_get( 'http://socialmetricspro.com/api/gsapi.php?context=getsocial' );
				if ( !is_wp_error( $msg ) ) {
					$dismiss_uri = add_query_arg( 'gsmsg', 'dismiss', $_SERVER[ 'REQUEST_URI' ] );
					$remind_uri = add_query_arg( 'gsmsg', 'remind', $_SERVER[ 'REQUEST_URI' ] );
					if ( $msg[ 'response' ][ 'code' ] == 200 && (!empty( $msg[ 'body' ] ) ) ) {
						echo '<div id="message" class="updated" style="padding:10px;margin-top:10px;">' . $msg[ 'body' ] . ' [ <a href="' . $remind_uri . '">Remind me later</a> | <a href="' . $dismiss_uri . '">Dismiss</a> ]</div>';
					}
				}
			}
		}

		/**
		 * Inverts a hexadecimal color value.
		 *
		 * @param string $color
		 * @return string
		 * @link http://psoug.org/snippet/PHP-Invert-Hex-Color_145.htm
		 */
		function inversehex( $color ) {
			$color = trim( $color );
			$prependhash = false;

			if ( strpos( $color, '#' ) !== false ) {
				$prependhash = true;
				$color = str_replace( '#', null, $color );
			}

			switch ( $len = strlen( $color ) ) {
				case 3:
					$color = preg_replace( "/(.)(.)(.)/", "\\1\\1\\2\\2\\3\\3", $color );
				case 6:
					break;
				default:
					$color = 'FFFFFF';
			}

			if ( !preg_match( '/[a-f0-9]{6}/i', $color ) ) {
				$color = htmlentities( 'FFFFFF' );
			}

			$r = dechex( 255 - hexdec( substr( $color, 0, 2 ) ) );
			$r = (strlen( $r ) > 1) ? $r : '0' . $r;
			$g = dechex( 255 - hexdec( substr( $color, 2, 2 ) ) );
			$g = (strlen( $g ) > 1) ? $g : '0' . $g;
			$b = dechex( 255 - hexdec( substr( $color, 4, 2 ) ) );
			$b = (strlen( $b ) > 1) ? $b : '0' . $b;

			return ($prependhash ? '#' : null) . $r . $g . $b;
		}

		function activate() {
			//* Do nothing
		}

		function deactivate() {
			//* Do nothing
		}

		function update_db_table_structure_if_needed() {
			//* Do nothing
		}

	}

//* End Class GetSocial

endif;

//* Instantiate the plugin class.
if ( class_exists( "GetSocial" ) ) {
	$gs = new GetSocial();
//	add_action( 'plugins_loaded', array( &$gs, 'update_db_table_structure_if_needed' ) );
}
