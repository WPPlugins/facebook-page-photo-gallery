<?php
/*
Plugin Name: Facebook Page Photo Gallery
Plugin URI: http://zoxion.com/facebook-page-photo-gallery/
Description: This plugins embeds a Facebook Page Album  photos into wordpress content. Just get the Album Id and use [fbphotos id=x] anywhere in your post. If you would like to embed  User and Friends Albums or Facebook Timeline then consider <a href="http://zoxion.com/walleria">Facebook Walleria</a> which you can get on <a href="http://codecanyon.net/item/facebook-walleria-wordpress-plugin/634775?ref=jmukoroyi">Codecanyon</a> 
Author:  Freeman Chari
Version: 2.0.6
Author URI: http://www.zoxion.com
*/
include_once('Fppg.class.php');

$fppg=new Fppg();
// Activation and deactivation hook
register_activation_hook( __FILE__, array($fppg, 'activate')  );
register_deactivation_hook( __FILE__, array($fppg, 'deactivate') );