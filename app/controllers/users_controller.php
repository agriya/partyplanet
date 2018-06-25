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
class UsersController extends AppController
{
    public $name = 'Users';
    public $components = array(
        'Cookie',
        'Email',
        'RequestHandler',
        'OauthConsumer'
    );
    public $uses = array(
        'User',
        'EmailTemplate',
    );
    public $helpers = array(
        'Calendar',
        'Csv'
    );
    public function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'User.makeActive',
            'User.makeInactive',
            'User.makeDelete',
            'User.venue_owner_id',
            'User.referred_by_user_id',
            'City.id',
            'UserProfile.gender_id',
            'UserProfile.country_id',
            'User.send_to_user_id',
            'User.country_id',
            'User.choose',
            'User.gender_id',
            'User.keyword',
            'City.city_id',
            'User.city_id',
        );
        parent::beforeFilter();
    }
    public function view($username = null)
    {
        $this->pageTitle = __l('User');
        if (is_null($username)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.username = ' => $username
            ) ,
            'contain' => array(
                'UserProfile' => array(
                    'fields' => array(
                        'UserProfile.first_name',
                        'UserProfile.last_name',
                        'UserProfile.middle_name',
                        'UserProfile.dob',
                        'UserProfile.about_me',
                        'UserProfile.address',
                        'UserProfile.zip_code',
                        'UserProfile.description',
                        'UserProfile.is_show_month_date'
                    ) ,
                    'City' => array(
                        'fields' => array(
                            'City.name'
                        )
                    ) ,
                    'Country' => array(
                        'fields' => array(
                            'Country.name'
                        )
                    ) ,
                    'Gender' => array(
                        'fields' => array(
                            'Gender.name'
                        )
                    )
                ) ,
                'UserAvatar'
            ) ,
            'recursive' => 2
        ));
        if (empty($user)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $username;
        $this->set('user', $user);
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'user_info') {
            $this->autoRender = false;
            $this->render('user_info');
        } else {
            $this->User->UserView->create();
            $this->request->data['UserView']['user_id'] = $user['User']['id'];
            $this->request->data['UserView']['viewing_user_id'] = $this->Auth->user('id');
            $this->request->data['UserView']['ip_id'] = $this->User->UserView->toSaveIp();;
            $this->User->UserView->save($this->request->data);
            // To set is this user in current user friends lists
            $friend = $this->User->UserFriend->find('first', array(
                'conditions' => array(
                    'UserFriend.user_id' => $this->Auth->user('id') ,
                    'UserFriend.friend_user_id' => $user['User']['id'],
                ) ,
                'recursive' => -1
            ));
            $this->set('friend', $friend);
            $this->request->data['UserComment']['comment_user_id'] = $user['User']['id'];
        }
    }
    public function register($type = NULL)
    {
        $unset_valodation = false;
        $this->pageTitle = __l('User Registration');
        $fbuser = $this->Session->read('fbuser');
        if (!empty($fbuser['fb_user_id'])) {
            $this->request->data['User']['username'] = $fbuser['username'];
            $this->request->data['User']['email'] = '';
            $this->request->data['User']['user_type_id'] = ConstUserTypes::User;
            $this->request->data['User']['fb_user_id'] = $fbuser['fb_user_id'];
            $this->request->data['User']['fb_access_token'] = $fbuser['fb_access_token'];
            $this->request->data['User']['is_facebook_register'] = 1;
            $this->Session->delete('fbuser');
        } else if (empty($this->request->data)) {
            $fb_sess_check = $this->Session->read('fbuser');
            if (Configure::read('facebook.is_enabled_facebook_connect') && !$this->Auth->user('id') && !empty($fb_sess_check)) {
                $this->_facebook_login();
            }
        }
        // Twitter modified registration: Comes for registration from oauth //
        $twuser = $this->Session->read('twuser');
        if (empty($this->request->data)) {
            if (!empty($twuser)) {
                $this->request->data['User']['username'] = $twuser['username'];
                $this->request->data['User']['email'] = '';
                $this->request->data['User']['user_type_id'] = ConstUserTypes::User;
                $this->request->data['User']['is_twitter_register'] = 1;
                $this->request->data['User']['twitter_user_id'] = $twuser['twitter_user_id'];
                $this->request->data['User']['twitter_access_token'] = $twuser['twitter_access_token'];
                $this->request->data['User']['twitter_access_key'] = $twuser['twitter_access_key'];
                if (!empty($twuser['twitter_avatar_url'])) {
                    $this->request->data['User']['twitter_avatar_url'] = $twuser['twitter_avatar_url'];
                }
                $this->request->data['User']['is_twitter_register'] = 1;
                $unset_valodation = true;
                $this->Session->delete('twuser');
            }
        }
        //open id component included
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Openid');
        $this->Openid = new OpenidComponent($collection);
        $openid = $this->Session->read('openid');
        if (!empty($openid['openid_url'])) {
            if (isset($openid['email'])) {
                $this->request->data['User']['email'] = $openid['email'];
                $this->request->data['User']['username'] = $openid['username'];
                $this->request->data['User']['openid_url'] = $openid['openid_url'];
                $openid_connect = true;
                $this->set('openid_connect', $openid_connect);
                if (!empty($openid['is_gmail_register'])) {
                    $this->request->data['User']['is_gmail_register'] = $openid['is_gmail_register'];
                }
                if (!empty($openid['is_yahoo_register'])) {
                    $this->request->data['User']['is_yahoo_register'] = $openid['is_yahoo_register'];
                }
                $this->Session->delete('openid');
            }
        }
        // handle the fields return from openid
        if (count($_GET) > 1) {
            $returnTo = Router::url(array(
                'controller' => 'users',
                'action' => 'register'
            ) , true);
            $response = $this->Openid->getResponse($returnTo);
            if ($response->status == Auth_OpenID_SUCCESS) {
                // Required Fields
                if ($user = $this->User->UserOpenid->find('first', array(
                    'conditions' => array(
                        'UserOpenid.openid' => $response->identity_url
                    )
                ))) {
                    //Already existing user need to do auto login
                    $this->request->data['User']['email'] = $user['User']['email'];
                    $this->request->data['User']['username'] = $user['User']['username'];
                    $this->request->data['User']['password'] = $user['User']['password'];
                    if ($this->Auth->login($this->request->data)) {
                        $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                        if ($this->RequestHandler->isAjax()) {
                            echo 'success';
                            exit;
                        } else {
                            $this->redirect(array(
                                'controller' => 'users',
                                'action' => 'dashboard'
                            ));
                        }
                    } else {
                        $this->Session->setFlash($this->Auth->loginError, 'default', null, 'error');
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'login'
                        ));
                    }
                } else {
                    if (Configure::read('affiliate.is_enabled')) {
                        //user id will be set in cookie
                        $cookie_value = $this->Cookie->read('referrer');
                        if (!empty($cookie_value)) {
                            $this->request->data['User']['referred_by_user_id'] = $cookie_value['refer_id'];
                        }
                    }
                    $sregResponse = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
                    $sreg = $sregResponse->contents();
                    $this->request->data['User']['username'] = isset($sreg['nickname']) ? $sreg['nickname'] : '';
                    $this->request->data['User']['email'] = isset($sreg['email']) ? $sreg['email'] : '';
                    $this->request->data['User']['openid_url'] = $response->identity_url;
                }
            } else {
                $this->Session->setFlash(__l('Authenticated failed or you may not have profile in your OpenID account'));
            }
        }
        // send to openid public function  with open id url and redirect page
        if (!empty($this->request->data['User']['openid']) && preg_match('/^(http|https)?:\/\/+[a-z]/', $this->request->data['User']['openid'])) {
            $this->User->set($this->request->data);
            unset($this->User->validate[Configure::read('user.using_to_login') ]);
            unset($this->User->validate['passwd']);
            unset($this->User->validate['email']);
            if ($this->User->validates()) {
                $this->request->data['User']['redirect_page'] = 'register';
                $this->_openid();
            } else {
                $this->Session->setFlash(__l('Your registration process is not completed. Please, try again.') , 'default', null, 'error');
            }
        } else {
            if (!empty($this->request->data)) {
                $this->User->set($this->request->data);
                if (!empty($this->request->data['UserProfile'])):
                    $this->User->UserProfile->set($this->request->data['UserProfile']);
                endif;
                if (!empty($this->request->data['City'])):
                    $this->User->UserProfile->City->set($this->request->data['City']);
                endif;
				$captcha_error = 0;
				if (Configure::read('system.captcha_type') == "Solve media") {
					if (!$this->User->_isValidCaptchaSolveMedia()) {
						$captcha_error = 1;
					}
				}
                if ($this->User->validates() &$this->User->UserProfile->validates() &$this->User->UserProfile->City->validates()  && empty($captcha_error)) {
                    $this->User->create();
                    if (!empty($this->request->data['User']['openid_url']) or !empty($this->request->data['User']['fb_user_id'])) {
                        $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['email'] . Configure::read('Security.salt'));
                        //For open id register no need for email confirm, this will override is_email_verification_for_register setting
                        $this->request->data['User']['is_agree_terms_conditions'] = 1;
                        $this->request->data['User']['is_email_confirmed'] = 1;
                        if (empty($this->request->data['User']['fb_user_id']) && empty($this->request->data['User']['is_gmail_register']) && empty($this->request->data['User']['is_yahoo_register'])) {
                            $this->request->data['User']['is_openid_register'] = 1;
                        }
                    } elseif (!empty($this->request->data['User']['twitter_user_id'])) { // Twitter modified registration: password  -> twitter user id and salt //
                        $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['twitter_user_id'] . Configure::read('Security.salt'));
                        $this->request->data['User']['is_email_confirmed'] = 1;
                    } else {
                        $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['passwd']);
                        $this->request->data['User']['is_email_confirmed'] = (Configure::read('user.is_email_verification_for_register')) ? 0 : 1;
                    }
                    if (!Configure::read('User.signup_fee')) {
                        $this->request->data['User']['is_active'] = (Configure::read('user.is_admin_activate_after_register')) ? 0 : 1;
                    }
                     $this->request->data['User']['ip_id'] = $this->User->toSaveIp();
                    if ($this->User->save($this->request->data, false)) {
                        $this->request->data['UserProfile']['user_id'] = $this->User->getLastInsertId();
                        if (!empty($this->request->data['User']['country_id'])) {
                            $this->request->data['UserProfile']['country_id'] = $this->User->UserProfile->Country->findCountryId($this->request->data['User']['country_id']);
                        }
                        if (!empty($this->request->data['City']['name'])) {
                            $this->request->data['UserProfile']['city_id'] = $this->request->data['UserProfile']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->User->UserProfile->City->findOrSaveAndGetId($this->request->data['City']['name']);
                        }
                        if (!empty($this->request->data['State']['name'])) {
                            $this->request->data['UserProfile']['state_id'] = $this->request->data['UserProfile']['state_id'] = !empty($this->request->data['State']['id']) ? $this->request->data['State']['id'] : $this->User->UserProfile->State->findOrSaveAndGetId($this->request->data['State']['name']);
                        } else {
                            $this->request->data['UserProfile']['state_id'] = 0;
                        }
                        $this->User->UserProfile->create();
                        $this->User->UserProfile->save($this->request->data['UserProfile'], false);
                        // send to admin mail if is_admin_mail_after_register is true
                        if (Configure::read('user.is_admin_mail_after_register')) {
                            $emailFindReplace = array(
                                '##USERNAME##' => $this->request->data['User']['username'],
                                '##SITE_NAME##' => Configure::read('site.name') ,
                                '##SITE_URL##' => Router::url('/', true) ,
                                '##USEREMAIL##' => $this->request->data['User']['email'],
                                '##SIGNUPIP##' => $this->RequestHandler->getClientIP(),
                            );
                            $email = $this->EmailTemplate->selectTemplate('New User Join');
                            // Send e-mail to users
                            $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
                            $this->Email->to = Configure::read('site.contact_email');
                            $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                            $this->Email->send(strtr($email['email_content'], $emailFindReplace));
                        }
                        if (!empty($this->request->data['User']['openid_url'])) {
                            $this->request->data['UserOpenid']['openid'] = $this->request->data['User']['openid_url'];
                            $this->request->data['UserOpenid']['user_id'] = $this->User->id;
                            $this->User->UserOpenid->create();
                            $this->User->UserOpenid->save($this->request->data);
                        }
                        $this->Session->setFlash(__l('You have successfully registered with our site.') , 'default', null, 'success');
                        if (!empty($this->request->data['User']['openid_url']) || !empty($this->request->data['User']['fb_user_id']) || !empty($this->request->data['User']['twitter_user_id'])) {
                            // send welcome mail to user if is_welcome_mail_after_register is true
                            if (Configure::read('user.is_welcome_mail_after_register')) {
                                $this->_sendWelcomeMail($this->User->id, $this->request->data['User']['email'], $this->request->data['User']['username']);
                            }
                            if (!Configure::read('user.is_admin_activate_after_register')) {
                                $this->Session->setFlash(__l('You have successfully registered with our site.') , 'default', null, 'success');
                            } else {
                                $this->Session->setFlash(__l('You have successfully registered with our site. But you can login after admin approval.') , 'default', null, 'success');
                            }
                            if ($this->Auth->login($this->request->data)) {
                                $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                                $this->redirect(Router::url('/', true));
                            }
                        } else {
                            //For openid register no need to send the activation mail, so this code placed in the else
                            if (Configure::read('user.is_email_verification_for_register')) {
                                $this->Session->setFlash(__l('You have successfully registered with our site and your activation mail has been sent to your mail inbox.') , 'default', null, 'success');
                                $this->_sendActivationMail($this->request->data['User']['email'], $this->User->id, $this->User->getActivateHash($this->User->id));
                            }
                        }
                        // send welcome mail to user if is_welcome_mail_after_register is true
                        if (!Configure::read('user.is_email_verification_for_register') and !Configure::read('user.is_admin_activate_after_register') and Configure::read('user.is_welcome_mail_after_register')) {
                            $this->_sendWelcomeMail($this->User->id, $this->request->data['User']['email'], $this->request->data['User']['username']);
                        }
                        if (!Configure::read('user.is_email_verification_for_register') and Configure::read('user.is_auto_login_after_register')) {
                            $this->Session->setFlash(__l('You have successfully registered with our site.') , 'default', null, 'success');
                            if ($this->Auth->login($this->request->data)) {
                                $cookie_value = $this->Cookie->read('referrer');
                                if (!empty($cookie_value) && (!Configure::read('affiliate.is_enabled'))) {
                                    $this->Cookie->delete('referrer'); // Delete referer cookie

                                }
                                $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                            }
                        } else {
                            $this->redirect(array(
                                'controller' => 'users',
                                'action' => 'login'
                            ));
                        }
                    }
                } else {
					if(!empty($captcha_error)) {
						$this->User->validationErrors['captcha'] = __l('Required');
					}
                    if (empty($this->request->data['User']['openid_url']) && empty($this->request->data['User']['twitter_user_id'])) {
                        $this->Session->setFlash(__l('Your registration process is not completed. Please, try again.') , 'default', null, 'error');
                    } elseif (!empty($this->request->data['User']['openid_url'])) {
                        if (!empty($this->request->data['User']['is_gmail_register'])) {
                            $flash_verfy = 'Gmail';
                        } elseif (!empty($this->request->data['User']['is_yahoo_register'])) {
                            $flash_verfy = 'Yahoo';
                        } else {
                            $flash_verfy = 'OpenID';
                        }
                        $this->Session->setFlash($flash_verfy . ' ' . __l('verification is completed successfully. But you have to fill the following required fields to complete our registration process.') , 'default', null, 'success');
                    }
                }
            }
        }
        if (!empty($this->request->params['named']['type'])) {
            $this->request->data['User']['user_type_id'] = $this->request->params['named']['type'];
        } else {
            $this->request->data['User']['user_type_id'] = ConstUserTypes::User;
        }
        unset($this->request->data['User']['passwd']);
        unset($this->request->data['User']['captcha']);
        // When already logged user trying to access the registration page we are redirecting to site home page
        if ($this->Auth->user('id')) {
            $this->redirect(Router::url('/', true));
        }
        if ($unset_valodation) {
            unset($this->User->validationErrors);
        }
        $genders = $this->User->UserProfile->Gender->find('list');
        $countries = $this->User->UserProfile->Country->find('list');
        $cities = $this->User->UserProfile->City->find('list');
        $this->set(compact('countries', 'cities', 'genders'));        
        unset($this->User->UserProfile->validate['dob']);
    }
    public function _facebook_login()
    {
        $me = $this->Session->read('fbuser');
        if (empty($me)) {
            $this->Session->setFlash(__l('Problem in Facebook connect. Please try again') , 'default', null, 'error');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'login'
            ));
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.fb_user_id' => $me['id']
            ) ,
            'fields' => array(
                'User.id',
                'User.email',
                'User.username',
                'User.password',
                'User.fb_user_id',
                'User.is_active',
            ) ,
        ));
        $this->Auth->fields['username'] = 'username';
        //create new user
        if (empty($user)) {
            $checkFacebookEmail = $this->User->find('first', array(
                'conditions' => array(
                    'User.email' => $me['email']
                ) ,
                'fields' => array(
                    'User.id',
                    'User.email',
                    'User.username',
                    'User.password',
                    'User.fb_user_id',
                    'User.is_active',
                ) ,
                'recursive' => -1
            ));
            if (!empty($checkFacebookEmail)) {
                $this->Session->delete('fbuser');
                if (empty($checkFacebookEmail['User']['is_active'])) {
                    $this->Session->setFlash($this->Auth->loginError, 'default', null, 'error');
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'login',
                        'admin' => false
                    ));
                }
                $_data['User']['username'] = $checkFacebookEmail['User']['username'];
                $_data['User']['email'] = $checkFacebookEmail['User']['email'];
                $_data['User']['password'] = $checkFacebookEmail['User']['password'];
                if ($this->Auth->login($_data)) {
                    $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                    if ($redirectUrl = $this->Session->read('Auth.redirectUrl')) {
                        $this->Session->delete('Auth.redirectUrl');
                        $this->redirect(Router::url('/', true) . $redirectUrl);
                    } else {
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'dashboard',
                        ));
                    }
                }
            }
            $this->User->create();
            $this->request->data['UserProfile']['first_name'] = !empty($me['first_name']) ? $me['first_name'] : '';
            $this->request->data['UserProfile']['middle_name'] = !empty($me['middle_name']) ? $me['middle_name'] : '';
            $this->request->data['UserProfile']['last_name'] = !empty($me['last_name']) ? $me['last_name'] : '';
            $this->request->data['UserProfile']['about_me'] = !empty($me['bio']) ? $me['bio'] : '';
            if (!empty($me['birthday'])) {
                $dob = explode('/', $me['birthday']);
                $this->request->data['UserProfile']['dob'] = $dob[2] . '-' . $dob[0] . '-' . $dob[1];
            }
            if ($me['gender'] == 'male') {
                $this->request->data['UserProfile']['gender_id'] = 1;
            } elseif ($me['gender'] == 'female') {
                $this->request->data['UserProfile']['gender_id'] = 2;
            } else {
                $this->request->data['UserProfile']['gender_id'] = 0;
            }
            if (empty($this->request->data['User']['username']) && strlen($me['first_name']) > 2) {
                $this->request->data['User']['username'] = $this->User->checkUsernameAvailable(strtolower($me['first_name']));
            }
            if (empty($this->request->data['User']['username']) && strlen($me['first_name'] . $me['last_name']) > 2) {
                $this->request->data['User']['username'] = $this->User->checkUsernameAvailable(strtolower($me['first_name'] . $me['last_name']));
            }
            if (empty($this->request->data['User']['username']) && strlen($me['first_name'] . $me['middle_name'] . $me['last_name']) > 2) {
                $this->request->data['User']['username'] = $this->User->checkUsernameAvailable(strtolower($me['first_name'] . $me['middle_name'] . $me['last_name']));
            }
            $this->request->data['User']['username'] = str_replace(' ', '', $this->request->data['User']['username']);
            $this->request->data['User']['username'] = str_replace('.', '_', $this->request->data['User']['username']);
            // A condtion to avoid unavilability of user username in our sites
            if (strlen($this->request->data['User']['username']) <= 2) {
                $this->request->data['User']['username'] = !empty($me['first_name']) ? str_replace(' ', '', strtolower($me['first_name'])) : 'fbuser';
                $i = 1;
                $created_user_name = $this->request->data['User']['username'] . $i;
                while (!$this->User->checkUsernameAvailable($created_user_name)) {
                    $created_user_name = $this->request->data['User']['username'] . $i++;
                }
                $this->request->data['User']['username'] = $created_user_name;
            }
            $this->request->data['User']['email'] = !empty($me['email']) ? $me['email'] : '';
            if (!empty($this->request->data['User']['email'])) {
                $check_user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.email' => $this->request->data['User']['email']
                    ) ,
                    'recursive' => -1
                ));
            }
            if (!empty($check_user['User']['email'])) {
                unset($this->request->data['User']['email']);
            }
            $this->request->data['User']['fb_user_id'] = $me['id'];
            $this->request->data['User']['fb_access_token'] = $me['access_token'];
            $this->request->data['User']['is_facebook_register'] = 1;
            $this->request->data['User']['password'] = $this->Auth->password($me['id'] . Configure::read('Security.salt'));
            $this->request->data['User']['is_agree_terms_conditions'] = '1';
            $this->request->data['User']['is_email_confirmed'] = 1;
            $this->request->data['User']['is_active'] = 1;
            $this->request->data['User']['user_type_id'] = ConstUserTypes::User;
            $this->request->data['User']['ip_id'] = $this->User->toSaveIp();
            if (Configure::read('user.is_referral_system_enabled')) {
                //user id will be set in cookie
                $cookie_value = $this->Cookie->read('referrer');
                if (!empty($cookie_value)) {
                    $this->request->data['User']['referred_by_user_id'] = $cookie_value;
                }
            }
            if ($this->Session->read('gift_user_id')) {
                $this->request->data['User']['gift_user_id'] = $this->Session->read('gift_user_id');
                $this->Session->delete('gift_user_id');
            }
            //for user referral system
            if (empty($this->request->data) && Configure::read('referral.referral_enable') && (Configure::read('referral.referral_enabled_option') == ConstReferralOption::GrouponLikeRefer)) {
                //user id will be set in cookie
                $cookie_value = $this->Cookie->read('referrer');
                if (!empty($cookie_value)) {
                    $this->request->data['User']['referred_by_user_id'] = $cookie_value['refer_id']; // Affiliate Changes //

                }
            }
            //end
            $this->User->save($this->request->data, false);
            $cookie_value = $this->Cookie->read('referrer');
            if (!empty($cookie_value) && (!Configure::read('affiliate.is_enabled'))) {
                $this->Cookie->delete('referrer'); // Delete referer cookie

            }
            $this->request->data['UserProfile']['user_id'] = $this->User->id;
            $this->User->UserProfile->save($this->request->data);
            if ($this->Auth->login($this->request->data)) {
                $this->Session->setFlash(__l('You have successfully registered with our site.') , 'default', null, 'success');
                if (Configure::read('user.is_welcome_mail_after_register')) {
                    $this->_sendWelcomeMail($this->User->id, $this->request->data['User']['email'], $this->request->data['User']['username']);
                }
                if (Configure::read('user.is_admin_mail_after_register')) {
                    $emailFindReplace = array(
                        '##USERNAME##' => $this->request->data['User']['username'],
                        '##SITE_NAME##' => Configure::read('site.name') ,
                        '##SITE_URL##' => Router::url('/', true) ,
                        '##USEREMAIL##' => $this->request->data['User']['email'],
                        '##SIGNUPIP##' => $this->request->data['User']['signup_ip'],
                    );
                    $email = $this->EmailTemplate->selectTemplate('New User Join');
                    // Send e-mail to users
                    $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
                    $this->Email->to = Configure::read('site.contact_email');
                    $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                    $this->Email->send(strtr($email['email_content'], $emailFindReplace));
                }
                if ($redirectUrl = $this->Session->read('Auth.redirectUrl')) {
                    $this->Session->delete('Auth.redirectUrl');
                    $this->redirect(Router::url('/', true) . $redirectUrl);
                } else {
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'dashboard'
                    ));
                }
            }
        } else {
            if (!$user['User']['is_active']) {
                $this->Session->setFlash(__l('Sorry, login failed.  Your account has been blocked') , 'default', null, 'error');
                $this->redirect(Router::url('/', true));
            }
            $this->request->data['User']['fb_user_id'] = $me['id'];
            $this->User->updateAll(array(
                'User.fb_access_token' => '\'' . $me['access_token'] . '\'',
                'User.fb_user_id' => '\'' . $me['id'] . '\'',
            ) , array(
                'User.id' => $user['User']['id']
            ));
            $this->request->data['User']['email'] = $user['User']['email'];
            $this->request->data['User']['username'] = $user['User']['username'];
            $this->request->data['User']['password'] = $user['User']['password'];
            if ($this->Auth->login($this->request->data)) {
                $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                if ($redirectUrl = $this->Session->read('Auth.redirectUrl')) {
                    $this->Session->delete('Auth.redirectUrl');
                    $this->redirect(Router::url('/', true) . $redirectUrl);
                } else {
                    $this->redirect(Router::url('/', true));
                }
            }
        }
    }
    public function _openid()
    {
        //open id component included
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Openid');
        $this->Openid = new OpenidComponent($collection);
        $returnTo = Router::url(array(
            'controller' => 'users',
            'action' => $this->request->data['User']['redirect_page']
        ) , true);
        $siteURL = Router::url('/', true);
        // send openid url and fields return to our server from openid
        if (!empty($this->request->data)) {
            try {
                $this->Openid->authenticate($this->request->data['User']['openid'], $returnTo, $siteURL, array(
                    'sreg_required' => array(
                        'email',
                        'nickname'
                    )
                ) , array());
            }
            catch(InvalidArgumentException $e) {
                $this->Session->setFlash(__l('Invalid OpenID') , 'default', null, 'error');
            }
            catch(Exception $e) {
                $this->Session->setFlash($e->getMessage());
            }
        }
    }
    public function oauth_callback()
    {
        $this->autoRender = false;
        App::import('Xml');
        // Fix to avoid the mail validtion for  Twitter
        $this->Auth->fields['username'] = 'username';
        $requestToken = $this->Session->read('requestToken');
        $requestToken = unserialize($requestToken);
        $accessToken = $this->OauthConsumer->getAccessToken('Twitter', 'https://twitter.com/oauth/access_token', $requestToken);
        $this->Session->write('accessToken', $accessToken);
        if (empty($accessToken->key) && empty($accessToken->secret) && !($this->Auth->user('id'))) {
            $this->Session->setFlash(__l('Problem in Twitter connect. Please try again') , 'default', null, 'error');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'login'
            ));
        }
        $oauth_json = $this->OauthConsumer->get('Twitter', $accessToken->key, $accessToken->secret, 'https://api.twitter.com/1.1/account/verify_credentials.json');
        $this->request->data['User']['twitter_access_token'] = (isset($accessToken->key)) ? $accessToken->key : '';
        $this->request->data['User']['twitter_access_key'] = (isset($accessToken->secret)) ? $accessToken->secret : '';
		$data['User'] = get_object_vars(json_decode($oauth_json->body));
        // So this to check whether it is  admin login to get its twiiter acces tocken
        if ($this->Auth->user('id') and $this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $this->loadModel('Setting');
            $this->Setting->updateAll(array(
                'Setting.value' => "'" . $this->request->data['User']['twitter_access_token'] . "'",
            ) , array(
                'Setting.name' => 'twitter.site_user_access_token'
            ));
            $this->Setting->updateAll(array(
                'Setting.value' => "'" . $this->request->data['User']['twitter_access_key'] . "'"
            ) , array(
                'Setting.name' => 'twitter.site_user_access_key'
            ));
            $this->Session->setFlash(__l('Your Twitter credentials are updated') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'settings',
                'admin' => true
            ));
        }
        if ($this->Auth->user('id') && $this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
            $user_id = $this->Session->read('auth_user_id');
            if (!empty($data['User']['profile_image_url'])) {
                $this->request->data['User']['twitter_avatar_url'] = $data['User']['profile_image_url'];
            }
            $this->request->data['twitter_access_token'] = (isset($accessToken->key)) ? $accessToken->key : '';
            $this->request->data['twitter_access_key'] = (isset($accessToken->secret)) ? $accessToken->secret : '';
            $this->request->data['User']['id'] = $this->Auth->user('id');
            $this->User->save($this->request->data, false);
            $this->Session->delete('auth_user_id');
            $this->Session->setFlash(__l('You have successfully connected with twitter.') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'dashboard',
                'admin' => false,
            ));
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.twitter_user_id =' => $data['User']['id']
            ) ,
            'fields' => array(
                'User.id',
                'UserProfile.id',
                'User.user_type_id',
                'User.username',
                'User.email',
            ) ,
            'recursive' => 0
        ));
        if (empty($user)) {
            $temp['first_name'] = empty($data['User']['name']) ? $data['User']['name'] : '';
            $temp['last_name'] = empty($data['User']['name']) ? $data['User']['name'] : '';
            if (empty($temp['username']) && strlen($data['User']['name']) > 2) {
                $temp['username'] = $this->User->checkUsernameAvailable(strtolower($data['User']['name']));
            }
            if (empty($temp['username']) && strlen($data['User']['name'] . $data['User']['screen_name']) < 2) {
                $temp['username'] = $this->User->checkUsernameAvailable(strtolower($data['User']['name'] . $data['User']['screen_name']));
            }
            $temp['twitter_avatar_url'] = !empty($data['User']['profile_image_url']) ? $data['User']['profile_image_url'] : '';
            $temp['twitter_user_id'] = !empty($data['User']['id']) ? $data['User']['id'] : '';
            $temp['twitter_access_token'] = (!empty($accessToken->key)) ? $accessToken->key : '';
            $temp['twitter_access_key'] = (!empty($accessToken->secret)) ? $accessToken->secret : '';
            $this->Session->write('twuser', $temp);
            $this->Session->setFlash(__l('Twitter verification is completed successfully. But you have to fill the following required fields to complete our registration process.') , 'default', null, 'success');
            unset($this->User->validationErrors);
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'register'
            ));
        } else {
            $this->request->data['User']['id'] = $user['User']['id'];
            $this->request->data['User']['username'] = $user['User']['username'];
        }
        unset($this->User->validate['username']['rule2']);
        unset($this->User->validate['username']['rule3']);
        $this->request->data['User']['password'] = $this->Auth->password($data['User']['id'] . Configure::read('Security.salt'));
        $this->request->data['User']['twitter_avatar_url'] = $data['User']['profile_image_url'];
        $this->request->data['User']['twitter_url'] = (isset($data['User']['url'])) ? $data['User']['url'] : '';
        $this->request->data['User']['description'] = (isset($data['User']['description'])) ? $data['User']['description'] : '';
        $this->request->data['User']['location'] = (isset($data['User']['location'])) ? $data['User']['location'] : '';
        if (Configure::read('invite.is_referral_system_enabled')) {
            //user id will be set in cookie
            $cookie_value = $this->Cookie->read('referrer');
            if (!empty($cookie_value)) {
                $this->request->data['User']['referred_by_user_id'] = $cookie_value;
            }
        }
        if ($this->User->save($this->request->data, false)) {
            $cookie_value = $this->Cookie->read('referrer');
            if (!empty($cookie_value)) {
                $this->Cookie->delete('referrer'); // Delete referer cookie

            }
            if ($this->Auth->login($this->request->data)) {
                $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                $this->redirect(Router::url('/', true));
            }
        }
        if (!empty($this->request->data['User']['f'])) {
            $this->redirect(Router::url('/', true) . $this->request->data['User']['f']);
        }
        $this->redirect(Router::url('/', true));
    }
    public function _sendActivationMail($user_email, $user_id, $hash)
    {
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.email' => $user_email
            ) ,
            'recursive' => -1
        ));
        $emailFindReplace = array(
            '##USERNAME##' => $user['User']['username'],
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##ACTIVATION_URL##' => Router::url(array(
                'controller' => 'users',
                'action' => 'activation',
                $user_id,
                $hash
            ) , true) ,
            '##SITE_URL##' => Router::url('/', true) ,
        );
        $email = $this->EmailTemplate->selectTemplate('Activation Request');
        $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
        $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
        $this->Email->to = $user_email;
        $this->Email->subject = strtr($email['subject'], $emailFindReplace);
        $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
        if ($this->Email->send(strtr($email['email_content'], $emailFindReplace))) {
            return true;
        }
    }
    public function _sendWelcomeMail($user_id, $user_email, $username)
    {
        $emailFindReplace = array(
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##USERNAME##' => $username,
            '##SUPPORT_EMAIL##' => Configure::read('site.contact_email') ,
            '##SITE_URL##' => Router::url('/', true)
        );
        $email = $this->EmailTemplate->selectTemplate('Welcome Email');
        $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
        $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
        $this->Email->to = $user_email;
        $this->Email->subject = strtr($email['subject'], $emailFindReplace);
        $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
        $this->Email->send(strtr($email['email_content'], $emailFindReplace));
    }
    public function activation($user_id = null, $hash = null)
    {
        $this->pageTitle = __l('Activate your account');
        if (is_null($user_id) or is_null($hash)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id,
                // 'User.is_email_confirmed' => 0

            ) ,
            'recursive' => -1
        ));
        if (empty($user)) {
            $this->Session->setFlash(__l('Invalid activation request, please register again'));
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'register'
            ));
        }
        if (!$this->User->isValidActivateHash($user_id, $hash)) {
            $hash = $this->User->getActivateHash($user_id);
            $this->Session->setFlash(__l('Invalid activation request'));
            $this->set('show_resend', 1);
            $resend_url = Router::url(array(
                'controller' => 'users',
                'action' => 'resend_activation',
                $user_id,
                $hash
            ) , true);
            $this->set('resend_url', $resend_url);
        } else {
            $this->request->data['User']['id'] = $user_id;
            $this->request->data['User']['is_email_confirmed'] = 1;
            // admin will activate the user condition check
            $this->request->data['User']['is_active'] = (Configure::read('user.is_admin_activate_after_register')) ? 0 : 1;
            $this->User->save($this->request->data);
            // active is false means redirect to home page with message
            if (!$this->request->data['User']['is_active']) {
                $this->Session->setFlash(__l('You have successfully activated your account. But you can login after admin approval.') , 'default', null, 'success');
                $this->redirect(Router::url('/', true));
            }
            // send welcome mail to user if is_welcome_mail_after_register is true
            if (Configure::read('user.is_welcome_mail_after_register')) {
                $this->_sendWelcomeMail($user['User']['id'], $user['User']['email'], $user['User']['username']);
            }
            // after the user activation check script check the auto login value. it is true then automatically logged in
            if (Configure::read('user.is_auto_login_after_register')) {
                $this->Session->setFlash(__l('You have successfully activated and logged in to your account.') , 'default', null, 'success');
                $this->request->data['User']['email'] = $user['User']['email'];
                $this->request->data['User']['username'] = $user['User']['username'];
                $this->request->data['User']['password'] = $user['User']['password'];
                if ($this->Auth->login($this->request->data)) {
                    $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                    $this->redirect(Router::url('/', true));
                }
            }
            // user is active but auto login is false then the user will redirect to login page with message
            $this->Session->setFlash(__l(sprintf('You have successfully activated your account. Now you can login with your %s.', Configure::read('user.using_to_login'))) , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'login'
            ));
        }
    }
    public function resend_activation($user_id = null, $hash = null)
    {
        if (is_null($user_id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $hash = $this->User->getActivateHash($user_id);
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
            ) ,
            'recursive' => -1
        ));
        if ($this->_sendActivationMail($user['User']['email'], $user_id, $hash)) {
            if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                $this->Session->setFlash(__l('Activation mail has been resent.') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('A Mail for activating your account has been sent.'));
            }
        } else {
            $this->Session->setFlash(__l('Try some time later as mail could not be dispatched due to some error in the server'));
        }
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'index',
                'admin' => true
            ));
        } else {
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'login'
            ));
        }
    }
    public function index()
    {
        if (!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'search') && !empty($this->request->data)) {
            $_SESSION['search'] = $this->request->data;
        } elseif (empty($this->request->params['named']['type']) or ($this->request->params['named']['type'] != 'search')) {
            unset($_SESSION['search']);
        }
		if (!empty($this->request->data['User']['city_id'])) {
			$this->request->data['City']['city_id'] = $this->request->data['User']['city_id'];
		}
        $this->pageTitle = Configure::read('site.name') . ' ' . __l("Users");
        if (!empty($_SESSION['search']) and empty($this->request->data)) {
            $this->request->data = $_SESSION['search'];
            $chooseval = $_SESSION['search']['User']['choose'];
            $this->set('chooseval', $chooseval);
        }
        $conditions = array();
        $conditions['User.user_type_id != '] = ConstUserTypes::Admin;
        $conditions['User.id != '] = $this->Auth->user('id');
        $conditions['User.is_active'] = 1;
        $conditions['User.is_email_confirmed'] = 1;
        if (!empty($this->request->data)) {
            if ($this->request->data['User']['choose'] == 1) {
                unset($this->request->data['User']['country_id']);
                unset($this->request->data['City']);
                unset($_SESSION['search']['User']['country_id']);
                unset($_SESSION['search']['City']);
            }
            if (!empty($this->request->data['User']['keyword'])) {
                $conditions['OR']['User.email like'] = '%' . $this->request->data['User']['keyword'] . '%';
                $conditions['OR']['User.username like'] = '%' . $this->request->data['User']['keyword'] . '%';
                $conditions['OR']['UserProfile.last_name like'] = '%' . $this->request->data['User']['keyword'] . '%';
                $conditions['OR']['UserProfile.first_name like'] = '%' . $this->request->data['User']['keyword'] . '%';
            }
            if (!empty($this->request->data['User']['country_id'])) {
                $conditions['UserProfile.country_id'] = $this->request->data['User']['country_id'];
            }
            if (!empty($this->request->data['City']['city_id'])) {
                $conditions['UserProfile.city_id'] = $this->request->data['City']['city_id'];
                $this->request->data['User']['city_id'] = $this->request->data['City']['city_id'];
            }
            if (!empty($this->request->data['User']['first_name'])) {
                $conditions['UserProfile.first_name like'] = '%' . $this->request->data['User']['first_name'] . '%';
            }
            if (!empty($this->request->data['User']['last_name'])) {
                $conditions['UserProfile.last_name like'] = '%' . $this->request->data['User']['last_name'] . '%';
            }
            if (!empty($this->request->data['User']['gender_id'])) {
                $conditions['UserProfile.gender_id'] = $this->request->data['User']['gender_id'];
            }
            if (!empty($this->request->data['User']['marital_status_id'])) {
                $conditions['UserProfile.marital_status_id'] = $this->request->data['User']['marital_status_id'];
            }
            if (!empty($this->request->data['User']['ethnicity_id'])) {
                $conditions['UserProfile.ethnicity_id'] = $this->request->data['User']['ethnicity_id'];
            }
            if (!empty($this->request->data['User']['sexual_orientation_id'])) {
                $conditions['UserProfile.sexual_orientation_id'] = $this->request->data['User']['sexual_orientation_id'];
            }
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'UserProfile' => array(
                    'City' => array(
                        'fields' => array(
                            'City.name'
                        )
                    ) ,
                    'Country' => array(
                        'fields' => array(
                            'Country.name'
                        )
                    ) ,
                    'Gender' => array(
                        'fields' => array(
                            'Gender.name'
                        )
                    )
                ) ,
                'UserAvatar' => array(
                    'fields' => array(
                        'UserAvatar.dir',
                        'UserAvatar.filename',
                        'UserAvatar.id'
                    )
                )
            ) ,
            'order' => 'User.id desc',
            'recursive' => 2,
            'limit' => 18,
        );
        $this->set('users', $this->paginate());
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'home') {
            $this->autoRender = false;
            $this->render('user_home');
        }
    }
    public function oauth_facebook()
    {
        App::import('Vendor', 'facebook/facebook');
        $this->facebook = new Facebook(array(
            'appId' => Configure::read('facebook.app_id') ,
            'secret' => Configure::read('facebook.fb_secrect_key') ,
            'cookie' => true
        ));
        $this->autoRender = false;
        if (!empty($_REQUEST['code'])) {
            $tokens = $this->facebook->setAccessToken(array(
                'redirect_uri' => Router::url(array(
                    'controller' => 'users',
                    'action' => 'oauth_facebook',
                    'admin' => false
                ) , true) ,
                'code' => $_REQUEST['code']
            ));
            $this->Session->write('fbuser', $tokens);
            $fb_return_url = $this->Session->read('fb_return_url');
            if (!empty($fb_return_url)) {
                $this->redirect($fb_return_url);
            } else {
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'register',
                    'admin' => false
                ));
            }
        } else {
            $this->Session->setFlash(__l('Invalid Facebook Connection.') , 'default', null, 'error');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'login'
            ));
        }
        exit;
    }
    public function login()
    {
        //need to remove once cron set
        $fb_sess_check = $this->Session->read('fbuser');
        if (empty($this->request->data) and Configure::read('facebook.is_enabled_facebook_connect') && !$this->Auth->user('id') && !empty($fb_sess_check) && !$this->Session->check('is_fab_session_cleared')) {
            $this->_facebook_login();
        }
        $this->pageTitle = __l('Login');
        // Twitter Login //
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'twitter') {
            $twitter_return_url = Router::url(array(
                'controller' => 'users',
                'action' => 'oauth_callback',
                'admin' => false
            ) , true);
            $requestToken = $this->OauthConsumer->getRequestToken('Twitter', 'https://api.twitter.com/oauth/request_token', $twitter_return_url);
            $this->Session->write('requestToken', serialize($requestToken));
            if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                $this->redirect('http://twitter.com/oauth/authorize?oauth_token=' . $requestToken->key);
            } else {
                $this->set('redirect_url', 'http://twitter.com/oauth/authorize?oauth_token=' . $requestToken->key);
                $this->set('authorize_name', 'twitter');
                $this->layout = 'redirection';
                $this->pageTitle.= ' - ' . __l('Twitter');
                $this->render('authorize');
            }
        }
        if (!empty($this->request->params['named']['user_type']) && $this->request->params['named']['user_type'] == 'company') {
            $this->Session->write('user_type', 'company');
        } else {
            if ($this->Session->check('user_type') && empty($_GET['openid_identity'])) {
                $this->Session->delete('user_type');
            }
        }
        // Facebook Login //
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'facebook' && Configure::read('facebook.is_enabled_facebook_connect')) {
            $fb_return_url = Router::url(array(
                'controller' => 'users',
                'action' => 'register',
                'admin' => false
            ) , true);
            $this->Session->write('fb_return_url', $fb_return_url);
            $this->set('redirect_url', $this->facebook->getLoginUrl(array(
                'redirect_uri' => Router::url(array(
                    'controller' => 'users',
                    'action' => 'oauth_facebook',
                    'admin' => false
                ) , true) ,
                'scope' => 'email,publish_stream'
            )));
            $this->set('authorize_name', 'facebook');
            $this->layout = 'redirection';
            $this->pageTitle.= ' - ' . __l('Facebook');
            $this->render('authorize');
        }
        // OpenID validation setting
        if (!empty($this->request->data) && (isset($this->request->data['User']['openid']))) {
            $openidSubmit = 1;
        }
        // yahoo Login //
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'yahoo' && Configure::read('user.is_enable_yahoo_openid')) {
            $this->request->data['User']['email'] = '';
            $this->request->data['User']['password'] = '';
            $this->request->data['User']['redirect_page'] = 'login';
            $this->request->data['User']['openid'] = 'http://yahoo.com/';
            $this->_openid();
        }
        // gmail Login //
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'gmail' && Configure::read('user.is_enable_gmail_openid')) {
            $this->request->data['User']['email'] = '';
            $this->request->data['User']['password'] = '';
            $this->request->data['User']['redirect_page'] = 'login';
            $this->request->data['User']['openid'] = 'https://www.google.com/accounts/o8/id';
            $this->_openid();
        }
        //open id component included
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'Openid');
        $this->Openid = new OpenidComponent($collection);
        // handle the fields return from openid
        if (!empty($_GET['openid_identity']) && (Configure::read('user.is_enable_openid') || Configure::read('user.is_enable_gmail_openid') || Configure::read('user.is_enable_yahoo_openid'))) {
            $returnTo = Router::url(array(
                'controller' => 'users',
                'action' => 'login'
            ) , true);
            $response = $this->Openid->getResponse($returnTo);
            if ($response->status == Auth_OpenID_SUCCESS) {
                // Required Fields
                if ($user = $this->User->UserOpenid->find('first', array(
                    'conditions' => array(
                        'UserOpenid.openid' => $response->identity_url
                    )
                ))) {
                    //Already existing user need to do auto login
                    $this->request->data['User']['email'] = $user['User']['email'];
                    $this->request->data['User']['username'] = $user['User']['username'];
                    $this->request->data['User']['password'] = $user['User']['password'];
                    if ($this->Auth->login($this->request->data)) {
                        $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                        if ($redirectUrl = $this->Session->read('Auth.redirectUrl')) {
                            $this->Session->delete('Auth.redirectUrl');
                            $this->redirect(Router::url('/', true) . $redirectUrl);
                        } else {
                            $this->redirect(Router::url('/', true));
                        }
                    } else {
                        $this->Session->setFlash($this->Auth->loginError, 'default', null, 'error');
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'login'
                        ));
                    }
                } else {
                    $sregResponse = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
                    $sreg = $sregResponse->contents();
                    $temp['username'] = isset($sreg['nickname']) ? $sreg['nickname'] : '';
                    $temp['email'] = isset($sreg['email']) ? $sreg['email'] : '';
                    $temp['openid_url'] = $response->identity_url;
                    $respone_url = $response->identity_url;
                    $respone_url = parse_url($respone_url);
                    if (!empty($respone_url['host']) && $respone_url['host'] == 'www.google.com') {
                        $temp['is_gmail_register'] = 1;
                    } elseif (!empty($respone_url['host']) && $respone_url['host'] == 'me.yahoo.com') {
                        $temp['is_yahoo_register'] = 1;
                    }
                    $this->Session->write('openid', $temp);
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'register'
                    ));
                }
            } else {
                $this->Session->setFlash(__l('Authenticated failed or you may not have profile in your OpenID account'));
            }
        }
        // check open id is given or not
        if ((Configure::read('user.is_enable_openid') || Configure::read('user.is_enable_gmail_openid') || Configure::read('user.is_enable_yahoo_openid')) && isset($this->request->data['User']['openid'])) {
            // Fix for given both email and openid url in login page....@todo
            $this->Auth->logout();
            $this->request->data['User']['email'] = '';
            $this->request->data['User']['password'] = '';
            $this->request->data['User']['redirect_page'] = 'login';
            $this->_openid();
        } else {
            // remember me for user
            if (!empty($this->request->data)) {
                $this->request->data['User'][Configure::read('user.using_to_login') ] = !empty($this->request->data['User'][Configure::read('user.using_to_login') ]) ? trim($this->request->data['User'][Configure::read('user.using_to_login') ]) : '';
                //Important: For login unique username or email check validation not necessary. Also in login method authentication done before validation.
                unset($this->User->validate[Configure::read('user.using_to_login') ]['rule3']);
                $this->User->set($this->request->data);
                if ($this->User->validates()) {
                    $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['passwd']);
                    if ($this->Auth->login($this->request->data)) {
                        $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                        if ($this->Auth->user('id')) {
                            if (!empty($this->request->data['User']['is_remember']) and $this->request->data['User']['is_remember'] == 1) {
                                $this->Cookie->delete('User');
                                $cookie = array();
                                $remember_hash = md5($this->request->data['User'][Configure::read('user.using_to_login') ] . $this->request->data['User']['password'] . Configure::read('Security.salt'));
                                $cookie['cookie_hash'] = $remember_hash;
                                $this->Cookie->write('User', $cookie, true, $this->cookieTerm);
                                $this->User->updateAll(array(
                                    'User.cookie_hash' => '\'' . md5($remember_hash) . '\'',
                                    'User.cookie_time_modified' => '\'' . date('Y-m-d h:i:s') . '\'',
                                ) , array(
                                    'User.id' => $this->Auth->user('id')
                                ));
                            } else {
                                $this->Cookie->delete('User');
                            }
                            if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                                $this->redirect(array(
                                    'controller' => 'users',
                                    'action' => 'stats',
                                    'admin' => true
                                ));
                            } else if (!empty($this->request->data['User']['f'])) {
                                $this->redirect(Router::url('/', true) . $this->request->data['User']['f']);
                            } else {
                                $this->redirect(array(
                                    'controller' => 'users',
                                    'action' => 'dashboard',
                                    'admin' => false
                                ));
                            }
                        }
                    } else {
                        if (!empty($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
                            $this->Session->setFlash(sprintf(__l('Sorry, login failed.  Your %s or password are incorrect') , Configure::read('user.using_to_login')) , 'default', null, 'error');
                        } else {
                            $this->Session->setFlash($this->Auth->loginError, 'default', null, 'error');
                        }
                    }
                }
            }
        }
        //When already logged user trying to access the login page we are redirecting to site home page
        if ($this->Auth->user('id')) {
            $this->redirect(Router::url('/', true));
        }
        if (!empty($this->request->data['User']['type']) && $this->request->data['User']['type'] == 'openid') {
            $this->request->params['named']['type'] = 'openid';
        }
        if (!empty($this->request->params['named']['type']) and $this->request->params['named']['type'] == 'openid') {
            if (!empty($this->request->data) && (empty($this->request->data['User']['openid']) || $this->request->data['User']['openid'] == "Click to Sign In")) {
                $this->User->validationErrors['openid'] = __l('Required');
                $this->Session->setFlash(__l('Invalid OpenID entered. Please enter valid OpenID') , 'default', null, 'error');
            }
            $this->render('login_ajax_openid');
        }
        $breadCrumbs = array();
        $breadCrumbs[__l('Login') ] = Router::url(array(
            'controller' => 'users',
            'action' => 'login'
        ) , true);
        $this->set('breadCrumbs', $breadCrumbs);
        $this->request->data['User']['passwd'] = '';
    }
    public function logout()
    {
        if ($this->Auth->user('fb_user_id')) {
            $this->Session->write('is_fab_session_cleared', 1); // Quick fix for facebook redirect loop issue.
            $this->Session->delete('fbuser');
        }
        if ($this->Session->check('is_fab_session_conected')) {
            $this->Session->delete('is_fab_session_conected');
        }
        $this->Auth->logout();
        $this->Cookie->delete('User');
        $this->Session->setFlash(__l('You are now logged out of the site.') , 'default', null, 'success');
        $this->redirect(array(
            'controller' => 'users',
            'action' => 'login',
            'admin' => false
        ));
    }
    public function forgot_password()
    {
        $this->pageTitle = __l('Forgot Password');
        if ($this->Auth->user('id')) {
            $this->redirect(Router::url('/', true));
        }
        if (!empty($this->request->data)) {
            $this->User->set($this->request->data);
            //Important: For forgot password unique email id check validation not necessary.
            unset($this->User->validate['email']['rule3']);
            if ($this->User->validates()) {
                $user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.email =' => $this->request->data['User']['email'],
                        'User.is_active' => 1
                    ) ,
                    'fields' => array(
                        'User.id',
                        'User.email'
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($user['User']['email'])) {
                    $user = $this->User->find('first', array(
                        'conditions' => array(
                            'User.email' => $user['User']['email']
                        ) ,
                        'recursive' => -1
                    ));
                    $emailFindReplace = array(
                        '##FIRST_NAME##' => (isset($user['User']['first_name'])) ? $user['User']['first_name'] : '',
                        '##USERNAME##' => (isset($user['User']['username'])) ? $user['User']['username'] : '',
                        '##LAST_NAME##' => (isset($user['User']['last_name'])) ? $user['User']['last_name'] : '',
                        '##SITE_NAME##' => Configure::read('site.name') ,
                        '##SITE_URL##' => Router::url('/', true) ,
                        '##RESET_URL##' => Router::url(array(
                            'controller' => 'users',
                            'action' => 'reset',
                            $user['User']['id'],
                            $this->User->getResetPasswordHash($user['User']['id'])
                        ) , true)
                    );
                    $email = $this->EmailTemplate->selectTemplate('Forgot Password');
                    $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
                    $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
                    $this->Email->to = $user['User']['email'];
                    $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                    $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                    $this->Email->send(strtr($email['email_content'], $emailFindReplace));
                    $this->Session->setFlash(__l('An email has been sent with a link where you can change your password') , 'default', null, 'success');
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'login'
                    ));
                } else {
                    $this->Session->setFlash(sprintf(__l('There is no user registered with the email %s or admin deactivated your account. If you spelled the address incorrectly or entered the wrong address, please try again.') , $this->request->data['User']['email']) , 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l('Enter Valid E-mail.') , 'default', null, 'error');
            }
        }
    }
    public function reset($user_id = null, $hash = null)
    {
        $this->pageTitle = __l('Reset Password');
        if (!empty($this->request->data)) {
            if ($this->User->isValidResetPasswordHash($this->request->data['User']['user_id'], $this->request->data['User']['hash'])) {
                $this->User->set($this->request->data);
                if ($this->User->validates()) {
                    $this->User->updateAll(array(
                        'User.password' => '\'' . $this->Auth->password($this->request->data['User']['passwd']) . '\'',
                    ) , array(
                        'User.id' => $this->request->data['User']['user_id']
                    ));
                    $this->Session->setFlash(__l('Your password changed successfully, Please login now') , 'default', null, 'success');
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'login'
                    ));
                }
                $this->request->data['User']['passwd'] = '';
                $this->request->data['User']['confirm_password'] = '';
            } else {
                $this->Session->setFlash(__l('Invalid change password request'));
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login'
                ));
            }
        } else {
            if (is_null($user_id) or is_null($hash)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.id' => $user_id,
                    'User.is_active' => 1,
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                $this->Session->setFlash(__l('User cannot be found in server or admin deactivated your account, please register again'));
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'register'
                ));
            }
            if (!$this->User->isValidResetPasswordHash($user_id, $hash)) {
                $this->Session->setFlash(__l('Invalid change password request'));
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login'
                ));
            }
            $this->request->data['User']['user_id'] = $user_id;
            $this->request->data['User']['hash'] = $hash;
        }
    }
    public function change_password()
    {
        $this->pageTitle = __l('Change Password');
        if ($this->Auth->user('fb_user_id') || $this->Auth->user('is_openid_register') || $this->Auth->user('is_twitter_register') || $this->Auth->user('is_yahoo_register') || $this->Auth->user('is_gmail_register')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            $this->User->set($this->request->data);
            if ($this->User->validates()) {
                $this->User->updateAll(array(
                    'User.password' => '\'' . $this->Auth->password($this->request->data['User']['passwd']) . '\'',
                ) , array(
                    'User.id' => $this->Auth->user('id')
                ));
                if (Configure::read('user.is_logout_after_change_password')) {
                    $this->Auth->logout();
                    $this->Session->setFlash(__l('Your password changed successfully. Please login now') , 'default', null, 'success');
                    $this->redirect(array(
                        'action' => 'login'
                    ));
                }
                $this->Session->setFlash(__l('Your password changed successfully') , 'default', null, 'success');
            }
        }
        $this->request->data['User']['old_password'] = '';
        $this->request->data['User']['passwd'] = '';
        $this->request->data['User']['confirm_password'] = '';
    }
    public function admin_home()
    {
        $this->pageTitle = __l('Dashboard');
        // total approved users list
        $this->set('users', $this->User->find('count', array(
            'conditions' => array(
                'User.is_active = ' => 1,
            ) ,
            'recursive' => -1
        )));
        // total event list
        $this->set('events', $this->User->Event->find('count', array(
            'conditions' => array(
                'Event.is_active = ' => 1,
            ) ,
            'recursive' => -1
        )));
        // total venue list
        $this->set('venues', $this->User->Venue->find('count', array(
            'conditions' => array(
                'Venue.is_active = ' => 1,
            ) ,
            'recursive' => -1
        )));
        // total eventsponsor list
        $this->set('event_sponsors', $this->User->EventSponsor->find('count', array(
            'conditions' => array(
                'EventSponsor.is_active = ' => 1,
            ) ,
            'recursive' => -1
        )));
    }
    public function admin_index()
    {
        //Redirect Get to namedparams
        $this->_redirectGET2Named(array(
            'keyword',
            'filter',
        ));
        $this->pageTitle = __l('Users');
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['filter_id'])) {
            $this->request->data['User']['filter'] = $this->request->params['named']['filter_id'];
        }
        if (isset($this->request->params['named']['main_filter_id'])) {
            $this->request->data['User']['main_filter_id'] = $this->request->params['named']['main_filter_id'];
        }
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['User']['keyword'] = $this->request->params['named']['keyword'];
        }
        if (!empty($this->request->data['User']['keyword'])) {
            $conditions = array(
                'OR' => array(
                    'User.username = ' => $this->request->data['User']['keyword'],
                    'User.email Like ' => '%' . $this->request->data['User']['keyword'] . '%',
                    'UserProfile.first_name Like ' => '%' . $this->request->data['User']['keyword'] . '%',
                    'UserProfile.last_name Like ' => '%' . $this->request->data['User']['keyword'] . '%',
                    'UserProfile.middle_name Like ' => '%' . $this->request->data['User']['keyword'] . '%'
                )
            );
            $this->request->params['named']['keyword'] = $this->request->data['User']['keyword'];
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(User.created) <= '] = 0;
            $this->pageTitle.= __l(' - Registered today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(User.created) <= '] = 7;
            $this->pageTitle.= __l(' - Registered in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(User.created) <= '] = 30;
            $this->pageTitle.= __l(' - Registered in this month');
        }
        if (!empty($this->request->data['User']['user_type_id'])) {
            if ($this->request->data['User']['user_type_id'] != 'all') {
                $conditions['User.user_type_id'] = $this->request->data['User']['user_type_id'];
            }
            $this->request->params['named']['filter'] = $this->request->data['User']['user_type_id'];
        }
        if (!empty($this->request->data['User']['main_filter_id'])) {
            if ($this->request->data['User']['main_filter_id'] == ConstUserTypes::User) {
                $conditions['User.user_type_id'] = ConstUserTypes::User;
                $conditions['User.is_active'] = 1;
                $this->pageTitle.= __l(' - Users ');
            } else if ($this->request->data['User']['main_filter_id'] == ConstUserTypes::VenueOwner) {
                $conditions['User.user_type_id'] = ConstUserTypes::VenueOwner;
                $conditions['User.is_active'] = 1;
                $this->pageTitle.= __l(' - Venue Owners ');
            } else if ($this->request->data['User']['main_filter_id'] == ConstUserTypes::Admin) {
                $conditions['User.user_type_id'] = ConstUserTypes::Admin;
                $conditions['User.is_active'] = 1;
                $this->pageTitle.= __l(' - Admin ');
            }
        }
        if (!empty($this->request->data['User']['filter'])) {
            if ($this->request->data['User']['filter'] == ConstMoreAction::OpenID) {
                $conditions['User.is_openid_register'] = 1;
                $this->pageTitle.= __l(' - Registered through OpenID ');
            } else if ($this->request->data['User']['filter'] == ConstMoreAction::Gmail) {
                $conditions['User.is_gmail_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Gmail ');
            } else if ($this->request->data['User']['filter'] == ConstMoreAction::Yahoo) {
                $conditions['User.is_yahoo_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Yahoo ');
            } else if ($this->request->data['User']['filter'] == ConstMoreAction::Active) {
                $conditions['User.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } else if ($this->request->data['User']['filter'] == ConstMoreAction::Site) {
                $conditions['User.is_yahoo_register'] = 0;
                $conditions['User.is_gmail_register'] = 0;
                $conditions['User.is_openid_register'] = 0;
                $conditions['User.is_facebook_register'] = 0;
                $conditions['User.is_twitter_register'] = 0;
                $this->pageTitle.= __l(' - Site ');
            } else if ($this->request->data['User']['filter'] == ConstMoreAction::Inactive) {
                $conditions['AND']['OR'][]['User.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            } else if ($this->request->data['User']['filter'] == ConstMoreAction::Twitter) {
                $conditions['User.is_twitter_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Twitter ');
            } else if ($this->request->data['User']['filter'] == ConstMoreAction::Facebook) {
                $conditions['User.is_facebook_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Facebook ');
            }
        }
        if ($this->RequestHandler->prefers('csv')) {
            Configure::write('debug', 0);
            $this->set('user', $this);
            $this->set('conditions', $conditions);
            if (isset($this->request->data['User']['q'])) {
                $this->set('q', $this->request->data['User']['q']);
            }
            $this->set('contain', $contain);
        } else {
            $this->paginate = array(
                'conditions' => $conditions,
                'contain' => array(                     
                    'UserProfile' => array(
                        'Country' => array(
                            'fields' => array(
                                'Country.name',
                                'Country.iso_alpha2',
                            )
                        )
                    ) ,
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.id',
                            'UserAvatar.dir',
                            'UserAvatar.filename',
                            'UserAvatar.width',
                            'UserAvatar.height'
                        )
                    ) ,
                    'LastLoginIp' => array(
                        'City' => array(
                            'fields' => array(
                                'City.name',
                            )
                        ) ,
                        'State' => array(
                            'fields' => array(
                                'State.name',
                            )
                        ) ,
                        'Country' => array(
                            'fields' => array(
                                'Country.name',
                                'Country.iso_alpha2',
                            )
                        ) ,
                        'Timezone' => array(
                            'fields' => array(
                                'Timezone.name',
                            )
                        ) ,
                        'fields' => array(
                            'LastLoginIp.ip',
                            'LastLoginIp.latitude',
                            'LastLoginIp.longitude',
                            'LastLoginIp.host',
                        )
                    ),
                       ) ,
                'limit' => 15,
                'order' => 'User.id DESC',
                'recursive' => 2
            );
            $this->set('inactive', $this->User->find('count', array(
                'conditions' => array(
                    'User.is_active' => 0,
                ) ,
                'recursive' => -1,
            )));
            $this->set('active', $this->User->find('count', array(
                'conditions' => array(
                    'User.is_active' => 1,
                ) ,
                'recursive' => -1,
            )));
            $this->set('normal_users', $this->User->find('count', array(
                'conditions' => array(
                    'User.is_active' => 1,
                    'User.user_type_id' => ConstUserTypes::User,
                ) ,
                'recursive' => -1,
            )));
            $this->set('venue_owner_users', $this->User->find('count', array(
                'conditions' => array(
                    'User.is_active' => 1,
                    'User.user_type_id' => ConstUserTypes::VenueOwner,
                ) ,
                'recursive' => -1,
            )));
            // total admin list
            $this->set('admin_users', $this->User->find('count', array(
                'conditions' => array(
                    'User.is_active' => 1,
                    'User.user_type_id' => ConstUserTypes::Admin,
                ) ,
                'recursive' => -1,
            )));
            $this->set('users', $this->paginate());
            $isFilterOptions = $this->User->isFilterOptions;
            $moreActions = $this->User->moreActions;
            $this->set(compact('isFilterOptions', 'moreActions'));
        }
    }
    public function admin_add()
    {
        $this->pageTitle = __l('Add New User/Admin');
        $this->loadModel('VenueOwner');
        if (!empty($this->request->data)) {
            $this->request->data['User']['password'] = $this->Auth->password($this->request->data['User']['passwd']);
            $this->request->data['User']['is_agree_terms_conditions'] = '1';
            $this->request->data['User']['is_email_confirmed'] = 0;
            $this->request->data['User']['is_active'] = 1;
            $this->request->data['User']['ip_id'] = $this->User->toSaveIp();
            $this->User->create();
            $this->User->set($this->request->data);
            $this->User->UserProfile->set($this->request->data);
            unset($this->User->UserProfile->validate['gender_id']);
            unset($this->User->UserProfile->validate['last_name']);
            if ($this->User->validates() &$this->User->UserProfile->validates()) {
                if ($this->User->save($this->request->data, false)) {
                    $this->request->data['UserProfile']['user_id'] = $this->User->getLastInsertId();
                    $this->User->UserProfile->create();
                    $this->User->UserProfile->save($this->request->data);
                    $flash_message = __l('User has been added');
                    if ($this->request->data['User']['user_type_id'] == ConstUserTypes::VenueOwner) {
                        $flash_message = __l('Venue Owner has been added');
                    }
                    if (!empty($this->request->data['User']['venue_owner_id'])) {
                        $venue_owner_data = array();
                        $venue_owner_data['VenueOwner']['id'] = $this->request->data['User']['venue_owner_id'];
                        $venue_owner_data['VenueOwner']['is_created'] = 1;
                        $this->VenueOwner->save($venue_owner_data);
                    }
                    // Send mail to user to activate the account and send account details
                    $emailFindReplace = array(
                        '##USERNAME##' => $this->request->data['User']['username'],
                        '##EMAILID##' => $this->request->data['User']['email'],
                        '##SITE_NAME##' => Configure::read('site.name') ,
                        '##ACTIVATION_URL##' => Router::url(array(
                            'controller' => 'users',
                            'action' => 'activation',
                            $this->User->getLastInsertId() ,
                            $this->User->getActivateHash($this->User->getLastInsertId()) ,
                            'admin' => false
                        ) , true) ,
                        '##PASSWORD##' => $this->request->data['User']['passwd'],
                        '##SITE_URL##' => Router::url('/', true) ,
                    );
                    $email = $this->EmailTemplate->selectTemplate('Admin User Add');
                    $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
                    $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
                    $this->Email->to = $this->request->data['User']['email'];
                    $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                    $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                    $this->Email->send(strtr($email['email_content'], $emailFindReplace));
                    $this->Session->setFlash($flash_message, 'default', null, 'success');
                    $this->redirect(array(
                        'action' => 'index'
                    ));
                }
            } else {
                unset($this->request->data['User']['passwd']);
                $this->Session->setFlash(__l('User could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            if (!empty($this->request->params['named']['venue_owner'])) {
                $user_details = $this->VenueOwner->find('first', array(
                    'conditions' => array(
                        'VenueOwner.id' => $this->request->params['named']['venue_owner']
                    ) ,
                    'recursive' => 0
                ));
                if (!empty($user_details)) {
                    $this->request->data['User']['user_type_id'] = 4;
                    $this->request->data['User']['email'] = $user_details['VenueOwner']['email'];
                    $this->request->data['User']['username'] = $user_details['VenueOwner']['first_name'];
                    $this->request->data['UserProfile']['phone'] = $user_details['VenueOwner']['other_mobile'];
                    $this->request->data['UserProfile']['mobile'] = $user_details['VenueOwner']['mobile'];
                    $this->request->data['UserProfile']['last_name'] = $user_details['VenueOwner']['last_name'];
                    $this->request->data['UserProfile']['gender_id'] = $user_details['VenueOwner']['gender_id'];
                    $this->request->data['UserProfile']['dob'] = $user_details['VenueOwner']['dob'];
                    $this->request->data['UserProfile']['country_id'] = $user_details['VenueOwner']['country_id'];
                    $this->request->data['UserProfile']['city_id'] = $user_details['VenueOwner']['city_id'];
                    $this->request->data['User']['venue_owner_id'] = $user_details['VenueOwner']['id'];
                }
            }
        }
        $userTypes = $this->User->UserType->find('list');
        $city_ids = $this->User->UserProfile->City->find('all', array(
            'conditions' => array(
                'City.country_id != ' => null,
            ) ,
            'fields' => array(
                'City.id',
                'City.country_id',
            ) ,
            'recursive' => -1,
        ));
        $countryIds = array();
        foreach($city_ids as $cityids):
            $countryIds[] = $cityids['City']['country_id'];
        endforeach;
        $countries = $this->User->UserProfile->Country->find('list', array(
            'conditions' => array(
                'Country.id' => array_unique($countryIds)
            ) ,
            'fields' => array(
                'Country.id',
                'Country.name',
            ) ,
            'recursive' => 2,
        ));
        $cities = $this->User->UserProfile->City->find('list');
        $this->set(compact('userTypes', 'countries', 'cities'));
        if (!isset($this->request->data['User']['user_type_id'])) {
            $this->request->data['User']['user_type_id'] = ConstUserTypes::User;
        }
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->_sendAdminActionMail($id, 'Admin User Delete');
        if ($this->User->delete($id)) {
            $this->Session->setFlash(__l('User deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_update()
    {
        if (!empty($this->request->data['User'])) {
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $userIds = array();
            foreach($this->request->data['User'] as $user_id => $is_checked) {
                if ($is_checked['id']) {
                    $userIds[] = $user_id;
                }
            }
            if ($actionid && !empty($userIds)) {
                if ($actionid == ConstMoreAction::Inactive) {
                    $this->User->updateAll(array(
                        'User.is_active' => 0
                    ) , array(
                        'User.id' => $userIds
                    ));
                    foreach($userIds as $key => $user_id) {
                        $this->_sendAdminActionMail($user_id, 'Admin User Deactivate');
                    }
                    $this->Session->setFlash(__l('Checked users has been inactivated') , 'default', null, 'success');
                    $this->User->CkSession->deleteAll(array(
                        'CkSession.user_id' => $user_id
                    ));
                } else if ($actionid == ConstMoreAction::Active) {
                    $this->User->updateAll(array(
                        'User.is_active' => 1
                    ) , array(
                        'User.id' => $userIds
                    ));
                    foreach($userIds as $key => $user_id) {
                        $this->_sendAdminActionMail($user_id, 'Admin User Active');
                    }
                    $this->Session->setFlash(__l('Checked users has been activated') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Delete) {
                    foreach($userIds as $key => $user_id) {
                        $this->_sendAdminActionMail($user_id, 'Admin User Delete');
                    }
                    $this->User->deleteAll(array(
                        'User.id' => $userIds
                    ));
                    $this->Session->setFlash(__l('Checked users has been deleted') , 'default', null, 'success');
                }
            }
        }
        $this->redirect(array(
            'action' => 'index'
        ));
    }
    public function _sendAdminActionMail($user_id, $email_template)
    {
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
            ) ,
            'fields' => array(
                'User.username',
                'User.email'
            ) ,
            'recursive' => -1
        ));
        $emailFindReplace = array(
            '##USERNAME##' => $user['User']['username'],
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##SITE_URL##' => Router::url('/', true) ,
        );
        $email = $this->EmailTemplate->selectTemplate($email_template);
        $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
        $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
        $this->Email->to = $user['User']['email'];
        $this->Email->subject = strtr($email['subject'], $emailFindReplace);
        $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
        $this->Email->send(strtr($email['email_content'], $emailFindReplace));
    }
    public function admin_change_password($user_id = null)
    {
        $this->setAction('change_password', $user_id);
    }
    public function admin_login()
    {
        $this->setAction('login');
    }
    public function admin_logout()
    {
        $this->setAction('logout');
    }
    public function admin_stats()
    {
        $this->pageTitle = __l('Snapshot');
    }
    public function user_search()
    {
		if (!empty($_SESSION['search'])) {
            $this->request->data = $_SESSION['search'];
            $chooseval = $_SESSION['search']['User']['choose'];
            $this->set('chooseval', $chooseval);
        }
        if (empty($this->request->data['User']['choose'])) {
            $this->request->data['User']['choose'] = '1';
        }
		if (!empty($this->request->data['City']['city_id'])) {
			$this->request->data['User']['city_id'] = $this->request->data['City']['city_id'];
		}
        $userFilterOptions = $this->User->userFilter;
        $userSearchFilterOptions = $this->User->UserSearchFilter;
        $genders = $this->User->UserProfile->Gender->find('list');
        $city_ids = $this->User->UserProfile->City->find('all', array(
            'conditions' => array(
                'City.country_id != ' => null,
            ) ,
            'fields' => array(
                'City.id',
                'City.country_id',
            ) ,
            'recursive' => -1,
        ));
        $countryIds = array();
        foreach($city_ids as $cityids):
            $countryIds[] = $cityids['City']['country_id'];
        endforeach;
        $countries = $this->User->UserProfile->Country->find('list', array(
            'conditions' => array(
                'Country.id' => array_unique($countryIds)
            ) ,
            'fields' => array(
                'Country.id',
                'Country.name',
            ) ,
            'recursive' => 2,
        ));
        if(!empty($this->request->params['named']['country_id'])) {
			$cities = $this->User->UserProfile->City->find('list', array(
				'conditions' => array(
					'City.country_id' => $this->request->params['named']['country_id']
				)
			));
			$this->set(compact('cities'));
		}
		
		
        $maritalstatus = $this->User->UserProfile->MaritalStatus->find('list');
        $ethnicity = $this->User->UserProfile->Ethnicity->find('list');
        $sexualorientations = $this->User->UserProfile->SexualOrientation->find('list');
        $this->set(compact('genders', 'countries', 'maritalstatus', 'ethnicity', 'sexualorientations', 'userSearchFilterOptions', 'userFilterOptions'));
    }
    public function admin_send_mail()
    {
        $this->pageTitle = __l('Email to users');
        if (!empty($this->request->data)) {
            $this->User->set($this->request->data);
            if ($this->User->validates()) {
                $conditions = $emails = array();
                $notSendCount = $sendCount = 0;
                if (!empty($this->request->data['User']['send_to'])) {
                    $sendTo = explode(',', $this->request->data['User']['send_to']);
                    foreach($sendTo as $email) {
                        $email = trim($email);
                        if (!empty($email)) {
                            if ($this->User->find('count', array(
                                'conditions' => array(
                                    'User.email' => $email
                                )
                            ))) {
                                $emails[] = $email;
                                $sendCount++;
                            } else {
                                $notSendCount++;
                            }
                        }
                    }
                }
                if (!empty($this->request->data['User']['bulk_mail_option_id'])) {
                    if ($this->request->data['User']['bulk_mail_option_id'] == 2) {
                        $conditions['User.is_active'] = 0;
                    }
                    if ($this->request->data['User']['bulk_mail_option_id'] == 3) {
                        $conditions['User.is_active'] = 1;
                    }
                    // @todo "User activation" check user.is_send_email_notifications_only_to_verified_email_account
                    $users = $this->User->find('all', array(
                        'conditions' => $conditions,
                        'fields' => array(
                            'User.email'
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($users)) {
                        $sendCount++;
                        foreach($users as $user) {
                            $emails[] = $user['User']['email'];
                        }
                    }
                }
                $this->request->data['User']['message'].= "\n\n";
                $this->request->data['User']['message'].= Configure::read('site.name') . "\n";
                $this->request->data['User']['message'].= Router::url('/', true);
                if (!empty($emails)) {
                    foreach($emails as $email) {
                        if (!empty($email)) {
                            $this->Email->from = Configure::read('EmailTemplate.from_email');
                            $this->Email->replyTo = Configure::read('site.reply_to_email');
                            $this->Email->to = trim($email);
                            $this->Email->subject = $this->request->data['User']['subject'];
                            $this->Email->send($this->request->data['User']['message']);
                        }
                    }
                }
                if ($sendCount && !$notSendCount) {
                    $this->Session->setFlash(__l('Email sent successfully') , 'default', null, 'success');
                    if (!empty($this->request->data['Contact']['id'])) {
                        $this->User->Contact->updateAll(array(
                            'Contact.is_replied' => 1
                        ) , array(
                            'Contact.id' => $this->request->data['Contact']['id']
                        ));
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'dashboard'
                        ));
                    } else {
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'index'
                        ));
                    }
                } elseif ($sendCount && $notSendCount) {
                    $this->Session->setFlash(__l('Email sent successfully. Some emails are not sent') , 'default', null, 'success');
                } else {
                    $this->Session->setFlash(__l('No email send') , 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l('Email couldn\'t be sent! Enter all required fields') , 'default', null, 'error');
                if (!empty($this->request->data['Contact']['id'])) {
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'send_mail',
                        'contact' => $this->request->data['Contact']['id']
                    ));
                }
            }
        }
        // Just to do the admin conatact us repay mangement
        if (!empty($this->request->params['named']['contact'])) {
            $contact_deatil = $this->User->Contact->find('first', array(
                'conditions' => array(
                    'Contact.id' => $this->request->params['named']['contact'],
                ) ,
                'contain' => array(
                    'ContactType'
                ) ,
                'recursive' => 0
            ));
            if (!empty($contact_deatil['Contact']['subject'])) {
                $subject = $contact_deatil['Contact']['subject'];
            } else {
                $subject = $contact_deatil['ContactType']['name'];
            }
            $this->pageTitle = __l('Contact us - Reply');
            $this->request->data['Contact']['id'] = $this->request->params['named']['contact'];
            $this->request->data['User']['subject'] = __l('Re:') . $subject;
            $this->request->data['User']['message'] = "\n\n\n";
            $this->request->data['User']['message'].= '------------------------------';
            $this->request->data['User']['message'].= "\n" . $contact_deatil['Contact']['message'];
            $this->request->data['User']['send_to'] = $contact_deatil['Contact']['email'];
        }
        $bulkMailOptions = $this->User->bulkMailOptions;
        $this->set(compact('bulkMailOptions'));
    }
    public function checkMultipleEmail()
    {
        $validation = &Validation::getInstance();
        $multipleEmails = explode(',', $this->request->data['User']['send_to']);
        foreach($multipleEmails as $key => $singleEmail) {
            if (!$validation->email(trim($singleEmail))) {
                return false;
            }
        }
        return true;
    }
    public function admin_stat_export($hash = null)
    {
        $stat = Configure::read('site.name') . ' ' . date('dS M Y', strtotime('today'));
        $models = array(
            'User' => array(
                'conditions' => array(
                    'User.user_type_id != ' => 1
                ) ,
            ) ,
            'Event' => array() ,
            'Venue' => array() ,
        );
        $i = 0;
        $start_date = $this->request->data['User']['start_date'];
        $end_date = $this->request->data['User']['end_date'];
        $abs = abs(strtotime($start_date) -strtotime($end_date));
        $n = $abs/(60*60*24);
        //for($start_date; $start_date<=$end_date; $start_date++){
        for ($k = 0; $k <= $n; $k++) {
            $start_date = date('Y-m-d 00:i:s', strtotime(date("Y-m-d", strtotime($start_date)) . " +1 day"));
            $data[$i][$stat]['date'] = $start_date;
            foreach($models as $model => $fields) {
                $data[$i][$stat][$model] = $this->{$model}->find('count', array(
                    'conditions' => array(
                        'created >=' => date('Y-m-d 00:i:s', (strtotime($start_date))) ,
                        'created <' => date('Y-m-d 00:i:s', strtotime(date("Y-m-d", strtotime($start_date)) . " +1 day"))
                    ) ,
                    'recursive' => -1
                ));
            }
            $i++;
        }
        $this->set('data', $data);
        $this->redirect(array(
            'action' => 'statistics'
        ));
        $this->autoLayout = false;
    }
    public function admin_manage_menu()
    {
        $view_path = ROOT . '' . DS . APP_DIR . DS . 'views/elements/header_menu.ctp';
        // starts here
        $menu_fileContent = file_get_contents($view_path);
        if (!empty($this->request->data)) {
            if (!empty($this->request->data['User']['script'])) {
                $menu_fileContent = html_entity_decode($this->request->data['User']['script']);
                $fw = fopen($view_path, 'w');
                fwrite($fw, $menu_fileContent);
                fclose($fw);
                $this->Session->setFlash('JS ' . __l('script file has been updated') , 'default', null, 'success');
            }
        }
        $this->set('file_content', $menu_fileContent);
    }
    public function joinus()
    {
        $this->pageTitle = __l('Join Us');
        if ($this->Auth->user('id')) {
            $this->redirect(Router::url('/', true));
        }
    }
    public function refer($referrer = null)
    {
        if (is_null($referrer)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $cookie_value = $this->Cookie->read('referrer');
        //cookie value should be empty or same user id should not be over written
        if (!empty($referrer) && (empty($cookie_value) || (!empty($cookie_value) && $cookie_value != $referrer))) {
            $user_refername = $this->User->find('first', array(
                'conditions' => array(
                    'User.id' => $referrer
                ) ,
                'recursive' => -1
            ));
            if (empty($user_refername)) {
                $this->Session->setFlash(__l('Referrer username does not exist.') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'register'
                ));
            } else {
                $this->Cookie->delete('referrer');
                $this->Cookie->write('referrer', $referrer, true, sprintf('+%s hours', Configure::read('user.referral_cookie_expire_time')));
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'register'
                ));
            }
        } else {
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'register'
            ));
        }
    }
    public function whois($ip = null)
    {
        if (!empty($ip)) {
            $this->redirect(Configure::read('site.whois') . $ip);
        }
    }
    public function dashboard()
    {
        $this->pageTitle = __l('Dashboard');
    }
    public function admin_diagnostics()
    {
        $this->pageTitle = __l('Diagnostics');
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_recent_users()
    {
        //recently registered users
        $recentUsers = $this->User->find('all', array(
            'conditions' => array(
                'User.is_active' => 1,
                'User.user_type_id != ' => ConstUserTypes::Admin
            ) ,
            'fields' => array(
                'User.user_type_id',
                'User.username',
                'User.id',
            ) ,
            'recursive' => -1,
            'limit' => 10,
            'order' => array(
                'User.id' => 'desc'
            )
        ));
        $this->set(compact('recentUsers'));
    }
    public function admin_online_users()
    {
        //online users
        $onlineUsers = $this->User->CkSession->find('all', array(
            'conditions' => array(
                'CkSession.user_id != ' => 0,
                'User.is_active' => 1,
                'User.user_type_id !=' => ConstUserTypes::Admin
            ) ,
            'fields' => array(
                'DISTINCT CkSession.user_id',
                'User.username',
                'User.user_type_id',
                'User.id',
            ) ,
            'recursive' => 1,
            'limit' => 10,
            'order' => array(
                'User.last_logged_in_time' => 'desc'
            )
        ));
        $this->set(compact('onlineUsers'));
    }
    }
?>