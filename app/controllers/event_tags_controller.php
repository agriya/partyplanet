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
class EventTagsController extends AppController
{
    public $name = 'EventTags';
    public function index() 
    {
        $this->pageTitle = __l('Event Tags');
        $conditions = array();
        if (!empty($this->request->params['named']['event_slug'])) {
            $conditions['Event.slug'] = $this->request->params['named']['event_slug'];
        }
        $eventTag = $this->EventTag->find('all', array(
            'contain' => array(
                'Event' => array(
                    'fields' => array(
                        'Event.id',
                        'Event.slug',
                    ) ,
                    'conditions' => $conditions,
                )
            ) ,
            'recursive' => 1
        ));
        $tag_arr = array();
        $tag_name_arr = array();
        foreach($eventTag as $eventTag) {
            if (!empty($eventTag['Event'])) {
                $tag_arr[$eventTag['EventTag']['slug']] = count($eventTag['Event']);
                $tag_name_arr[$eventTag['EventTag']['slug']] = $eventTag['EventTag']['name'];
            }
        }
        $this->set('tag_arr', $tag_arr);
        $this->set('tag_name_arr', $tag_name_arr);
    }
}
?>