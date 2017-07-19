<?php
include_once('classes/utility/FppgUtility.class.php');
include_once('classes/core/FppgAlbum.class.php');
/**
 * Core FPPG class
 *
 * @author fchari
 */
class Fppg {

    public $plugin_name='facebook-page-photo-gallery';
    public $version ='2.0.6';
    public $options;
    public $token;
    public $token_expiry;
    public function __construct() { 
       
        add_action('admin_init',array($this,'admin_init'));
        $this->options=  get_option('fppg');
               
        $this->define_constants();
       
        /**
         * Update checks
         */
        add_filter('pre_set_site_transient_update_plugins',array($this,'update'));
        /**
         * Load styles
         */
        add_action('init',array($this,'loadStyles'));
        /**
         * Load scripts
         */
         add_action('init',array($this,'loadJS'));
         add_action('admin_menu', array($this,'fppg_admin_menu')    ); 
        /**
         * set up ajax calls
         */
        $this->ajaxHooks();
         add_shortcode('fbphotos', array($this,'shortcodes'));
    }

public function activate(){ 
    global $wp_version;
    /**
     * Check version
     */
    if (version_compare(PHP_VERSION, '5.2.0', '<')) {
					deactivate_plugins($this->plugin_name); // Deactivate ourself
					wp_die("This plugin requires PHP 5.2 or higher.");
					return;
			}
   /**
    * Check WordPress Version
    */
    if($wp_version<3.2){
       deactivate_plugins($this->plugin_name); // Deactivate ourself
					wp_die("This plugin requires WordPress 3.2 or higher.");
					return; 
    } 
    /**
     * Save default options
     */
    $this->default_options();
}
/**
 * admin init function
 * @return void
 * 
 */
function admin_init() {
    register_setting('fppg-options', 'fppg');

}

/**
 * Uninstall FPPG
 */
public function deactivate(){
if($this->options['fppg_uninstall']){
    delete_option('fppg');
    
    } 
    
}

/**
 * Admin options page
 */
public function fppg_admin_menu() {

include_once FPPG_ABSPATH . '/admin/admin.php';

	$fppgadmin = add_submenu_page('options-general.php', 'Facebook Page Photo Gallery options', 'Facebook Page Photo','activate_plugins' , 'facebook-page-photo-gallery', 'fppg_options_page');
         add_action('admin_print_styles-' . $fppgadmin, array($this,'adminStyles'));
         add_action('admin_print_scripts-' . $fppgadmin, array($this,'adminScripts'));
}


/**
 * 
 */
public function adminScripts(){
    wp_enqueue_script('fppgadminjs',FPPG_URL . 'js/admin.js', array('jquery','jquery-ui-tabs')); // Load specific JS for
}
/**
 * 
 */
public function adminStyles(){
    wp_enqueue_style('fppgadmincss',FPPG_URL . 'css/jquery-ui.css'); // Load jQuery UI Tabs for Admin Page
   }
/**
     * Embed a Facebook album
     * @param type $albumId
     * @param type $limit
     * @param type $template
     * @return type
     */
    public static function embedAlbum($albumId, $limit, $size='large',$options=array(),$template = "") {
       
        $options['limit']=$limit;
        $album = new FppgAlbum($albumId,$options);
        $photos = $album->photos;
        /**
         * 
         */
        $sizes=  array(
            'large'=>array('position'=>5,'classtext'=>'Large'),
            'medium'=>array('position'=>6,'classtext'=>'Medium'),
            'small'=>array('position'=>7,'classtext'=>'Small')            
        );
        $next=isset($album->paging->next)?preg_replace('/access(.*&|.*)/','',parse_url($album->paging->next,PHP_URL_QUERY)):"";
        $previous=isset($album->paging->previous)?preg_replace('/access(.*&|.*)/','',parse_url($album->paging->previous,PHP_URL_QUERY)):"";
        $next=$noscroll?"":$next;
        $args=array_merge($sizes[$size],array('id'=>$albumId,'photos'=>$photos,'next'=>$next,'settings'=>$album->settings));
        $template=$template==""?'single-album':$template;
        $output = self::capture($template, $args);
        return $output;
    }
    
       /**
     * Embed a Facebook album
     * @param type $albumId
     * @param type $limit
     * @param type $template
     * @return type
     */
    public static function embedPhotosAjax($albumId, $args, $options=array(),$paging=25,$size='large',$scroll=false,$template = "") {
        $tokenOpts=get_option('fppg_accessToken');
        $token=$tokenOpts['expirydate']>time()?$tokenOpts['access_token']:"";
        $options['token']=$token;
        $album = new FppgAlbum();
        $album->getAlbumAjax($albumId, $args,$options);
        $photos = $album->photos;
       
        /**
         * 
         */
        $sizes=  array(
            'large'=>array('position'=>5,'classtext'=>'Large'),
            'medium'=>array('position'=>6,'classtext'=>'Medium'),
            'small'=>array('position'=>7,'classtext'=>'Small')            
        );
        $next=isset($album->paging->next)?preg_replace('/access(.*&|.*)/','',parse_url($album->paging->next,PHP_URL_QUERY)):"";
        $previous=isset($album->paging->previous)?preg_replace('/access(.*&|.*)/','',parse_url($album->paging->previous,PHP_URL_QUERY)):"";
        $args=array_merge($sizes[$size],array('photos'=>$photos,'id'=>$albumId,'next'=>$next,'previous'=>$previous,'paging'=>$paging,'settings'=>$album->settings));
        $template=$template==""?'single-album-ajax':$template;
        $output = self::capture($template, $args);
        return array('data'=>$output,'paging'=>array('next'=>$next,'previous'=>$previous));
    }
     
    /**
     * Renders a section of user display code.  The code is first checked for in the current theme display directory
     * before defaulting to the plugin
     * Call the function :	self::render ('template_name', array ('var1' => $var1, 'var2' => $var2));
     *
     * @param string $template_name Name of the template file (without extension)
     * @param string $vars Array of variable name=>value that is available to the display code (optional)
     * @param bool $callback In case we check we didn't find template we tested it one time more (optional)
     * @return void
     * */
    public static function render($template_name, $vars = array(), $callback = false) {
        foreach ($vars AS $key => $val) {
            $$key = $val;
        }

        // hook into the render feature to allow other plugins to include templates
        $custom_template = apply_filters('ffpg_render_template', false, $template_name);

        if (( $custom_template != false ) && file_exists($custom_template)) {
            include ( $custom_template );
        } else if (file_exists(STYLESHEETPATH . "/fppg/$template_name.php")) {
            include (STYLESHEETPATH . "/fppg/$template_name.php");
        } else if (file_exists(FPPG_ABSPATH . "/templates/$template_name.php")) {
            include (FPPG_ABSPATH . "templates/$template_name.php");
            
        } else if ($callback === true) {
            echo "<p>Rendering of template $template_name.php failed</p>";
        } else {
            //test without the "-template" name one time more
            $template_name = array_shift(explode('-', $template_name, 2));
            self::render($template_name, $vars, true);
        }
    }

    /**
     * Captures an section of user display code.
     *
     * @autor John Godley
     * @param string $template_name Name of the template file (without extension)
     * @param string $vars Array of variable name=>value that is available to the display code (optional)
     * @return void
     * */
    public static function capture($template_name, $vars = array()) {
        ob_start();
        self::render($template_name, $vars);
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }
    
   /*Function to hook to an embedding shortcode 
 * @param $album array passed through shortcode
 * 
 */

function shortcodes($album) {
static $count = 0;
    $count++;
    extract(shortcode_atts(array(
                'id' => '',
                'limit' => 20,
                'size'=>'large',
                'noscroll'=>false
                    ), $album));
    
    $code=self::embedAlbum($id, $limit,$size,$noscroll);
    return($code);

} 

 /**
  * Define constants to be used for Fppg
  */
    private function define_constants() {
        /**
         * Fppg folder
         */
        define('FPPG_FOLDER', basename(dirname(__FILE__)));
        /**
         * Absolute path
         */
        define('FPPG_ABSPATH', trailingslashit(str_replace("\\", "/", WP_PLUGIN_DIR . '/' . FPPG_FOLDER)));
        /**
         * Fppg url
         */
        define('FPPG_URL', trailingslashit(plugins_url(FPPG_FOLDER)));
        /**
         * define slug
         */
        define('FPPG_SLUG','facebook-page-photo-gallery');
       
    }
 
    /**
     * Load stylesheets that will be used by Fppg
     * @param mixed $stylesheet The stylesheet to use for the design
     * @param array $opts array with fppg_gallery key
     */
    public function loadStyles(){
    $opts = $this->options; 
      /**
       * Allow plugins to override with an arraywih keys handler and path
       */
        $custom_style=  apply_filters('fppg_custom_style',false,$custom_style);
      
    if (false !== $custom_style && file_exists($custom_style['path'])) {
            wp_deregister_style($custom_style['handler']);
            wp_register_style($custom_style['handler'], $custom_style['path']);
            wp_enqueue_style($custom_style['handler']);
        } elseif (file_exists(STYLESHEETPATH . "/fppg/css/style.css")) {
            wp_enqueue_style('fppg-style', STYLESHEETPATH . "/fppg/css/style.css");
        } else{
            wp_enqueue_style('fppg-style', FPPG_URL . "templates/css/style.css");
        
        }
    if ($opts['fppg_gallery'] == 'Fancybox') {
        wp_deregister_style('fancybox');
        wp_register_style('fancybox', FPPG_URL . 'js/fancybox/jquery.fancybox-1.3.4.css', false, '', 'screen');
        wp_enqueue_style('fancybox');
    } elseif ($opts['fppg_gallery'] == 'PrettyPhoto') {
        wp_deregister_style('prettyphoto');
        wp_register_style('prettyphoto', FPPG_URL . 'js/prettyPhoto/css/prettyPhoto.css', false,'', 'screen');
        wp_enqueue_style('prettyphoto');
    }
       
}
 
/**
 * register main walleribox script
 */
function loadJS() {
   $opts = $this->options;
   
   if (!is_admin()) {
        /**
       * Allow plugins to override with an arraywih keys handler and path
       */
        $custom_scripts=  apply_filters('fppg_custom_script',false,$custom_scripts);
  if (false !== $custom_script && file_exists($custom_script['path'])) {
       wp_deregister_script($custom_script['handler']);
       wp_register_script($custom_script['handler'], $custom_script['path']);
       wp_enqueue_script($custom_script['handler']);
  }
  elseif (file_exists(STYLESHEETPATH . "/fppg/js/ffpg.js")) {
            wp_enqueue_style('ffpg-style', STYLESHEETPATH . "/fppg/js/fppg.js");
  } else{
  
        if ($opts['fppg_gallery'] == 'Fancybox') {
            //deregister any fancybox
            wp_deregister_script('fancybox');
            //register fancybox
            wp_register_script('fancybox', FPPG_URL . 'js/fancybox/jquery.fancybox-1.3.4.pack.js', array('jquery'),'', $loadinfooter);
            //enqueue for use
            wp_enqueue_script('fancybox');
        } elseif ($opts['fppg_gallery'] == 'PrettyPhoto') { 
            //deregister 
            wp_deregister_script('prettyphoto');
            //register 
            wp_register_script('prettyphoto', FPPG_URL . 'js/prettyPhoto/jquery.prettyPhoto.js', array('jquery'), '', $loadinfooter);
            //enqueue for use
            wp_enqueue_script('prettyphoto');
        }
        
        wp_deregister_script('fppg');
        //re-register
        wp_register_script('fppg', FPPG_URL . 'js/fppg.js', array('jquery'), '', $loadinfooter);
        //enqueue ffpg js
        wp_enqueue_script('fppg');
        }
        
        //get array of settings
        $w = $this->options;

        $w['ajaxurl'] = admin_url('admin-ajax.php');
        $w['fppg_url']= FPPG_URL;
      wp_localize_script('fppg', 'fppgsettings', $w);
    }
}



public function ajaxHooks(){

/**
* Ajax get albums
*/
add_action( 'wp_ajax_nopriv_getphotos', array($this,'getPhotosAjax') );
add_action( 'wp_ajax_getphotos', array($this,'getPhotosAjax') );
}

public function getPhotosAjax(){ 
   $id=trim($_POST['id']);
   $args=trim($_POST['args']);
   $options['page']=trim($_POST['page']);
   echo json_encode($this->embedPhotosAjax($id,$args,$options));
   exit();
}

private function default_options(){
    $fppgopts['fppg_showTitle'] = 'on';
    $fppgopts['fppg_titlePosition'] = 'inside';
    $fppgopts['fppg_border'] = '';
    $fppgopts['fppg_cyclic'] = 'on';
    $fppgopts['fppg_borderColor'] = '#BBBBBB';
    $fppgopts['fppg_closeHorPos'] = 'right';
    $fppgopts['fppg_closeVerPos'] = 'top';
    $fppgopts['fppg_paddingColor'] = '#FFFFFF';
    $fppgopts['fppg_padding'] = '10';
    $fppgopts['fppg_overlayShow'] = 'on';
    $fppgopts['fppg_overlayColor'] = '#666666';
    $fppgopts['fppg_overlayOpacity'] = '0.3';
    $fppgopts['fppg_Opacity'] = 'on';
    $fppgopts['fppg_SpeedIn'] = '500';
    $fppgopts['fppg_SpeedOut'] = '500';
    $fppgopts['fppg_SpeedChange'] = '300';
    $fppgopts['fppg_easing'] = '';
    $fppgopts['fppg_easingIn'] = 'swing';
    $fppgopts['fppg_easingOut'] = 'swing';
    $fppgopts['fppg_easingChange'] = 'easeInOutQuart';
    $fppgopts['fppg_imageScale'] = 'on';
    $fppgopts['fppg_enableEscapeButton'] = 'on';
    $fppgopts['fppg_showCloseButton'] = 'on';
    $fppgopts['fppg_centerOnScroll'] = 'on';
    $fppgopts['fppg_hideOnOverlayClick'] = 'on';
    $fppgopts['fppg_hideOnContentClick'] = '';
    $fppgopts['fppg_loadAtFooter'] = 'on';
    $fppgopts['fppg_frameWidth'] = '560';
    $fppgopts['fppg_frameHeight'] = '340';
    $fppgopts['fppg_callbackOnStart'] = '';
    $fppgopts['fppg_callbackOnShow'] = '';
    $fppgopts['fppg_callbackOnClose'] = '';
    $fppgopts['fppg_galleryType'] = 'all';
    $fppgopts['fppg_customExpression'] = '';
    $fppgopts['fppg_nojQuery'] = '';
    $fppgopts['fppg_jQnoConflict'] = 'on';
    $fppgopts['fppg_uninstall'] = '';
    $fppgopts['fppg_appId'] = '';
    $fppgopts['fppg_appSecret'] = '';
    $fppgopts['fppg_accessToken'] = '';
    $fppgopts['fppg_showAdminError'] = true;
    $fppgopts['fppg_sharePic'] = '';
    $fppgopts['fppg_tokenTimeStamp'] = '';
    $fppgopts['fppg_cacheTime'] = 5;

    //which gallery to use                
    $fppgopts['fppg_gallery'] = 'PrettyPhoto';
    add_option('fppg', $fppgopts);  
    }
    
  
}

?>
