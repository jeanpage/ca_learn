<div class = "wrap gswrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<script>
		$(function() {
			$("#sortable").sortable({
				cursor: "move",
				update: function(event, ui) {
					var order = $(this).sortable('toArray').toString();
					document.forms["gsopt"]["getsocial_order"].value = order;
				}
			});
			$("#sortable").disableSelection();
		});
	</script><?php
	global $wp_query;
	$messages = get_transient( $this->_getsocial_settings_transient );

	delete_transient( $this->_getsocial_settings_transient );
	if ( !empty( $messages ) && !empty( $messages[ 'updates' ] ) ) {
		foreach ( $messages[ 'updates' ] as $key => $update ) {
			?><div id="getsocial-settings-updated-message-<?php echo $key; ?>" class="updated"><p><strong><?php echo wp_kses_post( $update ); ?></strong></p></div><?php
		}
	} elseif ( !empty( $messages ) && !empty( $messages[ 'errors' ] ) ) {
		foreach ( $messages[ 'errors' ] as $key => $error ) {
			?><div id="getsocial-settings-error-message-<?php echo $key; ?>" class="error"><p><strong><?php echo wp_kses_post( $error ); ?></strong></p></div><?php
					}
				}
				?>
	<form method="post" action="<?php echo add_query_arg( array( ) ); ?>" name="gsopt"><?php
		settings_fields( 'getsocial-settings-group' );
		wp_nonce_field( 'save-getsocial-settings', 'save-getsocial-settings-nonce' );
		?>
		<div class="getsocial-button-element">
			<button class="button button-primary"><?php _e( 'Save Changes' ) ?></button>
		</div>
		<div class="gs-container">
			<div id="gs-left">
				<h3>Social Media Buttons <span class="learnmore">(<a href="http://socialmetricspro.com/getsocial-plugin-settings/" target="_blank">Learn More</a>)</span></h3>
				<ul>
					<li>
						<div class="sortth" style="float:left">Twitter Username</div>
						<div class="sorttd"><input type="text" name="getsocial[main][twitter_username]" value="<?php echo esc_attr( $main[ 'twitter_username' ] ); ?>" /></div>
					</li>
					<li>
						<div class="sorttd"><input type="hidden" name="getsocial[main][order]" id="getsocial_order" value="<?php echo esc_attr( $main[ 'order' ] ); ?>" /></div>
					</li>
				</ul>
				<div class="gsclear"></div>
				<input type="hidden" name="order" id="order" value=""/>
				<div class="dragtitle">Simply drag and drop the boxes below to re-order the buttons on GetSocial bar.</div>
				<ul id="sortable" class="gstleft"><?php
					$order = esc_attr( $main[ 'order' ] );
					$order = trim( $order, ',' );
					$rows = explode( ",", $order );
					foreach ( $rows as $row ) {
						$this->getsocial_button_code_get( $row, 'options' );
					}
					?>
				</ul>
			</div>
			<div id="gs-right">
				<h3>Positioning and Size <span class="learnmore">(<a href="http://socialmetricspro.com/getsocial-plugin-settings/#pas" target="_blank">Learn More</a>)</span></h3>

				<table class="form-table gstleft">
					<tr valign="top">
						<th scope="row">Initial Offset from top</th>
						<td><input type="text" name="getsocial[main][initial_top]" value="<?php echo esc_attr( $main[ 'initial_top' ] ); ?>" style="width: 50px;" /> px</td>
					</tr>

					<tr valign="top">
						<th scope="row">Offset from left</th>
						<td><input type="text" name="getsocial[main][offset_left]" value="<?php echo esc_attr( $main[ 'offset_left' ] ); ?>" style="width: 50px;"/> px</td>
					</tr>

					<tr valign="top">
						<th scope="row">Browser Width</th>
						<td><input type="text" name="getsocial[main][browser_width]" value="<?php echo esc_attr( $main[ 'browser_width' ] ); ?>" style="width: 50px;"/> px</td>
					</tr>

					<tr valign="top">
						<th scope="row">Offset from top after scrolling</th>
						<td><input type="text" name="getsocial[main][scrolled_top]" value="<?php echo esc_attr( $main[ 'scrolled_top' ] ); ?>" style="width: 50px;"/>px</td>
					</tr>

					<tr valign="top">
						<th scope="row">Width of the GetSocial bar</th>
						<td><input type="text" name="getsocial[main][bar_width]" value="<?php echo esc_attr( $main[ 'bar_width' ] ); ?>" style="width: 50px;"/>px</td>
					</tr>
				</table>
				<h3>Display Options and Colors <span class="learnmore">(<a href="http://socialmetricspro.com/getsocial-plugin-settings/#doac" target="_blank">Learn More</a>)</span></h3>
				<table class="form-table gstleft">
					<tr valign="top">
						<th scope="row">Display on</th>
						<td>
							<div id="check-display">
								<input type="checkbox" name="getsocial[main][show_on_posts]" id="getsocial_show_on_posts" value="true" <?php
								$show_on_posts = esc_attr( $main[ 'show_on_posts' ] );
								if ( true == $show_on_posts ) {
									_e( 'checked="checked"', "getsocial_show_on_posts" );
								}
								?> /><label for="getsocial_show_on_posts">Posts</label>
								<input type="checkbox" name="getsocial[main][show_on_pages]" id="getsocial_show_on_pages" value="true" <?php
								$show_on_pages = esc_attr( $main[ 'show_on_pages' ] );
								if ( true == $show_on_pages ) {
									_e( 'checked="checked"', "getsocial_show_on_pages" );
								}
								?> /><label for="getsocial_show_on_pages">Pages</label>
								<input type="checkbox" name="getsocial[main][show_on_homepage]" id="getsocial_show_on_homepage" value="true" <?php
								$show_on_homepage = esc_attr( $main[ 'show_on_homepage' ] );
								if ( true == $show_on_homepage ) {
									_e( 'checked="checked"', "getsocial_show_on_homepage" );
								}
								?> /><label for="getsocial_show_on_homepage">Home</label>
							</div>
						</td>
					</tr>

					<tr valign="top">
						<th scope="row">Background Color</th>
						<td><input type="text" id ="bkcolor" name="getsocial[main][background_color]" value="<?php echo esc_attr( $main[ 'background_color' ] ); ?>" style="width: 103px;" />
							<div id="bkcolorpicker"></div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Border Color</th>
						<td><input type="text" id="bordercolor" name="getsocial[main][border_color]" value="<?php echo esc_attr( $main[ 'border_color' ] ); ?>" style="width: 103px;" />
							<div id="bordercolorpicker"></div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Hide Initially</th>
						<td>
							<div id="radio-ph">
								<input type="radio" name="getsocial[main][preload_hide]" id="gs-ph-yes" value="yes" <?php
								$hide = esc_attr( $main[ 'preload_hide' ] );
								if ( "yes" == $hide ) {
									echo 'checked';
								}
								?> /><label for="gs-ph-yes">Yes</label>
								<input type="radio" name="getsocial[main][preload_hide]" id="gs-ph-no" value="no" <?php
								if ( "no" == $hide ) {
									echo 'checked';
								}
								?> /><label for="gs-ph-no">No</label>
							</div>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Show Rounded Corners</th>
						<td>
							<div id="radio-rc">
								<input type="radio" name="getsocial[main][rounded_corners]" id="gs-rc-yes" value="yes" <?php
								$rounded_corners = esc_attr( $main[ 'rounded_corners' ] );
								if ( "yes" == $rounded_corners ) {
									echo 'checked';
								}
								?> /><label for="gs-rc-yes">Yes</label>
								<input type="radio" name="getsocial[main][rounded_corners]" id="gs-rc-no" value="no" <?php
								if ( "no" == $rounded_corners ) {
									echo 'checked';
								}
								?> /><label for="gs-rc-no">No</label>
							</div>
						</td>
					</tr>
				</table>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery('#bkcolorpicker').hide();
						jQuery('#bkcolorpicker').farbtastic("#bkcolor");
						jQuery("#bkcolor").click(function() {
							jQuery('#bkcolorpicker').slideToggle()
						});
					});
				</script>
				<script type="text/javascript">
					jQuery(document).ready(function() {
						jQuery('#bordercolorpicker').hide();
						jQuery('#bordercolorpicker').farbtastic("#bordercolor");
						jQuery("#bordercolor").click(function() {
							jQuery('#bordercolorpicker').slideToggle()
						});
					});
				</script>
				<h3>Additional Options <span class="learnmore">(<a href="http://socialmetricspro.com/getsocial-plugin-settings/#ao" target="_blank">Learn More</a>)</span></h3>
				<table class="form-table gstleft">
					<tr valign="top">
						<th scope="row">Load scripts in Footer</th>
						<td><div id="radio-lsif">
								<input type="radio" name="getsocial[main][scripts_in_footer]" id="gssfyes" value="true" <?php
								$scripts_in_footer = esc_attr( $main[ 'scripts_in_footer' ] );
								if ( "true" == $scripts_in_footer ) {
									_e( 'checked="checked"', "getsocial_scripts_in_footer" );
								}
								?> /><label for="gssfyes">Yes</label>
								<input type="radio" name="getsocial[main][scripts_in_footer]" id="gssfno" value="false" <?php
								if ( "false" == $scripts_in_footer ) {
									_e( 'checked="checked"', "getsocial_scripts_in_footer" );
								}
								?> /><label for="gssfno">No</label>
							</div></td>
					</tr>
					<tr valign="top">
						<th scope="row">Share some Linkluv</th>
						<td><div id="radio-sutl">
								<input type="radio" name="getsocial[main][linkluv]" id="gsllyes" value="true" <?php
								$linkluv = esc_attr( $main[ 'linkluv' ] );
								if ( "true" == $linkluv ) {
									_e( 'checked="checked"', "getsocial_linkluv" );
								}
								?> /><label for="gsllyes">Sure, I don't mind</label>
								<input type="radio" name="getsocial[main][linkluv]" id="gsllno" value="false" <?php
								if ( "false" == $linkluv ) {
									_e( 'checked="checked"', "getsocial_linkluv" );
								}
								?> /><label for="gsllno">No, Please hide it</label>
							</div></td>
					</tr>
				</table>
			</div>
			<div class="gsclear"></div>
		</div>
		<div class="getsocial-button-element">
			<button class="button button-primary"><?php _e( 'Save Changes' ) ?></button>
		</div>
		<div class="clear"></div>
		<div style="padding-top:20px;color:#808080;">For Help, Support, Bugs, Feedback, Suggestions, Feature requests, please visit <a href="http://socialmetricspro.com/getsocial/" style="text-decoration:none;" target="_blank">GetSocial Homepage</a>.</div>
	</form>
</div>

