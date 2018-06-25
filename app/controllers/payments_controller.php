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
class PaymentsController extends AppController
{
    public $name = 'Payments';
    public $uses = array(
        'Payment',
        'PaymentGateway',
        'Transaction',
        'Venue',
        'Event',
        'FeaturedVenueSubscription',
        'GuestListUser',
        'EmailTemplate'
    );
    public $helpers = array(
        'Gateway'
    );
    public $components = array(
        'Email',
        'Moneybookers',
        'Paypal',
    );
    public function checkPaymentValidation($data, $slug, $class_name)
    {
        $return = array();
        if ($class_name == 'venue') {
            if ($data['Venue']['is_featured'] || $data['Venue']['is_venue_enhanced_page'] || $data['Venue']['is_bump_up']) {
                $return['error'] = true;
                $return['item_name'] = 'Pay venue feature';
                $amount = 0;
                if (isset($data['Venue']['is_bump_up'])) {
                    $amount = $amount+Configure::read('site.is_venue_bumpup_amount');
                }
                if (isset($data['Venue']['is_venue_enhanced_page'])) {
                    $amount = $amount+Configure::read('site.is_venue_enhanced_amount');
                }
                if (isset($data['Venue']['is_featured'])) {
                    $subscription_id = $data['Venue']['featured_venue_subscription_id'];
                    $featuredVenueSubscription = $this->Venue->FeaturedVenueSubscription->find('first', array(
                        'conditions' => array(
                            'FeaturedVenueSubscription.is_active' => 1,
                            'FeaturedVenueSubscription.id' => $subscription_id
                        ) ,
                        'recursive' => -1
                    ));
                    $amount = $amount+$featuredVenueSubscription['FeaturedVenueSubscription']['amount'];
                } else {
                    $subscription_id = 0;
                }
                $return['amount'] = $amount;
            } else {
                $return['error'] = false;
            }
        } elseif ($class_name == 'event') {
            if ($data['Event']['is_featured'] || $data['Event']['is_bump_up']) {
                $return['item_name'] = 'Pay event feature';
                $return['error'] = true;
                $amount = 0;
                if (isset($data['Event']['is_bump_up'])) {
                    $amount = $amount+Configure::read('site.is_event_bumpup_amount');
                }
                if (isset($data['Event']['is_featured'])) {
                    $amount = $amount+Configure::read('site.is_event_fetured_amount');
                }
                $return['amount'] = $amount;
            } else {
                $return['error'] = false;
            }
        }
        return $return;
    }
    public function pay_now($guest_list_user_id = null)
    {
		if(!empty($this->request->data['GuestListUser']['id'])) {
			$guest_list_user_id = $this->request->data['GuestListUser']['id'];
		}
        $guestListUser = $this->GuestListUser->find('first', array(
            'conditions' => array(
                'GuestListUser.id' => $guest_list_user_id,
            ) ,
            'contain' => array(
                'GuestList' => array(
                    'Event' => array(
                        'Venue',
						'User' => array(
							'UserProfile' => array(
								'fields' => array(
									'UserProfile.paypal_account'
								)
							)
						)
                    )
                )
            ) ,
            'recursive' => 4,
        ));
        if (!empty($this->request->data)) {
            $transaction = $this->Payment->setTransactionCalculation($guestListUser['GuestList']['Event']['ticket_fee']*$guestListUser['GuestListUser']['in_party_count']);
			$paymentGateway = $this->PaymentGateway->find('first', array(
				'conditions' => array(
					'PaymentGateway.id' => ConstPaymentGateways::AdaptivePayPal,
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
			if (empty($paymentGateway)) {
				$this->cakeError('error404');
			}
			if ($paymentGateway['PaymentGateway']['name'] == 'Adaptive PayPal') {
				if (!empty($paymentGateway['PaymentGatewaySetting'])) {
					foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
						$gateway_settings_options[$paymentGatewaySetting['key']] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
					}
				}
				App::import('Vendor', 'adaptive_paypal/paypal_platform');
		        $this->PaypalPlatform = new PaypalPlatform();
				$gateway_settings_options['is_test_mode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
				$this->PaypalPlatform->settings($gateway_settings_options);
				$actionType = "PAY";
				$ipnNotificationUrl = Router::url(array(
					'controller' => 'payments',
					'action' => 'processpayment',
					'adaptivepaypal',
					'GuestListUser',
					$guest_list_user_id,
					'admin' => false
				) , true);
				$cancelUrl = Router::url(array(
					'controller' => 'payments',
					'action' => 'payment_cancel',
					'adaptivepaypal',
					'GuestListUser',
					$guest_list_user_id,
					'admin' => false
				) , true);
				$returnUrl = Router::url(array(
					'controller' => 'payments',
					'action' => 'payment_success',
					'adaptivepaypal',
					'GuestListUser',
					$guest_list_user_id,
					'admin' => false
				) , true);
				$currencyCode = Configure::read('paypal.currency_code');
				if(!empty($transaction['site_amount'])) {
					if (Configure::read('site.payment_gateway_flow_id') == ConstPaymentGatewayFlow::BuyerSellerSite) {
						$receiverEmailArray = array(
							$guestListUser['GuestList']['Event']['User']['UserProfile']['paypal_account'],
							$gateway_settings_options['payee_account']
						);
						$receiverAmountArray = array(
							$transaction['amount'],
							$transaction['site_amount'],
						);							
					} else {
						$receiverEmailArray = array(
							$gateway_settings_options['payee_account'],
							$guestListUser['GuestList']['Event']['User']['UserProfile']['paypal_account']
						);
						$receiverAmountArray = array(
							$transaction['amount'],
							$transaction['seller_amount'],
						);							
					}
					$receiverPrimaryArray = array(
						'true',
						''
					);					
					$receiverInvoiceIdArray = array(
						md5('primary_' . date('YmdHis')) ,
						md5('secondary1_' . date('YmdHis'))
					);  					
					$feesPayer = $this->Payment->_gatewayFeeSettings();
				} else {
					$receiverEmailArray = array(
						$guestListUser['GuestList']['Event']['User']['UserProfile']['paypal_account']
					);
					$receiverAmountArray = array(
						$transaction['seller_amount']
					);
					$receiverPrimaryArray = array();
					$receiverInvoiceIdArray = array(
						md5('ProjectPay_' . date('YmdHis'))
					);
					$feesPayer = 'EACHRECEIVER';
				}     
				$senderEmail = '';						
				$memo = Configure::read('site.name') . ' - ' . $guestListUser['GuestList']['Event']['title'];
				$pin = '';
				$preapprovalKey = '';
				$reverseAllParallelPaymentsOnError = '';
				$trackingId = $this->PaypalPlatform->generateTrackingID();
				// Make the Pay API call
				$resArray = $this->PaypalPlatform->CallPay($actionType, $cancelUrl, $returnUrl, $currencyCode, $receiverEmailArray, $receiverAmountArray, $receiverPrimaryArray, $receiverInvoiceIdArray, $feesPayer, $ipnNotificationUrl, $memo, $pin, $preapprovalKey, $reverseAllParallelPaymentsOnError, $senderEmail, $trackingId);
				$ack = strtoupper($resArray["responseEnvelope.ack"]);
				if ($ack == "SUCCESS") {
					if ('' == $preapprovalKey) {
						// redirect for web approval flow
						$data['GuestListUser']['id'] = $guest_list_user_id;
						$data['GuestListUser']['pay_key'] = $resArray["payKey"];
						$data['GuestListUser']['site_commission'] = $transaction['site_amount'];
						$data['GuestListUser']['amount'] = $transaction['amount'];
						$this->GuestListUser->save($data, false);
						$cmd = "cmd=_ap-payment&paykey=" . urldecode($resArray["payKey"]);
						$this->PaypalPlatform->RedirectToPayPal($cmd);
					} else {
						// the Pay API call was made for an existing preapproval agreement so no approval flow follows
						// payKey is the key that you can use to identify the result from this Pay call
						$payKey = urldecode($resArray["payKey"]);
						// paymentExecStatus is the status of the payment
						$paymentExecStatus = urldecode($resArray["paymentExecStatus"]);
						// note that in order to get the exact status of the transactions resulting from
						// a Pay API call you should make the PaymentDetails API call for the payKey

					}
				} else {
					//Display a user friendly Error on the page using any of the following error information returned by PayPal
					//TODO - There can be more than 1 error, so check for "error(1).errorId", then "error(2).errorId", and so on until you find no more errors.
					$ErrorCode = urldecode($resArray["error(0).errorId"]);
					$ErrorMsg = urldecode($resArray["error(0).message"]);
					$ErrorDomain = urldecode($resArray["error(0).domain"]);
					$ErrorSeverity = urldecode($resArray["error(0).severity"]);
					$ErrorCategory = urldecode($resArray["error(0).category"]);
					$this->Session->setFlash($ErrorMsg . $ErrorSeverity . $ErrorCode . $ErrorDomain . $ErrorCategory, 'default', null, 'error');
				}
			}
        }
        $this->request->data['GuestListUser']['id'] = $guest_list_user_id;
        $this->set('guestListUser', $guestListUser);
    }
    public function order($slug, $class_name)
    {
        $this->pageTitle = __l('Order');
        if (is_null($slug)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            $payment_gateway_id = $this->request->data['Payment']['payment_type_id'];
            $retun_array = $this->checkPaymentValidation($this->request->data, $slug, $class_name);
            if ($retun_array['error']) {
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
                $this->set('gateway_name', $paymentGateway['PaymentGateway']['name']);
                if (empty($paymentGateway)) {
                    throw new NotFoundException(__l('Invalid request'));
                }
                if ($this->request->data['Payment']['payment_type_id'] == ConstPaymentGateways::MoneyBooker) {
                    if (!empty($paymentGateway['PaymentGatewaySetting'])) {
                        foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                            if ($paymentGatewaySetting['key'] == 'seller_id') {
                                Configure::write('merchant_id', $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value']);
                            }
                            if ($paymentGatewaySetting['key'] == 'email') {
                                $this->Moneybookers->payee_account = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                                break;
                            }
                        }
                    }
                    $this->Moneybookers->is_test = $paymentGateway['PaymentGateway']['is_test_mode'];
                    $this->Moneybookers->form_class = 'normal';
                    $to_be_submitted = array(
                        'notify_url' => Router::url(array(
                            'controller' => 'payments',
                            'action' => 'processpayment',
                            'moneybookers',
                            $class_name
                        ) , true) ,
                        'cancel_return' => Router::url(array(
                            'controller' => 'payments',
                            'action' => 'payment_cancel',
                            $payment_gateway_id,
                        ) , true) ,
                        'return' => Router::url(array(
                            'controller' => 'payments',
                            'action' => 'payment_success',
                            $payment_gateway_id,
                        ) , true) ,
                        'account' => $this->Moneybookers->payee_account,
                        'amount' => $retun_array['amount'],
                        'item_name' => $retun_array['item_name'],
                        'user_defined' => array(
                            'user_id' => $this->Auth->user('id') ,
                            'payment_type_id' => $this->request->data['Payment']['payment_type_id'],
                            'ip' => $this->RequestHandler->getClientIP() ,
                            'foreign_id' => $this->request->data['Payment']['id'],
                            'needed_amount' => $retun_array['amount'],
                            'currency_code' => Configure::read('paypal.currency_code')
                        )
                    );
                    $this->set('gateway_options', $this->Moneybookers->moneybookers_form($to_be_submitted));
                } else if ($this->request->data['Payment']['payment_type_id'] == ConstPaymentGateways::PayPal) {
                    Configure::write('paypal.is_testmode', $paymentGateway['PaymentGateway']['is_test_mode']);
                    if (!empty($paymentGateway['PaymentGatewaySetting'])) {
                        foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                            if ($paymentGatewaySetting['key'] == 'payee_account') {
                                Configure::write('paypal.account', $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value']);
                            }
                            if ($paymentGatewaySetting['key'] == 'receiver_emails') {
                                $this->Paypal->paypal_receiver_emails = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                            }
                        }
                    }
                    $cmd = '_xclick';
                    //gateway options set
                    if ($class_name == 'venue') {
                        $user_define = array(
                            'user_id' => $this->Auth->user('id') ,
                            'payment_type_id' => $this->request->data['Payment']['payment_type_id'],
                            'foreign_id' => $this->request->data['Payment']['id'],
                            'is_featured' => (isset($this->request->data['Venue']['is_featured'])) ? $this->request->data['Venue']['is_featured'] : '',
                            'is_venue_enhanced_page' => (isset($this->request->data['Venue']['is_venue_enhanced_page'])) ? $this->request->data['Venue']['is_venue_enhanced_page'] : '',
                            'is_bump_up' => (isset($this->request->data['Venue']['is_bump_up'])) ? $this->request->data['Venue']['is_bump_up'] : '',
                            'featured_venue_subscription_id' => $this->request->data['Venue']['featured_venue_subscription_id'],
                        );
                    } elseif ($class_name == 'event') {
                        $user_define = array(
                            'user_id' => $this->Auth->user('id') ,
                            'payment_type_id' => $this->request->data['Payment']['payment_type_id'],
                            'foreign_id' => $this->request->data['Payment']['id'],
                            'is_featured' => (isset($this->request->data['Event']['is_featured'])) ? $this->request->data['Event']['is_featured'] : '',
                            'is_bump_up' => (isset($this->request->data['Event']['is_bump_up'])) ? $this->request->data['Event']['is_bump_up'] : '',
                        );
                    }
                    $gateway_options = array(
                        'cmd' => $cmd,
                        'notify_url' => Router::url('/', true) . 'payments/processpayment/paypal/' . $class_name,
                        'cancel_return' => Router::url('/', true) . 'payments/payment_cancel/' . $payment_gateway_id,
                        'return' => Router::url('/', true) . 'payments/payment_success/' . $payment_gateway_id,
                        'item_name' => $retun_array['item_name'],
                        'currency_code' => Configure::read('paypal.currency_code') ,
                        'amount' => $retun_array['amount'],
                        'user_defined' => $user_define,
                        'system_defined' => array(
                            'ip' => $this->RequestHandler->getClientIP() ,
                            'amount_needed' => $retun_array['amount'],
                            'currency_code' => Configure::read('paypal.currency_code') ,
                        ) ,
                    );
                    $this->set('gateway_options', $gateway_options);
                }
                $this->set('amount', $retun_array['amount']);
                $this->set('payment_gateway_id', $payment_gateway_id);
                $this->render('do_payment');
            } else {
                $this->Session->setFlash(__l('Please select atleast one premium option.') , 'default', null, 'error');
            }
        }
        $this->set('slug', $slug);
        $this->set('class_name', $class_name);
        if ($class_name == 'venue') {
            $featuredVenueSubscription_lists = $this->Venue->FeaturedVenueSubscription->find('all', array(
                'conditions' => array(
                    'FeaturedVenueSubscription.is_active' => 1
                ) ,
                'recursive' => -1
            ));
            foreach($featuredVenueSubscription_lists as $featuredVenueSubscription_list) {
                if ($featuredVenueSubscription_list['FeaturedVenueSubscription']['name'] < 30) {
                    $name = $featuredVenueSubscription_list['FeaturedVenueSubscription']['name'] . ' Days';
                } else {
                    //floor
                    $name = floor($featuredVenueSubscription_list['FeaturedVenueSubscription']['name']/30);
                    if ($name == 1) {
                        $name.= ' Month';
                    } else {
                        $name.= ' Months';
                    }
                }
                $featuredVenueSubscriptions[$featuredVenueSubscription_list['FeaturedVenueSubscription']['id']] = $name . ' - ' . Configure::read('site.currency') . $featuredVenueSubscription_list['FeaturedVenueSubscription']['amount'];
            }
            $this->set(compact('featuredVenueSubscriptions'));
            $venue = $this->Venue->find('first', array(
                'conditions' => array(
                    'Venue.slug = ' => $slug
                ) ,
                'fields' => array(
                    'Venue.name',
                    'Venue.slug',
                    'Venue.id',
                    'Venue.is_bump_up',
                    'Venue.is_venue_enhanced_page',
                    'Venue.is_featured',
                    'Venue.featured_end_date',
                    'Venue.featured_venue_subscription_id',
                ) ,
                'recursive' => -1,
            ));
            $this->request->data = $venue;
            $this->set('name', $venue['Venue']['name']);
            $this->request->data['Payment']['id'] = $venue['Venue']['id'];
        } elseif ($class_name == 'event') {
            $event = $this->Event->find('first', array(
                'conditions' => array(
                    'Event.slug = ' => $slug
                ) ,
                'fields' => array(
                    'Event.id',
                    'Event.title',
                    'Event.is_bump_up',
                    'Event.is_featured',
                    'Event.slug',
                ) ,
                'recursive' => -1,
            ));
            $this->set('name', $event['Event']['title']);
            $this->request->data = $event;
            $this->request->data['Payment']['id'] = $event['Event']['id'];
        }
        $this->request->data['Payment']['payment_type_id'] = ConstPaymentTypes::PayPal;
        $payment_options = $this->Payment->getPaymentTypes();
        $gateway_options = array();
        $gateway_options['paymentTypes'] = $payment_options;
        $this->set('gateway_options', $gateway_options);
    }
    public function processpayment($gateway_name, $class_name, $foreign_id)
    {
		$this->Payment->_saveIPNLog();
        $gateway['paypal'] = ConstPaymentGateways::PayPal;
        $gateway['moneybookers'] = ConstPaymentGateways::MoneyBooker;
		$gateway['adaptivepaypal'] = ConstPaymentGateways::AdaptivePayPal;
        $gateway_id = (!empty($gateway[$gateway_name])) ? $gateway[$gateway_name] : 0;
        $paymentGateway = $this->PaymentGateway->find('first', array(
            'conditions' => array(
                'PaymentGateway.id' => $gateway_id
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
        switch ($gateway_name) {
            case 'moneybookers':
                $this->Moneybookers->initialize($this);
                if (!empty($paymentGateway['PaymentGatewaySetting'])) {
                    foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                        if ($paymentGatewaySetting['key'] == 'seller_id') {
                            $this->Moneybookers->seller_id = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                        if ($paymentGatewaySetting['key'] == 'email') {
                            $this->Moneybookers->email = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                            break;
                        }
                    }
                }
                $this->Moneybookers->sanitizeServerVars($_POST);
                $this->Moneybookers->is_test_mode = $paymentGateway['PaymentGateway']['is_test_mode'];
                $this->Moneybookers->amount_for_item = !empty($this->Moneybookers->moneybookers_post_arr['amount']) ? $this->Moneybookers->moneybookers_post_arr['amount'] : 0;
                if ($this->Moneybookers->moneybookers_post_arr['status'] == 2) {
                    $id = $this->Moneybookers->moneybookers_post_arr['user_id'];
                    $data['Transaction']['user_id'] = $id;
                    $data['Transaction']['foreign_id'] = $this->Moneybookers->moneybookers_post_arr['foreign_id'];
                    $data['Transaction']['amount'] = $this->Moneybookers->moneybookers_post_arr['amount'];
                    $data['Transaction']['payment_gateway_id'] = $paymentGateway['PaymentGateway']['id'];
                    $data['Transaction']['description'] = 'Payment Success';
                    if ($class_name == 'venue') {
                        $data['Transaction']['class'] = 'Venue';
                        $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::BoughtEnhancementPackageInVenue;
                    } elseif ($class_name == 'event') {
                        $data['Transaction']['class'] = 'Event';
                        $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::BoughtEnhancementPackageInEvent;
                    }
                    $transaction_id = $this->Transaction->log($data);
                    if (!empty($transaction_id)) {
                        $this->Moneybookers->moneybookers_post_arr['transaction_id'] = $transaction_id;
                        if ($class_name == 'venue') {
                            $this->Venue->updateAll(array(
                                'Venue.is_paid' => 1,
                            ) , array(
                                'Venue.id' => $this->Moneybookers->moneybookers_post_arr['foreign_id']
                            ));
                        } elseif ($class_name == 'event') {
                            $this->Event->updateAll(array(
                                'Event.is_paid' => 1,
                            ) , array(
                                'Event.id' => $this->Moneybookers->moneybookers_post_arr['foreign_id']
                            ));
                        }
                        $this->Session->setFlash(__l('Your transaction has successfully completed') , 'default', null, 'success');
                    }
                } else {
                    //place to handle the failure of process
                    $this->pageTitle = __l('Payment Failure');
                    $this->Session->setFlash('Payment Failure', 'default', null, 'error');
                    $this->redirect(array(
                        'controller' => 'transactions',
                        'action' => 'index'
                    ));
                }
                $this->Moneybookers->logMoneybookersTransactions();
                break;

            case 'paypal':
                $this->Paypal->initialize($this);
                if (!empty($paymentGateway['PaymentGatewaySetting'])) {
                    foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                        if ($paymentGatewaySetting['key'] == 'payee_account') {
                            $this->Paypal->payee_account = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                        if ($paymentGatewaySetting['key'] == 'receiver_emails') {
                            $this->Paypal->paypal_receiver_emails = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                        }
                    }
                }
                $this->Paypal->sanitizeServerVars($_POST);
                $this->Paypal->is_test_mode = $paymentGateway['PaymentGateway']['is_test_mode'];
                $this->Paypal->amount_for_item = $this->Paypal->paypal_post_arr['amount_needed'];
                if ($this->Paypal->process()) {
                    //for normal payment through wallet
                    if ($this->Paypal->paypal_post_arr['payment_status'] == 'Completed') {
                        $id = $this->Paypal->paypal_post_arr['user_id'];
                        $data['Transaction']['user_id'] = $id;
                        $data['Transaction']['foreign_id'] = $this->Paypal->paypal_post_arr['foreign_id'];
                        $data['Transaction']['amount'] = $this->Paypal->paypal_post_arr['amount_needed'];
                        $data['Transaction']['payment_gateway_id'] = $paymentGateway['PaymentGateway']['id'];
                        $data['Transaction']['description'] = 'Payment Success';
                        if ($class_name == 'venue') {
                            $data['Transaction']['class'] = 'Venue';
                            $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::BoughtEnhancementPackageInVenue;
                        } elseif ($class_name == 'event') {
                            $data['Transaction']['class'] = 'Event';
                            $data['Transaction']['transaction_type_id'] = ConstTransactionTypes::BoughtEnhancementPackageInEvent;
                        }
                        $transaction_id = $this->Transaction->log($data);
                        if (!empty($transaction_id)) {
                            $this->Paypal->paypal_post_arr['transaction_id'] = $transaction_id;
                            if ($class_name == 'venue') {
                                $updates = array();
                                if ($this->Paypal->paypal_post_arr['is_featured'] == 1) {
                                    $updates['Venue.is_featured'] = 1;
                                    $updates['Venue.featured_venue_subscription_id'] = $this->Paypal->paypal_post_arr['featured_venue_subscription_id'];
                                    $featured = $this->FeaturedVenueSubscription->find('first', array(
                                        'conditions' => array(
                                            'FeaturedVenueSubscription.id' => $this->Paypal->paypal_post_arr['featured_venue_subscription_id']
                                        ) ,
                                        'recursive' => -1
                                    ));
                                    $venue = $this->Venue->find('first', array(
                                        'conditions' => array(
                                            'Venue.id = ' => $this->Paypal->paypal_post_arr['foreign_id']
                                        ) ,
                                        'fields' => array(
                                            'Venue.slug',
                                            'Venue.id',
                                            'Venue.featured_end_date',
                                        ) ,
                                        'recursive' => -1,
                                    ));
                                    if ($venue['Venue']['featured_end_date'] == '0000-00-00') {
                                        $updates['Venue.featured_end_date'] = '\'' . date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") +$featured['FeaturedVenueSubscription']['name'], date("Y"))) . '\'';
                                    } else {
                                        $date = explode('-', $venue['Venue']['featured_end_date']);
                                        $updates['Venue.featured_end_date'] = '\'' . date('Y-m-d', mktime(0, 0, 0, $date[1], $date[2]+$featured['FeaturedVenueSubscription']['name'], $date[0])) . '\'';
                                    }
                                }
                                if ($this->Paypal->paypal_post_arr['is_bump_up'] == 1) {
                                    $updates['Venue.is_bump_up'] = 1;
                                }
                                if ($this->Paypal->paypal_post_arr['is_venue_enhanced_page'] == 1) {
                                    $updates['Venue.is_venue_enhanced_page'] = 1;
                                }
                                $updates['Venue.is_paid'] = 1;
                                $this->Venue->updateAll($updates, array(
                                    'Venue.id' => $this->Paypal->paypal_post_arr['foreign_id']
                                ));
                            } elseif ($class_name == 'event') {
                                if ($this->Paypal->paypal_post_arr['is_bump_up'] == 1) {
                                    $updates['Event.is_bump_up'] = 1;
                                }
                                if ($this->Paypal->paypal_post_arr['is_featured'] == 1) {
                                    $updates['Event.is_featured'] = 1;
                                }
                                $updates['Event.is_paid'] = 1;
                                $this->Event->updateAll($updates, array(
                                    'Event.id' => $this->Paypal->paypal_post_arr['foreign_id']
                                ));
                            }
                            $this->Session->setFlash(__l('Your transaction has successfully completed') , 'default', null, 'success');
                        }
                    } else {
                        $this->pageTitle = __l('Payment Failure');
                        $this->Session->setFlash(__l('Error in payment') , 'default', null, 'error');
                        $this->redirect(array(
                            'controller' => 'transactions',
                            'action' => 'index',
                        ));
                    }
                } else {
                    $this->pageTitle = __l('Payment Failure');
                    $this->Session->setFlash(__l('Error in payment') , 'default', null, 'error');
                    $this->redirect(array(
                        'controller' => 'transactions',
                        'action' => 'index',
                    ));
                }
                $this->Paypal->logPaypalTransactions();
                break;
			case 'adaptivepaypal':
				if($class_name == 'GuestListUser') {
					$guestListUser = $this->GuestListUser->find('first', array(
						'conditions' => array(
							'GuestListUser.id' => $foreign_id,
						) ,
                        'contain' => array(
                            'GuestList' => array(
                                'Event' => array(
                                    'fields' => array(
                                        'Event.id',
                                        'Event.start_date',
                                        'Event.end_date',
                                        'Event.user_id',
                                        'Event.title',
                                        'Event.slug',
                                        'Event.ticket_fee',
                                    ) ,
                                    'Venue' => array(
                                        'City',
                                        'State',
                                        'Country'
                                    )
                                )
                            ),
                        ) ,
                        'recursive' => 4,
					));
                    
					if (empty($guestListUser)) {
			            $this->cakeError('error404');
					}
					$paymentGateway = $this->PaymentGateway->find('first', array(
						'conditions' => array(
							'PaymentGateway.id' => ConstPaymentGateways::AdaptivePayPal
						) ,
						'contain' => array(
							'PaymentGatewaySetting' => array(
								'fields' => array(
									'PaymentGatewaySetting.key',
									'PaymentGatewaySetting.test_mode_value',
									'PaymentGatewaySetting.live_mode_value',
								)
							)
						) ,
						'recursive' => 1
					));
					if (!empty($paymentGateway['PaymentGatewaySetting'])) {
						foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
							$gateway_settings_options[$paymentGatewaySetting['key']] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
						}
					}
					$gateway_settings_options['is_test_mode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
					App::import('Vendor', 'adaptive_paypal/paypal_platform');
    		        $this->PaypalPlatform = new PaypalPlatform();
					$this->PaypalPlatform->settings($gateway_settings_options);
					$payKey = $guestListUser['GuestListUser']['pay_key'];
					$transactionId = '';
					$trackingId = '';
					$paymentDetails = $this->PaypalPlatform->CallPaymentDetails($payKey, $transactionId, $trackingId);
					if (strtoupper($paymentDetails["responseEnvelope.ack"]) == "SUCCESS") {
						$this->Payment->_savePaidLog($foreign_id, $paymentDetails,$class_name,1);	                   
                        if ($paymentDetails['status'] == 'CANCELED' || $paymentDetails['status'] == 'REFUNDED') {
                             $this->request->data['GuestListUser']['is_paid'] = 0;
                             $this->request->data['GuestListUser']['id'] = $foreign_id;
                             $this->GuestListUser->save($this->request->data, false);                             
                        }
                        if ($paymentDetails['status'] == 'COMPLETED' || $paymentDetails['status'] == 'INCOMPLETE') {
                             $this->request->data['GuestListUser']['is_paid'] = 1;
                             $this->request->data['GuestListUser']['id'] = $foreign_id;
                             $this->GuestListUser->save($this->request->data, false);
                            // Send mail
							if(!$guestListUser['GuestListUser']['is_paid'] && $guestListUser['GuestListUser']['rsvp_response_id'] != 2) {
								$email = $this->EmailTemplate->selectTemplate('Guest List SignUp User');
								$this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
								$this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
								$this->Email->to = $this->Auth->user('email');
								$time = strftime(Configure::read('site.time.format') , strtotime($guestListUser['GuestList']['guest_close_time'] . ' GMT'));
								$emailFindReplace = array(
									'##USERNAME##' => $this->Auth->user('username') ,
									'##SITE_NAME##' => Configure::read('site.name') ,
									'##EVENTNAME##' => $guestListUser['GuestList']['Event']['title'],
									'##GUSTLISTDATE##' => date('d/m/Y', strtotime($guestListUser['GuestList']['Event']['start_date'])) ,
									'##TIME##' => $time,
									'##GUESTCOUNT##' => $guestListUser['GuestListUser']['in_party_count'],
									'##SITE_URL##' => Router::url('/', true) ,
									'##VENUEDETAILS##' => $guestListUser['GuestList']['Event']['Venue']['name'] . ', ' . $guestListUser['GuestList']['Event']['Venue']['address'] . ', ' . $guestListUser['GuestList']['Event']['Venue']['City']['name'] . ', ' . $guestListUser['GuestList']['Event']['Venue']['Country']['name']
								);
								$this->Email->subject = strtr($email['subject'], $emailFindReplace);
								$this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
								$this->Email->send(strtr($email['email_content'], $emailFindReplace));
								$total_amount = $guestListUser['GuestList']['Event']['ticket_fee'] * $guestListUser['GuestListUser']['in_party_count'];                            
								$this->Transaction->create();
								$data['Transaction']['user_id'] = $guestListUser['GuestListUser']['user_id'];
								$data['Transaction']['foreign_id'] = $foreign_id;
								$data['Transaction']['class'] = $class_name;
								$data['Transaction']['transaction_type_id'] = ConstTransactionTypes::TicketBooking;
								$data['Transaction']['amount'] = $total_amount;
								$data['Transaction']['site_fee'] = $guestListUser['GuestListUser']['site_commission'];;
								$data['Transaction']['payment_gateway_id'] = $gateway['adaptivepaypal'];                            
								$this->Transaction->save($data);                            
							}
						}						                        
					}
					if($this->request['action'] == 'payment_success') {
						return true;
					}
					$this->autoRender = false;
				}
            default:
                throw new NotFoundException(__l('Invalid request'));
        } // switch
        $this->autoRender = false;
    }
    public function payment_success($gateway_name, $class_name, $foreign_id)
    {
        switch ($gateway_name) {
			case 'adaptivepaypal':
				if($class_name == 'GuestListUser') {
					$guestListUser = $this->GuestListUser->find('first', array(
						'conditions' => array(
							'GuestListUser.id' => $foreign_id,
						) ,
                        'contain' => array(
                            'GuestList' => array(
                                'Event',
                            ),
                        ),
						'recursive' => 2,
					));
                    $this->processpayment($gateway_name, $class_name, $foreign_id);
                    $this->Session->setFlash(__l('Ticket booked successfully') , 'default', null, 'success');
                    $this->redirect(array(
                        'controller' => 'event',
                        'action' => 'view',
                        $guestListUser['GuestList']['Event']['slug']
                    ));
				}
			}            		
    }
    public function payment_cancel($gateway_name, $class_name, $foreign_id)
    {
        switch ($gateway_name) {
			case 'adaptivepaypal':
				if($class_name == 'GuestListUser') {
					$guestListUser = $this->GuestListUser->find('first', array(
						'conditions' => array(
							'GuestListUser.id' => $foreign_id,
						) ,
						'contain' => array(
							'GuestList' => array(
								'Event'
							)
						) ,
						'recursive' => 2,
					));
				}
			$this->Session->setFlash(__l('Payment failure. Please try once again.') , 'default', null, 'error');
			$this->redirect(array(
				'controller' => 'event',
				'action' => 'view',
				$guestListUser['GuestList']['Event']['slug']
			));

		}
    }
}
?>