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
App::import('Core', 'Helper');

class AppHelper extends Helper
{
    function getUserAvatarLink($user_id, $type = '') 
    {
        App::import('Model', 'User');
        $modelObj = new User();
        $user = $modelObj->find('first', array(
            'conditions' => array(
                'User.id' => $user_id,
            ) ,
            'contain' => array(
                'UserAvatar'
            ) ,
            'fields' => array(
                'User.fb_user_id',
                'User.username',
                'User.id',
                'User.twitter_avatar_url'
            ) ,
            'recursive' => 2
        ));
		if (!empty($user['User']['UserAvatar'])) {
			return $this->link($this->showImage('UserAvatar', $attach, array(
				'dimension' => $type,
				'alt' => sprintf(__l('[Image: %s]') , $this->cText($user['User']['username'], false)) ,
				'title' => $this->cText($user['User']['username'], false)
			) , null, null, false) , array(
				'controller' => 'users',
				'action' => 'view',
				$user['User']['username']
			) , array(
				'escape' => false
			));
		} else if (!empty($user['User']['twitter_avatar_url'])) {
                return $this->image($user['User']['twitter_avatar_url'], array(
                    'title' => $this->cText($user['User']['username'], false) ,
                ));
        } elseif (!empty($user['User']['fb_user_id'])) {
            $width = Configure::read('thumb_size.' . $type . '.width');
            $height = Configure::read('thumb_size.' . $type . '.height');
            $image = $this->getFacebookAvatar($user['User']['fb_user_id'], $height, $width);
            return $this->link($image, array(
                'controller' => 'users',
                'action' => 'view',
                $user['User']['username']
            ) , array(
                'escape' => false
            ));
        } else {            
                return $this->showImage('UserAvatar', $user['UserAvatar'], array(
                    'dimension' => $type,
                    'alt' => sprintf(__l('[Image: %s]') , $this->cText($user['User']['username'], false)) ,
                    'title' => $this->cText($user['User']['username'], false)
                ) , null, null, false);
            
        }
    }
    function getFacebookAvatar($user, $height = 35, $width = 35) 
    {
        return $this->image('http://graph.facebook.com/' . $user['fb_user_id'] . '/picture', array(
            'title' => $this->cText($user['username'], false) ,
            'height' => $height,
            'width' => $width
        ));
    }
    function getUserAvatar($user_details, $dimension = 'medium_thumb', $is_link = true) 
    {
        $user_image = '';
		App::import('Model', 'User');
        $modelObj = new User();
		$user = $modelObj->find('first', array(
            'conditions' => array(
                'User.id' => $user_details['id'],
            ) ,
            'contain' => array(
                'UserAvatar'
            ) ,
            'fields' => array(
                'User.fb_user_id',
                'User.username',
                'User.id',
                'User.twitter_avatar_url'
            ) ,
            'recursive' => 2
        ));
		if(!empty($user['UserAvatar'])) {
			//get user image
            $user_image = $this->showImage('UserAvatar', $user['UserAvatar'], array(
                'dimension' => $dimension,
                'alt' => sprintf('[Image: %s]', $user_details['username']) ,
                'title' => $user_details['username']
            ));
		} else if (isset($user_details['fb_user_id']) && !empty($user_details['fb_user_id']) && empty($user['UserAvatar']['id'])) {
            $width = Configure::read('thumb_size.' . $dimension . '.width');
            $height = Configure::read('thumb_size.' . $dimension . '.height');
            $user_image = $this->getFacebookAvatar($user_details, $height, $width);
        } elseif (isset($user_details['twitter_avatar_url']) && !empty($user_details['twitter_avatar_url']) && empty($user_details['UserAvatar']['id'])) {
            $width = Configure::read('thumb_size.' . $dimension . '.width');
            $height = Configure::read('thumb_size.' . $dimension . '.height');
            $user_image = $this->image($user_details['twitter_avatar_url'], array(
                'title' => $this->cText($user_details['username'], false) ,
                'height' => $height,
                'width' => $width
            ));
        } else {
             $user_image = $this->showImage('UserAvatar', array(), array(
                'dimension' => $dimension,
                'alt' => sprintf('[Image: %s]', $user_details['username']) ,
                'title' => $user_details['username']
            ));
        }
        //return image to user
        return (!$is_link) ? $user_image : $this->link($user_image, array(
            'controller' => 'users',
            'action' => 'view',
            $user_details['username'],
            'admin' => false
        ) , array(
            'title' => $this->cText($user_details['username'], false) ,
            'escape' => false
        ));
    }
    function checkForVideoPrivacy($type = null, $row_field_value = null, $logged_in_user = null, $username = null) 
    {
        $is_show = true;
        if ($row_field_value == ConstPrivacySetting::Users and !$logged_in_user) {
            $is_show = false;
        } else if ($row_field_value == ConstPrivacySetting::Nobody) {
            $is_show = false;
        } else if ($row_field_value == ConstPrivacySetting::Friends) {
            // To write user friends lists in config
            App::import('Model', 'UserFriend');
            $this->UserFriend = new UserFriend();
            $is_show = $this->UserFriend->checkIsFriend($logged_in_user, $username);
        }
        return $is_show;
    }
    // @to do:  Need to move to messages controller
    function findLabelName($message_id, $user_id) 
    {
        App::import('Model', 'LabelsMessage');
        $modelObjone = new LabelsMessage();
        $labels = $modelObjone->find('all', array(
            'conditions' => array(
                'LabelsMessage.message_id = ' => $message_id,
            ) ,
            'fields' => array(
                'LabelsMessage.label_id'
            ) ,
            'recursive' => -1
        ));
        App::import('Model', 'LabelsUser');
        $modelObjtwo = new LabelsUser();
        $label_name = '';
        foreach($labels as $label) {
            $labels_message = $modelObjtwo->find('count', array(
                'conditions' => array(
                    'LabelsUser.label_id = ' => $label['LabelsMessage']['label_id'],
                    'LabelsUser.user_id = ' => $user_id,
                ) ,
                'recursive' => -1
            ));
            if ($labels_message > 0) {
                App::import('Model', 'Label');
                $modelObject = new Label();
                $labels_name = $modelObject->find('first', array(
                    'conditions' => array(
                        'Label.id = ' => $label['LabelsMessage']['label_id'],
                    ) ,
                    'fields' => array(
                        'Label.name'
                    ) ,
                    'recursive' => -1
                ));
                if ($label_name == '') $label_name = $labels_name['Label']['name'];
                else $label_name.= ' - ' . $labels_name['Label']['name'];
            }
        }
        return $label_name;
    }
    function getUserVenue($venue_id, $user_id) 
    {
        App::import('Model', 'VenueUser');
        $this->VenueUser = new VenueUser();
        $joined = $this->VenueUser->find('first', array(
            'conditions' => array(
                'VenueUser.venue_id' => $venue_id,
                'VenueUser.user_id' => $user_id
            ) ,
            'fields' => array(
                'VenueUser.id'
            ) ,
            'recursive' => -1,
        ));
        return $joined;
    }
    function getEventUserType($event_slug) 
    {
        App::import('Model', 'Event');
        $this->Event = new Event();
        $event = $this->Event->find('first', array(
            'conditions' => array(
                'Event.slug' => $event_slug,
            ) ,
            'fields' => array(
                'Event.user_id',
                'User.user_type_id'
            ) ,
            'recursive' => 0,
        ));
        return $event['User']['user_type_id'];
    }
    function userDob($dob, $view) 
    {
        if (!$view) {
            $dob = date('M d, Y', strtotime($dob));
        } else {
            $dob = date('M d', strtotime($dob));
        }
        return $dob;
    }
    function userAge($birthday) 
    {
        if ($birthday != '0000-00-00') {
            list($year, $month, $day) = explode('-', $birthday);
            $year_diff = date('Y') -$year;
            $month_diff = date('m') -$month;
            $day_diff = date('d') -$day;
            if ($day_diff < 0 || $month_diff < 0) {
                $year_diff--;
            }
        } else {
            $year_diff = '--';
        }
        return $year_diff;
    }
    public function url($url = null, $full = false) 
    {
        return parent::url(router_url($url, $this->params['named']) , $full);
    }
    function getFacebookLikeCode() 
    {
        $fbFindReplace = array(
            '##fb_api_key##' => Configure::read('facebook.fb_api_key') ,
            '##app_id##' => Configure::read('facebook.app_id') ,
        );
        $fb_code = strtr(Configure::read('facebook.like_iframe_code') , $fbFindReplace);
        return $fb_code;
    }
    function getFacebookBuzzCode() 
    {
        $fbBuzzFindReplace = array(
            '##site_url##' => Router::url('/', true) ,
        );
        $fb_buzz_code = strtr(Configure::read('facebook.buzz_iframe_code') , $fbBuzzFindReplace);
        return $fb_buzz_code;
    }
    function getLanguage() 
    {
        App::import('Model', 'Translation');
        $modelObj = new Translation();
        $languages = $modelObj->find('all', array(
            'fields' => array(
                'DISTINCT(Translation.language_id)',
                'Language.name',
                'Language.iso2'
            )
        ));
        $languageList = array();
        if (!empty($languages)) {
            foreach($languages as $language) {
                $languageList[$language['Language']['iso2']] = $language['Language']['name'];
            }
        }
        return $languageList;
    }
    function getUserUnReadMessages($user_id = null) 
    {
        App::import('Model', 'Message');
        $this->Message = new Message();
        $unread_count = $this->Message->find('count', array(
            'conditions' => array(
                'Message.is_read' => '0',
                'Message.user_id' => $user_id,
                'Message.is_sender' => '0',
                'MessageContent.admin_suspend ' => 0,
                'Message.message_folder_id' => ConstMessageFolder::Inbox,
            ) ,
            'recursive' => 1
        ));
        return $unread_count;
    }
    function getImageUrl($model, $attachment, $options) 
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
        return '/img/' . $image_hash;
    }
    function getUserName($user_id = null) 
    {
        App::import('Model', 'User');
        $this->User = new User();
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id,
            ) ,
            'contain' => array(
                'UserProfile' => array(
                    'fields' => array(
                        'UserProfile.first_name',
                        'UserProfile.last_name',
                    )
                )
            ) ,
            'fields' => array(
                'User.id',
                'User.username'
            ) ,
            'recursive' => 2
        ));
        if (!empty($user['UserProfile']['first_name']) || !empty($user['UserProfile']['last_name'])) {
            $display_name = $user['UserProfile']['first_name'] . ' ' . $user['UserProfile']['last_name'];
        } else {
            $display_name = $user['User']['username'];
        }
        return $display_name;
    }
    function pendingAlbumCount($type = null) 
    {
        App::import('Model', 'PhotoAlbum');
        $this->PhotoAlbum = new PhotoAlbum();
        $conditions = array();
        $conditions['PhotoAlbum.is_active'] = 0;
        if ($type == 'event') {
            $conditions['PhotoAlbum.event_id !='] = 0;
        } else if ($type == 'venue') {
            $conditions['PhotoAlbum.venue_id !='] = 0;
        } else if ($type == 'user') {
            $conditions['PhotoAlbum.venue_id ='] = 0;
            $conditions['PhotoAlbum.event_id ='] = 0;
        }
        $photoalbumCount = $this->PhotoAlbum->find('count', array(
            'conditions' => $conditions
        ));
        return $photoalbumCount;
    }
    function getAffiliateCount($user_id = null) 
    {
        App::import('Model', 'Affiliate');
        $this->Affiliate = new Affiliate();
        $affiliate_count = $this->Affiliate->find('count', array(
            'conditions' => array(
                'Affiliate.affliate_user_id' => $user_id
            ) ,
        ));
        return $affiliate_count;
    }
    public function transactionDescription($transaction)
    {    
        if($transaction['Transaction']['class'] == "GuestListUser") {            
            $user_link = $this->getUserLink($transaction['User']);
            $event_link = $this->getEventLink($transaction['GuestListUser']['GuestList']['Event']);            
            $transactionReplace = array(
                '##USER##' => $user_link,
                '##EVENT_NAME##' => $event_link,
            );
            return strtr($transaction['TransactionType']['message'], $transactionReplace);
        }
    }
    public function getUserLink($user_details)
    {
        return $this->link($this->cText($user_details['username'], false) , array(
            'controller' => 'users',
            'action' => 'view',
            $user_details['username'],
            'admin' => false
        ) , array(
            'class' => 'link-style',
            'title' => $this->cText($user_details['username'], false) ,
            'escape' => false
        ));        
    }
    public function getEventLink($event_details)
    {        
        return $this->link($this->cText($event_details['title'], false) , array(
            'controller' => 'events',
            'action' => 'view',
            $event_details['slug'],
            'admin' => false
        ) , array(
            'class' => 'link-style',
            'title' => $this->cText($event_details['title'], false) ,
            'escape' => false
        ));        
    }
    public function siteCurrencyFormat($amount)
    {
        if (Configure::read('site.currency_symbol_place') == 'left') {
            return Configure::read('site.currency') . $amount;
        } else {
            return $amount . Configure::read('site.currency');
        }
    }
	function cDate($str, $wrap = 'span', $title = false)
    {
        $changed = (($r = $this->htmlPurifier->purify(strftime(Configure::read('site.date.format') , strtotime($str . ' GMT')))) != strftime(Configure::read('site.date.format') , strtotime($str . ' GMT')));
        if ($wrap) {
            if (!$title) {
                $title = ' title="' . strftime(Configure::read('site.datetime.tooltip') , strtotime($str . ' GMT')) . ' ' . Configure::read('site.timezone_offset') . '"';
            }
            $r = '<' . $wrap . ' class="c' . $changed . '"' . $title . '>' . $r . '</' . $wrap . '>';
        }
        return $r;
    }
    function cDateTime($str, $wrap = 'span', $title = false)
    {
        $changed = (($r = $this->htmlPurifier->purify(strftime(Configure::read('site.datetime.format') , strtotime($str . ' GMT')))) != strftime(Configure::read('site.datetime.format') , strtotime($str . ' GMT')));
        if ($wrap) {
            if (!$title) {
                $title = ' title="' . strftime(Configure::read('site.datetime.tooltip') , strtotime($str . ' GMT')) . ' ' . Configure::read('site.timezone_offset') . '"';
            }
            $r = '<' . $wrap . ' class="c' . $changed . '"' . $title . '>' . $r . '</' . $wrap . '>';
        }
        return $r;
    }
    function cTime($str, $wrap = 'span', $title = false)
    {
        $changed = (($r = $this->htmlPurifier->purify(strftime(Configure::read('site.time.format') , strtotime($str . ' GMT')))) != strftime(Configure::read('site.time.format') , strtotime($str . ' GMT')));
        if ($wrap) {
            if (!$title) {
                $title = ' title="' . strftime(Configure::read('site.datetime.tooltip') , strtotime($str . ' GMT')) . ' ' . Configure::read('site.timezone_offset') . '"';
            }
            $r = '<' . $wrap . ' class="c' . $changed . '"' . $title . '>' . $r . '</' . $wrap . '>';
        }
        return $r;
    }
	function cDateTimeHighlight($str, $wrap = 'span', $title = false)
    {
		$Str = $str;
        if (strtotime(_formatDate('Y-m-d', strtotime($str))) == strtotime(date('Y-m-d'))) {
            $str = strftime('%I:%M %p', strtotime($str . ' GMT'));
        } else if (strtotime(date('Y-m-d', strtotime(_formatDate('Y-m-d', strtotime($str))))) > strtotime(date('Y-m-d')) || mktime(0, 0, 0, 0, 0, date('Y', strtotime(_formatDate('Y-m-d', strtotime($str))))) < mktime(0, 0, 0, 0, 0, date('Y'))) {
            $str = strftime('%b %d, %Y', strtotime($str . ' GMT'));
        } else {
            $str = strftime('%b %d', strtotime($str . ' GMT'));
        }
        $changed = (($r = $this->htmlPurifier->purify($str)) != $str);
        if ($wrap) {
            if (!$title) {
                $title = ' title="' . strftime(Configure::read('site.datetime.tooltip') , strtotime($Str . ' GMT')) . ' ' . Configure::read('site.timezone_offset') . '"';
            }
            $r = '<' . $wrap . ' class="c' . $changed . '"' . $title . '>' . $r . '</' . $wrap . '>';
        }
        return $r;
    }
}
?>