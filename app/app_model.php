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
//App::import('Lib', 'LazyModel.LazyModel');
//class AppModel extends LazyModel
class AppModel extends Model
{
    public $actsAs = array(
        'Containable',
   );
    function beforeSave() 
    {
        $this->useDbConfig = 'master';
        return true;
    }
    function afterSave() 
    {
        $this->useDbConfig = 'default';
        return true;
    }
    function beforeDelete() 
    {
        $this->useDbConfig = 'master';
        return true;
    }
    function afterDelete() 
    {
        $this->useDbConfig = 'default';
        return true;
    }
    //Return comma separated tag string for a give tags array
    function _formatTags($tags = null) 
    {
        //Procsssing the tags for ModelnameTag (eg., EventTag) key in 1st domenstion of array
        if (isset($tags[0][$this->name . 'Tag']) or !empty($tags[0][$this->name . 'Tag'])) {
            static $comma_seperated_tag = array();
            foreach($tags as $tag) {
                if (!empty($tag['EventTag'])) {
                    $comma_seperated_tag[][$this->name . 'Tag'] = $this->_formatTags($tag[$this->name . 'Tag']);
                } else {
                    $comma_seperated_tag[][$this->name . 'Tag'] = array();
                }
            }
            return $comma_seperated_tag;
        }
        $comma_seperated_tag = '';
        if (!empty($tags)) {
            foreach($tags as $tag) {
                $comma_seperated_tag.= $tag['name'] . ', ';
            }
            $comma_seperated_tag = substr($comma_seperated_tag, 0, -2);
        }
        return $comma_seperated_tag;
    }
    function changeFromEmail($from_address = null) 
    {
        if (!empty($from_address)) {
            if (preg_match('|<(.*)>|', $from_address, $matches)) {
                return $matches[1];
            } else {
                return $from_address;
            }
        }
    }
    function getUserLanguageIso($user_id) 
    {
        App::import('Model', 'UserProfile');
        $this->UserProfile = new UserProfile();
        $user = $this->UserProfile->find('first', array(
            'conditions' => array(
                'UserProfile.user_id' => $user_id
            ) ,
            'contain' => array(
                'Language'
            ) ,
            'recursive' => 3
        ));
        return !empty($user['Language']['iso2']) ? $user['Language']['iso2'] : '';
    }
    function formatToAddress($user = null) 
    {
        if (!empty($user['UserProfile']['first_name']) && !empty($user['UserProfile']['last_name'])) {
            return $user['UserProfile']['first_name'] . ' ' . $user['UserProfile']['first_name'] . ' <' . $user['User']['email'] . '>';
        } elseif (!empty($user['UserProfile']['first_name'])) {
            return $user['UserProfile']['first_name'] . ' <' . $user['User']['email'] . '>';
        } else {
            return $user['User']['email'];
        }
    }
    function _saveTags($tag_name = null) 
    {
        $tag_ids = array();
        if (!empty($tag_name)) {
            $tag_names = explode(',', $tag_name);
            foreach($tag_names as $tag_name) {
                //Trimming to remove unnecessary spaces
                $tag_name = trim($tag_name);
                //Checking empty tag
                if (!empty($tag_name)) {
                    $tags = $this->find('first', array(
                        'conditions' => array(
                            $this->name . '.name =' => $tag_name
                        ) ,
                        'fields' => array(
                            $this->name . '.id'
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($tags)) {
                        $tag_ids[] = $tags[$this->name]['id'];
                    } else {
                        $data[$this->name]['name'] = $tag_name;
                        $this->create();
                        if ($this->save($data)) {
                            $tag_ids[] = $this->getLastInsertId();
                        }
                    }
                }
            }
        }
        return $tag_ids;
    }
    function findOrSaveAndGetId($data) 
    {
        $findExist = $this->find('first', array(
            'conditions' => array(
                'name' => $data
            ) ,
            'fields' => array(
                'id'
            ) ,
            'recursive' => -1
        ));
        if (!empty($findExist)) {
            return $findExist[$this->name]['id'];
        } else {
            $this->create();
            $this->data[$this->name]['name'] = $data;
            $this->save($this->data[$this->name]);
            return $this->getLastInsertId();
        }
    }
    function import($filename = null) 
    {
        $model = $this->name;
        require_once APP . DS . 'vendors' . DS . 'Excel' . DS . 'reader.php';
        $venuedata = new Spreadsheet_Excel_Reader();
        $venuedata->setOutputEncoding('UTF-8');
        $venuedata->read($filename);
        $return = array(
            'messages' => array() ,
            'errors' => array() ,
        );
        $error = '';
        // read each data row in the file
        $is_empty = 1;
        for ($i = 1; $i <= $venuedata->sheets[0]['numRows']; $i++) {
            if (!empty($venuedata->sheets[0]['cells'][$i][2])) {
                $is_empty = 0;
                $data = array();
                // for each header field
                $sub_category_id = '';
                $data['Venue']['name'] = (!empty($venuedata->sheets[0]['cells'][$i][2])) ? utf8_encode($venuedata->sheets[0]['cells'][$i][2]) : '';
                $data['Venue']['zip_code'] = (!empty($venuedata->sheets[0]['cells'][$i][4])) ? utf8_encode($venuedata->sheets[0]['cells'][$i][4]) : '';
                $data['Venue']['address'] = (!empty($venuedata->sheets[0]['cells'][$i][3])) ? utf8_encode($venuedata->sheets[0]['cells'][$i][3]) : '';
                $data['Venue']['street'] = (!empty($venuedata->sheets[0]['cells'][$i][7])) ? $venuedata->sheets[0]['cells'][$i][7] : '';
                $data['Venue']['phone'] = (!empty($venuedata->sheets[0]['cells'][$i][8])) ? $venuedata->sheets[0]['cells'][$i][8] : '';
                $data['Venue']['email'] = (!empty($venuedata->sheets[0]['cells'][$i][9])) ? $venuedata->sheets[0]['cells'][$i][9] : '';
                $data['Venue']['website'] = (!empty($venuedata->sheets[0]['cells'][$i][10])) ? $venuedata->sheets[0]['cells'][$i][10] : '';
                $data['Venue']['description'] = (!empty($venuedata->sheets[0]['cells'][$i][11])) ? utf8_encode($venuedata->sheets[0]['cells'][$i][11]) : '';
                if (!empty($venuedata->sheets[0]['cells'][$i][1])) {
                    $venueType = $this->VenueType->find('first', array(
                        'conditions' => array(
                            'VenueType.name' => $venuedata->sheets[0]['cells'][$i][1]
                        ) ,
                        'fields' => array(
                            'VenueType.id'
                        ) ,
                        'recursive' => -1
                    ));
                    if (empty($venueType)) {
                        $categoryinsert = array();
                        $categoryinsert['VenueType']['name'] = utf8_encode($venuedata->sheets[0]['cells'][$i][1]);
                        $categoryinsert['VenueType']['is_active'] = 1;
                        $this->VenueType->create();
                        $this->VenueType->save($categoryinsert);
                        $venue_type_id = $this->VenueType->getLastInsertId();
                    } else {
                        $venue_type_id = $venueType['VenueType']['id'];
                    }
                    $data['Venue']['venue_type_id'] = $venue_type_id;
                } else {
                    $data['Venue']['venue_type_id'] = '';
                }
                if (!empty($venuedata->sheets[0]['cells'][$i][6])) {
                    $country = $this->Country->find('first', array(
                        'conditions' => array(
                            'Country.name' => $venuedata->sheets[0]['cells'][$i][6]
                        ) ,
                        'fields' => array(
                            'Country.id'
                        ) ,
                        'recursive' => -1
                    ));
                    if (empty($country)) {
                        $return['errors'][] = __l(sprintf('Post for Row %d failed to validate.', $i) , true);
                    } else {
                        $data['Venue']['country_id'] = $country['Country']['id'];
                    }
                }
                if (empty($return['errors']) && !empty($venuedata->sheets[0]['cells'][$i][5])) {
                    $city = $this->City->find('first', array(
                        'conditions' => array(
                            'City.name' => $venuedata->sheets[0]['cells'][$i][5]
                        ) ,
                        'fields' => array(
                            'City.id',
                            'City.country_id'
                        ) ,
                        'recursive' => -1
                    ));
                    if (empty($city)) {
                        $this->City->create();
                        $city_data['City']['name'] = $venuedata->sheets[0]['cells'][$i][5];
                        $city_data['City']['country_id'] = $data['Venue']['country_id'];
                        $this->City->save($city_data['City']);
                        $data['Venue']['city_id'] = $this->City->getLastInsertId();
                    } else {
                        $data['Venue']['city_id'] = $city['City']['id'];
                        if (!empty($city['City']['country_id'])) {
                            $data['Venue']['country_id'] = $city['City']['country_id'];
                        }
                    }
                } else {
                    $data['Venue']['city_id'] = '';
                }
                if (empty($return['errors']) && !empty($data['Venue']['name'])) {
                    $data['Venue']['is_active'] = 1;
                    $data['Venue']['ip'] = $_SERVER['REMOTE_ADDR'];
                    $data['Venue']['host'] = gethostbyaddr($_SERVER['REMOTE_ADDR']);
                    $this->create();
                    $data['Venue']['user_id'] = 1;
                    $this->set($data);
                    unset($this->validate['start_date']);
                    unset($this->validate['landmark']);
                    unset($this->validate['phone']);
                    if (!$this->validates()) {
                        $return['errors'][] = __l(sprintf('Post for Row %d failed to validate.', $i) , true);
                    } else {
                        if (empty($return['errors']) && !$this->save($data)) {
                            $return['errors'][] = __l(sprintf('Post for Row %d failed to save.', $i) , true);
                        }
                        if (empty($return['errors'])) {
                            $inserted_id = $this->getLastInsertId();
                            $return['messages'][] = __l(sprintf('Post for Row %d was saved.', $i) , true);
                        }
                    }
                } else {
                    $return['errors'][] = __l(sprintf('Post for Row %d failed to save.', $i) , true);
                }
            }
            if ($is_empty) {
                $return['errors']['empty'] = __l('File does not contain any valied data');
            }
        }
        return $return;
    }
    function getPaymentTypes($field = null) 
    {
        App::import('Model', 'PaymentGatewaySetting');
        $this->PaymentGatewaySetting = new PaymentGatewaySetting();
        $payment_types = array();
        $PaymentGatewaySettings = $this->PaymentGatewaySetting->find('all', array(
            'conditions' => array(
                'PaymentGateway.is_active' => 1,
            ) ,
            'fields' => array(
                'PaymentGateway.display_name',
                'PaymentGatewaySetting.payment_gateway_id',
                'PaymentGatewaySetting.test_mode_value',
                'PaymentGatewaySetting.key',
            ) ,
            'order' => array(
                'PaymentGateway.display_name' => 'asc'
            ) ,
            'recursive' => 1
        ));
        foreach($PaymentGatewaySettings as $payment_option) {
            if (!empty($payment_option['PaymentGatewaySetting']['test_mode_value'])) {
                $payment_types[$payment_option['PaymentGatewaySetting']['payment_gateway_id']] = $payment_option['PaymentGateway']['display_name'];
                $is_paypal_enabled = 1;
            }
        }
        return $payment_types;
    }
     function toSaveIp()
    {
        App::import('Model', 'Ip');
        $this->Ip = new Ip();
        $this->data['Ip']['ip'] = RequestHandlerComponent::getClientIP();
        $ip = $this->Ip->find('first', array(
            'conditions' => array(
                'Ip.ip' => $this->data['Ip']['ip']
            ) ,
            'fields' => array(
                'Ip.id'
            ) ,
            'recursive' => -1
        ));
        if (empty($ip)) {
            $this->data['Ip']['host'] = gethostbyaddr($this->data['Ip']['ip']);
            if (!empty($_COOKIE['_geo'])) {
                $_geo = explode('|', $_COOKIE['_geo']);
                $country = $this->Ip->Country->find('first', array(
                    'conditions' => array(
                        'Country.iso_alpha2' => $_geo[0]
                    ) ,
                    'fields' => array(
                        'Country.id'
                    ) ,
                    'recursive' => -1
                ));
                if (empty($country)) {
                    $this->data['Ip']['country_id'] = 0;
                } else {
                    $this->data['Ip']['country_id'] = $country['Country']['id'];
                }
                $state = $this->Ip->State->find('first', array(
                    'conditions' => array(
                        'State.Name' => $_geo[1]
                    ) ,
                    'fields' => array(
                        'State.id'
                    ) ,
                    'recursive' => -1
                ));
                if (empty($state)) {
                    $this->data['State']['name'] = $_geo[1];
                    $this->Ip->State->create();
                    $this->Ip->State->save($this->data['State']);
                    $this->data['Ip']['state_id'] = $this->Ip->State->getLastInsertId();
                } else {
                    $this->data['Ip']['state_id'] = $state['State']['id'];
                }
                $city = $this->Ip->City->find('first', array(
                    'conditions' => array(
                        'City.Name' => $_geo[2]
                    ) ,
                    'fields' => array(
                        'City.id'
                    ) ,
                    'recursive' => -1
                ));
                if (empty($city)) {
                    $this->data['City']['name'] = $_geo[2];
                    $this->Ip->City->create();
                    $this->Ip->City->save($this->data['City']);
                    $this->data['Ip']['city_id'] = $this->Ip->City->getLastInsertId();
                } else {
                    $this->data['Ip']['city_id'] = $city['City']['id'];
                }
                $this->data['Ip']['latitude'] = $_geo[3];
                $this->data['Ip']['longitude'] = $_geo[4];
            }
            $this->Ip->create();
            $this->Ip->save($this->data['Ip']);
            return $this->Ip->getLastInsertId();
        } else {
            return $ip['Ip']['id'];
        }
    }
	public function _isValidCaptchaSolveMedia()
    {
        App::import('Vendor', 'solvemedialib');
        $privkey = Configure::read('captcha.verification_key');
        $hashkey = Configure::read('captcha.hash_key');
        $solvemedia_response = solvemedia_check_answer($privkey, $_SERVER["REMOTE_ADDR"], $_POST["adcopy_challenge"], $_POST["adcopy_response"], $hashkey);
        if (!$solvemedia_response->is_valid) {
            //handle incorrect answer
            return false;
        } else {
            return true;
        }
    }
}
?>