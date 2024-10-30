<?php
	function l_amp_tm_add_info_metabox() {
	
	}
	
	function l_amp_tm_add_social_links_metabox() {
		add_meta_box('l-amp-tm-add-social-links-metabox', 'Social Profile Links', 'l_amp_tm_add_social_links_metabox_content', 'l_amp_team_member', 'normal', 'high');
	}
	add_action('admin_init', 'l_amp_tm_add_social_links_metabox');
	
	$social_icons = array('linkedin', 'facebook', 'twitter', 'googleplus', 'youtube', 'vimeo', 'instagram', 'pintrest');
	function l_amp_tm_add_social_links_metabox_content() {
		global $post, $social_icons;
		
		$current_column = 0;
		$number_of_columns = 2;
	?>
		<table width="100%" cellpadding="0">
		<?php
			foreach($social_icons as $social_icon) {
				if($current_column == 0) {
					echo '<tr>';
				}
				
				$value = get_post_meta($post->ID, 'l-amp-tm-options-'. $social_icon, true);
				echo '<td><p><label for="l-amp-tm-options-'. $social_icon .'">'. $social_icon .':</label></p></td>';
				echo '<td><input id="l-amp-tm-options-'. $social_icon .'" size="37" name="l-amp-tm-options-'. $social_icon .'" type="text" value="'. (!empty($value) ? $value : $value) .'" /></td>';
		
				$current_column++;
				if($current_column == $number_of_columns) {
					$current_column = 0;
					echo '</tr>';
				}
			}
		?>
		</table>
	<?php
	}
	
	function l_amp_tm_save_social_links($post_id) {
		global $post, $social_icons;
		
		if(isset($post) && isset($_POST) && $post->post_type == 'l_amp_team_member') {
			foreach($social_icons as $social_icon) {
				update_post_meta($post->ID, 'l-amp-tm-options-'. $social_icon, $_POST['l-amp-tm-options-'. $social_icon]);
			}
		}
	}
	add_action('save_post', 'l_amp_tm_save_social_links');