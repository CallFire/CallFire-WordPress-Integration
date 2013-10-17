<?php
class CallFire {
    public static function rest($type, $login = null, $password = null) {
        if(!$login) {
            $login = get_option('callfire_login');
        }
        
        if(!$password) {
            $password = get_option('callfire_password');
        }
        
        return CallFire\Api\Client::Rest($login, $password, $type);
    }
    
    public static function soap($type, $login = null, $password = null) {
        if(!$login) {
            $login = get_option('callfire_login');
        }
        
        if(!$password) {
            $password = get_option('callfire_password');
        }
        
        return CallFire\Api\Client::Soap($login, $password, $type);
    }
    
    public static function has_viable_credentials() {
        $login = get_option('callfire_login');
        $password = get_option('callfire_password');
        
        return (strlen($login) > 0) && (strlen($password) > 0);
    }
}
