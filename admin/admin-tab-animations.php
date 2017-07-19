				<h2><?php _e('Animation Settings <span style="color:green">(basic)</span>', 'fppg'); ?></h2>

				<p><?php _e('These settings control the animations when opening and closing Fancybox, and the optional easing effects.', 'fppg'); ?></p>

			<table class="form-table" style="clear:none;">
					<tbody>

						<tr valign="top">
							<th scope="row"><?php _e(' Options', 'fppg'); ?></th>
							<td>
								<fieldset>

									<label for="Opacity">
										<input type="checkbox" name="fppg[fppg_Opacity] id="Opacity"<?php if ($settings['fppg_Opacity']) echo ' checked="yes"';?> />
										<?php _e('Change content transparency during  animations (default: on)', 'fppg'); ?>
									</label><br /><br />

									<label for="SpeedIn">
										<select name="fppg[fppg_SpeedIn]" id="SpeedIn">
											<?php
											foreach($msArray as $key=> $ms) {
												if($settings['fppg_SpeedIn'] != $ms) $selected = '';
												else $selected = ' selected';
												echo "<option value='$ms'$selected>$ms</option>\n";
											} ?>
										</select>
										<?php _e('Speed in miliseconds of the ing-in animation (default: 500)', 'fppg'); ?>
									</label><br /><br />

									<label for="SpeedOut">
										<select name="fppg[fppg_SpeedOut] id="SpeedOut">
											<?php
											foreach($msArray as $key=> $ms) {
												if($settings['fppg_SpeedOut'] != $ms) $selected = '';
												else $selected = ' selected';
												echo "<option value='$ms'$selected>$ms</option>\n";
											} ?>
										</select>
										<?php _e('Speed in miliseconds of the ing-out animation (default: 500)', 'fppg'); ?>
									</label><br /><br />
									
									<label for="SpeedChange">
										<select name="fppg[fppg_SpeedChange] id="SpeedChange">
											<?php
											foreach($msArray as $key=> $ms) {
												if($settings['fppg_SpeedChange'] != $ms) $selected = '';
												else $selected = ' selected';
												echo "<option value='$ms'$selected>$ms</option>\n";
											} ?>
										</select>
										<?php _e('Speed in miliseconds of the animation when navigating thorugh gallery items (default: 300)', 'fppg'); ?>
									</label><br /><br />

								</fieldset>
							</td>
						</tr>

						
					</tbody>
				</table>