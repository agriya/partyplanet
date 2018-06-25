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
Router::parseExtensions('rss', 'csv', 'json', 'txt', 'xml');
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/views/pages/home.ctp)...
 */
$controllers = Cache::read('controllers_list', 'long');
if ($controllers != 'null' || $controllers === false) {
    $controllers = App::objects('controller');
    foreach($controllers as &$value) {
        $value = Inflector::underscore($value);
    }
    foreach($controllers as $value) {
        $controllers[] = Inflector::singularize($value);
    }
    array_push($controllers, 'contactus', 'admin', 'sitemap.xml', 'robots.txt');
    $controllers = implode('|', $controllers);
    Cache::write('controllers_list', $controllers);
}
$prefix_parameter_key = Configure::read('site.prefix_parameter_key');
Router::connect('/css/*', array(
    'controller' => 'devs',
    'action' => 'asset_css'
));
Router::connect('/js/*', array(
    'controller' => 'devs',
    'action' => 'asset_js'
));
Router::connect('/files/*', array(
    'controller' => 'images',
    'action' => 'view',
    'size' => 'original'
));
Router::connect('/img/:size/*', array(
    'controller' => 'images',
    'action' => 'view'
) , array(
    'size' => '(?:[a-zA-Z_]*)*'
));
Router::connect('/img/*', array(
    'controller' => 'images',
    'action' => 'view',
    'size' => 'original'
));
Router::connect('/pages/*', array(
    'controller' => 'pages',
    'action' => 'display'
));
Router::connect('/admin', array(
    'controller' => 'users',
    'action' => 'stats',
    'prefix' => 'admin',
    'admin' => true
));
Router::connect('/', array(
    'controller' => 'pages',
    'action' => 'display',
    'home'
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key, array(
    'controller' => 'pages',
    'action' => 'display',
    'home'
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/pages/*', array(
    'controller' => 'pages',
    'action' => 'display'
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));

Router::connect('/:' . $prefix_parameter_key . '/venueowner/register/*', array(
    'controller' => 'venue_owners',
    'action' => 'add',
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/venues', array(
    'controller' => 'venues',
    'action' => 'index'
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));

Router::connect('/:' . $prefix_parameter_key . '/videos/type/:type', array(
    'controller' => 'videos',
    'action' => 'index'
) , array(
	'type' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));

Router::connect('/:' . $prefix_parameter_key . '/venues/sort/:sort/direction/:direction', array(
    'controller' => 'venues',
    'action' => 'index'
) , array(
	'sort' => '[a-zA-Z0-9\-]+',
	'direction' => '[a-zA-Z\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));

Router::connect('/:' . $prefix_parameter_key . '/photo_albums/type/:type', array(
    'controller' => 'photo_albums',
    'action' => 'index'
) , array(
	'type' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));

Router::connect('/:' . $prefix_parameter_key . '/photo_albums/add/venue_id/:venue_id', array(
    'controller' => 'photo_albums',
    'action' => 'add'
) , array(
	'venue_id' => '[0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));

Router::connect('/:' . $prefix_parameter_key . '/events/type/:type/time_str/:time_str', array(
    'controller' => 'events',
    'action' => 'index'
) , array(
	'type' => '[a-zA-Z0-9\-]+',
	'time_str' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));

Router::connect('/:' . $prefix_parameter_key . '/events/search/type/:type', array(
    'controller' => 'events',
    'action' => 'search'
) , array(
    'type' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));

Router::connect('/:' . $prefix_parameter_key . '/articles/category/:category', array(
    'controller' => 'articles',
    'action' => 'index'
) , array(
    'category' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));

Router::connect('/:' . $prefix_parameter_key . '/photo_albums/user/:username', array(
    'controller' => 'photo_albums',
    'action' => 'index'
) , array(
    'username' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));

Router::connect('/:' . $prefix_parameter_key . '/venues/beginning/:venue_beginning', array(
    'controller' => 'venues',
    'action' => 'index'
) , array(
    'venue_beginning' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));

Router::connect('/:' . $prefix_parameter_key . '/articles/sort_by/:sort_by', array(
    'controller' => 'articles',
    'action' => 'index'
) , array(
    'sort_by' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/venues/music/:music', array(
    'controller' => 'venues',
    'action' => 'index'
) , array(
    'music' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));

Router::connect('/:' . $prefix_parameter_key . '/events/q/:name', array(
    'controller' => 'events',
    'action' => 'search_keyword'
) , array(
    'name' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));

Router::connect('/:' . $prefix_parameter_key . '/venues/state/:state', array(
    'controller' => 'venues',
    'action' => 'index'
) , array(
    'state' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/venues/category/:category', array(
    'controller' => 'venues',
    'action' => 'index'
) , array(
    'category' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/venues/filter/:filter', array(
    'controller' => 'venues',
    'action' => 'index'
) , array(
    'filter' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/event/*', array(
    'controller' => 'events',
    'action' => 'view'
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/events/category/:category', array(
    'controller' => 'events',
    'action' => 'index'
) , array(
    'category' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/events/tag/:tag', array(
    'controller' => 'events',
    'action' => 'index'
) , array(
    'tag' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/events/type/:type', array(
    'controller' => 'events',
    'action' => 'index'
) , array(
    'type' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));

Router::connect('/:' . $prefix_parameter_key . '/events/filter/:filter', array(
    'controller' => 'events',
    'action' => 'index'
) , array(
    'filter' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/event_photos/album/:album', array(
    'controller' => 'event_photos',
    'action' => 'index'
) , array(
    'album' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/photos/tag/:tag', array(
    'controller' => 'photos',
    'action' => 'index'
) , array(
    'tag' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/venue_photos/album/:album', array(
    'controller' => 'venue_photos',
    'action' => 'index'
) , array(
    'album' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/videos/category/:category', array(
    'controller' => 'videos',
    'action' => 'index'
) , array(
    'category' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/videos/user/:username', array(
    'controller' => 'videos',
    'action' => 'index'
) , array(
    'username' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/videos/tag/:tag', array(
    'controller' => 'videos',
    'action' => 'index'
) , array(
    'tag' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/forum', array(
    'controller' => 'forum_categories',
    'action' => 'index'
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/forums/category/*', array(
    'controller' => 'forums',
    'action' => 'index'
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/' . 'admin', array(
    'controller' => 'users',
    'action' => 'stats',
    'prefix' => 'admin',
    'admin' => true
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/users/type/:type', array(
    'controller' => 'events',
    'action' => 'index'
) , array(
    'type' => '[a-zA-Z0-9\-]+',
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/cron/main/*', array(
    'controller' => 'crons',
    'action' => 'main'
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/contactus', array(
    'controller' => 'contacts',
    'action' => 'add'
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/r/*', array(
    'controller' => 'users',
    'action' => 'refer'
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/users/twitter/login', array(
    'controller' => 'users',
    'action' => 'login',
    'type' => 'twitter'
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/users/facebook/login', array(
    'controller' => 'users',
    'action' => 'login',
    'type' => 'facebook'
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/users/yahoo/login', array(
    'controller' => 'users',
    'action' => 'login',
    'type' => 'yahoo'
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/users/gmail/login', array(
    'controller' => 'users',
    'action' => 'login',
    'type' => 'gmail'
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/users/openid/login', array(
    'controller' => 'users',
    'action' => 'login',
    'type' => 'openid'
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/' . 'admin/:controller/:action/*', array(
    'admin' => true
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/admin/:controller/*', array(
    'action' => 'index',
    'admin' => true
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/:controller/:action/*', array() , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
Router::connect('/:' . $prefix_parameter_key . '/:controller/*', array(
    'action' => 'index'
) , array(
    $prefix_parameter_key => '(?!' . $controllers . ')[^\/]*'
));
?>