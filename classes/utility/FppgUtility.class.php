<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utility
 *
 * @author fchari
 */
class  FppgUtility {
    //put your code here
/**
 * Facebook graph endpoint
 */   
const FB_URL='https://graph.facebook.com/';
/**
 * Fetch contents of a remote Url
 * @param string $url
 * @param array $options Optional: array overrides default
 * @return array 
 */
public static function remoteGet($url,$options=array()) { 
    add_filter('http_request_timeout',array('FppgUtility','moreTime'));
    $raw_response = wp_remote_get($url,$options);
    if ( is_wp_error( $raw_response ) || 200 != wp_remote_retrieve_response_code( $raw_response ) )
            return false;

    $response = json_decode( wp_remote_retrieve_body( $raw_response ) );

    return $response;
}

/**
 * Fetch contents of a remote Url
 * @param string $url Url
 * @param array $options Optional: array overrides default
 * 
 * @return array 
 */
public static function remotePost($url,$options=array()) { 
    add_filter('http_request_timeout',30);
    $raw_response = wp_remote_post($url,$options);

    if ( is_wp_error( $raw_response ) || 200 != wp_remote_retrieve_response_code( $raw_response ) )
            return false;

    $response = json_decode( wp_remote_retrieve_body( $raw_response ) );

    return $response;
}

/**
 * Had to use this because some users are still on PHP >=5.2
 * @return int minutes
 */
public static function moreTime(){
    return 30;
}
}

?>
