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
class BannedIpsController extends AppController
{
    public $name = 'BannedIps';
    public function admin_index() 
    {
        $this->pageTitle = __l('Banned Ips');
        $this->BannedIp->recursive = 0;
        $this->set('bannedIps', $this->paginate());
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Banned Ip');
        if (!empty($this->request->data)) {
            $this->BannedIp->set($this->request->data);
            if ($this->BannedIp->validates()) {
                // To get the local ip
                if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $easyban_remote_ip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $easyban_remote_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                }
                // To set the time duration of banned ip
                if ($this->request->data['BannedIp']['duration_id'] == ConstBannedDurations::Days) {
                    $this->request->data['BannedIp']['timespan'] = ($this->request->data['BannedIp']['duration_time']*86400) +date('U');
                } else if ($this->request->data['BannedIp']['duration_id'] == ConstBannedDurations::Weeks) {
                    $this->request->data['BannedIp']['timespan'] = ($this->request->data['BannedIp']['duration_time']*604800) +date('U');
                } else {
                    $this->request->data['BannedIp']['timespan'] = 0;
                }
                $this->request->data['BannedIp']['thetime'] = date('U');
                $reserved = array(
                    '127.0.0.1',
                    '0.0.0.0',
                    'localhost',
                    '::1',
                    $easyban_remote_ip
                );
                // to set the ip range
                if ($this->request->data['BannedIp']['type_id'] == ConstBannedTypes::IPRange) {
                    $this->request->data['BannedIp']['address'] = sprintf("%u", ip2long(gethostbyname($this->request->data['BannedIp']['address'])));
                    $this->request->data['BannedIp']['range'] = sprintf("%u", ip2long(gethostbyname($this->request->data['BannedIp']['range'])));
                } else if ($this->request->data['BannedIp']['type_id'] == ConstBannedTypes::SingleIPOrHostName) {
                    $this->request->data['BannedIp']['address'] = sprintf("%u", ip2long(gethostbyname($this->request->data['BannedIp']['address'])));
                    $this->request->data['BannedIp']['range'] = sprintf("%u", ip2long(gethostbyname($this->request->data['BannedIp']['address'])));
                } else if ($this->request->data['BannedIp']['type_id'] == ConstBannedTypes::RefererBlock) {
                    $this->request->data['BannedIp']['address'] = $this->request->data['BannedIp']['range'] = strtolower($this->request->data['BannedIp']['address']);
                }
                if (!in_array(strtolower($this->request->data['BannedIp']['address']) , $reserved) and !in_array(strtolower($this->request->data['BannedIp']['range']) , $reserved)) {
                    $this->BannedIp->create();
                    if ($this->BannedIp->save($this->request->data)) {
                        $this->Session->setFlash(__l('Banned IP has been added') , 'default', null, 'success');
                        $this->redirect(array(
                            'action' => 'index'
                        ));
                    }
                } else {
                    $this->Session->setFlash(__l('Banned IP could not be added. Please, try again.') , 'default', null, 'error');
                }
            }
        }
        $this->set('ip', $this->RequestHandler->getClientIP());
        $this->set('types', $this->BannedIp->ipTypesOptions);
        $this->set('durations', $this->BannedIp->ipTimeOptions);
        if (empty($this->request->data['BannedIp']['type_id'])) {
            $this->request->data['BannedIp']['type_id'] = 1;
        }
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->BannedIp->delete($id)) {
            $this->Session->setFlash(__l('Banned Ip deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>