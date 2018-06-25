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
class VideoViewsController extends AppController
{
    public $name = 'VideoViews';
    public function beforeFilter() 
    {
        if (!Configure::read('Video.is_enable_video_module')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
    }
    public function admin_index() 
    {
        $this->_redirectGET2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Video Views');
        $conditions = array();
        if (!empty($this->request->params['named']['video'])) {
            $video = $this->{$this->modelClass}->Video->find('first', array(
                'conditions' => array(
                    'Video.slug' => $this->request->params['named']['video']
                ) ,
                'fields' => array(
                    'Video.id',
                    'Video.title',
                    'Video.slug'
                ) ,
                'recursive' => -1
            ));
            if (empty($video)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Video.id'] = $video['Video']['id'];
            $this->pageTitle.= sprintf(__l(' - Video - %s') , $video['Video']['title']);
        }
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['VideoView']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->VideoView->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.username'
                    )
                ) ,
                'Video' => array(
                    'fields' => array(
                        'Video.title',
                        'Video.default_thumbnail_id',
                        'Video.slug'
                    ) ,
                    'Attachment' => array(
                        'fields' => array(
                            'Attachment.id',
                            'Attachment.filename',
                            'Attachment.dir',
                            'Attachment.width',
                            'Attachment.height',
                        )
                    ),
                    'Thumbnail',
                ),
				'Ip' => array(
					'City' => array(
						'fields' => array(
							'City.name',
						)
					) ,
					'State' => array(
						'fields' => array(
							'State.name',
						)
					) ,
					'Country' => array(
						'fields' => array(
							'Country.name',
							'Country.iso_alpha2',
						)
					) ,
					'Timezone' => array(
						'fields' => array(
							'Timezone.name',
						)
					) ,
					'fields' => array(
						'Ip.ip',
						'Ip.latitude',
						'Ip.longitude',
						'Ip.host',
					)
				)
            ) ,
            'order' => array(
                'VideoView.id' => 'desc'
            ) ,
        );
        if (isset($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $this->set('videoViews', $this->paginate());
        $moreActions = $this->VideoView->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VideoView->delete($id)) {
            $this->Session->setFlash(__l('Video View deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>