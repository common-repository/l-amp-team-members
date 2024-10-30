<?php
	function l_amp_tm_settings_page() {
		$options = get_option('l-amp-tm-options');
?>
	<div class="wrap">
		<h2><?php _e('Settings', 'l-amp-team-members'); ?></h2>
	<?php
		if(isset($_GET['options-updated']) && $_GET['options-updated'] == 'true') {
			echo '<div id="message" class="updated"><p>'. __('Settings updated', 'l-amp-team-members') .'</p></div>';
		}
	?>
		<form method="post" action="options.php">
	<?php
		settings_fields('l-amp-team-members-settings');
	?>
			<table cellpadding="5" cellspacing="5">
				<tr>
					<td style="background-color:#f5f5f5;"><strong><?php _e('Image Sizes', 'l-amp-team-members'); ?></strong></td>
					<td nowrap>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td width="150"><?php _e('Thumbnail Image Size', 'l-amp-team-members'); ?></td>
					<td nowrap><?php _e('Width', 'l-amp-team-members'); ?>: 
						<input name="l-amp-tm-options[thumb_width]" type="text" value="<?php echo $options['thumb_width']; ?>" size="5" />
						<?php _e('Height', 'l-amp-team-members'); ?>:
						<input name="l-amp-tm-options[thumb_height]" type="text" value="<?php echo $options['thumb_height']; ?>" size="5" />
						<?php _e('Crop', 'l-amp-team-members'); ?>: 
						<select name="l-amp-tm-options[thumb_crop]">
							<option value="true" <?php selected($options['thumb_crop'], 'true'); ?>><?php _e('Yes', 'l-amp-team-members'); ?></option>
							<option value="false" <?php selected($options['thumb_crop'], 'false'); ?>><?php _e('No', 'l-amp-team-members'); ?></option>
						</select>
					</td>
					<td><span class="howto"><?php _e('This will be the size of the Images. When they are uploaded they will follow this settings. If you change this settings after the image is uploaded they will show scaled.', 'l-amp-team-members'); ?></span></td>
				</tr>
				<tr>
					<td><?php _e('Social Icons', 'l-amp-team-members'); ?></td>
					<td nowrap>
						<select name="l-amp-tm-options[social_icons]">
							<option value="round-16" <?php selected($options['social_icons'], 'round-16'); ?>><?php _e('Round 16x16', 'l-amp-team-members'); ?></option>
							<option value="round-24" <?php selected($options['social_icons'], 'round-24'); ?>><?php _e('Round 24x24', 'l-amp-team-members'); ?></option>
							<option value="round-32" <?php selected($options['social_icons'], 'round-32'); ?>><?php _e('Round 32x32', 'l-amp-team-members'); ?></option>
						</select>
					</td>
					<td><span class="howto"><?php _e('What Social Icons do you want to display?', 'l-amp-team-members'); ?></span></td>
				</tr>
				<tr>
					<td style="background-color:#f5f5f5;"><strong><?php _e('Post Type', 'l-amp-team-members'); ?></strong></td>
					<td nowrap>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td width="150"><?php _e('Singular Name', 'l-amp-team-members'); ?>:</td>
					<td nowrap><input type="text" name="l-amp-tm-options[singular]" value="<?php echo $options['singular']; ?>" /></td>
					<td><span class="howto"><?php _e('These will be the labels for your post type.', 'l-amp-team-members'); ?></span></td>
				</tr>
				<tr>
					<td><?php _e('Plural Name', 'l-amp-team-members'); ?>:</td>
					<td nowrap><input type="text" name="l-amp-tm-options[plural]" value="<?php echo $options['plural']; ?>" /></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><?php _e('Slug', 'l-amp-team-members'); ?>:</td>
					<td nowrap><input type="text" name="l-amp-tm-options[slug]" value="<?php echo $options['slug']; ?>" /></td>
					<td><strong><span class="howto"><?php _e('If you change this option, you might have to update/save the "permalink" settings again.', 'l-amp-team-members'); ?></span></strong></td>
				</tr>
				<tr>
					<td><?php _e('Category', 'l-amp-team-members'); ?>:</td>
					<td nowrap><input type="text" name="l-amp-tm-options[category]" value="<?php echo $options['category']; ?>" /></td>
					<td>&nbsp;</td>
				</tr>
				 <tr>
					<td><?php _e('Public', 'l-amp-team-members'); ?>:</td>
					<td nowrap>
						<select name="l-amp-tm-options[public]">
							<option value="true" <?php selected($options['public'], 'true'); ?>><?php _e('Yes', 'l-amp-team-members'); ?></option>
							<option value="false" <?php selected($options['public'], 'false'); ?>><?php _e('No', 'l-amp-team-members'); ?></option>
						</select>
					</td>
				</tr>
			</table>
			
			<?php submit_button(); ?>
		</form>
	</div>
<?php
	}
?>