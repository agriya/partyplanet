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
class PhotoViewsController extends AppController
{
    public $name = 'PhotoViews';
    public function admin_index() 
    {
        $this->_redirectGET2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Photo Views');
        $conditions = array();
        if (!empty($this->request->params['named']['photo'])) {
            $photo = $this->{$this->modelClass}->Photo->find('first', array(
                'conditions' => array(
                    'Photo.slug' => $this->request->params['named']['photo']
                ) ,
                'fields' => array(
                    'Photo.id',
                    'Photo.title',
                    'Photo.slug'
                ) ,
                'recursive' => -1
            ));
            if (empty($photo)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Photo.id'] = $photo['Photo']['id'];
            $this->pageTitle.= sprintf(__l(' - Photo - %s') , $photo['Photo']['title']);
        }
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['PhotoView']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->PhotoView->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.username'
                    )
                ) ,
                'Photo' => array(
                    'fields' => array(
                        'Photo.title',
                        'Photo.slug'
                    ) ,
                    'Attachment' => array(
                        'fields' => array(
                            'Attachment.id',
                            'Attachment.filename',
                            'Attachment.dir',
                            'Attachment.width',
                            'Attachment.height',
                        )
                    )
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
                'PhotoView.id' => 'desc'
            ) ,
        );
        if (isset($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $this->set('photoViews', $this->paginate());
        $moreActions = $this->PhotoView->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->PhotoView->delete($id)) {
            $this->Session->setFlash(__l('Photo View deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>