				<h2><?php _e('Gallery', 'fppg'); ?></h2>

				

			<table class="form-table" style="clear:none;">
					<tbody>

						<tr valign="top">
							<th scope="row"><?php _e('Gallery Type', 'fppg'); ?></th>
							<td>
								<fieldset>
								
									<label for="galleryType">
                                                                            <select name="fppg[fppg_gallery]">
                                                                                <option <?php if($settings['fppg_gallery']=="Fancybox"){echo 'selected=selected';}?> >Fancybox</option>
                                                                                <option <?php if($settings['fppg_gallery']=="PrettyPhoto"){echo 'selected=selected';}?>>PrettyPhoto</option>
                                                                                <option <?php if($settings['fppg_gallery']=="Custom"){echo 'selected=selected';}?>>Custom</option>
                                                                            </select>
									</label><br />

								</fieldset>
			</tr> <tr valign="top">
							<th scope="row"><?php _e('Cache time (<a href="http://zoxion.com">Help</a>)', 'fppg'); ?></th>
							<td>
								<fieldset>

									<label for="CacheTime">
										<select style="width: 200px;" type="text" name="fppg[fppg_cacheTime]" id="CacheTime"  >
                                                                                    <option value="1" <?php if ($settings['fppg_cacheTime']==1) echo "selected=selected";?>>1</option>
                                                                                    <option value="3" <?php if ($settings['fppg_cacheTime']==3) echo "selected=selected";?>>3</option>
                                                                                    <option value="5" <?php if ($settings['fppg_cacheTime']==5) echo "selected=selected";?>>5</option>
                                                                                    <option value="10" <?php if ($settings['fppg_cacheTime']==10) echo "selected=selected";?>>10</option>
                                                                                    <option value="30" <?php if ($settings['fppg_cacheTime']==30) echo "selected=selected";?>>30</option>
                                                                                    
                                                                                </select>
										<?php _e('Minutes to cache your data', 'facebook-fppg'); ?>
									</label><br /><br />
                                                                </fieldset>
                                                        </td>
                                                </tr>

					</tbody>
				</table>