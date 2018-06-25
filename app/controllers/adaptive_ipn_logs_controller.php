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
class AdaptiveIpnLogsController extends AppController
{
    var $name = 'AdaptiveIpnLogs';
    function admin_index()
    {
        $this->pageTitle = __l('Adaptive IPN Logs');
        $conditions = array(
            'AdaptiveIpnLog.post_variable !=' => ''
        );
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'AdaptiveIpnLog.id' => 'desc'
            ) ,
            'recursive' => 0
        );
        $this->set('adaptiveIpnLogs', $this->paginate());
    }
}
?>