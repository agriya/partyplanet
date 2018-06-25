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
class AffiliateBehavior extends ModelBehavior
{
    function afterSave(&$model, $created) 
    {
        if (Configure::read('affiliate.is_enabled')) {
            $affiliate_model = Cache::read('affiliate_model', 'affiliatetype');
            if (array_key_exists($model->name, $affiliate_model)) {
                if ($created) {
                    $this->__createAffiliate($model);
                } else {
                    $this->__createAffiliate($model);
                }
            }
        }
    }
    function __createAffiliate(&$model) 
    {
        App::import('Core', 'Cookie');
        $collection = new ComponentCollection();
        $cookie = new CookieComponent($collection);
        $referrer = $cookie->read('referrer');
        $this->User = $this->__getparentClass('User');
        $affiliate_model = Cache::read('affiliate_model', 'affiliatetype');
        if (((!empty($referrer['refer_id'])) || (!empty($model->data['User']['referred_by_user_id']))) && ($model->name == 'User')) {
            if (empty($referrer['refer_id'])) {
                $referrer['refer_id'] = $model->data['User']['referred_by_user_id'];
            }
            // update refer_id
            $data['User']['referred_by_user_id'] = $referrer['refer_id'];
            $data['User']['id'] = $model->id;
            $this->User->updateAll(array(
                'User.referred_by_user_id' => $referrer['refer_id']
            ) , array(
                'User.id' => $model->id
            ));
            // referred count update
            $this->User->updateAll(array(
                'User.referred_by_user_count' => 'User.referred_by_user_count + ' . 1
            ) , array(
                'User.id' => $referrer['refer_id']
            ));
            if ($this->__CheckAffiliateUSer($referrer['refer_id'])) {
                $this->AffiliateType = $this->__getparentClass('AffiliateType');
                $affiliateType = $this->AffiliateType->find('first', array(
                    'conditions' => array(
                        'AffiliateType.id' => $affiliate_model['User']
                    ) ,
                    'fields' => array(
                        'AffiliateType.id',
                        'AffiliateType.commission',
                        'AffiliateType.affiliate_commission_type_id'
                    ) ,
                    'recursive' => -1
                ));
                $affiliate_commision_amount = 0;
                if (!empty($affiliateType)) {
                    if (($affiliateType['AffiliateType']['affiliate_commission_type_id'] == ConstAffiliateCommissionType::Percentage)) {
                        $affiliate_commision_amount = 0.0; //($model->data['ProjectFund']['commission_amount'] * $affiliateType['AffiliateType']['commission']) / 100;
                        
                    } else {
                        $affiliate_commision_amount = $affiliateType['AffiliateType']['commission'];
                    }
                }
                // set affiliate data
                $affiliate['Affiliate']['class'] = 'User';
                $affiliate['Affiliate']['foreign_id'] = $model->id;
                $affiliate['Affiliate']['affiliate_type_id'] = $affiliate_model['User'];
                $affiliate['Affiliate']['affliate_user_id'] = $referrer['refer_id'];
                $affiliate['Affiliate']['affiliate_status_id'] = ConstAffiliateStatus::PipeLine;
                $affiliate['Affiliate']['commission_holding_start_date'] = date('Y-m-d');
                $affiliate['Affiliate']['commission_amount'] = $affiliate_commision_amount;
                $this->__saveAffiliate($affiliate);
            }
        }
    }
    function __saveAffiliate($data) 
    {
        $this->Affiliate = $this->__getparentClass('Affiliate');
        if (!isset($data['Affiliate']['id'])) {
            $this->Affiliate->create();
            $this->Affiliate->AffiliateUser->updateAll(array(
                'AffiliateUser.total_commission_pending_amount' => 'AffiliateUser.total_commission_pending_amount + ' . $data['Affiliate']['commission_amount']
            ) , array(
                'AffiliateUser.id' => $data['Affiliate']['affliate_user_id']
            ));
        }
        $this->Affiliate->save($data);
    }
    function __findAffiliate($condition) 
    {
        $this->Affiliate = $this->__getparentClass('Affiliate');
        $affiliate = $this->Affiliate->find('first', array(
            'conditions' => $condition,
            'recursive' => -1
        ));
        return $affiliate;
    }
    function __CheckAffiliateUSer($refer_user_id) 
    {
        $this->User = $this->__getparentClass('User');
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $refer_user_id
            ) ,
            'recursive' => -1
        ));
        if (!empty($user) && ($user['User']['is_affiliate_user'])) {
            return true;
        }
        return false;
    }
    function __getparentClass($parentClass) 
    {
        App::import('model', $parentClass);
        return new $parentClass;
    }
}
?>