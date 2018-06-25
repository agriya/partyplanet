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
class BannedIp extends AppModel
{
    public $name = 'BannedIp';
    function __construct($id = false, $table = null, $ds = null) 
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'address' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required') ,
                    'allowEmpty' => false
                )
            ) ,
            'range' => array(
                'rule1' => array(
                    'rule' => 'isRangeRequired',
                    'message' => __l('Required')
                )
            ) ,
            'duration_time' => array(
                'rule1' => array(
                    'rule' => 'isDurationRequired',
                    'message' => __l('Enter number higher than 0')
                )
            )
        );
        $this->ipTypesOptions = array(
            ConstBannedTypes::SingleIPOrHostName => __l('Single IP or hostname') ,
            ConstBannedTypes::IPRange => __l('IP Range') ,
            ConstBannedTypes::RefererBlock => __l('Referer block')
        );
        $this->ipTimeOptions = array(
            ConstBannedDurations::Permanent => __l('Permanent') ,
            ConstBannedDurations::Days => __l('Day(s)') ,
            ConstBannedDurations::Weeks => __l('Week(s)')
        );
    }
    // Function to check the given ip is in banned lists or not
    function checkIsIpBanned($ip = null) 
    {
        $is_ip_banned = false;
        $ip = ip2long($ip);
        // To get the banned ip lists
        $banned_ips = $this->find('all');
        foreach($banned_ips as $banned_ip) {
            $range_start = ip2long($banned_ip['BannedIp']['address']);
            $range_end = ip2long($banned_ip['BannedIp']['range']);
            if ($ip !== false && $ip >= $range_start && $ip <= $range_end) {
                return true;
            }
        }
        return $is_ip_banned;
    }
    function isRangeRequired() 
    {
        if ($this->data['BannedIp']['type_id'] == ConstBannedTypes::IPRange) {
            if (empty($this->data['BannedIp']['range'])) {
                return false;
            }
        }
        return true;
    }
    function isDurationRequired() 
    {
        if ($this->data['BannedIp']['duration_id'] != ConstBannedDurations::Permanent) {
            if (empty($this->data['BannedIp']['duration_time']) || $this->data['BannedIp']['duration_time'] <= 0) {
                return false;
            }
        }
        return true;
    }
}
?>