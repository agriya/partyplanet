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
class CronComponent extends Component
{
    var $controller;
    public function main() 
    {
        $this->encode();
        $this->guest_list();
        $this->affiliate();
    }
    public function encode() 
    {
        App::import('Model', 'Video');
        $this->Video = new Video();
        if ($this->_getEncodingCronSemaphore() != 'true') {
            Configure::write('video.convert_flv_command', 'ffmpeg -y -i #video_source_path# -sameq -ar 44100 -ab 64k -ac 1 -s 320x240');
            Configure::write('video.convert_thumbnail_command', 'ffmpeg -i #image_source_path# -an -ss 00:00:03 -r 1 -vframes 5 -s 120x80');
            $this->_setEncodingCronSemaphore('true');
            while ($videosToEncode = $this->Video->find('all', array(
                'conditions' => array(
                    'is_encoded' => 0
                ) ,
                'contain' => array(
                    'Attachment',
                    'User'
                ) ,
                'limit' => 10,
                'recursive' => 0
            ))) {
                $current_encoding_files_count = 0;
                do {
                    while (($current_encoding_files_count < Configure::read('Video.simultaneous_encoding')) && ($video = array_shift($videosToEncode))) {
                        if ($this->_startEncoding($video)) {
                            $in_encoding_queue[$video['Video']['id']] = $video;
                            ++$current_encoding_files_count;
                        }
                    }
                    if (!empty($in_encoding_queue)) {
                        foreach($in_encoding_queue as $video_id => $video) {
                            $this->Video->updateAll(array(
                                'Video.is_encoded' => 1,
                            ) , array(
                                'Video.id' => $video_id
                            ));
                            sleep(10);
                            //yamdi code.. quick work...
                            // @todo For better performance, make it in proc_open
                            $this->_yamdi($video);
                            unset($in_encoding_queue[$video_id]);
                            --$current_encoding_files_count;
                        }
                    }
                }
                while (!empty($videosToEncode) or !empty($in_encoding_queue));
            }
            $this->_setEncodingCronSemaphore('');
        }
    }
    // As Cache::read() and Cache::write() aren't working as expected
    public function _setEncodingCronSemaphore($value) 
    {
        @file_put_contents(TMP . 'encoding_cron_semaphore', $value);
    }
    public function _getEncodingCronSemaphore() 
    {
        return @file_get_contents(TMP . 'encoding_cron_semaphore');
    }
    public function _yamdi($video) 
    {
        $filename = substr($video['Attachment']['filename'], 0, (strrpos($video['Attachment']['filename'], '.')));
        $video_source_path = str_replace('/', DS, APP . 'media' . DS . $video['Attachment']['dir'] . DS . $filename . '_' . $video['Video']['id'] . '.flv');
        $video_destination_path = str_replace('/', DS, APP . 'media' . DS . $video['Attachment']['dir'] . DS . $filename . '_' . $video['Video']['id'] . '.metadata.flv');
        $yamdi_path = (strpos(PHP_OS, 'WIN') !== false) ? 'yamdi' : Configure::read('Video.yamdi_path');
        $flv_metadata_inject_command = $yamdi_path . ' -i "' . $video_source_path . '" -o "' . $video_destination_path . '" -c "' . Configure::read('site.name') . '(' . Configure::read('site.site_url_for_cron') . ')"';
        exec($flv_metadata_inject_command);
        unlink($video_source_path);
        rename($video_destination_path, $video_source_path);
    }
    public function _startEncoding($video) 
    {
        $user = $this->Video->User->find('first', array(
            'conditions' => array(
                'User.id' => $video['Video']['user_id']
            ) ,
            'recursive' => -1
        ));
        $default_thumbnail_id = $duration = $is_flv_created = 0;
        $filename = substr($video['Attachment']['filename'], 0, (strrpos($video['Attachment']['filename'], '.')));
        $video_source_path = str_replace('/', DS, APP . 'media' . DS . $video['Attachment']['dir'] . DS . $video['Attachment']['filename']);
        // @todo: Fix the temporary fix on & (background process) which is leaving the flow crazy. Even after the process is finished, the file size is too low
        $flv_extension = substr($video['Attachment']['filename'], strrpos($video['Attachment']['filename'], '.') +1);
        $video_destination_path = str_replace('/', DS, APP . 'media' . DS . $video['Attachment']['dir'] . DS . $filename . '_' . $video['Video']['id'] . '.flv');
        $VideoEncodingProfile['ffmpeg_command'] = str_replace('#video_source_path#', '"' . $video_source_path . '"', Configure::read('video.convert_flv_command'));
        $flv_create_command = $VideoEncodingProfile['ffmpeg_command'] . ' ' . '"' . $video_destination_path . '"';
        exec($flv_create_command);
        $is_flv_created = 1;
        $this->Video->Attachment->Behaviors->attach('ImageUpload');
        $this->Video->Attachment->enableUpload(false);
        $this->Video->Attachment->create();
        $_data = array();
        $_data['Attachment']['class'] = 'EncodeVideo';
        $_data['Attachment']['user_id'] = $video['Video']['user_id'];
        $_data['Attachment']['mimetype'] = 'video/x-flv';
        $_data['Attachment']['foreign_id'] = $video['Video']['id'];
        $_data['Attachment']['filesize'] = filesize($video_destination_path);
        $_data['Attachment']['filename'] = $filename . '_' . $video['Video']['id'] . '.flv';
        $_data['Attachment']['dir'] = $video['Attachment']['dir'];
        $this->Video->Attachment->save($_data);
        $this->Video->Attachment->Behaviors->detach('ImageUpload');
        $duration = $this->_getFLVDuration($video_destination_path);
        sleep(10);
        $video_thumb_destination_path = str_replace('/', DS, APP . 'media' . DS . $video['Attachment']['dir'] . DS . $filename . '_%d.jpg');
        $VideoEncodingProfile['thumbnail_command'] = str_replace('#image_source_path#', '"' . $video_source_path . '"', Configure::read('video.convert_thumbnail_command'));
        $video_thumb_create_command = $VideoEncodingProfile['thumbnail_command'] . ' -y ' . '"' . $video_thumb_destination_path . '"';
        exec($video_thumb_create_command);
        for ($i = 1; $i <= Configure::read('Video.no_of_thumbnail'); $i++) {
            $thumbnail_full_path = str_replace('/', DS, APP . 'media' . DS . $video['Attachment']['dir'] . DS . $filename . '_' . $i . '.jpg');
            if (is_file($thumbnail_full_path)) {
                $this->Video->Attachment->Behaviors->attach('ImageUpload');
                $this->Video->Attachment->enableUpload(false);
                $_data = array();
                $_data['Attachment']['class'] = 'Thumbnail';
                $_data['Attachment']['user_id'] = $video['Video']['user_id'];
                $_data['Attachment']['mimetype'] = 'image/jpeg';
                $_data['Attachment']['foreign_id'] = $video['Video']['id'];
                $_data['Attachment']['filesize'] = filesize($thumbnail_full_path);
                $_data['Attachment']['filename'] = $filename . '_' . $i . '.jpg';
                $_data['Attachment']['dir'] = $video['Attachment']['dir'];
                $this->Video->Attachment->create();
                if ($this->Video->Attachment->save($_data)) {
                    $default_thumbnail_id = $this->Video->Attachment->getLastInsertId();
                    $url = Router::url(array(
                        'controller' => 'videos',
                        'action' => 'view',
                        'admin' => false,
                        'video' => $video['Video']['slug']
                    ) , true);
                    $image_options = array(
                        'dimension' => 'normal_thumb',
                        'class' => '',
                        'alt' => $video['Video']['title'],
                        'title' => $video['Video']['title'],
                        'type' => 'jpg'
                    );
                    $video1['Attachment']['id'] = $default_thumbnail_id;
                    $post_data = array();
                    $post_data['message'] = $video['User']['username'] . ' ' . __l('addd a new video "') . '' . $video['Video']['title'] . __l('" in ') . Configure::read('site.name');
                    $post_data['image_url'] = Router::url('/', true) . getImageUrl('Video', $video1['Attachment'], $image_options);
                    $post_data['link'] = $url;
                    $post_data['description'] = $video['Video']['description'];
                    // Post on user facebook
                    if (Configure::read('social_networking.post_video_on_user_facebook')) {
                        if ($video['User']['fb_user_id'] > 0) {
                            $post_data['fb_user_id'] = $video['User']['fb_user_id'];
                            $post_data['fb_access_token'] = $video['User']['fb_access_token'];
                            $getFBReturn = $this->postOnFacebook($post_data, 0);
                        }
                    }
                    // post on site facebook
                    if (Configure::read('video.post_on_facebook')) {
                        $getFBReturn = $this->postOnFacebook($post_data, 1);
                    }
                    // post on user twitter
                    if (Configure::read('social_networking.post_video_on_user_twitter')) {
                        if (!empty($video['User']['twitter_access_token']) && !empty($video['User']['twitter_access_key'])) {
                            $post_data['twitter_access_key'] = $video['User']['twitter_access_key'];
                            $post_data['twitter_access_token'] = $video['User']['twitter_access_token'];
                            $getTewwtReturn = $this->postOnTwitter($post_data, 0);
                        }
                    }
                    // post on site twitter8
                    if (Configure::read('video.post_on_twitter')) {
                        $getTewwtReturn = $this->postOnTwitter($post_data, 1);
                    }
                }
                $this->Video->Attachment->Behaviors->detach('ImageUpload');
            }
        }
        $this->Video->updateAll(array(
            'Video.duration' => '\'' . $duration . '\'',
            'Video.default_thumbnail_id' => '\'' . $default_thumbnail_id . '\'',
        ) , array(
            'Video.id' => $video['Video']['id']
        ));
        if (!empty($is_flv_created)) {
            return true;
        } else {
            return false;
        }
    }
    public function _getFLVDuration($file) 
    {
        $duration = false;
        if (file_exists($file)) {
            $fp = fopen($file, 'r');
            if ($fp) {
                $header = fread($fp, 5);
                if ($header !== false) {
                    $is_flv = ($header[0] == 'F' && $header[1] == 'L' && $header[2] == 'V');
                    $is_flv_video = (hexdec(bin2hex($header[4])) &0x01);
                    if ($is_flv && $is_flv_video) {
                        if (fseek($fp, 0, SEEK_END) == 0) {
                            $length = ftell($fp);
                            if ($length !== false) {
                                if (fseek($fp, -4, SEEK_END) == 0) {
                                    $value = fread($fp, 4);
                                    if ($value !== false) {
                                        $taglen = hexdec(bin2hex($value));
                                        if ($length > $taglen) {
                                            if (fseek($fp, $length-$taglen, SEEK_SET) == 0) {
                                                $value = fread($fp, 3);
                                                if ($value !== false) {
                                                    // time in milliseconds
                                                    $duration = hexdec(bin2hex($value));
                                                    // time in seconds
                                                    $duration = $duration/1000;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                fclose($fp);
            }
        }
        return $duration;
    }
    public function guest_list() 
    {
        App::import('Model', 'GuestList');
        $this->GuestList = new GuestList();
        App::import('Model', 'EmailTemplate');
        $this->EmailTemplate = new EmailTemplate();
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Email');
        $this->Email = new EmailComponent($collection);
        $guestLists = $this->GuestList->find('all', array(
            'contain' => array(
                'Event' => array(
                    'User' => array(
                        'fields' => array(
                            'User.email',
                            'User.username',
                        ) ,
                    ) ,
                    'Venue' => array(
                        'City',
                        'Country',
                    ) ,
                ) ,
                'GuestListUser' => array(
                    'conditions' => array(
                        'GuestListUser.rsvp_response_id !=' => 2
                    ) ,
                    'User' => array(
                        'fields' => array(
                            'User.email',
                            'User.username',
                        ) ,
                    ) ,
                ) ,
            ) ,
            'recursive' => 3,
        ));
        foreach($guestLists as $guestList) {
            if (!empty($guestList['GuestListUser']) && strtotime($guestList['GuestList']['website_close_time']) == strtotime(date('H:i:s'))) {
                $guestListUserList = array();
                foreach($guestList['GuestListUser'] as $guestListUser) {
                    $guestListUserList[$guestListUser['date']][] = $guestListUser['User']['username'] . '(' . $guestListUser['in_party_count'] . ')';
                }
                $guest_names = '';
                foreach($guestListUserList as $key => $val) {
                    $guest_names.= $key . ' - ';
                    $guest_names.= implode(',', $val);
                    $guest_names.= ' , ';
                }
                $guest_names = nl2br($guest_names);
                $email = $this->EmailTemplate->selectTemplate('Guest List SignUp Owner');
                $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
                $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
                $emailFindReplace = array(
                    '##USERNAME##' => $guestList['Event']['User']['username'],
                    '##SITE_NAME##' => Configure::read('site.name') ,
                    '##EVENTNAME##' => $guestList['Event']['title'],
                    '##GUSTLISTDATE##' => '',
                    '##TIME##' => $guestList['GuestList']['guest_close_time'],
                    '##GUESTCOUNT##' => $guest_names,
                    '##VENUEDETAILS##' => $guestList['Event']['Venue']['name'] . ',' . $guestList['Event']['Venue']['address'] . ',' . $guestList['Event']['Venue']['City']['name'] . ',' . $guestList['Event']['Venue']['Country']['name']
                );
                $eventemails = array(
                    $guestList['Event']['User']['email']
                );
                if (!empty($guestList['GuestList']['email'])) {
                    $guestlist_emails = explode(',', $guestList['GuestList']['email']);
                    $eventemails = array_merge($guestlist_emails, $eventemails);
                }
                foreach($eventemails as $val) {
                    $this->Email->to = $val;
                    $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                    $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                    $this->Email->send(strtr($email['email_content'], $emailFindReplace));
                }
            }
        }
    }
    public function affiliate() 
    {
        if (Configure::read('affiliate.is_enabled')) {
            App::import('Model', 'Affiliate');
            $this->Affiliate = new Affiliate();
            $this->Affiliate->update_affiliate_status();
        }
    }
    // Posting on Facebook
    public function postOnFacebook($post_data = array() , $admin = null) 
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
    public function postOnTwitter($post_data = array() , $admin = null) 
    {
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'OauthConsumer');
        $this->OauthConsumer = new OauthConsumerComponent($collection);
        if ($admin) {
            $twitter_access_token = Configure::read('twitter.site_user_access_token');
            $twitter_access_key = Configure::read('twitter.site_user_access_key');
        } else {
            $twitter_access_token = $post_data['twitter_access_token'];
            $twitter_access_key = $post_data['twitter_access_key'];
        }
        $xml = $this->OauthConsumer->post('Twitter', $twitter_access_token, $twitter_access_key, 'https://twitter.com/statuses/update.xml', array(
            'status' => $post_data['message'] . ' ' . $post_data['link']
        ));
    }
}
?>