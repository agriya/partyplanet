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
class UserProfilesController extends AppController
{
    public $name = 'UserProfiles';
	public $components = array(
        'Email',
    );
    public $uses = array(
        'UserProfile',
        'BodyType',
        'CellProvider',
        'MaritalStatus',
        'FavoriteFashionBrand',
        'Ethnicity',
        'SexualOrientation',
        'MusicTypesUser',
        'MusicType',
        'Attachment',
		'EmailTemplate'
    );
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'UserAvatar.filename',
            'City.id',
            'UserProfile.longitude',
            'UserProfile.latitude',
            'UserProfile.next'
        );
        parent::beforeFilter();
    }
    public function edit($user_id = null, $type = null) 
    {
        $this->pageTitle = __l('Edit Profile');
        $this->disableCache();
        $this->UserProfile->User->UserAvatar->Behaviors->attach('ImageUpload', Configure::read('avatar.file'));
        unset($this->UserProfile->validate['zip_code']);
        unset($this->UserProfile->validate['mobile']);
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $this->UserProfile->validate = array();
            $this->UserProfile->User->validate = array();
        }
        if (!empty($this->request->data)) {
            if (empty($this->request->data['User']['id'])) {
                $this->request->data['User']['id'] = $this->Auth->user('id');
            }
            $user = $this->UserProfile->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->request->data['User']['id']
                ) ,
                'contain' => array(
                    'UserProfile' => array(
                        'fields' => array(
                            'UserProfile.id',
                            'UserProfile.paypal_account',
                            'UserProfile.paypal_first_name',
                            'UserProfile.paypal_last_name'
                        )
                    ) ,
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.id',
                            'UserAvatar.filename',
                            'UserAvatar.dir',
                            'UserAvatar.width',
                            'UserAvatar.height'
                        )
                    )
                ) ,
                'recursive' => 0
            ));
            if (!empty($user)) {
                $this->request->data['UserProfile']['id'] = $user['UserProfile']['id'];
                if (!empty($user['UserAvatar']['id'])) {
                    $this->request->data['UserAvatar']['id'] = $user['UserAvatar']['id'];
                }
            }
            $this->request->data['UserProfile']['user_id'] = $this->request->data['User']['id'];
            if (!empty($this->request->data['UserAvatar']['filename']['name'])) {
                $this->request->data['UserAvatar']['filename']['type'] = get_mime($this->request->data['UserAvatar']['filename']['tmp_name']);
            }
            if (!empty($this->request->data['UserAvatar']['filename']['name']) || (!Configure::read('avatar.file.allowEmpty') && empty($this->request->data['UserAvatar']['id']))) {
                $this->UserProfile->User->UserAvatar->set($this->request->data);
            }
            $this->UserProfile->set($this->request->data);
            $this->UserProfile->User->set($this->request->data);
            $ini_upload_error = 1;
            if (!empty($this->request->data['UserAvatar']['filename']) && $this->request->data['UserAvatar']['filename']['error'] == 1) {
                $ini_upload_error = 0;
            }
            if ($this->UserProfile->User->validates() &$this->UserProfile->validates() &$this->UserProfile->User->UserAvatar->validates()) {
                $paypal_account = 1;
                    if (!empty($this->request->data['UserProfile']['paypal_account']) && ($user['UserProfile']['paypal_account'] != $this->request->data['UserProfile']['paypal_account'] || $user['UserProfile']['paypal_first_name'] != $this->request->data['UserProfile']['paypal_first_name'] || $user['UserProfile']['paypal_last_name'] != $this->request->data['UserProfile']['paypal_last_name'])) {
                        App::import('Model', 'Payment');
						$this->Payment = new Payment();
                        $rsPayStatus = $this->Payment->getVerifiedStatus($this->request->data['UserProfile']);                                                
                        if (strtoupper($rsPayStatus['responseEnvelope.ack']) != 'SUCCESS' || strtoupper($rsPayStatus['accountStatus']) != 'VERIFIED') {
                            $this->Session->setFlash(__l('Enter PayPal verification email and name associated with your PayPal') , 'default', null, 'error');
                            $paypal_account = 0;
                            $this->UserProfile->validationErrors['paypal_account'] = __l('Enter PayPal verification email and name associated with your PayPal');
                        }
                    }                                
                if (isset($this->request->data['City'])) {
                    $this->request->data['UserProfile']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->UserProfile->City->findOrSaveAndGetId($this->request->data['City']['name']);
                }
                if (isset($this->request->data['User']['email'])) {
                    $this->request->data['UserProfile']['email'] = $this->request->data['User']['email'];
                }
                //User music type save
                if (!empty($this->request->data['UserProfile']['music_type_id'])) {
                    $this->UserProfile->User->MusicTypesUser->deleteAll(array(
                        'MusicTypesUser.user_id' => $this->request->data['User']['id']
                    ));
                    $musicTypesUser['user_id'] = $this->request->data['User']['id'];
                    for ($i = 0; $i < count($this->request->data['UserProfile']['music_type_id']); $i++) {
                        $this->UserProfile->User->MusicTypesUser->create();
                        $musicTypesUser['music_type_id'] = $this->request->data['UserProfile']['music_type_id'][$i];
                        $this->UserProfile->User->MusicTypesUser->save($musicTypesUser);
                    }
                } else {
                    $this->UserProfile->User->MusicTypesUser->deleteAll(array(
                        'MusicTypesUser.user_id' => $this->request->data['User']['id']
                    ));
                }
                if ($paypal_account && $this->UserProfile->save($this->request->data, false)) {
					if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
						if($this->request->data['User']['is_active'] != $this->request->data['User']['previous_status']) {
							if(empty($this->request->data['User']['is_active'])) {
								$this->_sendAdminActionMail($this->request->data['User']['id'], 'Admin User Deactivate');
							} else {
								$this->_sendAdminActionMail($this->request->data['User']['id'], 'Admin User Active');
							}
						}
						$this->UserProfile->User->validate = array();
					}
                    $this->UserProfile->User->save($this->request->data['User']);
                    if (!empty($this->request->data['UserAvatar']['filename']['name'])) {
                        $this->Attachment->create();
                        $this->request->data['UserAvatar']['class'] = 'UserAvatar';
                        $this->request->data['UserAvatar']['foreign_id'] = $this->request->data['User']['id'];
                        $this->Attachment->save($this->request->data['UserAvatar']);
                    }
                    $this->Session->setFlash(__l('User Profile has been updated') , 'default', null, 'success');
                }
                
                if (!empty($this->request->data['UserProfile']['next'])) {
                    if ($this->request->data['UserProfile']['type'] == 'basic') {
                        $this->request->data['UserProfile']['type'] = 'general';
                    } elseif ($this->request->data['UserProfile']['type'] == 'general') {
                        $this->request->data['UserProfile']['type'] = 'personal';
                    } elseif ($this->request->data['UserProfile']['type'] == 'personal') {
                        $this->request->data['UserProfile']['type'] = 'photo';
                    }
                }
            } else {
                if ($this->request->data['UserAvatar']['filename']['error'] == 1) {
                    $this->UserProfile->User->UserAvatar->validationErrors['filename'] = sprintf(__l('The file uploaded is too big, only files less than %s permitted') , ini_get('upload_max_filesize'));
                }
                $this->Session->setFlash(__l('User Profile could not be updated. Please, try again.') , 'default', null, 'error');
                $type = $this->request->data['UserProfile']['type'];
            }
            $user = $this->UserProfile->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->request->data['User']['id']
                ) ,
                'contain' => array(
                    'UserProfile' => array(
                        'fields' => array(
                            'UserProfile.id'
                        )
                    ) ,
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.id',
                            'UserAvatar.filename',
                            'UserAvatar.dir',
                            'UserAvatar.width',
                            'UserAvatar.height'
                        )
                    )
                ) ,
                'recursive' => 0
            ));
            if (!empty($user['User'])) {
                unset($user['UserProfile']);
				$this->request->data['User']['user_type_id'] = $user['User']['user_type_id'];
				$this->request->data['User']['fb_user_id'] = $user['User']['fb_user_id'];
                $this->request->data = array_merge($user, $this->request->data);
            }
        } else {
            if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                $user_id = $this->Auth->user('id');
            }
            $this->request->data = $this->UserProfile->User->find('first', array(
                'conditions' => array(
                    'User.id' => $user_id
                ) ,
                'contain' => array(
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.id',
                            'UserAvatar.dir',
                            'UserAvatar.filename',
                            'UserAvatar.width',
                            'UserAvatar.height'
                        )
                    ) ,
                    'UserProfile' => array(
                        'fields' => array(
                            'UserProfile.first_name',
                            'UserProfile.last_name',
                            'UserProfile.description',
                            'UserProfile.gender_id',
                            'UserProfile.about_me',
                            'UserProfile.address',
                            'UserProfile.country_id',
                            'UserProfile.city_id',
                            'UserProfile.zip_code',
                            'UserProfile.address2',
                            'UserProfile.phone',
                            'UserProfile.mobile',
                            'UserProfile.is_show_month_date',
                            'UserProfile.cell_provider_id',
                            'UserProfile.body_type_id',
                            'UserProfile.marital_status_id',
                            'UserProfile.daily_quote',
                            'UserProfile.favorite_fashion_brand_id',
                            'UserProfile.favorite_drinks',
                            'UserProfile.favorite_pickup_line',
                            'UserProfile.ethnicity_id',
                            'UserProfile.sexual_orientation_id',
                            'UserProfile.scrap_your_code',
                            'UserProfile.dob',
                            'UserProfile.paypal_account',
							'UserProfile.paypal_first_name',
							'UserProfile.paypal_last_name'
                        ) ,
                        'City' => array(
                            'fields' => array(
                                'City.name'
                            )
                        ) ,
                    )
                ) ,
                'recursive' => 2
            ));
            if (!empty($this->request->data['UserProfile']['City'])) {
                $this->request->data['City']['name'] = $this->request->data['UserProfile']['City']['name'];
            }
            if (empty($this->request->data['UserProfile']['first_name'])) {
                $this->request->data['UserProfile']['first_name'] = $this->request->data['User']['username'];
            }
        }
        $musictypeusers = $this->MusicTypesUser->find('list', array(
            'conditions' => array(
                'MusicTypesUser.user_id' => $this->request->data['User']['id']
            ) ,
            'fields' => array(
                'MusicTypesUser.id',
                'MusicTypesUser.music_type_id'
            )
        ));
        if (!empty($musictypeusers)) {
            $this->request->data['UserProfile']['music_type_id'] = $musictypeusers;
        }
        $this->pageTitle.= ' - ' . $this->request->data['User']['username'];
        $genders = $this->UserProfile->Gender->find('list');
        $countries = $this->UserProfile->Country->find('list');
        $bodytypes = $this->UserProfile->BodyType->find('list');
        $cellproviders = $this->CellProvider->find('list');
        $maritalstatus = $this->MaritalStatus->find('list', array(
            'conditions' => array(
                'MaritalStatus.is_active' => 1
            )
        ));
        $favoritefashionbrands = $this->UserProfile->FavoriteFashionBrand->find('list');
        $ethnicity = $this->UserProfile->Ethnicity->find('list');
        $musictypes = $this->MusicType->find('list');
        $sexualorientations = $this->UserProfile->SexualOrientation->find('list');
        $this->set(compact('genders', 'countries', 'bodytypes', 'musictypes', 'cellproviders', 'maritalstatus', 'favoritefashionbrands', 'ethnicity', 'sexualorientations'));
        $type = $type ? $type : 'basic';
        $this->set('type', $type);
    }
    public function admin_edit($id = null) 
    {
        if (is_null($id) && empty($this->request->data)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->setAction('edit', $id);
    }
	public function _sendAdminActionMail($user_id, $email_template)
    {
        $user = $this->UserProfile->User->find('first', array(
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
}
?>