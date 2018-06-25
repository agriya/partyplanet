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
class VideoEncodingProfilesController extends AppController
{
    public $name = 'VideoEncodingProfiles';
    public function beforeFilter() 
    {
        if (!Configure::read('Video.is_enable_video_module')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Security->validatePost = false;
        parent::beforeFilter();
    }
    public function admin_index($id = null) 
    {
        $this->pageTitle = __l('Video Encoding Profiles');
        $conditions = array();
        if (!empty($id)) {
            $conditions['VideoEncodingProfile.video_encoding_template_id'] = $id;
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'recursive' => 0
        );
        $this->set('videoEncodingProfiles', $this->paginate());
    }
    public function admin_add($id = null) 
    {
        $this->pageTitle = __l('Add Video Encoding Profile');
        if (!empty($this->request->data)) {
            $this->VideoEncodingProfile->create();
            $targetFileType = $this->VideoEncodingProfile->TargetFileType->find('first', array(
                'conditions' => array(
                    'TargetFileType.id' => $this->request->data['VideoEncodingProfile']['target_file_type_id']
                ) ,
                'recursive' => -1
            ));
            $this->request->data['VideoEncodingProfile']['ffmpeg_command'] = $this->VideoEncodingProfile->getFfmpegCommand($this->request->data, $targetFileType['TargetFileType']['extension']);
            $this->request->data['VideoEncodingProfile']['target_file_extension'] = $targetFileType['TargetFileType']['extension'];
            if ($this->VideoEncodingProfile->save($this->request->data)) {
                $this->Session->setFlash(__l('Video Encoding Profile has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'video_encoding_profiles',
                    'action' => 'index',
                    $this->request->data['VideoEncodingProfile']['video_encoding_template_id']
                ));
            } else {
                $this->Session->setFlash(__l('Video Encoding Profile could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $videoEncodingTemplates = $this->VideoEncodingProfile->VideoEncodingTemplate->find('list', array(
            'conditions' => array(
                'is_active' => 1
            )
        ));
        $targetFileTypes = $this->VideoEncodingProfile->TargetFileType->find('list', array(
            'conditions' => array(
                'is_active' => 1
            )
        ));
        $bitstreamFilters = $this->VideoEncodingProfile->BitstreamFilter->find('list', array(
            'conditions' => array(
                'is_active' => 1
            )
        ));
        $frameSizes = $this->VideoEncodingProfile->FrameSize->find('list', array(
            'conditions' => array(
                'is_active' => 1
            )
        ));
        $aspectRatios = $this->VideoEncodingProfile->AspectRatio->find('list', array(
            'conditions' => array(
                'is_active' => 1
            )
        ));
        $this->set(compact('videoEncodingTemplates', 'targetFileTypes', 'bitstreamFilters', 'frameSizes', 'aspectRatios'));
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Video Encoding Profile');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->VideoEncodingProfile->save($this->request->data)) {
                $this->Session->setFlash(__l('Video Encoding Profile has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('Video Encoding Profile could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->VideoEncodingProfile->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['VideoEncodingProfile']['id'];
        $videoEncodingTemplates = $this->VideoEncodingProfile->VideoEncodingTemplate->find('list', array(
            'conditions' => array(
                'is_active' => 1
            )
        ));
        $targetFileTypes = $this->VideoEncodingProfile->TargetFileType->find('list', array(
            'conditions' => array(
                'is_active' => 1
            )
        ));
        $bitstreamFilters = $this->VideoEncodingProfile->BitstreamFilter->find('list', array(
            'conditions' => array(
                'is_active' => 1
            )
        ));
        $frameSizes = $this->VideoEncodingProfile->FrameSize->find('list', array(
            'conditions' => array(
                'is_active' => 1
            )
        ));
        $aspectRatios = $this->VideoEncodingProfile->AspectRatio->find('list', array(
            'conditions' => array(
                'is_active' => 1
            )
        ));
        $this->set(compact('videoEncodingTemplates', 'targetFileTypes', 'bitstreamFilters', 'frameSizes', 'aspectRatios'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VideoEncodingProfile->delete($id)) {
            $this->Session->setFlash(__l('Video Encoding Profile deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>