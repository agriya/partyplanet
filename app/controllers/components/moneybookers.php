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
 * <b>Sample code to construct hash value</b>
 *
 * <code>
 * $tmp_str = $this->moneybookers_post_arr['merchant_id'].$this->moneybookers_post_arr['transaction_id'].
 * md5(strtoupper($this->secret_code)).$this->moneybookers_post_arr['mb_amount'].
 * this->moneybookers_post_arr['mb_currency'].$this->moneybookers_post_arr['status'];
 * $expected_hash = md5($tmp_str);
 * </code>
 */
class MoneybookersComponent extends Component
{
    public $components = array(
        'RequestHandler'
    );
    public $moneybookers_post_arr = array();
    public $gateway_id;
    private $moneybookers_post_vars_in_str = '';
    private $errno;
    private $paypal_response = '';
    // Overridable settings
    public $payee_account;
    public $is_test = 0;
    public $amount_for_item;
    public $moneybookers_transaction_model = 'MoneybookerTransactionLog';
    public $form_class = 'normal';
    public function initialize(&$controller, $settings = array()) 
    {
        $this->_set($settings);
    }
    public function startup(&$controller) 
    {
    }
    public public function process() 
    {
        $this->error = 0; //initialize to no error
        $this->error|= $this->_isVaildHash() ? 0 : (1<<0);
        $this->error|= (!$this->_isTransactionProcessed()) ? 0 : (1<<1);
        $this->error|= $this->_isValidPayeeAccount() ? 0 : (1<<2);
        $this->error|= $this->_isValidAmount() ? 0 : (1<<3);
        return (!$this->error);
    }
    private function _isVaildHash() 
    {
        // In getting the hash value the merchant_id,transaction_id,secret_code (which was set in the profile page of
        // the moneybookers account),mb_amount,mb_currency,status were used
        $tmp_str = $this->moneybookers_post_arr['merchant_id'] . $this->moneybookers_post_arr['transaction_id'] . md5(strtoupper($this->secret_code)) . $this->moneybookers_post_arr['mb_amount'] . $this->moneybookers_post_arr['mb_currency'] . $this->moneybookers_post_arr['status'];
        $expected_hash = md5($tmp_str);
        return (strcmp($expected_hash, $this->moneybookers_post_arr['md5sig']) == 0);
    }
    private function _isTransactionProcessed() 
    {
        $moneybookersTransactionModel = ClassRegistry::init($this->moneybookers_transaction_model);
        return ($moneybookersTransactionModel->find('count', array(
            'conditions' => array(
                $this->moneybookers_transaction_model . '.order_id' => $this->moneybookers_post_arr['mb_transaction_id'],
                $this->moneybookers_transaction_model . '.error_no' => 0
            )
        )));
    }
    private function _isValidPayeeAccount() 
    {
        // is posted payee account is ours...
        return (strcmp($this->payee_account, $this->moneybookers_post_arr['pay_to_email']) == 0);
    }
    private function _isValidAmount() 
    {
        return (($this->amount_for_item == $this->moneybookers_post_arr['mb_amount']) ? true : false);
    }
    public public function sanitizeServerVars($posted = array()) 
    {
        $this->moneybookers_post_arr = $posted;
        $expected_moneybookers_post_arr = array(
            'pay_to_email' => '',
            'pay_from_email' => '',
            'pay_to_email' => '',
            'merchant_id' => '',
            'transaction_id' => '',
            'mb_transaction_id' => '',
            'foreign_id' => '',
            'mb_amount' => '',
            'mb_currency' => '',
            'status' => '',
            'md5sig' => '',
            'amount' => '',
            'currency' => '',
            'Transkey' => '',
        );
        // todo: Missing customer_id, payment_type fields, may be those are not necessary
        //  @todo Check if $tmp_arr is really necessary. Can't we do it directly?
        $tmp_arr = array();
        foreach($expected_moneybookers_post_arr as $key => $default_value) {
            $tmp_arr[$key] = (isset($this->moneybookers_post_arr[$key])) ? htmlspecialchars(trim($this->moneybookers_post_arr[$key])) : $default_value;
        }
        if (!empty($this->moneybookers_post_arr['Transkey'])) {
            $user_defined = $this->decode($this->moneybookers_post_arr['Transkey']);
            if (is_array($user_defined)) {
                $tmp_arr = array_merge($tmp_arr, $user_defined);
            }
        }
        // following line is to avoid undefined index error...
        $this->moneybookers_post_arr = $tmp_arr;
    }
    public public function setPayeeAccount($payee_account) 
    {
        $this->payee_account = $payee_account;
    }
    public public function setMerchantPassword($secret_code) 
    {
        // The secret code was set in the profile page of the moneybookers account
        $this->secret_code = $secret_code;
    }
    public public function logMoneybookersTransactions() 
    {
        $moneybookersTransactionModel = ClassRegistry::init($this->moneybookers_transaction_model);
        $this->request->data['MoneybookerTransactionLog']['error_message'] = $this->error;
        $this->request->data['MoneybookerTransactionLog']['ip_id'] = $this->MoneybookerTransactionLog->toSaveIp();
        foreach($this->moneybookers_post_arr as $key => $value) {
            $this->request->data['MoneybookerTransactionLog'][$key] = $value;
        }
        $this->request->data['MoneybookerTransactionLog']['post_array'] = serialize($this->request->data['MoneybookerTransactionLog']);
        $moneybookersTransactionModel->save($this->request->data);
        return $moneybookersTransactionModel->getLastInsertId();
    }
    public public function moneybookers_form($settings = array()) 
    {
        $__default_settings = array(
            // Common fixed settings
            'action_url' => $this->url() , // Paypal URL to which the form to be posted
            // Overridable setting
            'notify_url' => '', // Our site URL to which the paypal will post the payment status details in background
            'cancel_return' => '', // Our site URL to which paypal transaction cancel click will return
            'return' => '', // Our site URL to which paypal transaction success click will return
            'item_name' => '', // Item/product name
            'account' => $this->payee_account,
            'currency_code' => Configure::read('paypal.currency_code') ,
            'amount' => $this->amount_for_item,
            'language' => 'EN',
            'name' => Configure::read('site.name') ,
            'description' => 'Test'
        );
        $settings = array_merge($__default_settings, $settings);
        if (!empty($settings['user_defined'])) {
            $settings['Transkey'] = $this->encode($settings['user_defined']);
        }
        $html = "";
        $html.= '<form action="' . $settings['action_url'] . '" method="get" id="selPaymentForm" class="' . $this->form_class . '">';
        $html.= '<input type="hidden" name="pay_to_email" value="' . $settings['account'] . '" />';
        $html.= '<input type="hidden" name="status_url" value="' . $settings['notify_url'] . '" />';
        $html.= '<input type="hidden" name="cancel_url" value="' . $settings['cancel_return'] . '" />';
        $html.= '<input type="hidden" name="return_url" value="' . $settings['return'] . '" />';
        $html.= '<input type="hidden" name="merchant_fields" value="Transkey" />';
        $html.= '<input type="hidden" name="detail1_description" value="' . $settings['description'] . '" />';
        $html.= '<input type="hidden" name="detail1_text" value="' . $settings['item_name'] . '" />';
        $html.= '<input type="hidden" name="amount" value="' . $settings['amount'] . '" />';
        $html.= '<input type="hidden" name="name" value="' . $settings['name'] . '" />';
        $html.= '<input type="hidden" name="currency" value="' . $settings['user_defined']['currency_code'] . '" />';
        $html.= '<input type="hidden" name="transaction_id" value="' . $settings['user_defined']['foreign_id'] . '" />';
        $html.= '<input type="hidden" name="Transkey" value="' . $settings['Transkey'] . '" />';
        $html.= '<input type="image" name="Moneybookers" src="http://www.moneybookers.com/images/banners/en/en_fasteasysecure.gif" style="border-width: 1px; border-color: #8B8583;" />';
        return $html;
    }
    private function url() 
    {
        if ($this->is_test == 1) {
            return 'https://www.moneybookers.com/app/test_payment.pl';
        } elseif ($this->is_test == 0) {
            return 'https://www.moneybookers.com/app/payment.pl';
        }
    }
    private function encode($user_defined) 
    {
        $ecnoded_params = base64_url_encode(gzdeflate(serialize($user_defined) , 9));
        $user_defined_hash = substr(md5(Configure::read('Security.salt') . $ecnoded_params) , 5, 5);
        return ($ecnoded_params . '~' . $user_defined_hash);
    }
    private function decode($hash) 
    {
        $transkey_parts = explode('~', $hash);
        if (count($transkey_parts) == 2) {
            if ($transkey_parts[1] == (substr(md5(Configure::read('Security.salt') . $transkey_parts[0]) , 5, 5))) {
                $user_defined = unserialize(gzinflate(base64_url_decode($transkey_parts[0])));
                if (is_array($user_defined)) {
                    return $user_defined;
                }
                return null; // added because user has not defined any array
                
            }
            return null; // added because user has not defined any array
            
        }
        return null; // failed here
        
    }
}
?>