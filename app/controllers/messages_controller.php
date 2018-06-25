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
class MessagesController extends AppController
{
    public $name = 'Messages';
    public $components = array(
        'Email'
    );
    public $uses = array(
        'Message',
        'Attachment',
        'LabelsMessage',
        'LabelsUser',
        'MessageFilter',
        'Label',
        'User',
        'EmailTemplate',
        'SpamFilter',
    );
    public function beforeFilter() 
    {
        $this->Security->validatePost = false;
        $this->Security->disabledFields = array(
            'Attachment.filename',
            'Message.Archive',
            'Message.ReportSpam',
            'Message.Delete',
            'Message.more_action_1',
            'Message.more_action_2',
            'Message.send',
            'Message.save',
            'Message.Id',
            'Message.is_starred',
            'Message.label_slug',
            'Message.folder_type',
            'User.id',
            'Message.more_action_id',
        );
        parent::beforeFilter();
        if (!Configure::read('suspicious_detector.is_enabled') && !Configure::read('message.auto_suspend_message_on_system_flag')) {
            $this->Message->Behaviors->detach('SuspiciousWordsDetector');
        }
    }
    public function index($folder_type = 'inbox', $is_starred = 0, $label_slug = 'null') 
    {
        if ($folder_type == 'inbox') {
            $this->pageTitle = __l('Messages - Inbox');
            $condition = array(
                'Message.user_id' => $this->Auth->user('id') ,
                'Message.is_sender ' => 0,
                'Message.message_folder_id' => ConstMessageFolder::Inbox,
            );
        } elseif ($folder_type == 'sent') {
            $this->pageTitle = __l('Messages - Sent Mail');
            $condition = array(
                'Message.user_id' => $this->Auth->user('id') ,
                'Message.is_sender' => 1,
                'Message.message_folder_id' => ConstMessageFolder::SentMail
            );
        } elseif ($folder_type == 'draft') {
            $this->pageTitle = __l('Messages - Drafts');
            $condition = array(
                'Message.user_id' => $this->Auth->user('id') ,
                'Message.is_sender' => 1,
                'Message.message_folder_id' => ConstMessageFolder::Drafts
            );
        } elseif ($folder_type == 'spam') {
            $this->pageTitle = __l('Messages - Spam');
            $condition = array(
                'Message.user_id' => $this->Auth->user('id') ,
                'Message.message_folder_id' => ConstMessageFolder::Spam
            );
        } elseif ($folder_type == 'trash') {
            $this->pageTitle = __l('Messages - Trash');
            $condition = array(
                'Message.user_id' => $this->Auth->user('id') ,
                'Message.message_folder_id' => ConstMessageFolder::Trash
            );
        } elseif ($folder_type == 'all') {
            $this->pageTitle = __l('Messages - All');
            $condition['Message.user_id'] = $this->Auth->user('id');
        } else {
            $condition['Message.other_user_id'] = $this->Auth->User('id');
        }
        // To find all messges size
        $total_size = $this->Message->myUsedSpace();
        // Getting users inbox paging size
        $message_page_size = $this->Message->myMessagePageSize();
        $condition['Message.is_deleted'] = 0;
        $condition['Message.is_archived'] = 0;
        if ($is_starred) {
            $condition['Message.is_starred'] = 1;
        }
        if (!empty($label_slug)) {
            $label = $this->Label->find('first', array(
                'conditions' => array(
                    'Label.slug' => $label_slug
                ) ,
                'recursive' => -1
            ));
            if (!empty($label)) {
                $this->pageTitle = sprintf(__l('Messages - %s') , $label['Label']['name']);
                $label_message_id = $this->LabelsMessage->find('all', array(
                    'conditions' => array(
                        'LabelsMessage.label_id' => $label['Label']['id']
                    ) ,
                    'fields' => array(
                        'LabelsMessage.message_id'
                    ) ,
                    'recursive' => -1
                ));
                $message_ids = array();
                if (!empty($label_message_id)) {
                    foreach($label_message_id as $id) {
                        array_push($message_ids, $id['LabelsMessage']['message_id']);
                    }
                }
                $condition['Message.id'] = $message_ids;
            }
        }
        $condition['MessageContent.admin_suspend'] = 0;
        $this->paginate = array(
            'conditions' => $condition,
            'recursive' => 2,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.username'
                    )
                ) ,
                'OtherUser' => array(
                    'fields' => array(
                        'OtherUser.username'
                    )
                ) ,
                'MessageContent' => array(
                    'fields' => array(
                        'MessageContent.subject',
                        'MessageContent.message'
                    ) ,
                    'Attachment'
                ) ,
                'Label' => array(
                    'fields' => array(
                        'Label.name'
                    )
                )
            ) ,
            'order' => array(
                'Message.id' => 'desc'
            ) ,
            'limit' => $message_page_size
        );
        $labels = $this->LabelsUser->find('all', array(
            'conditions' => array(
                'LabelsUser.user_id' => $this->Auth->user('id')
            )
        ));
        $this->set('messages', $this->paginate());
        $this->set('labels', $labels);
        $this->set('folder_type', $folder_type);
        $this->set('is_starred', $is_starred);
        $this->set('label_slug', $label_slug);
        $this->set('user_id', $this->Auth->user('id'));
        $this->set('size', $total_size);
        $this->set('mail_options', $this->Message->getMessageOptionArray($folder_type));
        $allowed_size = higher_to_bytes(Configure::read('message.allowed_message_size') , Configure::read('message.allowed_message_size_unit'));
        // to find the percentage of the uploaded photos size of the user
        $size_percentage = ($allowed_size) ? ($total_size/$allowed_size) *100 : 0;
        $this->set('size_percentage', round($size_percentage));
    }
    public function inbox() 
    {
        $this->setAction('index', 'inbox');
    }
    public function sentmail() 
    {
        $this->setAction('index', 'sent');
    }
    public function drafts() 
    {
        $this->setAction('index', 'draft');
    }
    public function all() 
    {
        $this->setAction('index', 'all');
    }
    public function spam() 
    {
        $this->setAction('index', 'spam');
    }
    public function trash() 
    {
        $this->setAction('index', 'trash');
    }
    public function starred($folder_type = 'all') 
    {
        $this->setAction('index', $folder_type, 1);
        $this->pageTitle = __l('Messages - Starred');
    }
    public function label($label_slug = null) 
    {
        $this->setAction('index', 'all', 0, $label_slug);
    }
    public function v($id = null, $folder_type = 'inbox', $is_starred = 0, $label_slug = 'null') 
    {
        $this->pageTitle = __l('Message');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $message = $this->Message->find('first', array(
            'conditions' => array(
                'Message.id = ' => $id,
            ) ,
            'contain' => array(
                'MessageContent' => array(
                    'Attachment'
                ) ,
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username',
                        'User.email'
                    )
                ) ,
                'OtherUser' => array(
                    'fields' => array(
                        'OtherUser.email',
                        'OtherUser.user_type_id',
                        'OtherUser.username'
                    )
                ) ,
            ) ,
            'recursive' => 2,
        ));
        if (empty($message)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin && $message['Message']['user_id'] != $this->Auth->user('id') && $message['Message']['other_user_id'] != $this->Auth->user('id')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin && !empty($message['MessageContent']['admin_suspend'])) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $all_parents = array();
        if (!empty($message['Message']['parent_message_id'])) {
            $parent_message = $this->Message->find('first', array(
                'conditions' => array(
                    'Message.id' => $message['Message']['parent_message_id']
                ) ,
                'recursive' => 0
            ));
            $all_parents = $this->_findParent($parent_message['Message']['id']);
        }
        if ($message['Message']['is_read'] == 0 && $message['Message']['user_id'] == $this->Auth->user('id')) {
            $message['Message']['is_read'] = 1;
            $message['Message']['id'] = $message['Message']['id'];
            $this->Message->save($message);
        }
        //Its for display details -> Who got this message
        $select_to_details = $this->Message->find('all', array(
            'conditions' => array(
                'Message.message_content_id = ' => $message['Message']['message_content_id'],
            ) ,
            'recursive' => 0,
            'contain' => array(
                'User.email',
                'User.username',
                'User.id'
            )
        ));
        if (!empty($select_to_details)) {
            $receiverNames = array();
            $show_detail_to = array();
            foreach($select_to_details as $select_to_detail) {
                if ($select_to_detail['Message']['is_sender'] == 0) {
                    if ($this->Auth->User('id') != $select_to_detail['User']['id']) {
                        array_push($receiverNames, $select_to_detail['User']['username']);
                    }
                    array_push($show_detail_to, $select_to_detail['User']['username']);
                }
            }
            $show_detail_to = implode(', ', $show_detail_to);
            $receiverNames = implode(', ', $receiverNames);
            $this->set('show_detail_to', $show_detail_to);
            $this->set('receiverNames', $receiverNames);
        }
        $labels = $this->Message->Label->LabelsUser->find('all', array(
            'conditions' => array(
                'LabelsUser.user_id' => $this->Auth->user('id')
            )
        ));
        $this->pageTitle.= ' - ' . $message['MessageContent']['subject'];
        $this->set('message', $message);
        $this->set('all_parents', $all_parents);
        $this->set('user_email', $this->Auth->user('email'));
        $this->set('labels', $labels);
        $this->set('folder_type', $folder_type);
        $this->set('is_starred', $is_starred);
        $this->set('label_slug', $label_slug);
        $this->set('user_id', $this->Auth->user('id'));
        // set the mail options array
        $this->set('mail_options', $this->Message->getMessageOptionArray($folder_type));
        // Set the folder type link
        $back_link_msg = ($folder_type == 'all') ? __l('All mails') : $folder_type;
        $this->set('back_link_msg', $back_link_msg);
    }
    public function admin_update_status($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->params['named']['flag']) && ($this->request->params['named']['flag'] == 'active')) {
            $this->Message->MessageContent->updateAll(array(
                'MessageContent.is_system_flagged' => 1
            ) , array(
                'MessageContent.id' => $id
            ));
            $this->Session->setFlash(__l('Message has been flagged') , 'default', null, 'success');
        } elseif (!empty($this->request->params['named']['flag']) && ($this->request->params['named']['flag'] == 'deactivate')) {
            $this->Message->MessageContent->updateAll(array(
                'MessageContent.is_system_flagged' => 0
            ) , array(
                'MessageContent.id' => $id
            ));
            $this->Session->setFlash(__l('Message has been Unflagged') , 'default', null, 'success');
        } elseif (!empty($this->request->params['named']['flag']) && ($this->request->params['named']['flag'] == 'suspend')) {
            $this->Message->MessageContent->updateAll(array(
                'MessageContent.admin_suspend' => 1
            ) , array(
                'MessageContent.id' => $id
            ));
            $this->Session->setFlash(__l('Message has been suspend') , 'default', null, 'success');
        } elseif (!empty($this->request->params['named']['flag']) && ($this->request->params['named']['flag'] == 'unsuspend')) {
            $this->Message->MessageContent->updateAll(array(
                'MessageContent.admin_suspend' => 0
            ) , array(
                'MessageContent.id' => $id
            ));
            $this->Session->setFlash(__l('Message has been unsuspend') , 'default', null, 'success');
        }
        $this->redirect(array(
            'action' => 'index',
        ));
    }
    public function admin_update() 
    {
        if (!empty($this->request->data['Message'])) {
            $this->Message->Behaviors->detach('SuspiciousWordsDetector');
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $userIds = array();
            foreach($this->request->data['Message'] as $message_id => $is_checked) {
                if ($is_checked['id']) {
                    $messageIds[] = $message_id;
                }
            }
            if ($actionid && !empty($messageIds)) {
                if ($actionid == ConstMoreAction::Delete) {
                    foreach($messageIds as $id) {
                        $this->Message->delete($id, false);
                    }
                    $this->Session->setFlash(__l('Checked messages has been deleted') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Suspend) {
                    $this->Message->updateAll(array(
                        'MessageContent.admin_suspend' => 1
                    ) , array(
                        'Message.id' => $messageIds
                    ));
                    $this->Session->setFlash(__l('Checked messages has been Suspended') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Unsuspend) {
                    $this->Message->updateAll(array(
                        'MessageContent.admin_suspend' => 0
                    ) , array(
                        'Message.id' => $messageIds
                    ));
                    $this->Session->setFlash(__l('Checked messages has been Unsuspended') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Flagged) {
                    $this->Message->updateAll(array(
                        'MessageContent.is_system_flagged' => 1
                    ) , array(
                        'Message.id' => $messageIds
                    ));
                    $this->Session->setFlash(__l('Checked messages has been Flagged') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Unflagged) {
                    $this->Message->updateAll(array(
                        'MessageContent.is_system_flagged' => 0
                    ) , array(
                        'Message.id' => $messageIds
                    ));
                    $this->Session->setFlash(__l('Checked messages has been Unflagged') , 'default', null, 'success');
                }
            }
        }
        $this->redirect(Router::url('/', true) . $r);
    }
    public function delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Message->delete($id)) {
            $this->Session->setFlash(__l('Message deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Message->delete($id)) {
            $this->Session->setFlash(__l('Message deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function left_sidebar() 
    {
        $folder_type = !empty($this->request->params['named']['contacts']) ? $this->request->params['named']['folder_type'] : '';
        $is_starred = !empty($this->request->params['named']['contacts']) ? $this->request->params['named']['is_starred'] : '';
        $contacts = !empty($this->request->params['named']['contacts']) ? $this->request->params['named']['contacts'] : '';
        $compose = !empty($this->request->params['named']['contacts']) ? $this->request->params['named']['compose'] : '';
        $settings = !empty($this->request->params['named']['contacts']) ? $this->request->params['named']['settings'] : '';
        $id = $this->Auth->user('id');
        $inbox = $this->Message->find('count', array(
            'conditions' => array(
                'Message.user_id' => $this->Auth->user('id') ,
                'Message.is_sender' => 0,
                'Message.message_folder_id' => ConstMessageFolder::Inbox,
                'MessageContent.admin_suspend ' => 0,
                'Message.is_read' => 0,
                'Message.is_deleted' => 0,
                'Message.is_archived' => 0
            )
        ));
        $draft = $this->Message->find('count', array(
            'conditions' => array(
                'Message.user_id' => $this->Auth->user('id') ,
                'Message.is_sender' => 1,
                'Message.message_folder_id' => ConstMessageFolder::Drafts,
                'Message.is_deleted' => 0,
                'Message.is_archived' => 0,
                'MessageContent.admin_suspend ' => 0,
            )
        ));
        $spam = $this->Message->find('count', array(
            'conditions' => array(
                'Message.user_id' => $this->Auth->user('id') ,
                'Message.is_sender' => 0,
                'Message.message_folder_id' => ConstMessageFolder::Spam,
                'Message.is_read' => 0,
                'Message.is_deleted' => 0,
                'Message.is_archived' => 0,
                'MessageContent.admin_suspend ' => 0,
            )
        ));
        $stared = $this->Message->find('count', array(
            'conditions' => array(
                'Message.user_id' => $this->Auth->user('id') ,
                'Message.is_sender ' => 0,
                'Message.message_folder_id' => ConstMessageFolder::Inbox,
                // 'Message.is_read' => 0,
                'Message.is_deleted' => 0,
                'Message.is_archived' => 0,
                'Message.is_starred' => 1,
                'MessageContent.admin_suspend ' => 0,
            )
        ));
        $trash = $this->Message->find('count', array(
            'conditions' => array(
                'Message.user_id' => $this->Auth->user('id') ,
                'Message.message_folder_id' => ConstMessageFolder::Trash,
                'Message.is_read' => 0,
                'MessageContent.admin_suspend ' => 0,
            )
        ));
        $this->set('inbox', $inbox);
        $this->set('draft', $draft);
        $this->set('spam', $spam);
        $this->set('stared', $stared);
        $this->set('trash', $trash);
        $this->set('folder_type', $folder_type);
        $this->set('is_starred', $is_starred);
        $this->set('contacts', $contacts);
        $this->set('compose', $compose);
        $this->set('settings', $settings);
    }
    public function compose($id = null, $action = null) 
    {
        $this->pageTitle = __l('Messages - Compose');
        if (!empty($id)) {
            $parent_message = $this->Message->find('first', array(
                'conditions' => array(
                    'Message.id' => $id
                ) ,
                'contain' => array(
                    'MessageContent' => array(
                        'Attachment'
                    ) ,
                    'OtherUser'
                ) ,
                'recursive' => 2
            ));
            $all_parents = $this->_findParent($id);
            $this->set('parent_message', $parent_message);
            $this->set('id', $id);
            $this->set('action', $action);
        }
        if (!empty($this->request->data)) {
            // To take the admin privacy settings
            $is_saved = 0;
            if (!intval(Configure::read('messages.is_allow_send_messsage'))) {
                $this->Session->setFlash(__l('Message send is temporarily stopped. Please try again later.') , 'default', null, 'error');
                $this->redirect(array(
                    'action' => 'inbox'
                ));
            }
            $size = strlen($this->request->data['Message']['message']) +strlen($this->request->data['Message']['subject']);
            $to_users = array();
            if (!empty($this->request->data['Message']['to'])) {
                $to_users = explode(',', $this->request->data['Message']['to']);
            }
            if (!empty($to_users)) {
                //  to save message content
                $message_content['MessageContent']['subject'] = $this->request->data['Message']['subject'];
                $message_content['MessageContent']['message'] = $this->request->data['Message']['message'];
                if (!empty($this->request->data['Message']['message_content_id'])) {
                    $message_content['MessageContent']['id'] = $this->request->data['Message']['message_content_id'];
                    $this->Message->MessageContent->save($message_content);
                    $message_id = $this->request->data['Message']['message_content_id'];
                } else {
                    $this->Message->MessageContent->save($message_content);
                    $message_id = $this->Message->MessageContent->id;
                }
                if (!empty($this->request->data['Attachment'])) {
                    foreach($this->request->data['Attachment']['filename'] as $filename) {
                        if (!empty($filename['name'])) {
                            $attachment['Attachment']['filename'] = $filename;
                            $attachment['Attachment']['class'] = 'MessageContent';
                            $attachment['Attachment']['description'] = 'message';
                            $attachment['Attachment']['foreign_id'] = $message_id;
                            $this->Message->MessageContent->Attachment->create();
                            $this->Message->MessageContent->Attachment->save($attachment);
                            $size+= $filename['size'];
                        } elseif (!empty($this->request->data['Attachment']['id']) && $this->request->data['Attachment']['id']) {
                            $attachmnets = $this->Message->MessageContent->Attachment->find('all', array(
                                'conditions' => array(
                                    'Attachment.id' => $this->request->data['Attachment']['id']
                                ) ,
                            ));
                            foreach($attachmnets as $arrachment_array) {
                                $_attachment = array();
                                if (!is_dir("../media/MessageContent/" . $message_id)) {
                                    mkdir("../media/MessageContent/" . $message_id, 0777);
                                }
                                copy("../media/MessageContent/" . $arrachment_array['Attachment']['foreign_id'] . '/' . $arrachment_array['Attachment']['filename'], "../media/MessageContent/" . $message_id . '/' . $arrachment_array['Attachment']['filename']);
                                $_attachment['Attachment']['dir'] = "ProjectScreenshot/" . $message_id;
                                $_attachment['Attachment']['filename'] = $arrachment_array['Attachment']['filename'];
                                $_attachment['Attachment']['foreign_id'] = $message_id;
                                $_attachment['Attachment']['class'] = 'MessageContent';
                                $_attachment['Attachment']['filesize'] = filesize("../media/MessageContent/" . $message_id . '/' . $arrachment_array['Attachment']['filename']);
                                $sizes = getimagesize("../media/MessageContent/" . $message_id . '/' . $arrachment_array['Attachment']['filename']);
                                $_attachment['Attachment']['width'] = $sizes[0];
                                $_attachment['Attachment']['height'] = $sizes[1];
                                $_attachment['Attachment']['mimetype'] = $sizes['mime'];
                                $this->Message->MessageContent->Attachment->enableUpload(false);
                                $this->Message->MessageContent->Attachment->create();
                                $this->Message->MessageContent->Attachment->save($_attachment);
                            }
                        }
                    }
                }
                foreach($to_users as $user_to) {
                    // To find the user id of the user
                    $user = $this->User->find('first', array(
                        'conditions' => array(
                            'User.username' => trim($user_to)
                        ) ,
                        'fields' => array(
                            'User.id',
                            'User.email',
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($user)) {
                        $is_send_message = true;
                        if ($is_send_message) {
                            if (!empty($this->request->data['Message']['parent_message_id']) && $this->request->data['Message']['type'] != 'draft') {
                                $parent_id = $this->request->data['Message']['parent_message_id'];
                            } else {
                                $parent_id = 0;
                            }
                            if (!empty($this->request->data['Message']['save'])) {
                                $this->Session->setFlash(__l('Message has been saved successfully') , 'default', null, 'success');
                                if ($this->_saveMessage($this->Auth->user('id') , $user['User']['id'], $message_id, ConstMessageFolder::Drafts, 1, 0, $parent_id, $size)) {
                                    $this->Session->setFlash(__l('Message has been saved successfully') , 'default', null, 'success');
                                } else {
                                    $this->Session->setFlash(__l('Problem in saving message') , 'default', null, 'success');
                                }
                            } else {
                                $spamFilter = $this->SpamFilter->find('count', array(
                                    'conditions' => array(
                                        'SpamFilter.user_id' => $this->Auth->user('id') ,
                                        'SpamFilter.other_user_id' => $user['User']['id'],
                                    )
                                ));
                                if ($spamFilter) {
                                    $folder_id = ConstMessageFolder::Spam;
                                } else {
                                    $folder_id = ConstMessageFolder::Inbox;
                                }
                                // To save in inbox //
                                $is_saved_inbox = $this->_saveMessage($user['User']['id'], $this->Auth->user('id') , $message_id, $folder_id, 0, 0, $parent_id, $size);
                                // To save in sent iteams //
                                $is_saved = $this->_saveMessage($this->Auth->user('id') , $user['User']['id'], $message_id, ConstMessageFolder::SentMail, 1, 1, $parent_id, $size);
                                // To send email when post comments
                                if (Configure::read('messages.is_send_email_on_new_message')) {
                                    $this->_sendAlertOnNewMessage($user['User']['email'], $this->request->data['Message']['message'], $is_saved_inbox);
                                }
                                $this->Session->setFlash(__l('Message has been sent successfully') , 'default', null, 'success');
                            }
                        } else if (!empty($this->request->data['Message']['save'])) {
                            if (!$this->_saveMessage($this->Auth->user('id') , $user['User']['id'], $message_id, ConstMessageFolder::Drafts, 1, 0, '', $size)) {
                                $this->Session->setFlash(__l('Message saved successfully') , 'default', null, 'success');
                            } else {
                                $this->Session->setFlash(__l('Problem in saving message') , 'default', null, 'success');
                            }
                        } else {
                            $this->Session->setFlash(__l('Problem in sending message. You can send message only to your friends network') , 'default', null, 'error');
                        }
                    }
                }
            } else {
                $this->Session->setFlash(sprintf(__l('Please specify atleast one recipient')) , 'default', null, 'error');
                $this->redirect(array(
                    'action' => 'compose'
                ));
            }
            if (!empty($this->request->data['Message']['type']) and $this->request->data['Message']['type'] == 'draft') {
                //deleting the old draft message for this messsage
                if ($is_saved and !empty($this->request->data['Message']['parent_message_id'])) {
                    $this->request->data['Message']['id'] = $this->request->data['Message']['parent_message_id'];
                    $this->Message->delete($this->request->data['Message']['id']);
                }
            }
            $this->redirect(array(
                'action' => 'inbox'
            ));
        }
        if (!empty($parent_message)) {
            if (!empty($action)) {
                $this->request->data['Message']['message'] = $parent_message['MessageContent']['message'];
                $this->request->data['Message']['to'] = $parent_message['OtherUser']['username'];
                $this->request->data['Message']['parent_message_id'] = $parent_message['Message']['id'];
                switch ($action) {
                    case 'reply':
                        $this->request->data['Message']['subject'] = __l('Re:') . $parent_message['MessageContent']['subject'];
                        $this->set('all_parents', $all_parents);
                        $this->request->data['Message']['type'] = 'reply';
                        break;

                    case 'draft':
                        $this->request->data['Message']['subject'] = $parent_message['MessageContent']['subject'];
                        $this->request->data['Message']['type'] = 'draft';
                        $this->request->data['Message']['message_content_id'] = $parent_message['MessageContent']['id'];
                        break;

                    case 'forword':
                        $this->request->data['Message']['subject'] = __l('Fwd:') . $parent_message['MessageContent']['subject'];
                        $this->request->data['Message']['to'] = '';
                        break;
                }
            }
        }
        $user_settings = $this->Message->User->UserProfile->find('first', array(
            'conditions' => array(
                'UserProfile.user_id' => $this->Auth->user('id')
            ) ,
            'fields' => array(
                'UserProfile.message_page_size',
                'UserProfile.message_signature'
            ) ,
            'recursive' => -1
        ));
        if (!empty($user_settings['UserProfile']['message_signature'])) {
            if (!empty($this->request->data['Message']['message'])) {
                if ($action != 'draft') {
                    $this->request->data['Message']['message'].= $user_settings['UserProfile']['message_signature'];
                }
            } else {
                $this->request->data['Message']['message'] = $user_settings['UserProfile']['message_signature'];
            }
        }
        if (!empty($this->request->params['named']['user'])) {
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['user']
                ) ,
                'fields' => array(
                    'User.username'
                ) ,
                'recursive' => -1
            ));
            $this->request->data['Message']['to'] = $user['User']['username'];
        }
    }
    public function _sendAlertOnNewMessage($email, $message, $message_id) 
    {
        $email_replace = array(
            '##USERNAME##' => $this->Auth->user('username') ,
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##MESSAGE_LINK##' => Router::url(array(
                'controller' => 'messages',
                'action' => 'v',
                $message_id,
                'city' => $this->_prefixSlug
            ) , true) ,
            '##MESSAGE##' => $message
        );
        $email_message = $this->EmailTemplate->selectTemplate('New Message');
        $subject = strtr($email_message['subject'], $email_replace);
        $content = strtr($email_message['email_content'], $email_replace);
        // Send e-mail to users
        $this->Email->from = ($email_message['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email_message['from'];
        $this->Email->to = $email;
        $this->Email->subject = $subject;
        $this->Email->send($content);
    }
    public function admin_compose($hash = null, $action = null) 
    {
        $this->pageTitle = __l('Messages') . ' | ' . __l('Compose message');
        if (!empty($this->request->data)) {
            $condition = array();
            if ($this->request->data['Message']['to_user'] != '0') {
                if ($this->request->data['Message']['to_user'] == '2') {
                    $condition['User.is_active'] = 1;
                } else if ($this->request->data['Message']['to_user'] == '3') {
                    $condition['User.is_active'] = 0;
                }
                $users = $this->User->find('all', array(
                    'conditions' => $condition,
                    'recursive' => -1
                ));
                foreach($users as $user) {
                    $id[] = $user['User']['id'];
                    $email[] = $user['User']['email'];
                }
            }
            if (!empty($this->request->data['Message']['to'])) {
                $to_users = explode(",", $this->request->data['Message']['to']);
                foreach($to_users as $user_to) {
                    $user_id = $this->User->find('first', array(
                        'fields' => array(
                            'User.id',
                            'User.email'
                        ) ,
                        'recursive' => -1
                    ));
                    $id[] = $user_id['User']['id'];
                    $email[] = $user_id['User']['email'];
                }
            }
            $has_sent = false;
            if (!empty($id)) {
                //  to save message content
                $message_content['MessageContent']['subject'] = $this->request->data['Message']['subject'];
                $message_content['MessageContent']['message'] = $this->request->data['Message']['message'];
                $this->Message->MessageContent->save($message_content);
                $message_id = $this->Message->MessageContent->id;
                $size = strlen($this->request->data['Message']['message']) +strlen($this->request->data['Message']['subject']);
                foreach($id as $user_id) {
                    if ($this->_saveMessage($user_id, $this->Auth->User('id') , $message_id, 1, $is_sender = 0, $is_read = 0, '', $size)) {
                        $has_sent = true;
                    }
                }
            }
            if ($has_sent) {
                $this->Session->setFlash(__l('Message has been sent successfully') , 'default', null, 'success');
            }
            if (!empty($email)) {
                foreach($email as $user_email) {
                    $this->_sendMail($user_email, $this->request->data['Message']['subject'], $this->request->data['Message']['message']);
                }
            } else {
                $this->Session->setFlash(sprintf(__l('Problem in sending mail to the appropriate user')) , 'default', null, 'error');
            }
        }
        $option = array(
            0 => 'Select',
            1 => 'All users',
            2 => 'All approved users',
            3 => 'All pending users'
        );
        $this->set('user_id', $this->Auth->user('id'));
        $this->set('option', $option);
    }
    public function _sendMail($to, $subject, $body, $format = 'text') 
    {
        $from = Configure::read('site.no_reply_email');
        $subject = $subject;
        $this->Email->from = $from;
        $this->Email->to = $to;
        $this->Email->subject = $subject;
        $this->Email->sendAs = $format;
        return $this->Email->send($body);
    }
    public function _saveMessage($user_id, $other_user_id, $message_id, $folder_id, $is_sender = 0, $is_read = 0, $parent_id = null, $size) 
    {
        $message['Message']['message_content_id'] = $message_id;
        $message['Message']['user_id'] = $user_id;
        $message['Message']['other_user_id'] = $other_user_id;
        $message['Message']['message_folder_id'] = $folder_id;
        $message['Message']['is_sender'] = $is_sender;
        $message['Message']['is_read'] = $is_read;
        $message['Message']['parent_message_id'] = $parent_id;
        $message['Message']['size'] = $size;
        $this->Message->create();
        $this->Message->save($message);
        $id = $this->Message->id;
        $message['Message']['id'] = $id;
        $this->Message->save($message);
        return $id;
    }
    public function download($id = null, $attachment_id = null) 
    {
        //checking Authontication
        if (empty($id) or empty($attachment_id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $message = $this->Message->find('first', array(
            'conditions' => array(
                'Message.id =' => $id,
            ) ,
            'fields' => array(
                'MessageContent.id'
            ) ,
            'recursive' => 0
        ));
        if (empty($message)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $file = $this->Attachment->find('first', array(
            'conditions' => array(
                'Attachment.id =' => $attachment_id,
                'Attachment.class =' => 'MessageContent',
                'Attachment.description =' => 'message',
            ) ,
            'recursive' => -1
        ));
        if ($file['Attachment']['foreign_id'] != $message['MessageContent']['id']) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $filename = substr($file['Attachment']['filename'], 0, strrpos($file['Attachment']['filename'], '.'));
        $file_extension = substr($file['Attachment']['filename'], strrpos($file['Attachment']['filename'], '.') +1, strlen($file['Attachment']['filename']));
        $file_path = str_replace('\\', '/', 'media' . DS . $file['Attachment']['dir'] . DS . $file['Attachment']['filename']);
        // Code to download
        Configure::write('debug', 0);
        $this->view = 'Media';
        $this->autoLayout = false;
        $this->set('name', trim($filename));
        $this->set('download', true);
        $this->set('extension', trim($file_extension));
        $this->set('path', $file_path);
    }
    // public function move_to . One copy of this action is in search action
    // If do change change.. please also make in search action
    public function move_to() 
    {
        if (!empty($this->request->data)) {
            if ((isset($this->request->data['Message']['more_action_1']) and $this->request->data['Message']['more_action_1'] == 'Create Label') or (isset($this->request->data['Message']['more_action_2']) and $this->request->data['Message']['more_action_2'] == 'Create Label')) {
                $this->redirect(array(
                    'controller' => 'labels',
                    'action' => 'add',
                ));
            }
            if (!empty($this->request->data['Message']['Id'])) {
                // To show alert message when message is not selected
                // By checking if any of the (Message id,value) pair have value=1
                if (!in_array('1', $this->request->data['Message']['Id'])) {
                    $this->Session->setFlash('No messages selected.', 'default', null, 'error');
                } else {
                    $do_action = '';
                    if (isset($this->request->data['Message']['more_action_1']) and $this->request->data['Message']['more_action_1'] != 'More actions' && $this->request->data['Message']['more_action_1'] != 'Apply label') {
                        $do_action = $this->request->data['Message']['more_action_1'];
                    } elseif (isset($this->request->data['Message']['more_action_2']) and $this->request->data['Message']['more_action_2'] != 'More actions' && $this->request->data['Message']['more_action_2'] != 'Apply label') {
                        $do_action = $this->request->data['Message']['more_action_2'];
                    }
                    foreach($this->request->data['Message']['Id'] AS $message_id => $is_checked) {
                        if ($is_checked) {
                            //	For make archived.  -- Change Status
                            if (!empty($this->request->data['Message']['Archive']) || !empty($this->request->data['Message']['NotSpam'])) {
                                $this->_make_archive($message_id);
                            }
                            //	For make spam.	-- Change folder
                            if (!empty($this->request->data['Message']['ReportSpam'])) {
                                $this->_addSpamMessage($message_id);
                                $this->_change_folder($message_id, ConstMessageFolder::Spam);
                            }
                            //	For make delete.	-- Change folder
                            if (!empty($this->request->data['Message']['Delete'])) {
                                if ($this->request->data['Message']['folder_type'] == 'trash') {
                                    $this->Message->updateAll(array(
                                        'Message.is_deleted' => 1
                                    ) , array(
                                        'Message.id' => $message_id,
                                        'Message.user_id' => $this->Auth->user('id')
                                    ));
                                }
                                $this->_change_folder($message_id, ConstMessageFolder::Trash);
                            }
                            //	Its from the Dropdown
                            switch ($do_action) {
                                case 'Mark as read':
                                    $this->_make_read($message_id, 1);
                                    $this->Session->setFlash('Selected messages ware marked read successfully.', 'default', null, 'success');
                                    break;

                                case 'Mark as unread':
                                    $this->_make_read($message_id, 0);
                                    $this->Session->setFlash('Selected messages ware meared unread successfully.', 'default', null, 'success');
                                    break;

                                case 'Add star':
                                    $this->_make_starred($message_id, 1);
                                    $this->Session->setFlash('Added star selected messages.', 'default', null, 'success');
                                    break;

                                case 'Remove star':
                                    $this->_make_starred($message_id, 0);
                                    $this->Session->setFlash('Removed star for the selected messages.', 'default', null, 'success');
                                    break;

                                case 'Move to inbox':
                                    $this->_change_folder($message_id, ConstMessageFolder::Inbox);
                                    $message = $this->Message->find('first', array(
                                        'conditions' => array(
                                            'Message.user_id =' => $this->Auth->User('id') ,
                                            'Message.id =' => $message_id
                                        ) ,
                                        'fields' => array(
                                            'Message.id',
                                            'Message.user_id',
                                            'Message.other_user_id',
                                            'Message.parent_message_id',
                                            'Message.is_sender',
                                        ) ,
                                        'recursive' => -1
                                    ));
                                    if ($message['Message']['is_sender'] == 1) {
                                        $this->Message->id = $message_id;
                                        $this->Message->saveField('is_sender', 2);
                                    }
                                    break;

                                default:
                                    //	Apply label.
                                    $is_apply = sizeof(explode('##apply##', $do_action)) -1;
                                    if ($is_apply) {
                                        $_do_action = str_replace('##apply##', '', $do_action);
                                        $label = $this->Label->find('first', array(
                                            'conditions' => array(
                                                'Label.slug' => $_do_action
                                            )
                                        ));
                                        if (!empty($label)) {
                                            $is_exist = $this->LabelsMessage->find('count', array(
                                                'conditions' => array(
                                                    'LabelsMessage.label_id' => $label['Label']['id'],
                                                    'LabelsMessage.message_id' => $message_id
                                                )
                                            ));
                                            if ($is_exist == 0) {
                                                $labelMessage['LabelsMessage']['label_id'] = $label['Label']['id'];
                                                $labelMessage['LabelsMessage']['message_id'] = $message_id;
                                                $this->Message->LabelsMessage->create();
                                                $this->Message->LabelsMessage->save($labelMessage);
                                            }
                                        }
                                    }
                                    //	Remove label.
                                    $is_remove = sizeof(explode('##remove##', $do_action)) -1;
                                    if ($is_remove) {
                                        $_do_action = str_replace('##remove##', '', $do_action);
                                        $label = $this->Label->find('first', array(
                                            'conditions' => array(
                                                'Label.slug' => $_do_action
                                            )
                                        ));
                                        if (!empty($label)) {
                                            $labelMessages = $this->LabelsMessage->find('first', array(
                                                'conditions' => array(
                                                    'LabelsMessage.label_id' => $label['Label']['id'],
                                                    'LabelsMessage.message_id' => $message_id
                                                )
                                            ));
                                            if (!empty($labelMessages)) {
                                                $this->LabelsMessage->delete($labelMessages['LabelsMessage']['id']);
                                            }
                                        }
                                    }
                                    break;
                            }
                        }
                    }
                }
            }
            // to redirect to to the previous page
            $folder_type = $this->request->data['Message']['folder_type'];
            $is_starred = $this->request->data['Message']['is_starred'];
            $label_slug = $this->request->data['Message']['label_slug'];
            if (!empty($label_slug) && $label_slug != 'null') {
                $this->redirect(array(
                    'action' => 'label',
                    $label_slug
                ));
            } elseif (!empty($is_starred)) {
                $this->redirect(array(
                    'action' => 'starred'
                ));
            } else {
                if ($folder_type == 'sent') $folder_type = 'sentmail';
                elseif ($folder_type == 'draft') $folder_type = 'drafts';
                $this->redirect(array(
                    'action' => $folder_type
                ));
            }
        } else {
            $this->redirect(array(
                'action' => 'index'
            ));
        }
    }
    public function star($message_id, $current_star) 
    {
        $message = '';
        $message['Message']['id'] = $message_id;
        if ($current_star == 'star') {
            $message['Message']['is_starred'] = 1;
        } else {
            $message['Message']['is_starred'] = 0;
        }
        if ($this->Message->save($message)) {
            if (!$this->RequestHandler->isAjax()) {
                if (!empty($message['Message']['is_starred'])) {
                    $this->Session->setFlash('Message has been starred', 'default', null, 'success');
                } else {
                    $this->Session->setFlash('Message has been unstarred', 'default', null, 'success');
                }
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                if ($message['Message']['is_starred'] == 1) {
                    $message = $message_id . '/unstar';
                } else {
                    $message = $message_id . '/star';
                }
            }
        }
        $this->set('message', $message);
    }
    public function _make_read($message_id, $read_status) 
    {
        $this->Message->id = $message_id;
        $this->Message->saveField('is_read', $read_status);
    }
    public function _make_starred($message_id, $starred_status) 
    {
        $this->Message->id = $message_id;
        $this->Message->saveField('is_starred', $starred_status);
    }
    public function _make_archive($message_id) 
    {
        $this->Message->id = $message_id;
        $this->Message->saveField('is_archived', 1);
    }
    public function _change_folder($message_id, $folder_id) 
    {
        $this->Message->id = $message_id;
        $this->Message->saveField('message_folder_id', $folder_id);
    }
    public function _addSpamMessage($message_id) 
    {
        $message = $this->Message->find('first', array(
            'conditions' => array(
                'Message.id' => $message_id,
            ) ,
            'recursive' => 0
        ));
        $spamFilterCount = $this->SpamFilter->find('count', array(
            'conditions' => array(
                'SpamFilter.user_id' => $message['Message']['user_id'],
                'SpamFilter.other_user_id' => $message['Message']['other_user_id'],
            ) ,
            'recursive' => -1
        ));
        if (!$spamFilterCount) {
            $spamFilter['SpamFilter']['user_id'] = $message['Message']['user_id'];
            $spamFilter['SpamFilter']['other_user_id'] = $message['Message']['other_user_id'];
            $spamFilter['SpamFilter']['subject'] = $message['MessageContent']['subject'];
            $spamFilter['SpamFilter']['content'] = $message['MessageContent']['message'];
            $this->SpamFilter->create();
            $this->SpamFilter->save($spamFilter, false);
        }
    }
    public function search($hash = null) 
    {
        if (isset($_SESSION['named_url'][$hash])) {
            if ($this->isValidNamedHash($_SESSION['named_url'][$hash], $hash)) {
                $url = $_SESSION['named_url'][$hash];
                foreach($url as $key => $value) {
                    $this->request->params['named'][$key] = $value;
                }
            }
            $this->set('hash', $hash);
        }
        if (!empty($this->request->params)) {
            // this is copy of move_to public function
            if (!empty($this->request->data['Message']['Id'])) {
                $do_action = '';
                if ($this->request->params['Message']['more_action_1'] != 'More actions' && $this->request->params['Message']['more_action_1'] != 'Apply label') {
                    $do_action = $this->request->params['Message']['more_action_1'];
                } elseif ($this->request->params['Message']['more_action_2'] != 'More actions' && $this->request->params['Message']['more_action_2'] != 'Apply label') {
                    $do_action = $this->request->params['Message']['more_action_2'];
                }
                foreach($this->request->params['Message']['Id'] AS $message_id => $is_checked) {
                    if ($is_checked) {
                        //	For make archived.  -- Change Status
                        if (!empty($this->request->params['Message']['Archive'])) {
                            MessagesController::_make_archive($message_id);
                        }
                        //	For make spam.	-- Change folder
                        if (!empty($this->request->params['Message']['ReportSpam'])) {
                            MessagesController::_change_folder($message_id, ConstMessageFolder::Spam);
                        }
                        //	For make delete.	-- Change folder
                        if (!empty($this->request->params['Message']['Delete'])) {
                            MessagesController::_change_folder($message_id, ConstMessageFolder::Trash);
                        }
                        //	Its from the Dropdown
                        if ($do_action == 'Mark as read') {
                            MessagesController::_make_read($message_id, 1);
                        } elseif ($do_action == 'Mark as unread') {
                            MessagesController::_make_read($message_id, 0);
                        } elseif ($do_action == 'Add star') {
                            MessagesController::_make_starred($message_id, 1);
                        } elseif ($do_action == 'Remove star') {
                            MessagesController::_make_starred($message_id, 0);
                        } elseif (!empty($do_action)) {
                            //	Apply label.
                            $is_apply = sizeof(explode('##apply##', $do_action)) -1;
                            if ($is_apply) {
                                $_do_action = str_replace('##apply##', '', $do_action);
                                $label = $this->Label->find('first', array(
                                    'conditions' => array(
                                        'Label.slug' => $_do_action
                                    )
                                ));
                                if (!empty($label)) {
                                    $is_exist = $this->LabelsMessage->find('count', array(
                                        'conditions' => array(
                                            'LabelsMessage.label_id' => $label['Label']['id'],
                                            'LabelsMessage.message_id' => $message_id
                                        )
                                    ));
                                    if ($is_exist == 0) {
                                        $labelMessage['LabelsMessage']['label_id'] = $label['Label']['id'];
                                        $labelMessage['LabelsMessage']['message_id'] = $message_id;
                                        $this->Message->LabelsMessage->create();
                                        $this->Message->LabelsMessage->save($labelMessage);
                                    }
                                }
                            }
                            //	Remove label.
                            $is_remove = sizeof(explode('##remove##', $do_action)) -1;
                            if ($is_remove) {
                                $_do_action = str_replace('##remove##', '', $do_action);
                                $label = $this->Label->find('first', array(
                                    'conditions' => array(
                                        'Label.slug' => $_do_action
                                    )
                                ));
                                if (!empty($label)) {
                                    $labelMessages = $this->LabelsMessage->find('first', array(
                                        'conditions' => array(
                                            'LabelsMessage.label_id' => $label['Label']['id'],
                                            'LabelsMessage.message_id' => $message_id
                                        )
                                    ));
                                    if (!empty($labelMessages)) {
                                        $this->LabelsMessage->delete($labelMessages['LabelsMessage']['id']);
                                    }
                                }
                            }
                        }
                    }
                }
            } //More Action End\
            $this->pageTitle = __l('Search Results');
            if (!empty($this->request->data)) {
                $this->request->data['Message']['user_id'] = $this->Auth->User('id');
                $this->request->params['named']['search'] = $this->request->data['Message']['search'];
                $this->request->params['named']['from'] = $this->request->data['Message']['from'];
                $this->request->params['named']['to'] = $this->request->data['Message']['to'];
                $this->request->params['named']['subject'] = $this->request->data['Message']['subject'];
                $this->request->params['named']['has_the_words'] = $this->request->data['Message']['has_the_words'];
                $this->request->params['named']['doesnt_have'] = $this->request->data['Message']['doesnt_have'];
                $this->request->params['named']['from_date'] = $this->request->data['Message']['from_date'];
                $this->request->params['named']['to_date'] = $this->request->data['Message']['to_date'];
                //	$this->request->params['form']['advanced_search']=$this->request->data['Message']['advanced_search'];
                $this->request->params['named']['search_by'] = $this->request->data['Message']['search_by'];
                $this->request->params['named']['has_attachment'] = $this->request->data['Message']['has_attachment'] ? $this->request->data['Message']['has_attachment'] : '0';
            }
            $condition = array();
            $search = isset($this->request->params['named']['search']) ? $this->request->params['named']['search'] : '';
            $from = isset($this->request->params['named']['from']) ? $this->request->params['named']['from'] : '';
            $to = isset($this->request->params['named']['to']) ? $this->request->params['named']['to'] : '';
            $subject = isset($this->request->params['named']['subject']) ? $this->request->params['named']['subject'] : '';
            $has_the_words = isset($this->request->params['named']['has_the_words']) ? $this->request->params['named']['has_the_words'] : '';
            $doesnt_have = isset($this->request->params['named']['doesnt_have']) ? $this->request->params['named']['doesnt_have'] : '';
            $from_date = isset($this->request->params['named']['from_date']) ? $this->request->params['named']['from_date'] : '';
            $to_date = isset($this->request->params['named']['to_date']) ? $this->request->params['named']['to_date'] : '';
            $advanced_search = isset($this->request->params['named']['advanced_search']) ? $this->request->params['named']['advanced_search'] : '';
            $search_by = isset($this->request->params['named']['search_by']) ? $this->request->params['named']['search_by'] : '';
            $has_attachment = ($this->request->params['named']['has_attachment']) ? 1 : 0;
            $condition['is_deleted != '] = 1;
            $condition['is_archived != '] = 1;
            if (!empty($subject)) {
                $condition[] = array(
                    'MessageContent.subject LIKE ' => '%' . $subject . '%',
                );
            }
            if (!empty($from)) {
                $from_condition = '';
                $from_users = $this->Message->User->find('first', array(
                    'conditions' => array(
                        'or' => array(
                            'User.email LIKE ' => '%' . $from . '%',
                            'User.username LIKE ' => '%' . $from . '%'
                        )
                    ) ,
                    'recursive' => -1
                ));
                $this->request->data['Message']['from_user_id'] = $from_users['User']['id'];
                $which_user = '';
                if ($this->Auth->User('id') == $from_users['User']['id']) {
                    $which_user = 'user_id';
                    $condition['Message.is_sender'] = 1;
                } else {
                    $which_user = 'other_user_id';
                    $condition['Message.is_sender'] = 0;
                }
                $condition['Message.' . $which_user] = $from_users['User']['id'];
            }
            if (!empty($to)) {
                $to_condition = '';
                $to_users = $this->Message->User->find('first', array(
                    'conditions' => array(
                        'or' => array(
                            'User.email LIKE ' => '%' . $to . '%',
                            'User.username LIKE ' => '%' . $to . '%'
                        )
                    ) ,
                    'recursive' => -1
                ));
                $this->request->data['Message']['to_user_id'] = $to_users['User']['id'];
                $check_message_content = array();
                $from_user = isset($from_users['User']['id']) ? $from_users['User']['id'] : $this->Auth->User('id');
                $check_messages = $this->Message->find('all', array(
                    'conditions' => array(
                        'Message.other_user_id =' => $to_users['User']['id'],
                        'Message.user_id =' => $from_user,
                    ) ,
                    'recursive' => -1
                ));
                foreach($check_messages as $check_message) {
                    $check_message_content[] = $check_message['Message']['message_content_id'];
                }
                if ($check_message_content) {
                    $condition['Message.message_content_id'] = $check_message_content;
                }
                $condition['Message.user_id'] = $this->Auth->User('id');
            }
            if (!empty($search_by)) {
                if ($search_by == 'Inbox') {
                    $condition['Message.message_folder_id'] = ConstMessageFolder::Inbox;
                    $condition['Message.is_sender'] = 0;
                    $condition['Message.user_id'] = $this->Auth->User('id');
                } else if ($search_by == 'Starred') {
                    $condition['Message.user_id'] = $this->Auth->User('id');
                    $condition['Message.is_starred'] = 1;
                    $condition['Message.user_id'] = $this->Auth->User('id');
                } else if ($search_by == 'Sent Mail') {
                    $condition['Message.message_folder_id'] = ConstMessageFolder::SentMail;
                    $condition['Message.is_sender'] = 1;
                    $condition['Message.user_id'] = $this->Auth->User('id');
                } else if ($search_by == 'Drafts') {
                    $condition['Message.message_folder_id'] = ConstMessageFolder::Drafts;
                    $condition['Message.user_id'] = $this->Auth->User('id');
                } else if ($search_by == 'Spam') {
                    $condition['Message.message_folder_id'] = ConstMessageFolder::Spam;
                    $condition['Message.user_id'] = $this->Auth->User('id');
                } else if ($search_by == 'Trash') {
                    $condition['Message.message_folder_id'] = ConstMessageFolder::Trash;
                    $condition['Message.user_id'] = $this->Auth->User('id');
                } else if ($search_by == 'Read Mail') {
                    $condition['Message.is_sender'] = 0;
                    $condition['Message.is_read'] = 1;
                    $condition['Message.user_id'] = $this->Auth->User('id');
                } else if ($search_by == 'Unread Mail') {
                    $condition['Message.is_sender'] = 0;
                    $condition['Message.is_read'] = 0;
                    $condition['Message.user_id'] = $this->Auth->User('id');
                } else if ($search_by == 'All Mail') {
                    $condition['Message.user_id'] = $this->Auth->User('id');
                }
            }
            if (!empty($search)) {
                $check_message = array();
                $find_mail_users = $this->Message->User->find('first', array(
                    'conditions' => array(
                        'or' => array(
                            'User.email LIKE ' => '%' . $search . '%',
                            'User.username LIKE ' => '%' . $search . '%'
                        )
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($find_mail_users['User']['id'])) {
                    $condition['Message.other_user_id'] = $find_mail_users['User']['id'];
                } else {
                    $condition['or'] = array(
                        'Messagecontent.subject LIKE ' => '%' . $search . '%',
                        'Messagecontent.message LIKE ' => '%' . $search . '%'
                    );
                }
            }
            if (!empty($from_date)) {
                $condition['Message.created >= '] = $from_date;
            }
            if (!empty($to_date)) {
                $condition['Message.created <= '] = $to_date;
            }
            $this->set('hasattachment', 0);
            if (!empty($has_attachment)) {
                $this->set('hasattachment', 1);
            }
            if (!empty($has_the_words)) {
                $condition[] = array(
                    'or' => array(
                        'MessageContent.subject LIKE ' => '%' . $has_the_words . '%',
                        'MessageContent.message LIKE ' => '%' . $has_the_words . '%'
                    )
                );
            }
            if (!empty($doesnt_have)) {
                $condition[] = array(
                    'and' => array(
                        'MessageContent.subject NOT LIKE ' => '%' . $doesnt_have . '%',
                        'MessageContent.message NOT LIKE ' => '%' . $doesnt_have . '%'
                    )
                );
            }
            $condition['Message.user_id'] = $this->Auth->User('id');
            $whichSearch = 'advanced';
            $message_page_size = $this->User->UserProfile->find('first', array(
                'conditions' => array(
                    'UserProfile.user_id' => $this->Auth->user('id')
                ) ,
                'fields' => array(
                    'UserProfile.message_page_size'
                )
            ));
            if (!empty($message_page_size['UserSetting']['message_page_size'])) {
                $limit = $message_page_size['UserSetting']['message_page_size'];
            } else {
                $limit = Configure::read('messages.page_size');
            }
            if (!empty($this->request->data)) {
                $messageFilter['MessageFilter'] = $this->request->data['Message'];
                $this->MessageFilter->create();
                $this->MessageFilter->save($messageFilter);
            }
            $this->paginate = array(
                'conditions' => $condition,
                'recursive' => 1,
                'contain' => array(
                    'User' => array(
                        'fields' => array(
                            'User.username'
                        )
                    ) ,
                    'OtherUser' => array(
                        'fields' => array(
                            'OtherUser.username'
                        )
                    ) ,
                    'MessageContent' => array(
                        'Attachment' => array(
                            'fields' => array(
                                'Attachment.id'
                            )
                        ) ,
                        'fields' => array(
                            'MessageContent.subject',
                            'MessageContent.message'
                        )
                    )
                ) ,
                'order' => array(
                    'Message.created DESC'
                ) ,
                'limit' => $limit
            );
            $this->set('messages', $this->paginate());
        }
        $labels = $this->LabelsUser->find('all', array(
            'conditions' => array(
                'LabelsUser.user_id' => $this->Auth->user('id')
            ) ,
            'recursive' => -1
        ));
        $options = array();
        $options['More actions'] = __l('---- More actions ----');
        $options['Mark as read'] = __l('Mark as read');
        $options['Mark as unread'] = __l('Mark as unread');
        $options['Add star'] = __l('Add star');
        $options['Remove star'] = __l('Remove star');
        if (!empty($labels)) {
            $options['Apply label'] = __l('----Apply label----');
            foreach($labels as $label) {
                $options['##apply##' . $label['Label']['slug']] = $label['Label']['name'];
            }
            $options['Remove label'] = __l('----Remove label----');
            foreach($labels as $label) {
                $options['##remove##' . $label['Label']['slug']] = $label['Label']['name'];
            }
        }
        $this->set('user_id', $this->Auth->user('id'));
        $this->set('more_option', $options);
    }
    public function settings() 
    {
        $this->pageTitle.= __l('Settings');
        $setting = $this->Message->User->UserProfile->find('first', array(
            'conditions' => array(
                'UserProfile.user_id' => $this->Auth->user('id')
            ) ,
            'fields' => array(
                'UserProfile.user_id',
                'UserProfile.id',
                'UserProfile.message_page_size',
                'UserProfile.message_signature'
            )
        ));
        if (!empty($this->request->data)) {
            $this->Message->User->UserProfile->set($this->request->data);
            if ($this->Message->User->UserProfile->validates()) {
                if (empty($setting)) {
                    $this->Message->User->UserProfile->create();
                    $this->request->data['UserProfile']['user_id'] = $this->Auth->user('id');
                } else {
                    $this->request->data['UserProfile']['id'] = $setting['UserProfile']['id'];
                }
                $this->Message->User->UserProfile->save($this->request->data);
                $this->Session->setFlash(__l('Message Settings has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('Message Settings could not be updated') , 'default', null, 'error');
            }
        } else {
            $this->request->data['UserProfile']['message_page_size'] = !empty($setting['UserProfile']['message_page_size']) ? $setting['UserProfile']['message_page_size'] : Configure::read('messages.page_size');
            $this->request->data['UserProfile']['message_signature'] = !empty($setting['UserProfile']['message_signature']) ? $setting['UserProfile']['message_signature'] : '';
            $this->set($this->request->data);
            $this->set('user_id', $this->Auth->user('id'));
        }
    }
    public function _findParent($id = null) 
    {
        $all_parents = array();
        for ($i = 0;; $i++) {
            $parent_message = $this->Message->find('first', array(
                'conditions' => array(
                    'Message.id' => $id
                ) ,
                'recursive' => 0
            ));
            array_unshift($all_parents, $parent_message);
            if ($parent_message['Message']['parent_message_id'] != 0) {
                $parent_message_data = $this->Message->find('first', array(
                    'conditions' => array(
                        'Message.id' => $parent_message['Message']['parent_message_id']
                    ) ,
                    'recursive' => 0
                ));
                $id = $parent_message_data['Message']['id'];
            } else {
                break;
            }
        }
        return $all_parents;
    }
    public function home_sidebar() 
    {
        $inbox = $this->Message->find('count', array(
            'conditions' => array(
                'Message.user_id' => $this->Auth->user('id') ,
                'Message.is_sender' => 0,
                'Message.message_folder_id' => ConstMessageFolder::Inbox,
                'Message.is_read' => 0,
                'Message.is_deleted' => 0,
                'Message.is_archived' => 0
            )
        ));
        $friend_request = $this->Message->find('count', array(
            'conditions' => array(
                'Message.user_id' => $this->Auth->user('id') ,
                'Message.is_sender' => 0,
                'Message.message_folder_id' => ConstMessageFolder::Inbox,
                'Message.is_read' => 0,
                'Message.is_deleted' => 0,
                'Message.is_archived' => 0,
                'MessageContent.subject LIKE ' => '%' . 'has requested to be your friend' . '%'
            ) ,
            'recursive' => 1
        ));
        $referer_request = $this->Message->find('count', array(
            'conditions' => array(
                'Message.user_id' => $this->Auth->user('id') ,
                'Message.is_sender' => 0,
                'Message.message_folder_id' => ConstMessageFolder::Inbox,
                'Message.is_read' => 0,
                'Message.is_deleted' => 0,
                'Message.is_archived' => 0,
                'MessageContent.subject' => 'Reference Request'
            ) ,
            'recursive' => 1
        ));
        $this->set('inbox', $inbox);
        $this->set('friend_request', $friend_request);
        $this->set('referer_request', $referer_request);
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Messages');
        $this->Message->recursive = 1;
        $conditions['Message.is_sender'] = 1;
        if (!empty($this->request->data['Message']['username']) || !empty($this->request->params['named']['from'])) {
            $this->request->data['Message']['username'] = !empty($this->request->data['Message']['username']) ? $this->request->data['Message']['username'] : $this->request->params['named']['from'];
            $conditions['User.username'] = $this->request->data['Message']['username'];
            $this->request->params['named']['from'] = $this->request->data['Message']['username'];
        }
        if (!empty($this->request->data['Message']['other_username']) || !empty($this->request->params['named']['to'])) {
            $this->request->data['Message']['other_username'] = !empty($this->request->data['Message']['other_username']) ? $this->request->data['Message']['other_username'] : $this->request->params['named']['to'];
            $conditions['OtherUser.username'] = $this->request->data['Message']['other_username'];
            $this->request->params['named']['to'] = $this->request->data['Message']['other_username'];
        }
        if (isset($this->request->params['named']['filter_id'])) {
            $this->request->data['Message']['filter_id'] = $this->request->params['named']['filter_id'];
        }
        if (!empty($this->request->data['Message']['filter_id'])) {
            if ($this->request->data['Message']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['MessageContent.admin_suspend'] = 1;
                $this->pageTitle.= __l(' - Suspend ');
            } elseif ($this->request->data['Message']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['MessageContent.is_system_flagged'] = 1;
                $this->pageTitle.= __l(' - Flagged ');
            }
            $this->request->params['named']['filter_id'] = $this->request->data['Message']['filter_id'];
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Message.created) <= '] = 0;
            $this->pageTitle.= __l(' - Messages sent today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Message.created) <= '] = 7;
            $this->pageTitle.= __l(' - Messages sent this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Message.created) <= '] = 30;
            $this->pageTitle.= __l(' - Messages sent this month');
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'Message.id' => 'desc'
            ) ,
        );
        $this->set('suspended', $this->Message->find('count', array(
            'conditions' => array(
                'MessageContent.admin_suspend = ' => 1,
                'Message.is_sender' => 1,
            )
        )));
        $this->set('system_flagged', $this->Message->find('count', array(
            'conditions' => array(
                'MessageContent.is_system_flagged = ' => 1,
                'Message.is_sender' => 1,
            )
        )));
        $this->set('all', $this->Message->find('count', array(
            'conditions' => array(
                'Message.is_sender' => 1,
            )
        )));
        $this->Message->User->validate = array();
        $moreActions = $this->Message->moreActions;
        $this->set(compact('moreActions'));
        $this->set('messages', $this->paginate());
    }
}
?>