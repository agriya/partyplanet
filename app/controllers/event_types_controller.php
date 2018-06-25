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
class EventTypesController extends AppController
{
    public $name = 'EventTypes';
    public function admin_index() 
    {
        $this->pageTitle = __l('Event Types');
        $this->EventType->recursive = 0;
        $this->set('eventTypes', $this->paginate());
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Event Type');
        if (!empty($this->request->data)) {
            $this->EventType->create();
            if ($this->EventType->save($this->request->data)) {
                $this->Session->setFlash(__l(' Event Type has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Event Type could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Event Type');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->EventType->save($this->request->data)) {
                $this->Session->setFlash(__l(' Event Type has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l(' Event Type could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->EventType->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['EventType']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->EventType->delete($id)) {
            $this->Session->setFlash(__l('Event Type deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>