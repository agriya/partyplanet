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
class PaymentGatewaysController extends AppController
{
    public $name = 'PaymentGateways';
    public function admin_index() 
    {
        $this->pageTitle = __l('Payment Gateways');
        $this->paginate = array(
            'conditions' => array(
                'PaymentGateway.id' => ConstPaymentGateways::AdaptivePayPal,
            ),
            'order' => array(
                'PaymentGateway.id' => 'desc'
            ) ,
            'recursive' => -1
        );
        $this->set('paymentGateways', $this->paginate());
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Payment Gateway');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->PaymentGateway->save($this->request->data)) {
                if (!empty($this->request->data['PaymentGatewaySetting'])) {
                    foreach($this->request->data['PaymentGatewaySetting'] as $key => $value) {
                        $this->PaymentGateway->PaymentGatewaySetting->updateAll(array(
                            'PaymentGatewaySetting.test_mode_value' => '\'' . trim($value['test_mode_value']) . '\'',
                            'PaymentGatewaySetting.live_mode_value' => '\'' . trim($value['live_mode_value']) . '\''
                        ) , array(
                            'PaymentGatewaySetting.id' => $key
                        ));
                    }
                }
                $this->Session->setFlash(__l('Payment Gateway has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Payment Gateway could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->PaymentGateway->read(null, $id);
            unset($this->request->data['PaymentGatewaySetting']);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $paymentGatewaySettings = $this->PaymentGateway->PaymentGatewaySetting->find('all', array(
            'conditions' => array(
                'PaymentGatewaySetting.payment_gateway_id' => $id
            ) ,
            'order' => array(
                'PaymentGatewaySetting.id' => 'asc'
            )
        ));
        if (!empty($this->request->data['PaymentGatewaySetting'])) {
            foreach($paymentGatewaySettings as $key => $paymentGatewaySetting) {
                $paymentGatewaySettings[$key]['PaymentGatewaySetting']['value'] = $this->request->data['PaymentGatewaySetting'][$paymentGatewaySetting['PaymentGatewaySetting']['id']]['value'];
            }
        }
        $this->set(compact('paymentGatewaySettings'));
        $this->pageTitle.= ' - ' . $this->request->data['PaymentGateway']['name'];
    }
	public function admin_paypal_diagnose($verify = null)
    {
		$id = array(3);
        $this->pageTitle = __l('Paypal Diagnose');
        $paymentGatewaySettings = $this->PaymentGateway->PaymentGatewaySetting->find('all', array(
            'conditions' => array(
                'PaymentGatewaySetting.payment_gateway_id' => $id
            ) ,
            'order' => array(
                'PaymentGatewaySetting.id' => 'asc'
            )
        ));
		if (($verify)) {
			$test_mode = $paymentGatewaySettings[0]['PaymentGateway']['is_test_mode'];
			$payee_account = ($test_mode) ? __l($paymentGatewaySettings[0]['PaymentGatewaySetting']['test_mode_value']) : __l($paymentGatewaySettings[0]['PaymentGatewaySetting']['live_mode_value']);
			$email_array[0] = $payee_account;
			$amount_array[0] = 0.01;
            $receiverEmailArray = array($email_array);
            $receiverAmountArray = $amount_array;
            $preapprovalKey = "";
            $senderEmail = $payee_account;
            $receiverPrimaryArray = $email_array;
            $feesPayer = "SENDER";
			$actionType = 'PAY';
			$currencyCode =Configure::read('paypal.currency_code');
			$receiverInvoiceIdArray = array("1");
			$ipnNotificationUrl = Cache::read('site_url_for_shell', 'long') . 'payment_gateways/paypal_diagnose';
			$cancelUrl = Cache::read('site_url_for_shell', 'long') . 'payment_gateways/paypal_diagnose';
			$returnUrl = Cache::read('site_url_for_shell', 'long') . 'payment_gateways/paypal_diagnose';
            $memo = '';
            $pin = '';
            $reverseAllParallelPaymentsOnError = '';
			App::import('Model', 'Payment');
			$this->Payment = new Payment();
			$PaypalPlatform = $this->Payment->_getPaypalPlatformObject();
            $trackingId = $PaypalPlatform->generateTrackingID();
            $resArray = $PaypalPlatform->CallPay($actionType, $cancelUrl, $returnUrl, $currencyCode, $email_array, $receiverAmountArray, $email_array, $receiverInvoiceIdArray, $feesPayer, $ipnNotificationUrl, $memo, $pin, $preapprovalKey, $reverseAllParallelPaymentsOnError, $payee_account, $trackingId);
            $ack = strtoupper($resArray['responseEnvelope.ack']);
            $return['error'] = 0;
            if ($ack == 'SUCCESS') {
				$this->Session->setFlash(__l('PayPal settings look ok.'), 'default', null, 'success');
			} else {
				$error_id = $resArray['error(0).errorId'];
				if($error_id == 579033)
				{
					$this->Session->setFlash(__l('PayPal settings look ok for test mode.'), 'default', null, 'success');
				} else {
					$this->Session->setFlash(__l('Sorry, given PayPal settings returned error.'), 'default', null, 'error');
				}
			}
			$this->redirect(array(
                    'action' => 'admin_paypal_diagnose'
                ));
		}
        $this->set(compact('paymentGatewaySettings'));
    }
}
?>