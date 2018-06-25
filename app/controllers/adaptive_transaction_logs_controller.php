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
class AdaptiveTransactionLogsController extends AppController
{
    public $name = 'AdaptiveTransactionLogs';
    public function admin_index($class = '', $foreign_id = '')
    {
        $this->pageTitle = __l('Adaptive Transaction Logs');
        $conditions = array();
        if (!empty($class)) {
            $conditions['AdaptiveTransactionLog.class'] = Inflector::classify($class);
        }
        if (!empty($foreign_id)) {
            $conditions['AdaptiveTransactionLog.foreign_id'] = $foreign_id;
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'AdaptiveTransactionLog.id' => 'desc'
            ) ,
            'recursive' => 0
        );
        $this->set('adaptiveTransactionLogs', $this->paginate());
    }
    public function admin_view($id = null)
    {
        $this->pageTitle = __l('Adaptive Transaction Log');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $adaptiveTransactionLog = $this->AdaptiveTransactionLog->find('first', array(
            'conditions' => array(
                'AdaptiveTransactionLog.id = ' => $id
            ) ,
            'recursive' => 0,
        ));
        if (empty($adaptiveTransactionLog)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $adaptiveTransactionLog['AdaptiveTransactionLog']['id'];
        $this->set('adaptiveTransactionLog', $adaptiveTransactionLog);
    }
}
?>