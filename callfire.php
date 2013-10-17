<?php
/**
 * @package CallFire
 * @version 0.1
 */
/*
Plugin Name: CallFire
Description: Integration of the CallFire platform
Author: CallFire Inc.
Version: 0.1
Author URI: http://callfire.com
*/
require_once ABSPATH . 'wp-admin/includes/plugin.php';
define('CALLFIRE_PLUGIN_PATH', plugin_dir_path(__FILE__));
if(!file_exists(__DIR__.'/vendor/CallFire-PHP-SDK/src/CallFire/Api/Client.php')) {
    deactivate_plugins(CALLFIRE_PLUGIN_PATH.'callfire.php');
    return;
}

if(!class_exists('SplClassLoader')) {
    require_once __DIR__.'/vendor/SplClassLoader.php';
}

$callfire_loader = new SplClassLoader('CallFire', __DIR__ . '/vendor/CallFire-PHP-SDK/src');
$callfire_loader->register();

$stdlib_loader = new SplClassLoader('Zend\Stdlib', __DIR__ . '/vendor');
$stdlib_loader->register();

require_once CALLFIRE_PLUGIN_PATH.'callfire_admin.php';
add_action('init', array('CallFireAdmin', 'init'));

require_once CALLFIRE_PLUGIN_PATH.'callfire_helper.php';

require_once CALLFIRE_PLUGIN_PATH.'callfire_clicktosubscribe.php';
