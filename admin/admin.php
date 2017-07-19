<?php

function fppg_options_page() {

	include_once(FPPG_ABSPATH. '/admin/admin-head.php');
       
	?>

	<div class="wrap">

	<div id="icon-plugins" class="icon32"></div><h2><?php _e('Facebook Page Photo Gallery', 'fppg'); ?></h2>

	<br />

	<form method="post" action="options.php" id="options">

		<?php

		wp_nonce_field('update-options');
		settings_fields('fppg-options');

		?>

		<div id="fbfwTabs">
                   
			<ul>
				<li><a id="show-fppgd" href="#fbfw-info"><?php _e('Info', 'fppg'); ?></a></li>
                                <li><a href="#fbfw-gallery"><?php _e('Gallery', 'fppg'); ?></a></li>
				<li><a href="#fbfw-appearance"><?php _e('Appearance', 'fppg'); ?></a></li>
				<li><a href="#fbfw-animations"><?php _e('Animations', 'fppg'); ?></a></li>
				<li><a href="#fbfw-behaviour"><?php _e('Behaviour', 'fppg'); ?></a></li>
                                <li><a href="#fbfw-support" style="color:green;"><?php _e('Support', 'fppg'); ?></a></li>
				<li><a href="#fbfw-uninstall" style="color:red;"><?php _e('Uninstall', 'fppg'); ?></a></li>
			</ul>

			<div id="fbfw-info">
				<?php require_once (FPPG_ABSPATH . '/admin/admin-tab-info.php'); ?>
			</div>
                        <div id="fbfw-gallery">
				<?php require_once (FPPG_ABSPATH . '/admin/admin-tab-gallery.php'); ?>
			</div>
			<div id="fbfw-appearance">
				<?php require_once (FPPG_ABSPATH . '/admin/admin-tab-appearance.php'); ?>
			</div>

			<div id="fbfw-animations">
				<?php require_once (FPPG_ABSPATH . '/admin/admin-tab-animations.php'); ?>
			</div>

			<div id="fbfw-behaviour">
				<?php require_once (FPPG_ABSPATH . '/admin/admin-tab-behaviour.php'); ?>
			</div>
                    
			<div id="fbfw-support">
				<?php require_once (FPPG_ABSPATH . '/admin/admin-tab-support.php'); ?>
			</div>

			<div id="fbfw-uninstall">
				<?php require_once (FPPG_ABSPATH . '/admin/admin-tab-uninstall.php'); ?>
			</div>

		</div>

		<input type="hidden" name="fppg_action" value="update" />

		<p class="submit" style="text-align:center;">
                    <input type="hidden" name="fppg_active_version" class="button-primary" value="<?php echo $settings['fppg_active_version']  ?>" />
			<input type="submit" name="fppg_Submit" class="button-primary" value="<?php _e('Save Changes','fppg'); ?>" />
		</p>

	</form>

	<div id="fppgd" style="border-top:1px dashed #DDDDDD;margin:20px auto 40px;overflow:hidden;padding-top:25px;width:735px">

		<div style="background-color:#FFFFE0;border:1px solid #E6DB55;padding:0 .6em;margin:5px 15px 2px;-moz-border-radius:3px;-khtml-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;height:100px;float:left;text-align:center;width:200px">
			<p style="line-height:1.5em;"><?php _e('If you like this plugin,  buy me a drink!', 'fppg'); ?></p>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="fppg_cmd" value="_donations">
<input type="hidden" name="fppg_business" value="freemanchari@yahoo.com">
<input type="hidden" name="fppg_lc" value="US">
<input type="hidden" name="fppg_no_note" value="0">
<input type="hidden" name="fppg_currency_code" value="USD">
<input type="hidden" name="fppg_bn" value="PP-DonationsBF:btn_donate_SM.gif:NonHostedGuest">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="fppg_submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>


		</div>

		<div style="background-color: greenyellow ;border:1px solid #E6DB55;padding:0 .6em;margin:5px 15px 2px;-moz-border-radius:3px;-khtml-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;height:90px;float:left;margin-left:10px;text-align:center;width:200px">
			<p style="line-height:1.5em;"><?php _e('Find me on', 'fppg'); ?></p>
                        <a href="http://www.facebook.com/pages/Code-by-Freeman/121757457911887"><img src="<?php echo FPPG_URL ?>/images/facebook.jpg"/></a>
		</div>

		<div style="background-color:#9DD1F2;border:1px solid #419ED9;padding:0 .6em;margin:5px 15px 2px;-moz-border-radius:3px;-khtml-border-radius:3px;-webkit-border-radius:3px;border-radius:3px;height:90px;float:left;margin-left:10px;text-align:center;width:200px">
			<p style="line-height:1.5em;"><a href="http://twitter.com/codebyfreeman/"><?php _e('Follow me on Twitter for more WordPress Plugins and Themes', 'fppg'); ?></a></p>
			<img height="16" width="16" border="0" alt="" src="<?php echo FPPG_URL ?>/images/extra_twitter.png" />
		</div>

	</div>

</div>

<?php } ?>
