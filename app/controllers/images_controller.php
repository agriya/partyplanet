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
// For SERVING UP Images or other files
class ImagesController extends AppController
{
    public function __setupDir($destination) 
    {
        new Folder(dirname($destination) , true, 0755); // make sure folders exist
        if (!file_exists(dirname($destination))) {
            die('couldn\'t create webdir folder');
        }
        return true;
    }
    public function view() 
    {
        $args = func_get_args();
        $hash_id = array_pop($args);
        if (count((explode('.', $hash_id))) < 3) {
            throw new NotFoundException(__l('Invalid request'));
        }
        list($id, $hash, $ext) = explode('.', $hash_id);
        $model = implode('/', $args);
        if ($hash != md5(Configure::read('Security.salt') . $model . $id . $ext . $this->request->params['named']['size'] . Configure::read('site.name'))) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->autoRender = false;
        $this->Image->recursive = -1;
        $data = $this->Image->findById($id);
        if (!$data) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Image->id = $data['Image']['id'];
        $this->Image->data = $data;
        $size = $this->request->params['named']['size'];
        $original = $this->Image->absolutePath();
        if (!file_exists($original)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        //Added for Windows.. slash problem
        if (substr(PHP_OS, 0, 3) == 'WIN') {
            $destination = WWW_ROOT . str_replace('/', '\\', $this->request->url);
        } else {
            //Was $this->here, resolving to /atei/img/... (added atei)
            $destination = WWW_ROOT . $this->request->url;
        }
        // check for valid dimensions
        // Checking Config settings value
        $is_watermark_logo = (Configure::read($model . '.' . $size . '.is_watermark_logo') !== null) ? Configure::read($model . '.' . $size . '.is_watermark_logo') : Configure::read($model . '.is_watermark_logo');
        $watermark_image_url = '';
        if (!empty($is_watermark_logo)) {
            $this->loadModel('WaterMark');
            $watermark = $this->WaterMark->find('first', array(
                'conditions' => array(
                    'WaterMark.class' => 'WaterMark'
                ) ,
                'recursive' => -1
            ));
            if (!empty($watermark)) {
                $options['dimension'] = $size;
                $options['type'] = 'png';
                $watermark_image_url = Router::url('/', true) . trim(getImageUrl('WaterMark', $watermark['WaterMark'], $options) , '/');
            }
        }
        $is_text_watermark = (Configure::read($model . '.' . $size . '.is_text_watermark') !== null) ? Configure::read($model . '.' . $size . '.is_text_watermark') : Configure::read($model . '.is_text_watermark');
        if ($size == 'original') {
            $this->__setupDir($destination);
            if ($this->Image->original($original, $destination, $is_watermark_logo, $is_text_watermark, $watermark_image_url, $model)) {
                $this->redirect('/' . $this->request->url, null, true);
            }
            // Was $this->here
            $this->redirect('/' . $this->request->url, null, true);
        } else if (!(array_key_exists($size, Configure::read('thumb_size')))) {
            throw new NotFoundException(__l('Invalid request'));
        }
        extract(Configure::read('thumb_size.' . $size));
        if (strpos($data['Image']['mimetype'], 'image/') !== 0) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $saveToWwwRoot = true; // switch
        if ($saveToWwwRoot) {
            $this->__setupDir($destination);
            $aspect = (Configure::read($model . '.' . $size . '.is_handle_aspect') !== null) ? Configure::read($model . '.' . $size . '.is_handle_aspect') : Configure::read($model . '.is_handle_aspect');
            $is_beyond_original = (Configure::read($model . '.' . $size . '.is_not_allow_resize_beyond_original_size') !== null) ? Configure::read($model . '.' . $size . '.is_not_allow_resize_beyond_original_size') : Configure::read($model . '.is_not_allow_resize_beyond_original_size');
            if ($this->Image->resize(null, $width, $height, $destination, $aspect, $original, $is_beyond_original, $is_watermark_logo, $is_text_watermark, $watermark_image_url)) {
                $this->redirect('/' . $this->request->url, null, true);
            }
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>