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
class VideoTagsController extends AppController
{
    public $name = 'VideoTags';
    public function beforeFilter() 
    {
        if (!Configure::read('Video.is_enable_video_module') && !Configure::read('Video.is_enable_video_tags')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
    }
    public function index() 
    {
        $this->pageTitle = __l('Video Tags');
        $conditions = array();
        if (!empty($this->request->params['named']['username']) && !empty($this->request->params['named']['username'])) {
            $videos = $this->VideoTag->Video->find('all', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['username']
                ) ,
                'contain' => array(
                    'VideoTag',
                    'User',
                ) ,
                'fields' => array(
                    'Video.id'
                ) ,
                'recursive' => 1
            ));
            $video_tag_ids = array();
            foreach($videos as $video) {
                foreach($video['VideoTag'] as $videoTag) {
                    $video_tag_ids[] = $videoTag['id'];
                }
            }
            $conditions['VideoTag.id'] = $video_tag_ids;
        }
        $videoTags = $this->VideoTag->find('all', array(
            'conditions' => $conditions,
            'fields' => array(
                'VideoTag.name',
                'VideoTag.slug',
                'VideoTag.video_count',
            ) ,
            'order' => array(
                'VideoTag.video_count' => 'desc'
            ) ,
            'recursive' => -1,
        ));
        $tag_arr = $tag_name_arr = array();
        foreach($videoTags as $videoTag) {
            $tag_arr[$videoTag['VideoTag']['slug']] = $videoTag['VideoTag']['video_count'];
            $tag_name_arr[$videoTag['VideoTag']['slug']] = $videoTag['VideoTag']['name'];
        }
        $this->set('tag_arr', $tag_arr);
        $this->set('tag_name_arr', $tag_name_arr);
    }
}
?>