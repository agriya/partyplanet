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
class AppController extends Controller
{
    public $components = array(
        'DebugKit.Toolbar',
        'RequestHandler',
        'Session',
        'Security',
        'Auth',
        'XAjax',
        'Cookie'
    );
    public $helpers = array(
        'Html',
        'Session',
        'Javascript',
        'Form',
        'Auth',
        'Time'
    );
    var $cookieTerm = '+52 weeks';
    //    var $view = 'Theme';
    //    var $theme = 'default';
    function beforeRender() 
    {
        $this->set('city_name', $this->_prefixName);
        $this->set('meta_for_layout', Configure::read('meta'));
        $this->set('js_vars_for_layout', (isset($this->js_vars)) ? $this->js_vars : '');
        parent::beforeRender();
    }
    function __construct($request = null) 
    {
        parent::__construct($request);
        //Setting cache related code
        $setting_key_value_pairs = Cache::read('setting_key_value_pairs');
        if (empty($setting_key_value_pairs)) {
            App::import('Model', 'Setting');
            $setting_model_obj = new Setting();
            $setting_key_value_pairs = $setting_model_obj->getKeyValuePairs();
            Cache::write('setting_key_value_pairs', $setting_key_value_pairs);
        }
        Configure::write($setting_key_value_pairs);
        $lang_code = Configure::read('site.language');
        if (!empty($_COOKIE['CakeCookie']['user_language'])) {
            $lang_code = $_COOKIE['CakeCookie']['user_language'];
        }
        Configure::write('lang_code', $lang_code);
        $translations = Cache::read($lang_code . '_translations');
        if (empty($translations) and $translations === false) {
            $this->loadModel('Translation');
            $translations = $this->Translation->find('all', array(
                'conditions' => array(
                    'Language.iso2' => $lang_code
                ) ,
                'fields' => array(
                    'Translation.key',
                    'Translation.lang_text'
                ) ,
                'contain' => array(
                    'Language' => array(
                        'fields' => array(
                            'Language.iso2'
                        )
                    )
                ) ,
                'recursive' => 0
            ));
            Cache::set(array(
                'duration' => '+100 days'
            ));
            Cache::write($lang_code . '_translations', $translations);
        }
        if (!empty($translations)) {
            foreach($translations as $translation) {
                $GLOBALS['_langs'][$translation['Language']['iso2']][$translation['Translation']['key']] = $translation['Translation']['lang_text'];
            }
        }
        $this->js_vars = array();
        $js_trans_array = array(
            'Are you sure you want to ' => __l('Are you sure you want to ') ,
            'Are you sure you want to do this action?' => __l('Are you sure you want to do this action?') ,
            'Are you sure you want to Discard this message?' => __l('Are you sure you want to Discard this message?') ,
            'Send message without a subject?' => __l('Send message without a subject?') ,
            'Please select atleast one record!' => __l('Please select atleast one record!') ,
            'to' => __l('to') ,
            'Daily' => __l('Daily') ,
            'Every' => __l('Every') ,
            'days' => __l('days') ,
            'Weekly on weekdays' => __l('Weekly on weekdays') ,
            'Weekly on Monday' => __l('Weekly on Monday') ,
            'Weekly on Tuesday' => __l('Weekly on Tuesday') ,
            'Sunday' => __l('Sunday') ,
            'Monday' => __l('Monday') ,
            'Tuesday' => __l('Tuesday') ,
            'Wednesday' => __l('Wednesday') ,
            'Thursday' => __l('Thursday') ,
            'Friday' => __l('Friday') ,
            'Saturday' => __l('Saturday') ,
            'Weekly on ' => __l('Weekly on ') ,
            'Monthly ' => __l('Monthly ') ,
            'months' => __l('months') ,
            'on day ' => __l('on day') ,
            'first' => __l('first') ,
            'second' => __l('second') ,
            'third' => __l('third') ,
            'fourth' => __l('fourth') ,
            'fifth' => __l('fifth') ,
            'on the ' => __l('on the') ,
            'Yearly ' => __l('Yearly') ,
            'Year' => __l('Year') ,
            'on' => __l('on') ,
            'until' => __l('until') ,
            'Could not load this tab. We will try to fix this as soon as possible. If this would not be a demo.' => __l('Could not load this tab. We will try to fix this as soon as possible. If this would not be a demo.') ,
        );
        foreach($js_trans_array as $k => $v) {
            $this->js_vars['cfg']['lang'][$k] = $v;
        }
        // affiliate type write cache
        $this->_cacheWriteAffiliateType();
    }
    function beforeFilter() 
    {
		// Coding done to disallow demo user to change the admin settings
        if ($this->request->params['action'] != 'flashupload') {
            $cur_page = $this->request->params['controller'] . '/' . $this->request->params['action'];
            if ($this->Auth->user('id') && !Configure::read('site.is_admin_settings_enabled') && (in_array($this->request->params['action'], Configure::read('site.admin_demo_mode_not_allowed_actions')) || (!empty($this->request->data) && in_array($cur_page, Configure::read('site.admin_demo_mode_update_not_allowed_pages'))))) {
                $this->Session->setFlash(__l('Sorry. We have disabled this action in demo mode') , 'default', null, 'error');
                if (in_array($this->request->params['controller'], array(
                    'settings',
                    'email_templates'
                ))) {
                    unset($this->request->data);
                } else {
                    $this->redirect(array(
                        'controller' => $this->request->params['controller'],
                        'action' => 'index'
                    ));
                }
            }
        }
        $cur_page = $this->request->params['controller'] . '/' . $this->request->params['action'];
        $current_slug = !empty($_COOKIE['CakeCookie']['slug']) ? $_COOKIE['CakeCookie']['slug'] : '';
        $cookie_val = !empty($_COOKIE['CakeCookie']['slug']) ? $_COOKIE['CakeCookie']['slug'] : '';
        $prefix_parameter_key = Configure::read('site.prefix_parameter_key');
        $prefix_parameter_model = Configure::read('site.prefix_parameter_model');
        // check site is under maintenance mode or not. admin can set in settings page and then we will display maintenance message, but admin side will work.
        $maintenance_exception_array = array(
            'devs/asset_js',
            'devs/asset_css',
            'devs/robots',
            'devs/sitemap',
        );
        if (Configure::read('site.maintenance_mode') && $this->Auth->user('user_type_id') != ConstUserTypes::Admin && empty($this->request->params['prefix']) && !in_array($cur_page, $maintenance_exception_array)) {
            throw new MaintenanceModeException(__l('Maintenance Mode'));
        }
        if ((!empty($this->request->params['named'][$prefix_parameter_key]) && $current_slug != $this->request->params['named'][$prefix_parameter_key]) || (empty($current_slug))) {
            $current_slug = !empty($this->request->params['named'][$prefix_parameter_key]) ? $this->request->params['named'][$prefix_parameter_key] : $current_slug;
            App::import('Model', $prefix_parameter_model);
            $modelObj = new $prefix_parameter_model();
            $modelVal = $modelObj->find('first', array(
                'conditions' => array(
                    $prefix_parameter_model . '.slug' => $current_slug,
                ) ,
                'recursive' => -1
            ));
            if (!empty($modelVal)) {
                $this->Cookie->write('slug', $modelVal[$prefix_parameter_model]['slug'], false, $this->cookieTerm);
                $this->_prefixName = $modelVal[$prefix_parameter_model]['name'];
                $this->_prefixSlug = $modelVal[$prefix_parameter_model]['slug'];
                $this->_prefixId = $modelVal[$prefix_parameter_model]['id'];
                $this->set('_prefixName', $this->_prefixName);
                $this->set('_prefixSlug', $this->_prefixSlug);
                $this->set('_prefixId', $this->_prefixId);
                if (empty($cookie_val)) {
                    $this->redirect(Router::url('/', true));
                }
            }

        } else {
            $current_slug = !empty($this->request->params['named'][$prefix_parameter_key]) ? $this->request->params['named'][$prefix_parameter_key] : $current_slug;
            App::import('Model', $prefix_parameter_model);
            $modelObj = new $prefix_parameter_model();
            $modelVal = $modelObj->find('first', array(
                'conditions' => array(
                    $prefix_parameter_model . '.slug' => $current_slug,
                ) ,
                'recursive' => -1
            ));
            if (!empty($modelVal)) {
                $this->_prefixName = $modelVal[$prefix_parameter_model]['name'];
                $this->_prefixSlug = $modelVal[$prefix_parameter_model]['slug'];
                $this->_prefixId = $modelVal[$prefix_parameter_model]['id'];
                $this->set('_prefixName', $this->_prefixName);
                $this->set('_prefixSlug', $this->_prefixSlug);
                $this->set('_prefixId', $this->_prefixId);
            }
        }
        $cookie_slug = $this->Cookie->read('slug');
        if (empty($cookie_slug) && empty($this->request->params['prefix']) && $this->request->params['controller'] != 'images' && $this->request->params['action'] != 'flashupload' && strpos(env('HTTP_USER_AGENT') , 'facebookexternalhit') === false) {
            $prefix_exception_array = array(
                'cities/index',
                'cities/autocomplete',
                'cities/view',
                'users/register',
                'users/login',
                'users/refer',
                'users/activation',
                'users/oauth_callback',
                'pages/view',
                'crons/main',
                'photos/face_friends',
                'photos/face_deletetag',
                'photos/face_diplaytag',
                'photos/face_addtag',
                'devs/asset_css',
                'devs/asset_js',
                'users/show_captcha',
                'users/captcha_play',
				'payment_gateways/paypal_diagnose',
				'payments/processpayment',
				'payments/payment_success',
				'payments/payment_cancel',
            );
            $prefix_cur_page = $this->request->params['controller'] . '/' . $this->request->params['action'];
            if (!in_array($prefix_cur_page, $prefix_exception_array) and strpos(strtolower($_SERVER['HTTP_USER_AGENT']) , 'facebookexternalhit') === false) {
                $this->redirect(array(
                    'controller' => 'cities',
                    'action' => 'index',
                    'admin' => false
                ));
            }
        }
		// Writing site name in cache, required for getting sitename retrieving in cron
        if (!(Cache::read('site_url_for_shell', 'long'))) {
            Cache::write('site_url_for_shell', Router::url('/', true) , 'long');
        }
        //Fix to upload the file through the flash multiple uploader
        if ((isset($_SERVER['HTTP_USER_AGENT']) and ((strtolower($_SERVER['HTTP_USER_AGENT']) == 'shockwave flash') or (strpos(strtolower($_SERVER['HTTP_USER_AGENT']) , 'adobe flash player') !== false))) and isset($this->request->params['pass'][0]) and ($this->action == 'flashupload')) {
            $this->Session->id($this->request->params['pass'][0]);
        }
        if ($this->Auth->user('fb_user_id') || (!$this->Auth->user('id') && Configure::read('facebook.is_enabled_facebook_connect')) || ($this->request->params['controller'] == 'cities' && ($this->request->params['action'] == 'admin_index' || $this->request->params['action'] == 'fb_update')) || $this->request->params['controller'] == 'settings' || $this->request->params['controller'] == 'users') {
            App::import('Vendor', 'facebook/facebook');
            // Prevent the 'Undefined index: facebook_config' notice from being thrown.
            $GLOBALS['facebook_config']['debug'] = NULL;
            // Create a Facebook client API object.
            $this->facebook = new Facebook(array(
                'appId' => Configure::read('facebook.app_id') ,
                'secret' => Configure::read('facebook.fb_secrect_key') ,
                'cookie' => true
            ));
        }
        $timezone_code = Configure::read('site.timezone_offset');
        if (!empty($timezone_code)) {
            date_default_timezone_set($timezone_code);
        }
        if (strpos($this->here, '/view/') !== false) {
            trigger_error('*** dev1framework: Do not view page through /view/; use singular/slug', E_USER_ERROR);
        }
        // check the method is exist or not in the controller
        $methods = array_flip($this->methods);
        if (!isset($methods[strtolower($this->request->params['action']) ])) {
            throw new NotFoundException(__l('Invalid request'));
        }
        // referral link that update cookies
        $this->_affiliate_referral();
        $this->_checkAuth();
        $this->js_vars['cfg']['path_relative'] = Router::url('/');
        $this->js_vars['cfg']['path_absolute'] = Router::url('/', true);
        $this->js_vars['cfg']['date_format'] = 'M d, Y';
        $this->js_vars['cfg']['timezone'] = date('Z') /(60*60);
        parent::beforeFilter();
    }
    function _checkAuth() 
    {
        $this->Auth->fields = array(
            'username' => Configure::read('user.using_to_login') ,
            'password' => 'password'
        );
        $exception_array = Configure::read('site.exception_array');
        $cur_page = $this->request->params['controller'] . '/' . $this->request->params['action'];
        if (!in_array($cur_page, $exception_array) && $this->request->params['action'] != 'flashupload') {
            if (!$this->Auth->user('id')) {
                // check cookie is present and it will auto login to account when session expires
                $cookie_hash = $this->Cookie->read('User.cookie_hash');
                if (!empty($cookie_hash)) {
                    if (is_integer($this->cookieTerm) || is_numeric($this->cookieTerm)) {
                        $expires = time() +intval($this->cookieTerm);
                    } else {
                        $expires = strtotime($this->cookieTerm, time());
                    }
                    App::import('Model', 'User');
                    $user_model_obj = new User();
                    $this->request->data = $user_model_obj->find('first', array(
                        'conditions' => array(
                            'User.cookie_hash =' => md5($cookie_hash) ,
                            'User.cookie_time_modified <= ' => date('Y-m-d h:i:s', $expires) ,
                        ) ,
                        'fields' => array(
                            'User.' . Configure::read('user.using_to_login') ,
                            'User.password'
                        ) ,
                        'recursive' => -1
                    ));
                    // auto login if cookie is present
                    if ($this->Auth->login($this->request->data)) {
                        $user_model_obj->UserLogin->insertUserLogin($this->Auth->user('id'));
                        $this->redirect(Router::url('/', true) . $this->request->url);
                    }
                }
                $this->Session->setFlash(__l('Authorization Required'));
                $is_admin = false;
                if (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin') {
                    $is_admin = true;
                }
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login',
                    'admin' => $is_admin,
                    '?f=' . $this->request->url
                ));
            }
            if (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin' and $this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                $this->redirect(Router::url('/', true));
            }
        } else {
            $this->Auth->allow('*');
        }
        $this->Auth->autoRedirect = false;
        $this->Auth->userScope = array(
            'User.is_active' => 1,
            'User.is_email_confirmed' => 1
        );
        if (isset($this->Auth)) {
            $this->Auth->loginError = __l(sprintf('Sorry, login failed.  Either your %s or password are incorrect or admin deactivated your account.', Configure::read('user.using_to_login')));
        }
        $this->layout = 'default';
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin && (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin')) {
            $this->layout = 'admin';
        }
        $cookie_slug = $this->Cookie->read('slug');
        if (empty($cookie_slug) && !($this->Auth->user('user_type_id') == ConstUserTypes::Admin && (isset($this->request->params['prefix']) and $this->request->params['prefix'] == 'admin')) && strpos(env('HTTP_USER_AGENT') , 'facebookexternalhit') === false) {
            $this->layout = 'home';
        }
        if (Configure::read('site.maintenance_mode') && !$this->Auth->user('user_type_id')) {
            $this->layout = 'maintenance';
        }
    }
    function autocomplete($param_encode = null, $param_hash = null) 
    {
        $modelClass = Inflector::singularize($this->name);
        $conditions = false;
        if (isset($this->{$modelClass}->_schema['is_approved'])) {
            $conditions['is_approved = '] = '1';
        }
        if (isset($this->{$modelClass}->_schema['is_active'])) {
            $conditions['is_active = '] = '1';
        }
        if (isset($this->{$modelClass}->_schema['admin_suspend'])) {
            $conditions['admin_suspend = '] = '0';
        }
        if ($modelClass == 'Venue') {
            $conditions['city_id = '] = $this->_prefixId;
        }
        $this->XAjax->autocomplete($param_encode, $param_hash, $conditions);
    }
    function show_captcha() 
    {
        include_once VENDORS . DS . 'securimage' . DS . 'securimage.php';
        $img = new securimage();
        $img->show(); // alternate use:  $img->show('/path/to/background.jpg');
        $this->autoRender = false;
    }
    function captcha_play() 
    {
        App::import('Vendor', 'securimage/securimage');
        $img = new Securimage();
        $this->disableCache();
        $this->RequestHandler->respondAs('mp3', array(
            'attachment' => 'captcha.mp3'
        ));
        $img->audio_format = 'mp3';
        echo $img->getAudibleCode('mp3');
    }
    function _redirectGET2Named($whitelist_param_names = null) 
    {
        $query_strings = array();
        if (is_array($whitelist_param_names)) {
            foreach($whitelist_param_names as $param_name) {
                if (isset($this->request->query[$param_name])) { // querystring
                    $query_strings[$param_name] = $this->request->query[$param_name];
                }
            }
        } else {
            $query_strings = $this->request->query;
            unset($query_strings['url']); // Can't use ?url=foo
            
        }
        if (!empty($query_strings)) {
            $query_strings = array_merge($this->request->params['named'], $query_strings);
            $this->redirect($query_strings, null, true);
        }
    }
    public function redirect($url, $status = null, $exit = true) 
    {
        parent::redirect(router_url($url, $this->request->params['named']) , $status, $exit);
    }
    public function flash($message, $url, $pause = 1) 
    {
        parent::flash($message, router_url($url, $this->request->params['named']) , $pause);
    }
    // Posting on Facebook
    function postOnFacebook($post_data = array() , $admin = null) 
    {
        if ($admin) {
            $facebook_dest_user_id = Configure::read('facebook.page_id'); // Site Page ID
            $facebook_dest_access_token = Configure::read('facebook.fb_access_token');
        } else {
            $facebook_dest_user_id = $post_data['fb_user_id'];
            $facebook_dest_access_token = $post_data['fb_access_token'];
        }
        App::import('Vendor', 'facebook/facebook');
        $this->facebook = new Facebook(array(
            'appId' => Configure::read('facebook.api_key') ,
            'secret' => Configure::read('facebook.secrect_key') ,
            'cookie' => true
        ));
        try {
            $getPostCheck = $this->facebook->api('/' . $facebook_dest_user_id . '/feed', 'POST', array(
                'access_token' => $facebook_dest_access_token,
                'message' => $post_data['message'],
                'picture' => $post_data['image_url'],
                'icon' => $post_data['image_url'],
                'link' => $post_data['link'],
                'description' => $post_data['description']
            ));
        }
        catch(Exception $e) {
            $this->log('Post on facebook error');
            return 2;
        }
    }
    // post on twitter
    function postOnTwitter($post_data = array() , $admin = null) 
    {
        if ($admin) {
            $twitter_access_token = Configure::read('twitter.site_user_access_token');
            $twitter_access_key = Configure::read('twitter.site_user_access_key');
        } else {
            $twitter_access_token = $post_data['twitter_access_token'];
            $twitter_access_key = $post_data['twitter_access_key'];
        }
        try {
            $xml = $this->OauthConsumer->post('Twitter', $twitter_access_token, $twitter_access_key, 'https://twitter.com/statuses/update.xml', array(
                'status' => $post_data['message'] . ' ' . $post_data['link']
            ));
        } catch(Exception $e) {
            $this->log('Post on twitter error');
            return 2;
        }        
    }
    function admin_update() 
    {
        if (!empty($this->request->data[$this->modelClass])) {
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $ids = array();
            foreach($this->request->data[$this->modelClass] as $id => $is_checked) {
                if ($is_checked['id']) {
                    $ids[] = $id;
                }
            }
            if ($actionid && !empty($ids)) {
                switch ($actionid) {
                    case ConstMoreAction::Inactive:
                        if ($this->request->params['controller'] == "cities") {
                            $this->{$this->modelClass}->updateAll(array(
                                $this->modelClass . '.is_approved' => 0
                            ) , array(
                                $this->modelClass . '.id' => $ids
                            ));
                        }
                         else {
                           $this->{$this->modelClass}->updateAll(array(
                                $this->modelClass . '.is_active' => 0
                            ) , array(
                                $this->modelClass . '.id' => $ids
                            ));
                             if ($this->request->params['controller'] == "venues"){
                              foreach($ids as $key => $venue_id) {
                                    $this->_sendVenueActionMail($venue_id, 'Admin Venue Deactivate');
                                }
                           }
                        }
                        $this->Session->setFlash(__l('Checked records has been inactivated') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Active:
                        if ($this->request->params['controller'] == "cities") {
                            $this->{$this->modelClass}->updateAll(array(
                                $this->modelClass . '.is_approved' => 1
                            ) , array(
                                $this->modelClass . '.id' => $ids
                            ));
                        } else {
                            $this->{$this->modelClass}->updateAll(array(
                                $this->modelClass . '.is_active' => 1
                            ) , array(
                                $this->modelClass . '.id' => $ids
                            ));
                          if ($this->request->params['controller'] == "venues"){
                              foreach($ids as $key => $venue_id) {
                                    $this->_sendVenueActionMail($venue_id, 'Admin Venue Activate');
                                }
                           }
                        }
                        $this->Session->setFlash(__l('Checked records has been activated') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Delete:
                        $this->{$this->modelClass}->deleteAll(array(
                            $this->modelClass . '.id' => $ids
                        ));
                        $this->Session->setFlash(__l('Checked records has been deleted') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Featured:
                        $this->{$this->modelClass}->updateAll(array(
                            $this->modelClass . '.is_feature' => 1
                        ) , array(
                            $this->modelClass . '.id' => $ids
                        ));
                        $this->Session->setFlash(__l('Checked records has been featured') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::NonFeatured:
                        $this->{$this->modelClass}->updateAll(array(
                            $this->modelClass . '.is_feature' => 0
                        ) , array(
                            $this->modelClass . '.id' => $ids
                        ));
                        $this->Session->setFlash(__l('Checked records has been non featured') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Suspend:
                        $this->{$this->modelClass}->updateAll(array(
                            $this->modelClass . '.admin_suspend' => 1
                        ) , array(
                            $this->modelClass . '.id' => $ids
                        ));
                        $this->Session->setFlash(__l('Checked records has been suspended') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Unsuspend:
                        $this->{$this->modelClass}->updateAll(array(
                            $this->modelClass . '.admin_suspend' => 0
                        ) , array(
                            $this->modelClass . '.id' => $ids
                        ));
                        $this->Session->setFlash(__l('Checked records has been unsuspended') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Flagged:
                        $this->{$this->modelClass}->updateAll(array(
                            $this->modelClass . '.is_system_flagged' => 1
                        ) , array(
                            $this->modelClass . '.id' => $ids
                        ));
                        $this->Session->setFlash(__l('Checked records has been flagged') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Unflagged:
                        $this->{$this->modelClass}->updateAll(array(
                            $this->modelClass . '.is_system_flagged' => 0
                        ) , array(
                            $this->modelClass . '.id' => $ids
                        ));
                        $this->Session->setFlash(__l('Checked records has been unflagged') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Cancel:
                        $this->{$this->modelClass}->updateAll(array(
                            $this->modelClass . '.is_cancel' => 1
                        ) , array(
                            $this->modelClass . '.id' => $ids
                        ));
                        if ($this->modelClass == 'Event') {
                            $this->{$this->modelClass}->PhotoAlbum->updateAll(array(
                                'PhotoAlbum.is_active' => 0
                            ) , array(
                                'PhotoAlbum.event_id' => $ids
                            ));
                            $albums = $this->{$this->modelClass}->PhotoAlbum->find('list', array(
                                'conditions' => array(
                                    'PhotoAlbum.event_id' => $event_id
                                ) ,
                                'fields' => array(
                                    'PhotoAlbum.id'
                                )
                            ));
                            $this->{$this->modelClass}->PhotoAlbum->Photo->updateAll(array(
                                'Photo.is_active' => 0
                            ) , array(
                                'Photo.photo_album_id' => $albums
                            ));
                            $this->{$this->modelClass}->Video->updateAll(array(
                                'Video.is_approved' => 0,
                                'Video.is_canceled' => 1
                                
                            ) , array(
                                'Video.foreign_id' => $event_id,
                                'Video.class' => 'Event'
                            ));
                        }
                        $this->Session->setFlash(__l('Checked records has been canceled') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Contacted:
                        $this->{$this->modelClass}->updateAll(array(
                            $this->modelClass . '.is_contacted' => 1
                        ) , array(
                            $this->modelClass . '.id' => $ids
                        ));
                        $this->Session->setFlash(__l('Checked records has been marked as contacted') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::NotContacted:
                        $this->{$this->modelClass}->updateAll(array(
                            $this->modelClass . '.is_contacted' => 0
                        ) , array(
                            $this->modelClass . '.id' => $ids
                        ));
                        $this->Session->setFlash(__l('Checked records has been marked as not contacted') , 'default', null, 'success');
                        break;
                }
            }
        }
        $this->redirect(Router::url('/', true) . $r);
    }
    function admin_update_stats($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->params['named']['flag']) && ($this->request->params['named']['flag'] == 'active')) {
            $this->{$this->modelClass}->updateAll(array(
                $this->modelClass . '.is_system_flagged' => 1
            ) , array(
                $this->modelClass . '.id' => $id
            ));
            $this->Session->setFlash(__l('Record has been flagged') , 'default', null, 'success');
        } elseif (!empty($this->request->params['named']['flag']) && ($this->request->params['named']['flag'] == 'deactivate')) {
            $this->{$this->modelClass}->updateAll(array(
                $this->modelClass . '.is_system_flagged' => 0
            ) , array(
                $this->modelClass . '.id' => $id
            ));
            $this->Session->setFlash(__l('Record has been unflagged') , 'default', null, 'success');
        } elseif (!empty($this->request->params['named']['flag']) && ($this->request->params['named']['flag'] == 'suspend')) {
            // refund amount
            $this->{$this->modelClass}->updateAll(array(
                $this->modelClass . '.admin_suspend' => 1
            ) , array(
                $this->modelClass . '.id' => $id
            ));
            $this->Session->setFlash(__l('Record has been suspended') , 'default', null, 'success');
        } elseif (!empty($this->request->params['named']['flag']) && ($this->request->params['named']['flag'] == 'unsuspend')) {
            $this->{$this->modelClass}->updateAll(array(
                $this->modelClass . '.admin_suspend' => 0
            ) , array(
                $this->modelClass . '.id' => $id
            ));
            $this->Session->setFlash(__l('Record has been unsuspended') , 'default', null, 'success');
        } elseif (!empty($this->request->params['named']['flag']) && ($this->request->params['named']['flag'] == 'delete')) {
            if ($this->{$this->modelClass}->delete($id)) {
                $this->Session->setFlash(__l('Record has been deleted') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('Record has not been Deleted') , 'default', null, 'error');
            }
        }
        $this->redirect(array(
            'action' => 'index',
        ));
    }
    function getImageUrl($model, $attachment, $options, $full_path = false) 
    {
        $default_options = array(
            'dimension' => 'big_thumb',
            'class' => '',
            'alt' => 'alt',
            'title' => 'title',
            'type' => 'jpg'
        );
        $options = array_merge($default_options, $options);
        $image_hash = $options['dimension'] . '/' . $model . '/' . $attachment['id'] . '.' . md5(Configure::read('Security.salt') . $model . $attachment['id'] . $options['type'] . $options['dimension'] . Configure::read('site.name')) . '.' . $options['type'];
        if ($full_path) {
            return Router::url('/', true) . 'img/' . $image_hash;
        } else {
            return '/img/' . $image_hash;
        }
    }
    function _affiliate_referral() 
    {
        if (!empty($this->request->params['named']['r'])) {
            $this->loadModel('User');
            $referrer = array();
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['r'],
                    //   'User.is_affiliate_user' => 1
                    
                ) ,
                'fields' => array(
                    'User.username',
                    'User.id'
                ) ,
                'recursive' => -1
            ));
            if (!empty($user)) {
                // not check for particular url or page, so that set in refer_id in common, future apply for specific url
                $referrer['refer_id'] = $user['User']['id'];
                if (!empty($this->request->params['controller']) && $this->request->params['controller'] == 'events') {
                    if (!empty($this->request->params['named']['category'])) {
                        $referrer['refer_id'] = $user['User']['id'];
                        $referrer['type'] = 'category';
                        $referrer['slug'] = $this->request->params['named']['category'];
                    } else if (!empty($this->request->params['action']) && $this->request->params['action'] == 'view') {
                        $referrer['refer_id'] = $user['User']['id'];
                        $referrer['type'] = 'view';
                        $referrer['slug'] = $this->request->params['pass']['0'];
                    }
                } else if (!empty($this->request->params['controller']) && $this->request->params['controller'] == 'users') {
                    $referrer['refer_id'] = $user['User']['id'];
                    $referrer['type'] = 'user';
                    $referrer['slug'] = '';
                }
                $this->Cookie->delete('referrer');
                $this->Cookie->write('referrer', $referrer, false, sprintf('+%s hours', Configure::read('affiliate.referral_cookie_expire_time')));
                unset($this->request->params['named']['r']);
                $params = '';
                foreach($this->request->params['pass'] as $value) {
                    $params.= $value . '/';
                }
                foreach($this->request->params['named'] as $key => $value) {
                    $params.= $key . ':' . $value . '/';
                }
                $this->redirect(array(
                    'controller' => $this->request->params['controller'],
                    'action' => $this->request->params['action'],
                    $params
                ));
            }
        }
    }
    // affiliate type write in cache file: cake_affiliate_type_affiliate_model
    function _cacheWriteAffiliateType() 
    {
        $affiliate_model = Cache::read('affiliate_model', 'affiliatetype');
        if (empty($affiliate_model) or $affiliate_model === false) {
            $this->loadModel('AffiliateType');
            $affiliateType = $this->AffiliateType->find('list', array(
                'conditions' => array(
                    'AffiliateType.is_active' => 1
                ) ,
                'fields' => array(
                    'AffiliateType.model_name',
                    'AffiliateType.id'
                ) ,
                'recursive' => -1
            ));
            Cache::write('affiliate_model', $affiliateType, 'affiliatetype', 'too_long');
            $affiliate_model = Cache::read('affiliate_model', 'affiliatetype');
        }
    }
}
function whois($ip = null) 
{
    if (!empty($ip)) {
        $this->redirect(Configure::read('site.look_up_url') . $ip);
    }
}
?>