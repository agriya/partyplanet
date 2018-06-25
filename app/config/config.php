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
/* SVN: $Id: config.php 6740 2009-10-05 10:45:36Z sudhakar_110at09 $ */
/**
 * Custom configurations
 */
if (!defined('DEBUG')) {
    define('DEBUG', 0);
	// permanent cache re1ated settings
    define('PERMANENT_CACHE_CHECK', (!empty($_SERVER['SERVER_ADDR']) && $_SERVER['SERVER_ADDR'] != '127.0.0.1') ? true : false);
    // site default language
    define('PERMANENT_CACHE_DEFAULT_LANGUAGE', 'en');
    // cookie variable name for site language
    define('PERMANENT_CACHE_COOKIE', '');
    // salt used in setcookie
    define('PERMANENT_CACHE_GZIP_SALT', 'e9a556134534545ab47c6c81c14f06c0b8sdfsdf');
    // sub admin is available in site or not
    define('PERMANENT_CACHE_HAVE_SUB_ADMIN', false);
    // Enable support for HTML5 History/State API
    // By enabling this, users will not see full page load
    define('IS_ENABLE_HTML5_HISTORY_API', false);
    // Force hashbang based URL for all browsers
    // When this is disabled, browsers that don't support History API (IE, etc) alone will use hashbang based URL. When enabled, all browsers--including links in Google search results will use hashbang based URL (similar to new Twitter).
    define('IS_ENABLE_HASHBANG_URL', false);
    $_is_hashbang_supported_bot = (!empty($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'Googlebot') !== false);
    define('IS_HASHBANG_SUPPORTED_BOT', $_is_hashbang_supported_bot);
}
$config['debug'] = DEBUG;
// site actions that needs random attack protection...
$config['site']['_hashSecuredActions'] = array(
    'edit',
    'delete',
    'update',
    'refer',
    'cancel',
    'v',
	'print_ticket',
	'pay_now'
);
$config['site']['exception_array'] = array(
    'pages/display',
    'pages/view',
    'images/view',
    'contacts/add',
    'languages/change_language',
    'users/register',
    'users/user_search',
    'users/joinus',
    'users/refer',
    'users/login',
    'users/logout',
    'users/reset',
    'users/forgot_password',
    'users/openid',
    'users/activation',
    'users/resend_activation',
    'users/view',
    'users/admin_login',
    'users/show_captcha',
    'users/captcha_play',
    'users/index',
    'users/alert',
    'users/oauth_callback',
    'user_comments/index',
    'user_friends/myfriends',
    'venues/index',
    'venues/view',
    'venues/search',
    'venues/search_keyword',
    'venues/autocomplete',
    'users/autocomplete',
    'venue_categories/index',
    'venue_categories/view',
    'venue_comments/index',
    'venue_comments/add',
    'venue_comments/view',
    'venue_favorites/index',
    'venue_favorites/view',
    'venue_users/index',
    'venue_users/view',
    'events/index',
    'events/view',
    'events/user_events',
    'events/week_events',
    'events/search',
    'events/home_search',
    'events/search_keyword',
    'event_categories/index',
    'event_categories/view',
    'event_users/index',
    'event_users/view',
    'event_favorites/index',
    'event_favorites/view',
    'event_sponsors/index',
    'event_sponsors/view',
    'event_comments/index',
    'event_comments/add',
    'event_comments/view',
    'event_tags/index',
    'photos/index',
    'photos/view',
    'photos/face_diplaytag',
    'photos/fb_update',
    'photos/face_friends',
    'photos/face_addtag',
    'photos/face_deletetag',
    'photos/random_photo',
    'photo_comments/index',
    'photo_tags/index',
    'photo_albums/index',
    'videos/home',
    'videos/index',
    'videos/v',
    'videos/view',
    'video_categories/index',
    'video_comments/index',
    'video_tags/index',
    'articles/index',
    'articles/view',
    'article_tags/index',
    'article_comments/index',
    'articles/slider',
    'forums/index',
    'forums/view',
    'forum_categories/index',
    'forum_comments/index',
    'party_planners/add',
    'links/index',
    'link_categories/index',
    'cities/index',
    'cities/view',
    'cities/lst',
    'cities/autocomplete',
    'states/lst',
    'venue_owners/add',
    'venue_owners/show_captcha',
    'crons/main',
    'payments/processpayment',
    'users/oauth_facebook',
    'devs/robots',
    'devs/asset_css',
    'devs/asset_js',
    'devs/yadis',
	'guest_list_users/user_list'
);
$config['photo']['file'] = array(
    'allowedMime' => array(
        'image/jpeg',
        'image/gif',
        'image/png'
    ) ,
    'allowedExt' => array(
        'jpg',
        'jpeg',
        'gif',
        'png'
    ) ,
    'allowedSize' => '5',
    'allowedSizeUnits' => 'MB'
);
$config['video']['file'] = array(
    'allowedMime' => array(
        'video/mpeg',
        'video/quicktime',
        'video/flv',
        'application/octet-stream',
        'video/x-ms-wmv'
    ) ,
    'allowedExt' => array(
        'mpeg',
        'wmv',
        'mov',
        'flv'
    ) ,
    'allowedSize' => '50',
    'allowedSizeUnits' => 'MB',
    'allowEmpty' => false
);
$config['avatar']['file'] = array(
    'allowedMime' => array(
        'image/jpeg',
        'image/gif',
        'image/png'
    ) ,
    'allowedExt' => array(
        'jpg',
        'jpeg',
        'gif',
        'png'
    ) ,
    'allowedSize' => '5',
    'allowedSizeUnits' => 'MB',
    'allowEmpty' => true
);
$config['event']['file'] = array(
    'allowedMime' => array(
        'image/jpeg',
        'image/gif',
        'image/png'
    ) ,
    'allowedExt' => array(
        'jpg',
        'jpeg',
        'gif',
        'png'
    ) ,
    'allowedSize' => '5',
    'allowedSizeUnits' => 'MB',
    'allowEmpty' => false
);
$config['eventsponsor']['file'] = array(
    'allowedMime' => array(
        'image/jpeg',
        'image/gif',
        'image/png'
    ) ,
    'allowedExt' => array(
        'jpg',
        'jpeg',
        'gif',
        'png'
    ) ,
    'allowedSize' => '5',
    'allowedSizeUnits' => 'MB',
    'allowEmpty' => false
);
$config['venue']['file'] = array(
    'allowedMime' => array(
        'image/jpeg',
        'image/gif',
        'image/png'
    ) ,
    'allowedExt' => array(
        'jpg',
        'jpeg',
        'gif',
        'png'
    ) ,
    'allowedSize' => '5',
    'allowedSizeUnits' => 'MB',
    'allowEmpty' => false
);
$config['venuecsv']['file'] = array(
    'allowedMime' => array(
        'application/vnd.ms-excel',
        'text/plain',
        'text/csv',
        'application/octet-stream'
    ) ,
    'allowedExt' => array(
        'csv',
        'xls'
    ) ,
    'allowedSize' => '5',
    'allowedSizeUnits' => 'MB',
    'allowEmpty' => false
);
$config['thumb_size']['micro_thumb']['width'] = '18';
$config['thumb_size']['micro_thumb']['height'] = '18';
$config['thumb_size']['small_thumb']['width'] = '25';
$config['thumb_size']['small_thumb']['height'] = '25';
$config['thumb_size']['medium_thumb']['width'] = '30';
$config['thumb_size']['medium_thumb']['height'] = '30';
$config['thumb_size']['normal_thumb']['width'] = '90';
$config['thumb_size']['normal_thumb']['height'] = '55';
$config['thumb_size']['big_thumb']['width'] = '80';
$config['thumb_size']['big_thumb']['height'] = '80';
$config['thumb_size']['small_big_thumb']['width'] = '138';
$config['thumb_size']['small_big_thumb']['height'] = '84';
$config['thumb_size']['medium_big_thumb']['width'] = '200';
$config['thumb_size']['medium_big_thumb']['height'] = '200';
$config['thumb_size']['very_big_thumb']['width'] = '550';
$config['thumb_size']['very_big_thumb']['height'] = '550';
$config['thumb_size']['menu_thumb']['width'] = '160';
$config['thumb_size']['menu_thumb']['height'] = '110';
$config['thumb_size']['venue_view_thumb']['width'] = '990';
$config['thumb_size']['venue_view_thumb']['height'] = '210';
$config['thumb_size']['micro_normal_thumb']['width'] = '47';
$config['thumb_size']['micro_normal_thumb']['height'] = '47';
$config['thumb_size']['micro_medium_thumb']['width'] = '50';
$config['thumb_size']['micro_medium_thumb']['height'] = '50';
$config['thumb_size']['normalhigh_thumb']['width'] = '60';
$config['thumb_size']['normalhigh_thumb']['height'] = '60';
$config['thumb_size']['home_slide_thumb']['width'] = '710';
$config['thumb_size']['home_slide_thumb']['height'] = '300';
$config['thumb_size']['custom_thumb']['width'] = '700';
$config['thumb_size']['custom_thumb']['height'] = '700';
$config['thumb_size']['event_view_thumb']['width'] = '311';
$config['thumb_size']['event_view_thumb']['height'] = '269';
$config['thumb_size']['article_home_thumb']['width'] = '150';
$config['thumb_size']['article_home_thumb']['height'] = '150';
$config['thumb_size']['user_info_thumb']['width'] = '105';
$config['thumb_size']['user_info_thumb']['height'] = '135';
$config['thumb_size']['event_view_thumb']['width'] = '560';
$config['thumb_size']['event_view_thumb']['height'] = '350';
$config['thumb_size']['photo_thumb']['width'] = '588';
$config['thumb_size']['photo_thumb']['height'] = '300';
$config['thumb_size']['photo_slider_thumb']['width'] = '75';
$config['thumb_size']['photo_slider_thumb']['height'] = '75';
$config['thumb_size']['wide_screen_thumb']['width'] = '990';
$config['thumb_size']['wide_screen_thumb']['height'] = '200';
$config['thumb_size']['venu_gallery_thumb']['width'] = '500';
$config['thumb_size']['venu_gallery_thumb']['height'] = '289';
$config['thumb_size']['featured_venue_thumb']['width'] = '230';
$config['thumb_size']['featured_venue_thumb']['height'] = '95';
$config['thumb_size']['featured_event_thumb']['width'] = '120';
$config['thumb_size']['featured_event_thumb']['height'] = '92';
$config['thumb_size']['featured_photo_thumb']['width'] = '125';
$config['thumb_size']['featured_photo_thumb']['height'] = '125';
$config['thumb_size']['sidebar_thumb']['width'] = '80';
$config['thumb_size']['sidebar_thumb']['height'] = '51';
$config['thumb_size']['home_newest_thumb']['width'] = '140';
$config['thumb_size']['home_newest_thumb']['height'] = '102';
$config['thumb_size']['home_featured_thumb']['width'] = '160';
$config['thumb_size']['home_featured_thumb']['height'] = '102';
$config['thumb_size']['home_banner_big_thumb']['width'] = '954';
$config['thumb_size']['home_banner_big_thumb']['height'] = '294';
$config['thumb_size']['home_banner_small_thumb']['width'] = '76';
$config['thumb_size']['home_banner_small_thumb']['height'] = '73';
$config['thumb_size']['view_page_big_thumb']['width'] = '550';
$config['thumb_size']['view_page_big_thumb']['height'] = '366';
$config['thumb_size']['admin_listing_thumb']['width'] = '166';
$config['thumb_size']['admin_listing_thumb']['height'] = '165';
// CDN...
$config['cdn']['images'] = null; // 'http://images.localhost/';
$config['cdn']['css'] = null; // 'http://static.localhost/';
$config['event']['is_cancel_event'] = true;
/*
date_default_timezone_set('Asia/Calcutta');
Configure::write('Config.language', 'spa');
setlocale (LC_TIME, 'es');
*/
$config['site']['is_admin_settings_enabled'] = 1;
$config['Photo']['is_watermark_logo'] = 1;
$config['search']['default_search_circle'] = 5;
$config['WaterMark']['is_handle_aspect'] = 1;
$config['WaterMark']['is_not_allow_resize_beyond_original_size'] = 1;
$config['Video']['is_encoding'] = 'Immediate';
//social_networking settings
$config['social_networking']['post_event_on_user_facebook'] = 1;
$config['social_networking']['post_event_on_user_twitter'] = 1;
$config['event']['post_on_facebook'] = 1;
$config['event']['post_on_twitter'] = 1;
$config['social_networking']['post_venue_on_user_facebook'] = 1;
$config['social_networking']['post_venue_on_user_twitter'] = 1;
$config['venue']['post_on_facebook'] = 1;
$config['venue']['post_on_twitter'] = 1;
$config['social_networking']['post_photo_on_user_facebook'] = 1;
$config['social_networking']['post_photo_on_user_twitter'] = 1;
$config['photo']['post_on_facebook'] = 1;
$config['photo']['post_on_twitter'] = 1;
$config['social_networking']['post_video_on_user_facebook'] = 1;
$config['social_networking']['post_video_on_user_twitter'] = 1;
$config['video']['post_on_facebook'] = 1;
$config['video']['post_on_twitter'] = 1;
$config['site']['payment_gateway_flow_id'] = 'Attendee -> Event Owner -> Site';
$config['site']['payment_gateway_fee_id'] = 'Event Owner';
$config['article']['post_on_facebook'] = 1;
$config['article']['post_on_twitter'] = 1;
// url prefix parameter
$config['site']['prefix_parameter_key'] = 'city';
// model to find prefix parameter value
$config['site']['prefix_parameter_model'] = 'City';


$config['site']['is_admin_settings_enabled'] = true;
if (!empty($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] == 'burrow.dev.agriya.com' && !in_array($_SERVER['REMOTE_ADDR'], array('118.102.143.2', '119.82.115.146', '122.183.135.202', '122.183.136.34', '122.183.136.36'))) {
	$config['site']['is_admin_settings_enabled'] = false;
	$config['site']['admin_demo_mode_update_not_allowed_pages'] = array(
		'pages/admin_edit',
		'settings/admin_edit',
		'email_templates/admin_edit',
	);
	$config['site']['admin_demo_mode_not_allowed_actions'] = array(
		'admin_delete',
		'admin_update',
	);
}
?>