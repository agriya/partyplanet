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
class LabelsController extends AppController
{
    public $name = 'Labels';
    public function beforeFilter() 
    {
        $this->Security->validatePost = false;
        parent::beforeFilter();
    }
    public function index() 
    {
        $pageTitle = __l('Labels');
        $user_lables = $this->Label->LabelsUser->find('list', array(
            'conditions' => array(
                'LabelsUser.user_id' => $this->Auth->user('id')
            ) ,
            'fields' => array(
                'LabelsUser.label_id'
            )
        ));
        $this->Label->recursive = 0;
        $this->paginate = array(
            'conditions' => array(
                'Label.id' => $user_lables
            ) ,
            'contain' => array(
                'User' => array(
                    'conditions' => array(
                        'LabelsUser.user_id' => $this->Auth->user('id')
                    ) ,
                    'LabelsUser' => array(
                        'fields' => array(
                            'LabelsUser.label_id'
                        ) ,
                    ) ,
                    'fields' => array(
                        'User.id'
                    )
                )
            ) ,
        );
        $this->set('labels', $this->paginate());
        if (isset($this->request->params['label_slug'])) $this->set('label_slug', $this->request->params['label_slug']);
        if (isset($this->request->params['view_type']) && $this->request->params['view_type'] == 'compact') $this->render('lst');
        $this->set('title_for_layout', $pageTitle);
    }
    public function add() 
    {
        $pageTitle = __l('Add Label');
        if (!empty($this->request->data)) {
            $label_id = $this->Label->findOrSaveAndGetId($this->request->data['Label']['name']);
            if (!empty($label_id)) {
                $is_exist = $this->Label->LabelsUser->find('count', array(
                    'conditions' => array(
                        'LabelsUser.label_id' => $label_id,
                        'LabelsUser.user_id' => $this->Auth->user('id')
                    )
                ));
                if ($is_exist == 0) {
                    $this->request->data['LabelsUser']['label_id'] = $label_id;
                    $this->request->data['LabelsUser']['user_id'] = $this->Auth->user('id');
                    $this->Label->LabelsUser->create();
                    $this->Label->LabelsUser->save($this->request->data);
                    $this->Session->setFlash(__l(' Label has been added') , 'default', null, 'success');
                    $this->redirect(array(
                        'controller' => 'labels',
                        'action' => 'index'
                    ));
                } else {
                    $this->Session->setFlash(__l(' Labels already exist.') , 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l(' Label could not be added. Please, try again') , 'default', null, 'error');
            }
        }
        $this->set('title_for_layout', $pageTitle);
    }
    public function edit($id = null) 
    {
        $pageTitle = __l('Edit Labels User');
        if (is_null($id)) {
            $label = $this->Label->LabelsUser->find('first', array(
                'conditions' => array(
                    'LabelsUser.id' => $this->request->data['Label']['id']
                )
            ));
        } else {
            $label = $this->Label->LabelsUser->find('first', array(
                'conditions' => array(
                    'LabelsUser.id' => $id
                )
            ));
        }
        if (!empty($this->request->data)) {
            if (!empty($this->request->data['Label']['name'])) {
                if ($this->request->data['Label']['name'] != $label['Label']['name']) {
                    $label_id = $this->Label->findOrSaveAndGetId($this->request->data['Label']['name']);
                    $is_label_alredy_added_by_user = $this->Label->LabelsUser->find('count', array(
                        'conditions' => array(
                            'LabelsUser.label_id' => $label_id,
                            'LabelsUser.user_id' => $this->Auth->user('id')
                        )
                    ));
                    if (!$is_label_alredy_added_by_user) {
                        $this->request->data['LabelsUser']['id'] = $this->request->data['Label']['id'];
                        $this->request->data['LabelsUser']['label_id'] = $label_id;
                        if ($this->Label->LabelsUser->save($this->request->data['LabelsUser'])) {
                            $this->Session->setFlash(__l(' Labels User has been updated') , 'default', null, 'success');
                            $this->redirect(array(
                                'controller' => 'labels',
                                'action' => 'index'
                            ));
                        } else {
                            $this->Session->setFlash(__l(' Labels could not be updated. Please, try again.') , 'default', null, 'error');
                        }
                    } else {
                        $this->Session->setFlash(__l(' Labels already exist.') , 'default', null, 'error');
                    }
                } else {
                    $this->redirect(array(
                        'controller' => 'labels',
                        'action' => 'index'
                    ));
                }
            } else {
                $this->Session->setFlash(__l(' Label should not be empty') , 'default', null, 'error');
            }
        } else {
            $this->request->data['Label']['id'] = $id;
            $this->request->data['Label']['name'] = $label['Label']['name'];
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->set('title_for_layout', $pageTitle);
    }
    public function delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Label->LabelsUser->delete($id)) {
            $this->Session->setFlash(__l('Label deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>