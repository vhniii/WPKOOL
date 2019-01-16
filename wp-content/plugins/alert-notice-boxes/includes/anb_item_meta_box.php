<?php
function anb_item_meta_box() {
	global $wpdb;
	$post = get_post();
	$table_name = $wpdb->prefix . 'alert_notice_boxes';
	$alert_notice_box = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE post_ID = %d", $post->ID) );
	if ($alert_notice_box == null) {
		$anb_exists = false;
	} else {
		$anb_exists = true;
	}
	$post_id = false;
	$display_in_value = false;
	if ($alert_notice_box == true) {
		$post_id = $alert_notice_box->post_ID;
		$display_in_value = $alert_notice_box->display_in;
	}
 ?>
	<form id="formanb" method="POST">
	<input type="hidden" name="prevent_delete_meta_movetotrash" id="prevent_delete_meta_movetotrash" value="<?php echo wp_create_nonce(YCANB_PLUGIN_URL.$post->ID); ?>" />
	<div class="anb-settings">
		<ul class="tab">
			<li><span class="tablinks active" data-opentab="General"><?php _e( 'General', 'alert-notice-boxes' ) ?></span></li>
			<li><span class="tablinks" data-opentab="Display"><?php _e( 'Display', 'alert-notice-boxes' ) ?></span></li>
			<li><span class="tablinks" data-opentab="Publish"><?php _e( 'Publish', 'alert-notice-boxes' ) ?></span></li>
			<li><span class="tablinks" data-opentab="Frequency"><?php _e( 'Frequency', 'alert-notice-boxes' ) ?></span></li>
		</ul>
		<div id="General" class="tabcontent" style="display: block;">
			<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
				<tbody>
				<tr class="form-field">
					<th valign="top" scope="row">
						<label for="menu_icon"><?php _e('On / Off', 'alert-notice-boxes')?></label>
						<p><?php _e('If the check box is enabled the message will be active by your chosen settings', 'alert-notice-boxes')?></p>
					</th>
					<td>
					<?php
					if ( $anb_exists ) {
						$enabled_value = $alert_notice_box->enabled;
						$check_enabled_value  = strpos($enabled_value, 'enabled');
						if ($check_enabled_value !== false) {
							?>
							<input name="enabled" id="enabled" type="checkbox" value="enabled" checked><label for="enabled"><?php _e('Enabled', 'alert-notice-boxes')?></label>
							<?php
						} else {
							?>
							<input name="enabled" id="enabled" type="checkbox" value="enabled"><label for="enabled"><?php _e('Enabled', 'alert-notice-boxes')?></label>
							<?php
						} ?>
					<?php } else { ?>
						<input name="enabled" id="enabled" type="checkbox" value="enabled" checked><label for="enabled"><?php _e('Enabled', 'alert-notice-boxes')?></label>
					<?php } ?>

					</td>
				</tr>
				<tr class="form-field">
					<th valign="top" scope="row">
						<label for="menu_icon"><?php _e('Only for logged users', 'alert-notice-boxes')?></label>
						<p><?php _e('If the check box is checked, the message will be appear for only logged users', 'alert-notice-boxes')?></p>
					</th>
					<td>
					<select name="user_types">
							<?php
							$user_types_value = (isset($alert_notice_box->user_types)) ? $alert_notice_box->user_types : null;
							$user_types_values = array(
								'All users' => 'all-users',
								'Only logged users' => 'only-logged-users',
								'Only guests' => 'only-guests',
							);
							foreach($user_types_values as $key => $value) 								{
								if ( $value == $user_types_value ) {
									?>
										<option selected value="<?php echo $value; ?>"><?php echo $key; ?></option>
									<?php
								} else {
									?>
										<option value="<?php echo $value; ?>"><?php echo $key; ?></option>
									<?php
								}
							}
							?>
						</select>
					</td>
				</tr>
				<tr class="form-field">
					<th valign="top" scope="row">
						<label for="devices"><?php _e('Devices', 'alert-notice-boxes')?></label>
						<p><?php _e('Choose the alart box devices', 'alert-notice-boxes')?></p>
					</th>
					<td>
						<select name="devices">
								<?php
									if (isset($alert_notice_box->device_class)) {
										$device_class = $alert_notice_box->device_class;
									} else {
										$device_class = '';
									}
									$devices_values = array(
										'All devices' => 'all-devices',
										'Only Desktop & Wide screen' => 'anb-desktop-widescreen',
										'Only Desktop & Tablet' => 'anb-desktop-tablet',
										'Only Tablet & Mobile' => 'anb-tablet-mobile',
										'Only Tablet' => 'anb-tablet',
										'Only Mobile' => 'anb-mobile',
									);

									foreach($devices_values as $key => $value)
									{
										if ( $value == $device_class ) {
											?>
												<option selected value="<?php echo $value; ?>"><?php echo $key; ?></option>
											<?php
										} else {
											?>
												<option value="<?php echo $value; ?>"><?php echo $key; ?></option>
											<?php
										}
									}
								?>
							</select>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		<div id="Display" class="tabcontent">
			<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
				<tbody>
				<tr class="form-field">
					<th valign="top" scope="row">
						<label for="location"><?php _e('Location', 'alert-notice-boxes')?></label>
						<p><?php _e('Choose the alart box location', 'alert-notice-boxes')?></p>
					</th>
					<td>
						<select name="location">
								<?php
									if (isset($alert_notice_box->location_id)) {
										$location_id = $alert_notice_box->location_id;
									} else {
										$location_id = '';
									}
									$location_values = array(
										'Default' => 'default',
									);

									$anb_locations = get_posts(array(
										'posts_per_page'=> -1,
										'post_type' => 'anb_locations',
									));

									foreach ( $anb_locations as $location ) {
										$location_values[get_the_title( $location->ID )] = $location->ID;
									}

									foreach($location_values as $key => $value)
									{
										if ( $value == $location_id ) {
											?>
												<option selected value="<?php echo $value; ?>"><?php echo $key; ?></option>
											<?php
										} else {
											?>
												<option value="<?php echo $value; ?>"><?php echo $key; ?></option>
											<?php
										}
									}
								?>
							</select>
					</td>
				</tr>
				<tr class="form-field">
					<th valign="top" scope="row">
						<label for="design"><?php _e('Design', 'alert-notice-boxes')?></label>
						<p><?php _e('Choose the alart box design', 'alert-notice-boxes')?></p>
					</th>
					<td>
						<select name="design">
								<?php
									if (isset($alert_notice_box->design_id)) {
										$design_id = $alert_notice_box->design_id;
									} else {
										$design_id = '';
									}
									$design_values = array(
										'success' => 'success',
										'info' => 'info',
										'warning' => 'warning',
										'danger' => 'danger',
									);

									$anb_designs = get_posts(array(
										'posts_per_page'=> -1,
										'post_type' => 'anb_designs',
									));

									foreach ( $anb_designs as $design ) {
										$design_values[get_the_title( $design->ID )] = $design->ID;
									}

									foreach($design_values as $key => $value)
									{
										if( $value == $design_id ) {
											?>
												<option selected value="<?php echo $value; ?>"><?php echo $key; ?></option>
											<?php
										} else {
											?>
												<option value="<?php echo $value; ?>"><?php echo $key; ?></option>
											<?php
										}
									}
								?>
							</select>
					</td>
				</tr>
				<tr class="form-field">
					<th valign="top" scope="row">
						<label for="animation"><?php _e('Animation In', 'alert-notice-boxes')?></label>
						<p><?php _e('Choose the alart box animation in', 'alert-notice-boxes')?></p>
					</th>
					<td>
						<select name="animation">
								<?php
									if (isset($alert_notice_box->animation_id)) {
										$animation_id = $alert_notice_box->animation_id;
									} else {
										$animation_id = '';
									}
									$animation_values = array(
										'default' => 'Default',
									);

									$anb_animations = get_posts(array(
										'posts_per_page'=> -1,
										'post_type' => 'anb_animations',
									));

									foreach ( $anb_animations as $animation ) {
										$animation_values[$animation->ID] = get_the_title( $animation->ID );
									}

									foreach($animation_values as $key => $value)
									{
										if( $key == $animation_id ) {
											?>
												<option selected value="<?php echo $key; ?>"><?php echo $value; ?></option>
											<?php
										} else {
											?>
												<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
											<?php
										}
									}
								?>
							</select>
					</td>
				</tr>
				<tr class="form-field">
					<th valign="top" scope="row">
						<label for="animation_out"><?php _e('Animation Out', 'alert-notice-boxes')?></label>
						<p><?php _e('Choose the alart box animation out', 'alert-notice-boxes')?></p>
					</th>
					<td>
						<select name="animation_out">
								<?php
									if (isset($alert_notice_box->animation_out_id)) {
										$animation_out_id = $alert_notice_box->animation_out_id;
									} else {
										$animation_out_id = '';
									}
									$animation_out_values = array(
										'default' => 'Default',
									);

									$anb_out_animations = get_posts(array(
										'posts_per_page'=> -1,
										'post_type' => 'anb_animations_out',
									));

									foreach ( $anb_out_animations as $animation_out ) {
										$animation_out_values[$animation_out->ID] = get_the_title( $animation_out->ID );
									}

									foreach($animation_out_values as $key => $value)
									{
										if( $key == $animation_out_id ) {
											?>
												<option selected value="<?php echo $key; ?>"><?php echo $value; ?></option>
											<?php
										} else {
											?>
												<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
											<?php
										}
									}
								?>
							</select>
					</td>
				</tr>
				<tr class="form-field">
					<th valign="top" scope="row">
						<label for="delay"><?php _e('Delay', 'alert-notice-boxes')?></label>
						<p><?php _e('The time it takes the notice to appear in milliseconds', 'alert-notice-boxes')?></p>
					</th>
					<td>
						<?php
						if ( $anb_exists ) {
							$delay_value = $alert_notice_box->delay;
							?>
							<input name="delay" type="number" step="any"  value="<?php echo $delay_value; ?>">
							<?php
						} else {
							?>
							<input name="delay" type="number" step="any" value="2">
							<?php
						} ?>
					</td>
				</tr>
				<tr class="form-field">
					<th valign="top" scope="row">
						<label for="show_time"><?php _e('Show Time', 'alert-notice-boxes')?></label>
						<p><?php _e('The duration of the notice will appear in seconds', 'alert-notice-boxes')?></p>
					</th>
					<td>
						<?php
						if ( $anb_exists ) {
							$show_time_value = $alert_notice_box->show_time;
							?>
							<input name="show_time" type="number" value="<?php echo $show_time_value; ?>" min="0" >
							<?php
						} else {
							?>
							<input name="show_time" type="number" value="8" min="0" >
							<?php
						} ?>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		<div id="Publish" class="tabcontent">
			<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
				<tbody>
				<tr class="form-field">
					<th valign="top" scope="row">
						<label for="display_in"><?php _e('Published in', 'alert-notice-boxes')?></label>
						<p><?php _e('Choose where you want to display the alert', 'alert-notice-boxes')?></p>
					</th>
					<td>
				<?php

					$post_types = get_post_types( array( 'public' => true ) );
					if (class_exists('BuddyPress')) {
						$boddypress_typs = array(
							'front' => 'BuddyPress user main page',
							'activity' => 'BuddyPress activities',
							'members' => 'BuddyPress members',
							'profile' => 'BuddyPress profile',
						);
						$post_types = array_merge($post_types, $boddypress_typs);
					}
					// print_r($post_types);
					foreach ( $post_types as $post_type => $post_type_name ) {
						$check_post_type = strpos($display_in_value, $post_type);
						if ($check_post_type !== false) {
							?>
							<input name="<?php echo esc_attr( $post_type ); ?>" type="checkbox" id="anb_<?php echo esc_attr( $post_type ); ?>" value="<?php echo esc_attr( $post_type ); ?>" checked><label for="post_types_<?php echo esc_attr( $post_type ); ?>"><?php echo esc_html( ucfirst( $post_type_name ) ); ?></label><br>
							<?php
						} else {
							?>
							<input name="<?php echo esc_attr( $post_type ); ?>" type="checkbox" id="anb_<?php echo esc_attr( $post_type ); ?>" value="<?php echo esc_attr( $post_type ); ?>"><label for="post_types_<?php echo esc_attr( $post_type ); ?>"><?php echo esc_html( ucfirst( $post_type_name ) ); ?></label><br>
							<?php
						}
					}
				?>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		<div id="Frequency" class="tabcontent">
			<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
				<tbody>
				<tr class="form-field">
					<th valign="top" scope="row">
						<label for="close_button"><?php _e('Close Button', 'alert-notice-boxes')?></label>
						<p><?php _e('Select how many days will be canceled the message when click on button close', 'alert-notice-boxes')?></p>
					</th>
					<td>
						<select name="click_on_close_button_anb_option" data-hide-close-options>
							<?php
								$click_on_close_button = get_post_meta( $post->ID, "click_on_close_button_anb_option", true );
								$click_on_close_button_values = array(
									'Do nothing' => 'do-nothing',
									'Cancel for' => 'cancel-for',
								);

								foreach($click_on_close_button_values as $key => $value)
								{
									if ( $value == $click_on_close_button ) {
										?>
											<option selected value="<?php echo $value; ?>"><?php echo $key; ?></option>
										<?php
									} else {
										?>
											<option value="<?php echo $value; ?>"><?php echo $key; ?></option>
										<?php
									}
								}
							?>
						</select>
						<p id="form-field-cancel-for" <?php if(get_post_meta( $post->ID, "click_on_close_button_anb_option", true )!= 'cancel-for') {echo 'style="display: none;"';} ?>>
							<?php
							$days_click_on_close_button_anb_option = get_post_meta( $post->ID, "days_click_on_close_button_anb_option", true );
							if ( $days_click_on_close_button_anb_option != '' ) {
								?>
								<input name="days_click_on_close_button_anb_option" class="anb_small_input" type="number" value="<?php echo $days_click_on_close_button_anb_option; ?>" min="1" ><span> <?php _e('Days', 'alert-notice-boxes')?></span><br>
								<?php
							} else {
								?>
								<input name="days_click_on_close_button_anb_option" class="anb_small_input" type="number" value="10" min="1" > <span> <?php _e('Days', 'alert-notice-boxes')?></span><br>
								<?php
							}
							?>
						</p>
					</td>
				</tr>
				<tr class="form-field">
					<th valign="top" scope="row">
						<label for="limitations"><?php _e('Limitations', 'alert-notice-boxes')?></label>
						<p><?php _e('Limit the number of shows at selected time range', 'alert-notice-boxes')?></p>
					</th>
					<td>
						<select name="limitations_anb_option" data-hide-limitations-options>
							<?php
								$limitations = get_post_meta( $post->ID, "limitations_anb_option", true );
								$limitations_values = array(
									'No limitations' => 'no-limitations',
									'Custom limitations' => 'custom-limitations',
								);

								foreach($limitations_values as $key => $value)
								{
									if ( $value == $limitations ) {
										?>
											<option selected value="<?php echo $value; ?>"><?php echo $key; ?></option>
										<?php
									} else {
										?>
											<option value="<?php echo $value; ?>"><?php echo $key; ?></option>
										<?php
									}
								}
							?>
						</select>
						<p id="form-field-custom-limitations" <?php if(get_post_meta( $post->ID, "limitations_anb_option", true )!= 'custom-limitations') {echo 'style="display: none;"';} ?>>
							<?php _e('Choose how many times the message will appear, in a number of selected days', 'alert-notice-boxes')?>:<br>
							<?php
							$times_custom_limitations_value = get_post_meta( $post->ID, "times_custom_limitations_anb_option", true );
							if ( $times_custom_limitations_value != '' ) {
								?>
								<input name="times_custom_limitations_anb_option" class="anb_small_input" type="number" value="<?php echo $times_custom_limitations_value; ?>" min="1" ><span> <?php _e('Times', 'alert-notice-boxes')?></span><br>
								<?php
							} else {
								?>
								<input name="times_custom_limitations_anb_option" class="anb_small_input" type="number" value="10" min="1" > <span> <?php _e('Times', 'alert-notice-boxes')?></span><br>
								<?php
							}
							$days_custom_limitations_value = get_post_meta( $post->ID, "days_custom_limitations_anb_option", true );
							if ( $days_custom_limitations_value != '' ) {
								?>
								<input name="days_custom_limitations_anb_option" class="anb_small_input" type="number" value="<?php echo $days_custom_limitations_value; ?>" min="1" ><span> <?php _e('Days', 'alert-notice-boxes')?></span>
								<?php
							} else {
								?>
								<input name="days_custom_limitations_anb_option" class="anb_small_input" type="number" value="10" min="1" ><span> <?php _e('Days', 'alert-notice-boxes')?></span>
								<?php
							}
							?>
						</p>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	</form>
<?php
}

function anb_create_meta_boxes() {
    add_meta_box("alert-notice-boxes-item-meta-box", __( 'Alert Notice Box settings', 'alert-notice-boxes' ), 'anb_item_meta_box', "anb", "normal", "core", null);
}
add_action( 'add_meta_boxes', 'anb_create_meta_boxes' );
