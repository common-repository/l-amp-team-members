<?php
	/*
	Plugin Name: L'Amp Team Members
	Version: 1.4.2
	Description: Add your team members.
	Author: Dave Nieuwenhuijzen
	Author URI: http://www.l-amp.nl
	Text Domain: l-amp-team-members
	Domain Path: /languages/
	*/
	define('L_AMP_TM_PLUGIN_PATH', dirname(__FILE__));
	require_once L_AMP_TM_PLUGIN_PATH .'/inc/settings.php';
	require_once L_AMP_TM_PLUGIN_PATH .'/inc/metabox.php';
	
	$options = get_option('l-amp-tm-options');
	add_image_size('l-amp-tm-thumb', $options['thumb_width'], $options['thumb_height'], (bool) $options['thumb_crop']);	
	
	function l_amp_tm_activation() {
		if(!get_option('l-amp-tm-options')) {
			$options = array(
				'singular' => 'Member',
				'plural' => 'Members',
				'slug' => 'team',
				'public' => 'true',
				'category' => 'Groups',
				'thumb_width' => 160,
				'thumb_height' => 160,
				'thumb_crop' => 'false',
				'social_icons' => 'round-24'
			);
			
			update_option('l-amp-tm-options', $options);
		}
		
		flush_rewrite_rules();
	}
	register_activation_hook(__FILE__, 'l_amp_tm_activation');
	
	function l_amp_tm_deactivation() {
		flush_rewrite_rules();
	}
	register_deactivation_hook(__FILE__, 'l_amp_tm_deactivation');
	
	function l_amp_tm_enqueue_scripts() {
		wp_enqueue_style('l-amp-tm-style', plugins_url('css/style.css', __FILE__));
	}
	add_action('wp_enqueue_scripts', 'l_amp_tm_enqueue_scripts');
	
	function l_amp_tm_register_posttype() {
		$options = get_option('l-amp-tm-options');
		
		$singular = $options['singular'];
		$plural = $options['plural'];
		$slug = $options['slug'];
		$public = (bool) $options['public'];
		
		$labels = array(
			'name' => sprintf(__('%1$s', 'l-amp-team-members'), $plural),
			'singular_name' => sprintf(__('%1$s', 'l-amp-team-members'), $singular),
			'menu_name' => sprintf(__('%1$s', 'l-amp-team-members'), $plural),
			'add_new' => __('Add New', 'l-amp-team-members'),
			'add_new_item' => sprintf(__('Add New %1$s', 'l-amp-team-members'), $singular),
			'new_item' => sprintf(__('New %1$s', 'l-amp-team-members'), $singular),
			'view_item' => sprintf(__('View %1$s', 'l-amp-team-members'), $singular),
			'search_items' => sprintf(__('Search %1$s', 'l-amp-team-members'), $singular),
			'not_found' => sprintf(__('No %1$s found', 'l-amp-team-members'), $singular),
			'not_found_in_trash' => sprintf(__('No %1$s found in thrash', 'l-amp-team-members'), $plural),
			'parent_item_colon' => ''
		);
		
		$args = array(
			'labels' => $labels,
			'hierarchical' => false,        
			'supports' => array('title', 'thumbnail', 'custom-fields', 'editor','page-attributes'),
			'public' => $public,
			'show_ui' => true,
			'show_in_menu' => true,       
			'show_in_nav_menus' => true,
			'publicly_queryable' => true,
			'exclude_from_search' => true,
			'has_archive' => true,
			'query_var' => true,
			'can_export' => true,
			'rewrite' => true,
			'capability_type' => 'post',
			'menu_icon' => plugins_url('img/icon16.png', __FILE__),
			'rewrite' => array(
					'slug' => $slug
				)
		);
	
		register_post_type('l_amp_team_member', $args);
			
	}
	add_action('init', 'l_amp_tm_register_posttype');
	
	function l_amp_tm_admin_footer() {
	?>
		<script type="text/javascript">
			jQuery("#l_amp_team_member_categorieschecklist > li > label input").each(function(){
				this.type = 'radio';
			});
		</script>
	<?php
	}
	add_action('admin_footer', 'l_amp_tm_admin_footer'); 
	
	function l_amp_tm_build_taxonomies() {
		$options = get_option('l-amp-tm-options');
		$categories = $options['category'];
		
		register_taxonomy('l_amp_team_member_categories', 'l_amp_team_member', array('hierarchical' => true, 'label' => $categories, 'query_var' => true, 'rewrite' => true));
	}
	add_action('init', 'l_amp_tm_build_taxonomies');
	
	function l_amp_tm_register_settings() {
		register_setting('l-amp-team-members-settings', 'l-amp-tm-options');
	}
	add_action('admin_init', 'l_amp_tm_register_settings');
	
	function l_amp_tm_settings_page_menu() {
		add_submenu_page(
			'edit.php?post_type=l_amp_team_member',
			'Settings',
			'Settings',
			'manage_options',
			'l_amp_tm_settings',
			'l_amp_tm_settings_page'
		);
	}
	add_action('admin_menu', 'l_amp_tm_settings_page_menu');
	
	function l_amp_tm_load_textdomain() {
		load_plugin_textdomain('l-amp-team-members', false, dirname(plugin_basename(__FILE__)) .'/languages');
	}
	add_action('plugins_loaded', 'l_amp_tm_load_textdomain');
	
	$attributes = array();
	function l_amp_tm_shortcode($atts) {
		global $post, $attributes;
		$attributes = $atts;
		if(!is_array($attributes)) {
			$attributes = array();
		}
		
		$attributes['orderby'] = (array_key_exists('orderby', $attributes) ? $attributes['orderby'] : 'menu_order');
		$attributes['order'] = (array_key_exists('order', $attributes) ? $attributes['order'] : 'ASC');
		$attributes['limit'] = (array_key_exists('limit', $attributes) ? $attributes['limit'] : 0);
		$attributes['url'] = (array_key_exists('url', $attributes) ? $attributes['url'] : 'inactive');
		$attributes['category'] = (array_key_exists('category', $attributes) ? $attributes['category'] : '0');
		$attributes['display'] = (array_key_exists('display', $attributes) ? $attributes['display'] : 'name,photo,position');
		$attributes['style'] = (array_key_exists('style', $attributes) ? $attributes['style'] : '2-columns');
		
		$display_options = explode(',', $attributes['display']);
		$order_by_category = (bool) in_array('order_by_category', $display_options);
		
		$posts_per_page = -1;
		if($attributes['limit'] >= 1) {
			$posts_per_page = $attributes['limit'];
		}
		
		$output = '<div class="l-amp-tm-wrap">';
		
		$categories = get_terms('l_amp_team_member_categories', 'orderby=menu_order&order=ASC&hide_empty=1');
		
		if(!$order_by_category || count($categories) == 0) {
			$args = array(
				'post_type' => 'l_amp_team_member',
				'orderby' => $attributes['orderby'],
				'order' => $attributes['order'],
				'posts_per_page' => $posts_per_page
			);
			$output .= l_amp_tm_get_posts($args);
		} else {
			foreach($categories as $category) {
				$output .= '<div class="l-amp-tm-category l-amp-tm-category-'. $category->slug .'">';
				$output .= '<h2>'. $category->name .'</h2>';
				
				$args = array(
					'post_type' => 'l_amp_team_member',
					'taxonomy' => $category->taxonomy,
					'term' => $category->slug,
					'orderby' => $orderby,
					'order' => $order,
					'posts_per_page' => $posts_per_page
				);
				
				$output .= l_amp_tm_get_posts($args);
				
				$output .= '</div>';
			}
		}
		
		$output .= '</div>';
		
		return $output;
	}
	add_shortcode('l-amp-tm', 'l_amp_tm_shortcode');
	
	function l_amp_tm_get_posts($args) {
		global $post, $attributes;
		
		$output = '';
		$thumb_size = 'l-amp-tm-thumb';
		
		$options = get_option('l-amp-tm-options');
		$display_options = explode(',', $attributes['display']);
		$style_options = explode('-', $attributes['style']);
		$number_of_columns = $style_options[0];
		$style_options = 'l-amp-tm-box-'. $attributes['style'];
		
		$current_column = 0;
		query_posts($args);
		if(have_posts()) {
			while(have_posts()) {
				the_post();
				
				if($current_column == 0) {
					$output .= '<div class="l-amp-tm-row">';
				}
				
				$output .= '<div class="l-amp-tm-box l-amp-tm-member-'. get_the_ID() .' '. $style_options .'">';
				$output .= '<div class="l-amp-tm-box-photo">';
				
				if(has_post_thumbnail() && in_array('photo', $display_options)) {
					$image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), $thumb_size);
					$image_src = $image[0];
					$image_width = $image[1];
					$image_height = $image[2];
				} else {
					$image_src = plugins_url('img/default.png', __FILE__);
					$image_width = $options['thumb_width'];
					$image_height = $options['thumb_height'];
				}
				
				if($attributes['url'] == 'active') {
					$output .= '<a href="'. get_permalink($post->ID) .'">';
				}
				
				$output .= '<img src="'. $image_src .'" width="'. $image_width .'" height="'. $image_height .'" />';
				
				if($attributes['url'] == 'active') {
					$output .= '</a>';
				}
				
				$output .= '</div>';
				
				$output .= '<div class="l-amp-tm-box-info">';
				
				if(in_array('name', $display_options)) {
					$output .= '<div class="l-amp-tm-box-name">';
					
					if($attributes['url'] == 'active') {
						$output .= '<a href="'. get_permalink($post->ID) .'">';
					}
					$output .= the_title_attribute('echo=0');
						
					if($attributes['url'] == 'active') {
						$output .= '</a>';
					}
					$output .= '</div>';
				}
				
				if(in_array('info', $display_options)) {
					$output .= '<div class="l-amp-tm-box-content">';
					$output .= apply_filters('the_excerpt', $post->post_excerpt);
					$output .= '</div>';
				}
				
				if(in_array('social_icons', $display_options)) {
					$output .= '<div class="l-amp-tm-box-social-icons">';
					$dimensions = explode('-', $options['social_icons']);
					$social_icons = array('linkedin', 'facebook', 'twitter', 'googleplus', 'youtube', 'vimeo', 'instagram', 'pintrest');
					foreach($social_icons as $social_icon) {
						$social_icon_url = get_post_meta(get_the_ID(), 'l-amp-tm-options-'. $social_icon, true);
						if(!empty($social_icon_url)) {
							$output .= '<a href="'. $social_icon_url .'" target="_blank"><img src="'. plugins_url('img/social/'. $options['social_icons'] .'/'. $social_icon .'.png', __FILE__) .'" width="'. $dimensions[1] .'" height="'. $dimensions[1] .'" /></a>';
						}
					}
					
					$output .= '</div>';
				}
				
				$output .= '</div>';
				$output .= '</div>';
				
				$current_column++;
				if($current_column == $number_of_columns) {
					$current_column = 0;
					$output .= '</div>';
				}
			}
			
			if($current_column > 0) {
				$output .= '</div>';
			}
		}
		wp_reset_query();
		
		return $output;
	}