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
class PhotoTagsController extends AppController
{
    public $name = 'PhotoTags';
    public function index() 
    {
        $this->pageTitle = __l('Photo Tags');
        $conditions = array();
        $photo_conditions = array();
        $albums = $this->PhotoTag->Photo->PhotoAlbum->find('list', array(
            'conditions' => array(
                'PhotoAlbum.city_id' => $this->_prefixId,
                'PhotoAlbum.photo_count !=' => 0
            )
        ));
        $album_ids = array_unique(array_keys($albums));
        $photo_conditions['Photo.photo_album_id'] = $album_ids;
        $photo_conditions['Photo.is_active'] = 1;
        $photo_conditions['Photo.admin_suspend'] = 0;
        if (isset($this->request->params['named']['username']) && !empty($this->request->params['named']['username'])) {
            $photo_conditions['User.username'] = $this->request->params['named']['username'];
        }
        $photos = $this->PhotoTag->Photo->find('all', array(
            'conditions' => $photo_conditions,
            'contain' => array(
                'PhotoTag' => array(
                    'fields' => array(
                        'PhotoTag.id'
                    )
                ) ,
                'User' => array(
                    'fields' => array(
                        'User.username'
                    )
                ) ,
            ) ,
            'fields' => array(
                'Photo.id'
            ) ,
            'recursive' => 2
        ));
        $photo_tag_ids = array();
        foreach($photos as $photo) {
            if (!empty($photo['PhotoTag'])) {
                foreach($photo['PhotoTag'] as $photoTag) {
                    $photo_tag_ids[] = $photoTag['id'];
                }
            }
        }
        $conditions['PhotoTag.id'] = array_unique($photo_tag_ids);
        $this->set('photoTags', $this->PhotoTag->Photo->selectTag($conditions));
    }
}
?>