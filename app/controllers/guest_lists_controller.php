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
class GuestListsController extends AppController
{
    public $name = 'GuestLists';
    public function index() 
    {
        $this->pageTitle = __l('Guest Lists');
        $this->GuestList->recursive = 0;
        $this->set('guestLists', $this->paginate());
    }
    public function add() 
    {
        $this->pageTitle = __l('Add Guest List');
        if (!empty($this->request->data)) {
            $this->GuestList->create();
            if ($this->GuestList->save($this->request->data)) {
                $this->Session->setFlash(__l(' Guest List has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Guest List could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $events = $this->GuestList->Event->find('list');
        $this->set(compact('events'));
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Guest Lists');
        $this->GuestList->recursive = 0;
        $this->set('guestLists', $this->paginate());
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Guest List');
        if (!empty($this->request->data)) {
            $this->GuestList->create();
            if ($this->GuestList->save($this->request->data)) {
                $this->Session->setFlash(__l(' Guest List has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Guest List could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $events = $this->GuestList->Event->find('list');
        $this->set(compact('events'));
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Guest List');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->GuestList->save($this->request->data)) {
                $this->Session->setFlash(__l(' Guest List has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l(' Guest List could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->GuestList->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['GuestList']['name'];
        $events = $this->GuestList->Event->find('list');
        $this->set(compact('events'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->GuestList->delete($id)) {
            $this->Session->setFlash(__l('Guest List deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>