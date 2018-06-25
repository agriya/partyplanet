<?php
/**
 * Party Planet
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    partyplanet
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
/**
 *
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php is loaded
 * This is an application wide file to load any function that is not used within a class define.
 * You can also use this to include or require any files in your application.
 *
 */
/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * $modelPaths = array('full path to models', 'second full path to models', 'etc...');
 * $viewPaths = array('this path to views', 'second full path to views', 'etc...');
 * $controllerPaths = array('this path to controllers', 'second full path to controllers', 'etc...');
 *
 */
App::import('Core', 'config/PhpReader');
Configure::config('default', new PhpReader());
Configure::load('config');
require 'constants.php';
$user_preferred = (!empty($_COOKIE['CakeCookie']['slug'])) ? $_COOKIE['CakeCookie']['slug'] : '';
if (!isset($_GET['url'])) {
    if (!empty($user_preferred)) {
        $_GET['url'] = $user_preferred;
    }
} else {

    $controllers = Cache::read('controllers_list', 'default');
    $controller_arr = explode('|', $controllers);
    // hardcoded for view pages
    array_push($controller_arr, 'message', 'page', 'user', 'event', 'party_planner', 'venue', 'article', 'photo', 'forum', 'contactus', 'admin', 'sitemap.xml', 'robots.txt', 'r');
    $url_arr = explode('/', $_GET['url']);
    if (in_array($url_arr[0], $controller_arr)) {
        // quick fix. need to discuss.
        if (preg_match('/' . Configure::read('site.prefix_parameter_key') . ':([^\/]*)(\/)*/', $_GET['url'], $matches)) {
            $current_tmp = $matches[1];
        }
        $tmp_url = $_GET['url'];
        unset($_GET['url']);
        if (!empty($current_tmp)) {
            $_GET['url'] = $current_tmp . '/' . $tmp_url;
        } else if (!empty($user_preferred)) {
            $_GET['url'] = $user_preferred . '/' . $tmp_url;
        }
    }
}