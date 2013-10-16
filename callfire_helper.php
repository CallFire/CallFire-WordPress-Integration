<?php
class CallFire {
    public static function rest($type, $login = null, $password = null) {
        if(!$login) {
            $login = get_option('callfire_login');
        }
        
        if(!$password) {
            $password = get_option('callfire_password');
        }
        
        return CallFire\Api\Client::Rest($type, $login, $password);
    }
    
    public static function soap($type, $login = null, $password = null) {
        if(!$login) {
            $login = get_option('callfire_login');
        }
        
        if(!$password) {
            $password = get_option('callfire_password');
        }
        
        return CallFire\Api\Client::Rest($type, $login, $password);
    }
}
