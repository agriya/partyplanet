<?
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
class ChartsController extends AppController
{
    public $name = 'Charts';
    public $lastDays;
    public $lastMonths;
    public $lastYears;
    public $lastWeeks;
    public $selectRanges;
    public $lastDaysStartDate;
    public $lastMonthsStartDate;
    public $lastYearsStartDate;
    public $lastWeeksStartDate;
    public function initChart() 
    {
        //# last days date settings
        $days = 6;
        $this->lastDaysStartDate = date('Y-m-d', strtotime("-$days days"));
        for ($i = $days; $i > 0; $i--) {
            $this->lastDays[] = array(
                'display' => date('D, M d', strtotime("-$i days")) ,
                'conditions' => array(
                    "DATE_FORMAT(#MODEL#.created, '%Y-%m-%d')" => date('Y-m-d', strtotime("-$i days")) ,
                )
            );
        }
        $this->lastDays[] = array(
            'display' => date('D, M d') ,
            'conditions' => array(
                "DATE_FORMAT(#MODEL#.created, '%Y-%m-%d')" => date('Y-m-d')
            )
        );
        //# last weeks date settings
        $timestamp_end = strtotime('last Saturday');
        $weeks = 3;
        $this->lastWeeksStartDate = date('Y-m-d', $timestamp_end-((($weeks*7) -1) *24*3600));
        for ($i = $weeks; $i > 0; $i--) {
            $start = $timestamp_end-((($i*7) -1) *24*3600);
            $end = $start+(6*24*3600);
            $this->lastWeeks[] = array(
                'display' => date('M d', $start) . ' - ' . date('M d', $end) ,
                'conditions' => array(
                    '#MODEL#.created >=' => date('Y-m-d', $start) ,
                    '#MODEL#.created <=' => date('Y-m-d', $end) ,
                )
            );
        }
        $this->lastWeeks[] = array(
            'display' => date('M d', $timestamp_end+24*3600) . ' - ' . date('M d') ,
            'conditions' => array(
                '#MODEL#.created >=' => date('Y-m-d', $timestamp_end+24*3600) ,
                '#MODEL#.created <=' => date('Y-m-d 23:59:59')
            )
        );
        //# last months date settings
        $months = 2;
        $this->lastMonthsStartDate = date('Y-m-01', strtotime("-$i months", strtotime(date("F") . "1")));
        for ($i = $months; $i > 0; $i--) {
            $this->lastMonths[] = array(
                'display' => date('M, Y', strtotime("-$i months", strtotime(date("F") . "1"))) ,
                'conditions' => array(
                    "DATE_FORMAT(#MODEL#.created, '%Y-%m')" => _formatDate('Y-m', date('Y-m-d', strtotime("-$i months")) , true)
                )
            );
        }
        $this->lastMonths[] = array(
            'display' => date('M, Y') ,
            'conditions' => array(
                "DATE_FORMAT(#MODEL#.created, '%Y-%m')" => _formatDate('Y-m', date('Y-m-d') , true)
            )
        );
        //# last years date settings
        $years = 2;
        $this->lastYearsStartDate = date('Y-01-01', strtotime("-$years years"));
        for ($i = $years; $i > 0; $i--) {
            $this->lastYears[] = array(
                'display' => date('Y', strtotime("-$i years")) ,
                'conditions' => array(
                    "DATE_FORMAT(#MODEL#.created, '%Y')" => _formatDate('Y', date('Y-m-d', strtotime("-$i years")) , true)
                )
            );
        }
        $this->lastYears[] = array(
            'display' => date('Y') ,
            'conditions' => array(
                "DATE_FORMAT(#MODEL#.created, '%Y')" => _formatDate('Y', date('Y-m-d') , true)
            )
        );
        $this->selectRanges = array(
            'lastDays' => __l('Last 7 days') ,
            'lastWeeks' => __l('Last 4 weeks') ,
            'lastMonths' => __l('Last 3 months') ,
            'lastYears' => __l('Last 3 years')
        );
    }
    public function admin_chart_users() 
    {
        $this->initChart();
        $this->loadModel('User');
        if (isset($this->request->params['named']['user_type_id'])) {
            $this->request->data['Chart']['user_type_id'] = $this->request->params['named']['user_type_id'];
        }
        if (isset($this->request->params['named']['select_range_id'])) {
            $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
        }
        if (isset($this->request->data['Chart']['select_range_id'])) {
            $select_var = $this->request->data['Chart']['select_range_id'];
        } else {
            $select_var = 'lastDays';
        }
        $user_type_id = ConstUserTypes::User;
        $this->request->data['Chart']['select_range_id'] = $select_var;
        $this->request->data['Chart']['user_type_id'] = $user_type_id;
        $model_datas['Normal'] = array(
            'display' => __l('Normal') ,
            'conditions' => array(
                'User.is_facebook_register' => 0,
                'User.is_twitter_register' => 0,
                'User.is_openid_register' => 0,
                'User.is_gmail_register' => 0,
                'User.is_yahoo_register' => 0,
            )
        );
        $model_datas['Twitter'] = array(
            'display' => __l('Twitter') ,
            'conditions' => array(
                'User.is_twitter_register' => 1,
            ) ,
        );
        if (Configure::read('facebook.is_enabled_facebook_connect')) {
            $model_datas['Facebook'] = array(
                'display' => __l('Facebook') ,
                'conditions' => array(
                    'User.is_facebook_register' => 1,
                )
            );
        }
        if (Configure::read('user.is_enable_openid')) {
            $model_datas['OpenID'] = array(
                'display' => __l('OpenID') ,
                'conditions' => array(
                    'User.is_openid_register' => 1,
                )
            );
        }
        $model_datas['Gmail'] = array(
            'display' => __l('Gmail') ,
            'conditions' => array(
                'User.is_gmail_register' => 1,
            )
        );
        $model_datas['Yahoo'] = array(
            'display' => __l('Yahoo') ,
            'conditions' => array(
                'User.is_yahoo_register' => 1,
            )
        );
        if (Configure::read('affiliate.is_enabled')) {
            $_periods['Affiliate'] = array(
                'display' => __l('Affiliate') ,
                'conditions' => array(
                    'User.is_affiliate_user' => 1,
                )
            );
        }
        $model_datas['All'] = array(
            'display' => __l('All') ,
            'conditions' => array()
        );
        $common_conditions = array(
            'User.user_type_id' => $user_type_id
        );
        $_data = $this->_setLineData($select_var, $model_datas, 'User', 'User', $common_conditions);
        $this->set('chart_data', $_data);
        $this->set('chart_periods', $model_datas);
        $this->set('selectRanges', $this->selectRanges);
        // overall pie chart
        $select_var.= 'StartDate';
        $startDate = $this->$select_var;
        $endDate = date('Y-m-d 23:59:59');
        $total_users = $this->User->find('count', array(
            'conditions' => array(
                'User.user_type_id' => $user_type_id,
                'created >=' => $startDate,
                'created <=' => $endDate
            ) ,
            'recursive' => -1
        ));
        unset($model_datas['All']);
        unset($model_datas['Affiliate']);
        $_pie_data = $chart_pie_relationship_data = $chart_pie_education_data = $chart_pie_employment_data = $chart_pie_income_data = $chart_pie_gender_data = $chart_pie_age_data = array();
        if (!empty($total_users)) {
            foreach($model_datas as $_period) {
                $new_conditions = array();
                $new_conditions = array_merge($_period['conditions'], array(
                    'created >=' => $startDate,
                    'created <=' => $endDate
                ));
                $new_conditions['User.user_type_id'] = $user_type_id;
                $sub_total = $this->User->find('count', array(
                    'conditions' => $new_conditions,
                    'recursive' => -1
                ));
                $_pie_data[$_period['display']] = number_format(($sub_total/$total_users) *100, 2);
            }
            // demographics
            $conditions = array(
                'User.created >=' => $startDate,
                'User.created <=' => $endDate,
                'User.user_type_id' => $user_type_id
            );
            $this->_setDemographics($total_users, $conditions);
        }
        $this->set('chart_pie_data', $_pie_data);
        $is_ajax_load = false;
        if ($this->RequestHandler->isAjax()) {
            $is_ajax_load = true;
        }
        $this->set('is_ajax_load', $is_ajax_load);
    }
    public function admin_chart_user_logins() 
    {
        $this->initChart();
        $this->loadModel('UserLogin');
        if (isset($this->request->params['named']['user_type_id'])) {
            $this->request->data['Chart']['user_type_id'] = $this->request->params['named']['user_type_id'];
        }
        if (isset($this->request->params['named']['select_range_id'])) {
            $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
        }
        if (isset($this->request->data['Chart']['select_range_id'])) {
            $select_var = $this->request->data['Chart']['select_range_id'];
        } else {
            $select_var = 'lastDays';
        }
        $user_type_id = ConstUserTypes::User;
        $this->request->data['Chart']['select_range_id'] = $select_var;
        $this->request->data['Chart']['user_type_id'] = $user_type_id;
        $model_datas['Normal'] = array(
            'display' => __l('Normal') ,
            'conditions' => array(
                'User.is_facebook_register' => 0,
                'User.is_twitter_register' => 0,
                // 'User.is_foursquare_register' => 0,
                'User.is_openid_register' => 0,
                'User.is_gmail_register' => 0,
                'User.is_yahoo_register' => 0,
            )
        );
        $model_datas['Twitter'] = array(
            'display' => __l('Twitter') ,
            'conditions' => array(
                'User.is_twitter_register' => 1,
            ) ,
        );
        if (Configure::read('facebook.is_enabled_facebook_connect')) {
            $model_datas['Facebook'] = array(
                'display' => __l('Facebook') ,
                'conditions' => array(
                    'User.is_facebook_register' => 1,
                )
            );
        }
        if (Configure::read('user.is_enable_openid')) {
            $model_datas['OpenID'] = array(
                'display' => __l('OpenID') ,
                'conditions' => array(
                    'User.is_openid_register' => 1,
                )
            );
        }
        $model_datas['Gmail'] = array(
            'display' => __l('Gmail') ,
            'conditions' => array(
                'User.is_gmail_register' => 1,
            )
        );
        $model_datas['Yahoo'] = array(
            'display' => __l('Yahoo') ,
            'conditions' => array(
                'User.is_yahoo_register' => 1,
            )
        );
        $model_datas['All'] = array(
            'display' => __l('All') ,
            'conditions' => array()
        );
        $common_conditions = array(
            'User.user_type_id' => $user_type_id
        );
        $_data = $this->_setLineData($select_var, $model_datas, 'UserLogin', 'UserLogin', $common_conditions);
        $this->set('chart_data', $_data);
        $this->set('chart_periods', $model_datas);
        $this->set('selectRanges', $this->selectRanges);
        // overall pie chart
        $select_var.= 'StartDate';
        $startDate = $this->$select_var;
        $endDate = date('Y-m-d H:i:s');
        $total_users = $this->UserLogin->find('count', array(
            'conditions' => array(
                'User.user_type_id' => $user_type_id,
                'UserLogin.created >=' => $startDate,
                'UserLogin.created <=' => $endDate,
            ) ,
            'recursive' => 0
        ));
        unset($model_datas['All']);
        $_pie_data = array();
        if (!empty($total_users)) {
            foreach($model_datas as $_period) {
                $new_conditions = array();
                $new_conditions = array_merge($_period['conditions'], array(
                    'UserLogin.created >=' => $startDate,
                    'UserLogin.created <=' => $endDate
                ));
                $new_conditions['User.user_type_id'] = $user_type_id;
                $sub_total = $this->UserLogin->find('count', array(
                    'conditions' => $new_conditions,
                    'recursive' => 0
                ));
                $_pie_data[$_period['display']] = number_format(($sub_total/$total_users) *100, 2);
            }
        }
        $this->set('chart_pie_data', $_pie_data);
        $is_ajax_load = false;
        if ($this->RequestHandler->isAjax()) {
            $is_ajax_load = true;
        }
        $this->set('is_ajax_load', $is_ajax_load);
    }
    protected function _setDemographics($total_users, $conditions = array()) 
    {
        $this->loadModel('User');
        $chart_pie_relationship_data = $chart_pie_education_data = $chart_pie_employment_data = $chart_pie_income_data = $chart_pie_gender_data = $chart_pie_age_data = array();
        if (!empty($total_users)) {
            $not_mentioned = array(
                '0' => __l('Not Mentioned')
            );
            //# genders
            $genders = $this->User->UserProfile->Gender->find('list');
            $genders = array_merge($not_mentioned, $genders);
            foreach($genders As $gen_key => $gender) {
                $new_conditions = $conditions;
                if ($gen_key == 0) {
                    $new_conditions['UserProfile.gender_id'] = NULL;
                } else {
                    $new_conditions['UserProfile.gender_id'] = $gen_key;
                }
                $gender_count = $this->User->UserProfile->find('count', array(
                    'conditions' => $new_conditions,
                    'recursive' => 0
                ));
                $chart_pie_gender_data[$gender] = number_format(($gender_count/$total_users) *100, 2);
            }
            //# age calculation
            $user_ages = array(
                '1' => __l('18 - 34 Yrs') ,
                '2' => __l('35 - 44 Yrs') ,
                '3' => __l('45 - 54 Yrs') ,
                '4' => __l('55+ Yrs')
            );
            $user_ages = array_merge($not_mentioned, $user_ages);
            foreach($user_ages As $age_key => $user_ages) {
                $new_conditions = $conditions;
                if ($age_key == 1) {
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) >= '] = 18;
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) <= '] = 34;
                } elseif ($age_key == 2) {
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) >= '] = 35;
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) <= '] = 44;
                } elseif ($age_key == 3) {
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) >= '] = 45;
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) <= '] = 54;
                } elseif ($age_key == 4) {
                    $new_conditions['Year(Now()) - Year(UserProfile.dob) >= '] = 55;
                } elseif ($age_key == 0) {
                    $new_conditions['UserProfile.dob'] = NULL;
                }
                $age_count = $this->User->UserProfile->find('count', array(
                    'conditions' => $new_conditions,
                    'recursive' => 0
                ));
                $chart_pie_age_data[$user_ages] = number_format(($age_count/$total_users) *100, 2);
            }
        }
        $this->set('chart_pie_education_data', $chart_pie_education_data);
        $this->set('chart_pie_relationship_data', $chart_pie_relationship_data);
        $this->set('chart_pie_employment_data', $chart_pie_employment_data);
        $this->set('chart_pie_income_data', $chart_pie_income_data);
        $this->set('chart_pie_gender_data', $chart_pie_gender_data);
        $this->set('chart_pie_age_data', $chart_pie_age_data);
    }
    //chart for venues
    public function admin_chart_venues() 
    {
        $this->loadModel('Venue');
        $this->loadModel('VenueComment');
        $this->initChart();
        if (isset($this->request->params['named']['select_range_id'])) {
            $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
        }
        if (isset($this->request->data['Chart']['select_range_id'])) {
            $select_var = $this->request->data['Chart']['select_range_id'];
        } else {
            $select_var = 'lastDays';
        }
        $this->request->data['Chart']['select_range_id'] = $select_var;
        //# Venues
        $conditions = array();
        $venue_model_datas['Active'] = array(
            'display' => __l('Active') ,
            'conditions' => array(
                'Venue.is_active' => 1,
                'Venue.admin_suspend' => 0,
            ) ,
        );
        $venue_model_datas['Inactive'] = array(
            'display' => __l('Inactive') ,
            'conditions' => array(
                'Venue.is_active' => 0,
            ) ,
        );
        $venue_model_datas['Featured'] = array(
            'display' => __l('Featured') ,
            'conditions' => array(
                'Venue.is_paid' => 1,
                'Venue.is_featured' => 1,
                'Venue.featured_end_date >= ' => date('Y-m-d') ,
            ) ,
        );
        $venue_model_datas['Reviews'] = array(
            'display' => __l('Reviews') ,
            'model' => 'VenueComment',
            'conditions' => array(
                'VenueComment.created <=' => date('Y-m-d') ,
            ) ,
            'recursive' => -1,
        );
        $common_conditions = array();
        $chart_venues_data = $this->_setLineData($select_var, $venue_model_datas, 'Venue', $common_conditions);
        $this->_setVenueRegulars($select_var);
        $this->set('chart_venues_data', $chart_venues_data);
        $this->set('chart_venues_periods', $venue_model_datas);
        $this->set('selectRanges', $this->selectRanges);
        $is_ajax_load = false;
        if ($this->RequestHandler->isAjax()) {
            $is_ajax_load = true;
        }
        $this->set('is_ajax_load', $is_ajax_load);
    }
    protected function _setVenueRegulars($select_var) 
    {
        $this->loadModel('VenueUser');
        $common_conditions = array();
        $venue_regular_model_datas['Regulars'] = array(
            'display' => __l('Regulars') ,
            'conditions' => array() ,
        );
        $chart_venue_regulars_data = $this->_setLineData($select_var, $venue_regular_model_datas, array(
            'VenueUser'
        ) , 'VenueUser', $common_conditions);
        $this->set('chart_venue_regulars_data', $chart_venue_regulars_data);
    }
    //chart for events
    public function admin_chart_events() 
    {
        $this->loadModel('Event');
        $this->loadModel('EventComment');
        $this->initChart();
        if (isset($this->request->params['named']['select_range_id'])) {
            $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
        }
        if (isset($this->request->data['Chart']['select_range_id'])) {
            $select_var = $this->request->data['Chart']['select_range_id'];
        } else {
            $select_var = 'lastDays';
        }
        $this->request->data['Chart']['select_range_id'] = $select_var;
        //# Events
        $conditions = array();
        $event_model_datas['Active'] = array(
            'display' => __l('Active') ,
            'conditions' => array(
                'Event.is_active' => 1,
                'Event.admin_suspend' => 0,
            ) ,
        );
        $event_model_datas['Inactive'] = array(
            'display' => __l('Inactive') ,
            'conditions' => array(
                'Event.is_active' => 0,
            ) ,
        );
        $event_model_datas['Featured'] = array(
            'display' => __l('Featured') ,
            'conditions' => array(
                'Event.is_feature' => 1,
                'Event.start_date <=' => date('Y-m-d', strtotime(date('Y-m-d', time()) . " +6 days")) ,
            ) ,
        );
        $event_model_datas['Reviews'] = array(
            'display' => __l('Reviews') ,
            'model' => 'EventComment',
            'conditions' => array(
                'EventComment.created <=' => date('Y-m-d') ,
            ) ,
            'recursive' => -1,
        );
        $common_conditions = array();
        $chart_events_data = $this->_setLineData($select_var, $event_model_datas, 'Event', $common_conditions);
        $this->_setEventRegulars($select_var);
        $this->set('chart_events_data', $chart_events_data);
        $this->set('chart_events_periods', $event_model_datas);
        $this->set('selectRanges', $this->selectRanges);
        $is_ajax_load = false;
        if ($this->RequestHandler->isAjax()) {
            $is_ajax_load = true;
        }
        $this->set('is_ajax_load', $is_ajax_load);
    }
    protected function _setEventRegulars($select_var) 
    {
        $this->loadModel('GuestListUser');
        $common_conditions = array('GuestListUsers.is_paid' => 1);
        $event_regular_model_datas['Joined Events'] = array(
            'display' => __l('Joined Events') ,
            'conditions' => array() ,
        );
        $chart_event_regulars_data = $this->_setLineData($select_var, $event_regular_model_datas, array(
            'GuestListUsers'
        ) , 'GuestListUsers', $common_conditions);
        $this->set('chart_joined_events_data', $chart_event_regulars_data);
    }
    //chart for modules like photos, videos,article.forum, partyplanner
    public function admin_chart_modules() 
    {
        $this->loadModel('Photo');
        $this->loadModel('PhotoView');
        $this->loadModel('PhotoComment');
        $this->loadModel('PhotoFlag');
        $this->loadModel('Video');
        $this->loadModel('VideoView');
        $this->loadModel('VideoComment');
        $this->loadModel('VideoFlag');
        $this->loadModel('Article');
        $this->loadModel('ArticleComment');
        $this->loadModel('Forum');
        $this->loadModel('ForumView');
        $this->loadModel('ForumComment');
        $this->loadModel('PartyPlanner');
        $this->loadModel('Contact');
        $this->loadModel('ContactType');
        $this->initChart();
        if (isset($this->request->params['named']['select_range_id'])) {
            $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
        }
        if (isset($this->request->data['Chart']['select_range_id'])) {
            $select_var = $this->request->data['Chart']['select_range_id'];
        } else {
            $select_var = 'lastDays';
        }
        $this->request->data['Chart']['select_range_id'] = $select_var;
        // Photos
        $conditions = array();
        $photo_model_datas['Active'] = array(
            'display' => __l('Active') ,
            'conditions' => array(
                'Photo.is_active' => 1,
            ) ,
        );
        $photo_model_datas['Inactive'] = array(
            'display' => __l('Inactive') ,
            'conditions' => array(
                'Photo.is_active' => 0,
            ) ,
        );
        $photo_model_datas['Views'] = array(
            'display' => __l('Views') ,
            'model' => 'PhotoView',
            'conditions' => array(
                'PhotoView.created <=' => date('Y-m-d') ,
            ) ,
            'recursive' => -1,
        );
        $photo_model_datas['Comments'] = array(
            'display' => __l('Comments') ,
            'model' => 'PhotoComment',
            'conditions' => array(
                'PhotoComment.created <=' => date('Y-m-d') ,
            ) ,
            'recursive' => -1,
        );
        $photo_model_datas['Flags'] = array(
            'display' => __l('Flags') ,
            'model' => 'PhotoFlag',
            'conditions' => array(
                'PhotoFlag.created <=' => date('Y-m-d') ,
            ) ,
            'recursive' => -1,
        );
        $common_conditions = array();
        $chart_photos_data = $this->_setLineData($select_var, $photo_model_datas, 'Photo', $common_conditions);
        $this->set('chart_photos_data', $chart_photos_data);
        $this->set('chart_photos_periods', $photo_model_datas);
        //Videos
        $video_model_datas['Active'] = array(
            'display' => __l('Active') ,
            'conditions' => array(
                'Video.is_approved' => 1,
            ) ,
        );
        $video_model_datas['Inactive'] = array(
            'display' => __l('Inactive') ,
            'conditions' => array(
                'Video.is_approved' => 0,
            ) ,
        );
        $video_model_datas['Views'] = array(
            'display' => __l('Views') ,
            'model' => 'VideoView',
            'conditions' => array(
                'VideoView.created <=' => date('Y-m-d') ,
            ) ,
            'recursive' => -1,
        );
        $video_model_datas['Comments'] = array(
            'display' => __l('Comments') ,
            'model' => 'VideoComment',
            'conditions' => array(
                'VideoComment.created <=' => date('Y-m-d') ,
            ) ,
            'recursive' => -1,
        );
        $video_model_datas['Flags'] = array(
            'display' => __l('Flags') ,
            'model' => 'VideoFlag',
            'conditions' => array(
                'VideoFlag.created <=' => date('Y-m-d') ,
            ) ,
            'recursive' => -1,
        );
        $chart_videos_data = $this->_setLineData($select_var, $video_model_datas, 'Video', $common_conditions);
        $this->set('chart_videos_data', $chart_videos_data);
        $this->set('chart_video_periods', $video_model_datas);
        // Articles
        $article_model_datas['Active'] = array(
            'display' => __l('Active') ,
            'conditions' => array(
                'Article.is_active' => 1,
            ) ,
        );
        $article_model_datas['Inactive'] = array(
            'display' => __l('Inactive') ,
            'conditions' => array(
                'Article.is_active' => 0,
            ) ,
        );
        $article_model_datas['Comments'] = array(
            'display' => __l('Comments') ,
            'model' => 'ArticleComment',
            'conditions' => array(
                'ArticleComment.created <=' => date('Y-m-d') ,
            ) ,
            'recursive' => -1,
        );
        $chart_articles_data = $this->_setLineData($select_var, $article_model_datas, 'Article', $common_conditions);
        $this->set('chart_articles_data', $chart_articles_data);
        $this->set('chart_article_periods', $article_model_datas);
        // Forums
        $forum_model_datas['Active'] = array(
            'display' => __l('Active') ,
            'conditions' => array(
                'Forum.is_active' => 1,
            ) ,
        );
        $forum_model_datas['Inactive'] = array(
            'display' => __l('Inactive') ,
            'conditions' => array(
                'Forum.is_active' => 0,
            ) ,
        );
        $forum_model_datas['Views'] = array(
            'display' => __l('Views') ,
            'model' => 'ForumView',
            'conditions' => array(
                'ForumView.created <=' => date('Y-m-d') ,
            ) ,
            'recursive' => -1,
        );
        $forum_model_datas['Comments'] = array(
            'display' => __l('Comments') ,
            'model' => 'ForumComment',
            'conditions' => array(
                'ForumComment.created <=' => date('Y-m-d') ,
            ) ,
            'recursive' => -1,
        );
        $chart_forums_data = $this->_setLineData($select_var, $forum_model_datas, 'Forum', $common_conditions);
        $this->set('chart_forums_data', $chart_forums_data);
        $this->set('chart_forum_periods', $forum_model_datas);
        // Party Planner
        $party_planner_model_datas['Count'] = array(
            'display' => __l('Count') ,
            'conditions' => array() ,
        );
        $party_planner_model_datas['Contacted'] = array(
            'display' => __l('Contacted') ,
            'conditions' => array(
                'PartyPlanner.is_contacted' => 1,
            ) ,
            'recursive' => -1,
        );
        $chart_party_planner_data = $this->_setLineData($select_var, $party_planner_model_datas, 'PartyPlanner', $common_conditions);
        $this->set('chart_party_planner_data', $chart_party_planner_data);
        $this->set('party_planner_periods', $party_planner_model_datas);
        // Contacts
        $contact_types = $this->ContactType->find('all');
        foreach($contact_types as $contact_type) {
            $contact_type_model_datas[$contact_type['ContactType']['name']] = array(
                'display' => $contact_type['ContactType']['name'],
                'conditions' => array(
                    'Contact.contact_type_id' => $contact_type['ContactType']['id']
                ) ,
            );
        }
        $chart_contacts_data = $this->_setLineData($select_var, $contact_type_model_datas, 'Contact', $common_conditions);
        $this->set('chart_contacts_data', $chart_contacts_data);
        $this->set('contact_type_periods', $contact_type_model_datas);
        $this->set('selectRanges', $this->selectRanges);
        $is_ajax_load = false;
        if ($this->RequestHandler->isAjax()) {
            $is_ajax_load = true;
        }
        $this->set('is_ajax_load', $is_ajax_load);
    }
    protected function _setLineData($select_var, $model_datas, $models, $model = '', $common_conditions = array(), $type = '') 
    {
        if (is_array($models)) {
            foreach($models as $m) {
                $this->loadModel($m);
            }
        } else {
            $this->loadModel($models);
            $model = $models;
        }
        $_data = array();
        foreach($this->$select_var as $val) {
            foreach($model_datas as $model_data) {
                $new_conditions = array();
                foreach($val['conditions'] as $key => $v) {
                    $key = str_replace('#MODEL#', $model, $key);
                    $new_conditions[$key] = $v;
                }
                $new_conditions = array_merge($new_conditions, $model_data['conditions']);
                $new_conditions = array_merge($new_conditions, $common_conditions);
                if (isset($model_data['model'])) {
                    $modelClass = $model_data['model'];
                } else {
                    $modelClass = $model;
                }
                if($type == 'sum') {                    
                    $sum = $this->{$modelClass}->find('first', array(
                        'conditions' => $new_conditions,
						'fields' => $model_data['fields'],
                        'recursive' => -1
                    ));
					$_data[$val['display']][] = ($sum[0]['amount']) ? $sum[0]['amount'] : 0;
                } else {
                    $_data[$val['display']][] = $this->{$modelClass}->find('count', array(
                        'conditions' => $new_conditions,
                        'recursive' => 0
                    ));
                }
            }
        }
        return $_data;
    }
    public function admin_chart_transactions() {
        $this->initChart();
        $this->loadModel('Transaction');        
        if (isset($this->request->params['named']['select_range_id'])) {
            $this->request->data['Chart']['select_range_id'] = $this->request->params['named']['select_range_id'];
        }
        if (isset($this->request->data['Chart']['select_range_id'])) {
            $select_var = $this->request->data['Chart']['select_range_id'];
        } else {
            $select_var = 'lastDays';
        }
        
        $this->request->data['Chart']['select_range_id'] = $select_var;
                
        $model_datas['Amount'] = array(
            'display' => __l('Site Commision') ,
            'conditions' => array(),
            'fields' => array(
                'SUM(Transaction.site_fee) as amount'
			)
            
        );
        $model_datas['Site'] = array(
            'display' => __l('Turnover') ,
            'conditions' => array(),
            'fields' => array(
                'SUM(Transaction.amount) as amount'
			)
            
        );
        
        $common_conditions = array();
        $_data = $this->_setLineData($select_var, $model_datas, 'Transaction', 'Transaction', $common_conditions,'sum');
        $this->set('chart_data', $_data);
        $this->set('chart_periods', $model_datas);
        $this->set('selectRanges', $this->selectRanges);
        unset($model_datas['Amount']);
        unset($model_datas['Site']);
		// Venue owner request
        $venue_owner_model_datas['Count'] = array(
            'display' => __l('Request Received') ,
            'conditions' => array() ,
        );
        $venue_owner_request_data = $this->_setLineData($select_var, $venue_owner_model_datas, 'VenueOwner', $common_conditions);
        $this->set('venue_owner_request_data', $venue_owner_request_data);
        $this->set('venue_owner_request_periods', $venue_owner_model_datas);
        $is_ajax_load = false;
        if ($this->RequestHandler->isAjax()) {
            $is_ajax_load = true;
        }
        $this->set('is_ajax_load', $is_ajax_load);
    }
    public function admin_chart_stats() 
    {
    }
}
