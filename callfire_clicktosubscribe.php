<?php
/**
 * @package CallFire
 * @version 0.1
 */
/*
Plugin Name: CallFire Click-To-Subscribe
Description: Add a number to a contact list
Author: CallFire Inc.
Version: 0.1
Author URI: http://callfire.com
*/
require_once ABSPATH . 'wp-admin/includes/plugin.php';

register_activation_hook(__FILE__, array('CallFireClickToSubscribe', 'dependencies'));

if(CallFireClickToSubscribe::dependencies() && is_plugin_active(basename(__DIR__).'/callfire_clicktosubscribe.php')) {
    add_action('init', array('CallFireClickToSubscribe', 'init'));
}

class CallFireClickToSubscribe {
    public static $query = array(
        'clicktosubscribe_submit_hidden' => null,
        'clicktosubscribe_shortcode_id' => null,
        'clicktosubscribe_phone_number' => null,
    );
    
    public static $instances = array();

    public static function dependencies() {
        if(!is_plugin_active(basename(__DIR__).'/callfire.php')) {
            deactivate_plugins(__FILE__);
            return false;
        }
        
        return true;
    }
    
    public static function init() {
        if(!CallFire::has_viable_credentials()) {
            return false;
        }
        
        $callback = array(__CLASS__, 'shortcode');
        add_shortcode('click-to-subscribe', $callback);
        add_shortcode('click_to_subscribe', $callback);
        add_shortcode('clicktosubscribe', $callback);
        
        add_action('parse_request', array(__CLASS__, 'process_request'));
        add_filter('query_vars', array(__CLASS__, 'add_query_vars'));
        
        add_action('shutdown', array(__CLASS__, 'execute_request'));
    }
    
    public static function shortcode($attributes, $content = '', $tag = 'click-to-subscribe') {
        $attributes = shortcode_atts(array(
            'list_id' => null
        ), $attributes);
        
        if(is_null($attributes['list_id'])) {
            return '<!-- Missing contact list ID -->';
        }
        
        $shortcode_id = static::shortcode_id($attributes);
        
        static::$instances[$shortcode_id] = $attributes;
        
        $form = require CALLFIRE_PLUGIN_PATH . 'templates/click_to_subscribe.tpl';
        
        return $form;
    }
    
    public static function execute_request() {
        $shortcode_id = static::$query['clicktosubscribe_shortcode_id'];
        if(!(static::$query['clicktosubscribe_submit_hidden'] == 'Y') || !$shortcode_id || !isset(static::$instances[$shortcode_id])) {
            return;
        }
        
        $attributes = static::$instances[$shortcode_id];
        
        $list_id = $attributes['list_id'];
        if(is_null($attributes['list_id'])) {
            return;
        }
        
        $number = static::$query['clicktosubscribe_phone_number'];
        if(!$number) {
            return;
        }
        
        var_dump($number);
        
        static::add_to_list($list_id, $number);
    }
    
    public static function contact_list_map() {
        if(false === ($contact_list_map = get_transient('callfire_contact_list_map'))) {
            $client = CallFire::rest('Contact');

            $request = $client::request('QueryContactLists');
            $response = $client->QueryContactLists($request);
            $result = $client::response($response);

            if(!($result instanceof CallFire\Common\Response\ResourceListInterface)) {
                return array();
            }

            $contact_list_map = array();

            foreach($result as $contact_list) {
                $contact_list_map[$contact_list->getId()] = $contact_list->getName();
            }
            
            set_transient('callfire_contact_list_map', $contact_list_map, 60);
        }

        return $contact_list_map;
    }
    
    public static function contact_list_name($contact_list_id) {
        // TODO: Use GetContactList

        $contact_list_map = static::contact_list_map();
        if(isset($contact_list_map[$contact_list_id])) {
            return $contact_list_map[$contact_list_id];
        }

        return FALSE;
    }
    
    public static function add_to_list($contact_list_id, $number) {
        $client = CallFire::rest('Contact');
        $request = $client::request('AddContactsToList');
        $request->setNumbers(array($number));

        $response = $client->AddContactsToList($contact_list_id, $request);
        $result = $client::response($response);
        if($result instanceof CallFire\Common\Response\ResourceExceptionInterface) {
            return false;
        }

        return true;
    }
    
    public static function process_request($wp) {
        foreach(static::$query as $key => &$value) {
            if(isset($wp->query_vars[$key])) {
                $value = $wp->query_vars[$key];
            }
        }
    }
    
    public static function add_query_vars($vars) {
        foreach(array_keys(static::$query) as $key) {
            if(!isset($vars[$key])) {
                $vars[] = $key;
            }
        }
        
        return $vars;
    }
    
    protected static function shortcode_id($attributes) {
        return md5(serialize($attributes));
    }
}
