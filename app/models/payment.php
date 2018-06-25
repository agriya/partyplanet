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
class Payment extends AppModel
{
    var $useTable = false;
    function __construct($id = false, $table = null, $ds = null) 
    {
        parent::__construct($id, $table, $ds);
    }
	public function _getPaypalPlatformObject()
    {
        App::import('Vendor', 'adaptive_paypal/paypal_platform');
        $this->PaypalPlatform = new PaypalPlatform();
        App::import('Model', 'Transaction');
        $this->Transaction = new Transaction();
        $payment_gateway_id = ConstPaymentGateways::AdaptivePayPal;
        $paymentGateway = $this->Transaction->PaymentGateway->find('first', array(
            'conditions' => array(
                'PaymentGateway.id' => $payment_gateway_id,
            ) ,
            'contain' => array(
                'PaymentGatewaySetting' => array(
                    'fields' => array(
                        'PaymentGatewaySetting.key',
                        'PaymentGatewaySetting.test_mode_value',
                        'PaymentGatewaySetting.live_mode_value',
                    ) ,
                ) ,
            ) ,
            'recursive' => 1
        ));
        if (!empty($paymentGateway['PaymentGatewaySetting'])) {
            foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                $gateway_settings_options[$paymentGatewaySetting['key']] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
            }
        }
        $gateway_settings_options['is_test_mode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
        $this->PaypalPlatform->settings($gateway_settings_options);
        return $this->PaypalPlatform;
    }
    public function CustomizePaymentPage($payKey)
    {
        $PaypalPlatform = $this->_getPaypalPlatformObject();
        $options['headerImageUrl'] = Cache::read('site_url_for_shell', 'long') . 'img/logo.png';
        $options['businessName'] = Configure::read('site.name');
        $PaypalPlatform->CallSetPaymentOptions($payKey, $options);
    }
    public function _saveIPNLog()
    {
        App::import('Model', 'AdaptiveIpnLog');
        $this->AdaptiveIpnLog = new AdaptiveIpnLog();
        $paypal_post_vars_in_str = $this->_parse_array_query($_POST);
        if (!empty($paypal_post_vars_in_str)) {
            $adaptiveIpnLog['post_variable'] = $paypal_post_vars_in_str;
            $adaptiveIpnLog['ip_id'] = $this->AdaptiveIpnLog->toSaveIp();
            $this->AdaptiveIpnLog->create();
            $this->AdaptiveIpnLog->save($adaptiveIpnLog);
        }
    }
    public function _parse_array_query($array, $convention = '%s')
    {
        if (count($array) == 0) {
            return '';
        } else {
            $query = '';
            foreach($array as $key => $value) {
                if (is_array($value)) {
                    $new_convention = sprintf($convention, $key) . '[%s]';
                    $query.= $this->_parse_array_query($value, $new_convention);
                } else {
                    $key = urlencode($key);
                    $value = urlencode($value);
                    $query.= sprintf($convention, $key) . "=$value&";
                }
            }
            return $query;
        }
    }
    public function _savePaidLog($order_id, $paymentDetails, $class = 'JobOrder', $receiver_count = 2)
    {
        App::import('Model', 'AdaptiveTransactionLog');
        $this->AdaptiveTransactionLog = new AdaptiveTransactionLog();        
        $adaptiveTransactionLog['foreign_id'] = $order_id;
        $adaptiveTransactionLog['class'] = $class;
        $adaptiveTransactionLog['timestamp'] = !empty($paymentDetails['responseEnvelope.timestamp']) ? $paymentDetails['responseEnvelope.timestamp'] : '';
        $adaptiveTransactionLog['ack'] = !empty($paymentDetails['responseEnvelope.ack']) ? $paymentDetails['responseEnvelope.ack'] : '';
        $adaptiveTransactionLog['correlation_id'] = !empty($paymentDetails['responseEnvelope.correlationId']) ? $paymentDetails['responseEnvelope.correlationId'] : '';
        $adaptiveTransactionLog['build'] = !empty($paymentDetails['responseEnvelope.build']) ? $paymentDetails['responseEnvelope.build'] : '';
        $adaptiveTransactionLog['currency_code'] = !empty($paymentDetails['currencyCode']) ? $paymentDetails['currencyCode'] : '';
        $adaptiveTransactionLog['sender_email'] = !empty($paymentDetails['senderEmail']) ? $paymentDetails['senderEmail'] : '';
        $adaptiveTransactionLog['status'] = !empty($paymentDetails['status']) ? $paymentDetails['status'] : '';
        $adaptiveTransactionLog['tracking_id'] = !empty($paymentDetails['trackingId']) ? $paymentDetails['trackingId'] : '';
        $adaptiveTransactionLog['pay_key'] = !empty($paymentDetails['payKey']) ? $paymentDetails['payKey'] : '';
        $adaptiveTransactionLog['action_type'] = !empty($paymentDetails['actionType']) ? $paymentDetails['actionType'] : '';
        $adaptiveTransactionLog['fees_payer'] = !empty($paymentDetails['feesPayer']) ? $paymentDetails['feesPayer'] : '';
        $adaptiveTransactionLog['memo'] = !empty($paymentDetails['memo']) ? $paymentDetails['memo'] : '';
        $adaptiveTransactionLog['reverse_all_parallel_payments_on_error'] = !empty($paymentDetails['reverseAllParallelPaymentsOnError']) ? $paymentDetails['reverseAllParallelPaymentsOnError'] : '';
        for ($i = 0; $i < $receiver_count; $i++) {
            $adaptiveTransactionLog['transaction_id'] = !empty($paymentDetails['paymentInfoList.paymentInfo(' . $i . ').transactionId']) ? $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').transactionId'] : '';
            $adaptiveTransactionLog['transaction_status'] = !empty($paymentDetails['paymentInfoList.paymentInfo(' . $i . ').transactionStatus']) ? $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').transactionStatus'] : '';
            $adaptiveTransactionLog['amount'] = !empty($paymentDetails['paymentInfoList.paymentInfo(' . $i . ').receiver.amount']) ? $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').receiver.amount'] : '0';
            $adaptiveTransactionLog['email'] = !empty($paymentDetails['paymentInfoList.paymentInfo(' . $i . ').receiver.email']) ? $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').receiver.email'] : '';
            $adaptiveTransactionLog['primary'] = !empty($paymentDetails['paymentInfoList.paymentInfo(' . $i . ').receiver.primary']) ? $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').receiver.primary'] : '';
            $adaptiveTransactionLog['invoice_id'] = !empty($paymentDetails['paymentInfoList.paymentInfo(' . $i . ').receiver.invoiceId']) ? $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').receiver.invoiceId'] : '';
            $adaptiveTransactionLog['refunded_amount'] = !empty($paymentDetails['paymentInfoList.paymentInfo(' . $i . ').refundedAmount']) ? $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').refundedAmount'] : '';
            $adaptiveTransactionLog['pending_refund'] = !empty($paymentDetails['paymentInfoList.paymentInfo(' . $i . ').pendingRefund']) ? $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').pendingRefund'] : '';
            $adaptiveTransactionLog['sender_transaction_id'] = !empty($paymentDetails['paymentInfoList.paymentInfo(' . $i . ').senderTransactionId']) ? $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').senderTransactionId'] : '';
            $adaptiveTransactionLog['sender_transaction_status'] = !empty($paymentDetails['paymentInfoList.paymentInfo(' . $i . ').senderTransactionStatus']) ? $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').senderTransactionStatus'] : '';
            $this->AdaptiveTransactionLog->create();
            $this->AdaptiveTransactionLog->save($adaptiveTransactionLog);
        }
    }
    public function getPaymentDetails($payKey)
    {
        $PaypalPlatform = $this->_getPaypalPlatformObject();
        $transactionId = '';
        $trackingId = '';
        $paymentDetails = $PaypalPlatform->CallPaymentDetails($payKey, $transactionId, $trackingId);
        return $paymentDetails;
    }
    function getPreapprovalDetails($preapprovalKey)
    {
        $PaypalPlatform = $this->_getPaypalPlatformObject();
        $paypalConnection = $PaypalPlatform->CallPreapprovalDetails($preapprovalKey);
        return $paypalConnection;
    }
    public function getBalance()
    {
        $PaypalPlatform = $this->_getPaypalPlatformObject();
        $resArray = $this->PaypalPlatform->CallGetBalance();
        $ack = strtoupper($resArray["ACK"]);
        $return = 0;
        if ($ack == "SUCCESS") {
            foreach($resArray as $k => $v) {
                if (strtoupper($v) == strtoupper(Configure::read('paypal.currency_code'))) {
                    $pos = strpos($k, 'L_CURRENCYCODE');
                }
            }
            $return = $resArray['L_AMT' . $pos];
        }
        return $return;
    }
    public function getMerchantReferralURL()
    {
        App::uses('Transaction', 'Utility');
        $this->Transaction = &new Transaction();
        $payment_gateway_id = ConstPaymentGateways::PayPal;
        $paymentGateway = $this->Transaction->PaymentGateway->find('first', array(
            'conditions' => array(
                'PaymentGateway.id' => $payment_gateway_id,
            ) ,
            'contain' => array(
                'PaymentGatewaySetting' => array(
                    'fields' => array(
                        'PaymentGatewaySetting.key',
                        'PaymentGatewaySetting.test_mode_value',
                        'PaymentGatewaySetting.live_mode_value',
                    ) ,
                ) ,
            ) ,
            'recursive' => 1
        ));
        if (!empty($paymentGateway['PaymentGatewaySetting'])) {
            foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                $gateway_settings_options[$paymentGatewaySetting['key']] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
            }
        }
        if (!empty($paymentGateway['PaymentGateway']['is_test_mode'])) {
            return 'https://www.sandbox.paypal.com/us/mrb/pal=' . $gateway_settings_options['MRB_ID'];
        } else {
            return 'https://www.paypal.com/us/mrb/pal=' . $gateway_settings_options['MRB_ID'];
        }
    }
    public function createPaypalAccount($paypalAccount)
    {
        App::uses('User', 'Utility');
        $this->User = &new User();
        App::import('Model', 'Paypal');
        $this->Paypal = new Paypal();
        $PaypalPlatform = $this->_getPaypalPlatformObject();
        $cancelUrl = Router::url(array(
            'controller' => 'payments',
            'action' => 'cancel_account',
            'admin' => false
        ) , true);
        $returnUrl = Router::url(array(
            'controller' => 'payments',
            'action' => 'success_account',
            'admin' => false
        ) , true);
        $referralId = '';
        $notificationURL = '';
        $preferredLanguageCode = 'en_US';
        $accountType = $paypalAccount['PaypalAccount']['payment_types'];
        $firstName = $paypalAccount['PaypalAccount']['first_name'];
        $lastName = $paypalAccount['PaypalAccount']['last_name'];
        $dateOfBirth = $paypalAccount['PaypalAccount']['dob'];
        $address1 = $paypalAccount['PaypalAccount']['address1'];
        $address2 = $paypalAccount['PaypalAccount']['address2'];
        $city = $paypalAccount['PaypalAccount']['city'];
        $state = $paypalAccount['PaypalAccount']['state'];
        $zip = $paypalAccount['PaypalAccount']['zip'];
        $countryCode = $paypalAccount['PaypalCountry']['code'];
        $citizenshipCountryCode = $paypalAccount['PaypalCitizenshipCountry']['code'];
        $contactPhoneNumber = $paypalAccount['PaypalAccount']['phone'];
        $currencyCode = $paypalAccount['PaypalAccount']['currency_code'];
        $currencyCode = Configure::read('site.currency');
        $emailAddress = $paypalAccount['PaypalAccount']['email'];
        $resArray = $PaypalPlatform->CallCreateAccount($preferredLanguageCode, $accountType, $firstName, $lastName, $dateOfBirth, $address1, $address2, $city, $state, $zip, $countryCode, $citizenshipCountryCode, $contactPhoneNumber, $currencyCode, $emailAddress, $returnUrl, $cancelUrl, $notificationURL, $referralId);
        $ack = strtoupper($resArray["responseEnvelope.ack"]);
        $return['error'] = 0;
        if ($ack == "SUCCESS") {
            $data['PaypalAccount']['id'] = $paypalAccount['PaypalAccount']['id'];
            $data['PaypalAccount']['create_account_key'] = $resArray["createAccountKey"];
            $this->User->PaypalAccount->save($data, false);
            header('Location: ' . $resArray["redirectURL"]);
            exit;
        } else {
            $ErrorMsg = urldecode($resArray["error(0).message"]);
            $return['error_message'] = $ErrorMsg;
            $return['error'] = 1;
        }
        return $return;
    }
    public function getVerifiedStatus($data)
    {
        $PaypalPlatform = $this->_getPaypalPlatformObject();
        $paymentDetails = $PaypalPlatform->CallGetVerifiedStatus($data['paypal_account'], $data['paypal_first_name'], $data['paypal_last_name']);
        return $paymentDetails;
    }
    public function _saveRefundLog($order_id, $paymentDetails)
    {
        App::import('Model', 'AdaptiveTransactionLog');
        $this->AdaptiveTransactionLog = &new AdaptiveTransactionLog();
        $adaptiveTransactionLog['foreign_id'] = $order_id;
        $adaptiveTransactionLog['class'] = 'JobOrder';
        $adaptiveTransactionLog['timestamp'] = $paymentDetails['responseEnvelope.timestamp'];
        $adaptiveTransactionLog['ack'] = $paymentDetails['responseEnvelope.ack'];
        $adaptiveTransactionLog['correlation_id'] = $paymentDetails['responseEnvelope.correlationId'];
        $adaptiveTransactionLog['build'] = $paymentDetails['responseEnvelope.build'];
        $adaptiveTransactionLog['currency_code'] = $paymentDetails['currencyCode'];
        $paypal_post_vars_in_str = '';
        foreach($paymentDetails as $key => $value) {
            $value = urlencode(stripslashes($value));
            $paypal_post_vars_in_str.= "&$key=$value";
        }
        $adaptiveTransactionLog['paypal_post_vars'] = $paypal_post_vars_in_str;
        for ($i = 0; $i < 2; $i++) {
            $adaptiveTransactionLog['amount'] = $paymentDetails['refundInfoList.refundInfo(' . $i . ').receiver.amount'];
            $adaptiveTransactionLog['email'] = $paymentDetails['refundInfoList.refundInfo(' . $i . ').receiver.email'];
            $adaptiveTransactionLog['refund_status'] = $paymentDetails['refundInfoList.refundInfo(' . $i . ').refundStatus'];
            $adaptiveTransactionLog['refund_net_amount'] = $paymentDetails['refundInfoList.refundInfo(' . $i . ').refundNetAmount'];
            $adaptiveTransactionLog['refund_fee_amount'] = $paymentDetails['refundInfoList.refundInfo(' . $i . ').refundFeeAmount'];
            $adaptiveTransactionLog['refund_gross_amount'] = $paymentDetails['refundInfoList.refundInfo(' . $i . ').refundGrossAmount'];
            $adaptiveTransactionLog['total_of_alll_refunds'] = $paymentDetails['refundInfoList.refundInfo(' . $i . ').totalOfAllRefunds'];
            $adaptiveTransactionLog['refund_has_become_full'] = $paymentDetails['refundInfoList.refundInfo(' . $i . ').refundHasBecomeFull'];
            $adaptiveTransactionLog['encrypted_refund_transaction_id'] = $paymentDetails['refundInfoList.refundInfo(' . $i . ').encryptedRefundTransactionId'];
            $adaptiveTransactionLog['refund_transaction_status'] = $paymentDetails['refundInfoList.refundInfo(' . $i . ').refundTransactionStatus'];
            $this->AdaptiveTransactionLog->create();
            $this->AdaptiveTransactionLog->save($adaptiveTransactionLog);
        }
    }
    public function getFeesPayer($transaction_type)
    {
        $feesPayer = 'SENDER';
        switch ($transaction_type) {
            case ConstPaymentType::ContestFee:
                $feesPayer = 'EACHRECEIVER';
                break;

            case ConstPaymentType::ContestPrize:
                $feesPayer = 'EACHRECEIVER';
                break;

            case ConstPaymentType::Wallet:
                if (Configure::read('wallet.wallet_fee_payer') == 'Site') {
                    $feesPayer = 'EACHRECEIVER';
                }
                break;

            case ConstPaymentType::SignupFee:
                if (Configure::read('user.signup_fee_payer') == 'Site') {
                    $feesPayer = 'EACHRECEIVER';
                }
                break;
        }
        return $feesPayer;
    }
    public function cancelPreapproval($preapprovalKey)
    {
        $PaypalPlatform = $this->_getPaypalPlatformObject();
        $paymentDetails = $PaypalPlatform->CallCancelPreapproval($preapprovalKey);
        return $paymentDetails;
    }
    public function processPayment($foreign_id, $transaction_type)
    {
        $PaypalPlatform = $this->_getPaypalPlatformObject();
        $payment_gateway_id = ConstPaymentGateways::PayPal;
        App::uses('PaymentGateway', 'Model');
        $this->PaymentGateway = new PaymentGateway();
        $paymentGateway = $this->PaymentGateway->find('first', array(
            'conditions' => array(
                'PaymentGateway.id' => $payment_gateway_id,
            ) ,
            'contain' => array(
                'PaymentGatewaySetting' => array(
                    'fields' => array(
                        'PaymentGatewaySetting.key',
                        'PaymentGatewaySetting.test_mode_value',
                        'PaymentGatewaySetting.live_mode_value',
                    ) ,
                ) ,
            ) ,
            'recursive' => 1
        ));
        if (!empty($paymentGateway['PaymentGatewaySetting'])) {
            foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                $gateway_settings_options[$paymentGatewaySetting['key']] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
            }
        }
        $gateway_settings_options['is_test_mode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
        $PaypalPlatform->settings($gateway_settings_options);
        $return['error'] = 0;
        if ($transaction_type == ConstPaymentType::ContestPrize || $transaction_type == ConstPaymentType::ContestFee) {
            App::uses('Contests.Contest', 'Model');
            $obj = new Contest();
            $contest = $obj->find('first', array(
                'conditions' => array(
                    'Contest.id' => $foreign_id
                ) ,
                'recursive' => -1
            ));
            if ((!empty($contest) && empty($contest['Contest']['is_paid'])) || ($transaction_type == ConstPaymentType::ContestPrize && $contest['Contest']['contest_status_id'] == ConstContestStatus::PaidToParticipant)) {
                if ($transaction_type == ConstPaymentType::ContestFee) {
                    $data['is_paid'] = 1;
                    $data['id'] = $foreign_id;
                    $obj->save($data, false);
                }
                $receiver_data = $obj->getReceiverdata($foreign_id, $transaction_type, $gateway_settings_options['payee_account']);
            } else {
                $return['error'] = 1;
            }
        }
        if ($transaction_type == ConstPaymentType::SignupFee) {
            App::uses('User', 'Model');
            $obj = new User();
            $receiver_data = $obj->getReceiverdata($foreign_id, $transaction_type, $gateway_settings_options['payee_account']);
        }
        if ($transaction_type == ConstPaymentType::Wallet) {
            App::import('Model', 'Wallet.UserAddWalletAmount');
            $obj = new UserAddWalletAmount();
            $receiver_data = $obj->getReceiverdata($foreign_id, $transaction_type, $gateway_settings_options['payee_account']);
        }
        if (empty($return['error'])) {
            // Request specific required fields
            $actionType = 'PAY';
            $cancelUrl = Cache::read('site_url_for_shell', 'long') . 'paypals/cancel_payment/' . $foreign_id . '/' . $transaction_type;
            $returnUrl = Cache::read('site_url_for_shell', 'long') . 'paypals/success_payment/' . $foreign_id . '/' . $transaction_type;
            $ipnNotificationUrl = Cache::read('site_url_for_shell', 'long') . 'paypals/process_ipn/' . $foreign_id . '/' . $transaction_type;
            $currencyCode = Configure::read('paypal.currency_code');
            $receiverEmailArray = $receiver_data['receiverEmail'];
            $receiverAmountArray = $receiver_data['amount'];
            $preapprovalKey = trim($receiver_data['pre_approval_key']);
            $senderEmail = $receiver_data['senderEmail'];
            $receiverPrimaryArray = $receiver_data['receiverPrimaryArray'];
            $receiverInvoiceIdArray = $receiver_data['receiverInvoiceIdArray'];
            $feesPayer = $this->getFeesPayer($transaction_type);
            $memo = '';
            $pin = '';
            $reverseAllParallelPaymentsOnError = '';
            $trackingId = $PaypalPlatform->generateTrackingID();
            $resArray = $PaypalPlatform->CallPay($actionType, $cancelUrl, $returnUrl, $currencyCode, $receiverEmailArray, $receiverAmountArray, $receiverPrimaryArray, $receiverInvoiceIdArray, $feesPayer, $ipnNotificationUrl, $memo, $pin, $preapprovalKey, $reverseAllParallelPaymentsOnError, $senderEmail, $trackingId);
            $ack = strtoupper($resArray['responseEnvelope.ack']);
            $return['error'] = 0;
            if ($ack == 'SUCCESS') {
                App::import('Model', 'Transaction');
                $this->Transaction = new Transaction();
                if ($transaction_type == ConstPaymentType::ContestFee) {
                    $data['is_paid'] = 1;
                    $this->Transaction->log($foreign_id, 'Contests.Contest', ConstPaymentGateways::PayPal, ConstTransactionTypes::SiteCommisionDeductUsingPaypal);
                }
                if ($transaction_type == ConstPaymentType::ContestPrize) {
                    $this->Transaction->log($foreign_id, 'Contests.Contest', ConstPaymentGateways::PayPal, ConstTransactionTypes::AmountDeductedForCompletedContest);
                    $this->Transaction->log($foreign_id, 'Contests.Contest', ConstPaymentGateways::PayPal, ConstTransactionTypes::ParticipantCommisionDeductUsingPaypal);
                    $this->Transaction->log($foreign_id, 'Contests.Contest', ConstPaymentGateways::PayPal, ConstTransactionTypes::PrizeAmountForCompletedContest);
                }
                $paymentDetails = $this->getPaymentDetails($resArray['payKey']);
                $this->_savePaidLog($foreign_id, $paymentDetails, 'Contest', 1);
                $data['id'] = $foreign_id;
                $data['pay_key'] = $resArray['payKey'];
                $obj->save($data, false);
                if ('' == $preapprovalKey) {
                    $cmd = 'cmd=_ap-payment&paykey=' . urldecode($resArray['payKey']);
                    $this->CustomizePaymentPage($resArray['payKey']);
                    $PaypalPlatform->RedirectToPayPal($cmd);
                } else {
                    $payKey = urldecode($resArray['payKey']);
                    $paymentExecStatus = urldecode($resArray['paymentExecStatus']);
                    $return['success'] = 1;
                    return $return;
                }
            } else {
                if ($transaction_type == ConstPaymentType::ContestFee) {
                    $data['is_paid'] = 0;
                    $data['id'] = $foreign_id;
                    $obj->save($data, false);
                }
                $ErrorMsg = urldecode($resArray['error(0).message']);
                $return['error_message'] = $ErrorMsg;
                $return['error'] = 1;
                return $return;
            }
        }
        return $return;
    }
	public function _gatewayFeeSettings()
    {
        $feesPayer = '';
        if (Configure::read('site.payment_gateway_flow_id') == ConstPaymentGatewayFlow::BuyerSellerSite) {
            if (Configure::read('site.payment_gateway_fee_id') == ConstPaymentGatewayFee::Seller) {
                $feesPayer = 'PRIMARYRECEIVER';
            } else if (Configure::read('site.payment_gateway_fee_id') == ConstPaymentGatewayFee::Site) {
                $feesPayer = 'SECONDARYONLY';
            } else if (Configure::read('site.payment_gateway_fee_id') == ConstPaymentGatewayFee::SiteAndSeller) {
                $feesPayer = 'EACHRECEIVER';
            }
        } else if (Configure::read('site.payment_gateway_flow_id') == ConstPaymentGatewayFlow::BuyerSiteSeller) {
            if (Configure::read('site.payment_gateway_fee_id') == ConstPaymentGatewayFee::Seller) {
                $feesPayer = 'SECONDARYONLY';
            } else if (Configure::read('site.payment_gateway_fee_id') == ConstPaymentGatewayFee::Site) {
                $feesPayer = 'PRIMARYRECEIVER';
            } else if (Configure::read('site.payment_gateway_fee_id') == ConstPaymentGatewayFee::SiteAndSeller) {
                $feesPayer = 'EACHRECEIVER';
            }
        }
        return $feesPayer;
    }
	public function setTransactionCalculation($ticket_fee) {
		$transaction['site_amount'] = round($ticket_fee * Configure::read('site.commission') / 100, 2);
		$transaction['seller_amount'] = round($ticket_fee - $transaction['site_amount'], 2);
        $transaction['amount'] = $ticket_fee;
		return $transaction;
	} 
}
?>