<?php
class CallFireAdmin {
    public static $plugin_options = array(
        'callfire_login' => 'CallFire API Login',
        'callfire_password' => 'CallFire API Password'
    );
    
    public static function init() {
        foreach(static::$plugin_options as $option_name => $friendly_name) {
            add_option($option_name);
        }
        
        add_action('admin_menu', array(__CLASS__, 'plugin_menu'));
    }
    
    public static function plugin_menu() {
        add_options_page('CallFire Options', 'CallFire', 'manage_options', 'callfire-options', array(__CLASS__, 'plugin_options'));
    }
    
    public static function plugin_options() {
        if(!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        $settingsUpdated = false;
        if(array_key_exists("callfire_options_submit_hidden", $_POST) && $_POST["callfire_options_submit_hidden"] == "Y") {
            foreach(self::$plugin_options as $option_name => $friendly_name) {
                if(array_key_exists("option_{$option_name}", $_POST)) {
                    update_option($option_name, $_POST["option_{$option_name}"]);
                }
            }
            
            $settingsUpdated = true;
        }
        
        include(CALLFIRE_PLUGIN_PATH.'templates/plugin_options.tpl');
    }
}
