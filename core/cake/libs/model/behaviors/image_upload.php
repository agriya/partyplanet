<?php
require_once ('upload.php');
class ImageUploadBehavior extends UploadBehavior
{
    function setup(&$model, $config = array())
    {
        // Overriding defaults
        $this->__defaultSettings['allowedMime'] = array(
            'image/jpeg',
            'image/gif',
            'image/png',
            'image/bmp'
        );
        $this->__defaultSettings['allowedExt'] = array(
            'jpeg',
            'jpg',
            'gif',
            'png',
            'bmp'
        );
        $this->_is_use_imagick = false;
        parent::setup($model, $config);
    }
    function _afterProcessUpload(&$model, $data, $direct)
    {
        list($width, $height) = getimagesize($model->absolutePath());
        $model->data[$model->name]['width'] = $width;
        $model->data[$model->name]['height'] = $height;
        return true;
    }
    function _beforeProcessUpload(&$model, $data, $direct)
    {
        return true;
    }
    function original(&$model, $original, $destination, $is_watermark_logo = false, $is_text_watermark = false, $watermark_image_url = false, $model_class = false)
    {
        if ($model_class == 'Video') {
            if (!copy($original, $destination)) {
                die('couldn\'t  move file to webdir');
            }
        } else {
            //getting the image size
            if (!($size = getimagesize($original))) {
                // image doesn't exist
                return false;
            }
            list($currentWidth, $currentHeight, $currentType) = $size;
            $return = false;
            if ($this->_is_use_imagick) {
                $new_image_obj = new imagick($original);
                $new_image = $new_image_obj->clone();
                $new_image->flattenImages();
                if (!$new_image->writeImage($destination)) {
                    die('couldn\'t  move file to webdir');
                }
            } else {
                $target['width'] = $currentWidth;
                $target['height'] = $currentHeight;
                $target['x'] = $target['y'] = 0;
                $types = array(
                    1 => "gif",
                    "jpeg",
                    "png",
                    "swf",
                    "psd",
                    "wbmp"
                );
                //rajesh_04ag02 // 2008-09-25 // fix for memory error
                $fullPath = $original;
                $this->_setMemoryLimitForImage($fullPath);
                $image = call_user_func('imagecreatefrom' . $types[$currentType], $fullPath);
                ini_restore('memory_limit');
                $temp = imagecreate($currentWidth, $currentHeight);
                if (function_exists("imagecreatetruecolor") && ($temp = imagecreatetruecolor($currentWidth, $currentHeight))) {
                    imagecopyresampled($temp, $image, 0, 0, $target['x'], $target['y'], $currentWidth, $currentHeight, $target['width'], $target['height']);
                } else {
                    $temp = imagecreate($currentWidth, $currentHeight);
                    imagecopyresized($temp, $image, 0, 0, 0, 0, $currentWidth, $currentHeight, $currentWidth, $currentHeight);
                }
                if (!empty($is_watermark_logo)) {
                    if ($is_text_watermark) {
                        $font = APP . WEBROOT_DIR . DS . 'files' . DS . 'fonts' . DS . 'arial.ttf';
                        $grey = imagecolorallocate($temp, 128, 128, 128);
                        $watermark_text = (Configure::read('Watermark.watermark_text') !== null) ? Configure::read('Watermark.watermark_text') : Configure::read('site.name');
                        $op_watermark_text = '';
                        for ($i = 0; $i < 10; $i++) {
                            $op_watermark_text.= '   ' . $watermark_text;
                        }
                        imagettftext($temp, 20, 325, 0, 20, $grey, $font, $op_watermark_text);
                    } else {
                        $watermark_image_info = getimagesize($watermark_image_url);
                        $watermark_position_x = $currentWidth;
                        $watermark_position_y = $currentHeight;
                        $watermark_image_width = $watermark_image_height = 0;
                        if (!empty($watermark_image_info)) {
                            $watermark_position_x = $currentWidth - ($watermark_image_info[0] + 10);
                            $watermark_position_y = $currentHeight - ($watermark_image_info[1] + 10);
                            $watermark_image_width = $watermark_image_info[0];
                            $watermark_image_height = $watermark_image_info[1];
                        }
                        $watermark = imagecreatefrompng($watermark_image_url);
                        imagecopymerge($temp, $watermark, $watermark_position_x, $watermark_position_y, 0, 0, $watermark_image_width, $watermark_image_height, 20);
                        imagedestroy($watermark);
                    }
                }
                //define the destination into writeto
                $writeTo = $destination;
                if ($writeTo) {
                  App::import('Core', 'File');
                    new File($writeTo, true);
                    if ($types[$currentType] == 'jpeg') {
                        if (call_user_func("image" . $types[$currentType], $temp, $writeTo, 100)) {
                            $return = true;
                        }
                    } else {
                        if (call_user_func("image" . $types[$currentType], $temp, $writeTo)) {
                            $return = true;
                        }
                    }
                } else {
                    ob_start();
                    call_user_func("image" . $types[$currentType], $temp);
                    $return = ob_get_clean();
                }
                imagedestroy($image);
                imagedestroy($temp);
            }
        }
    }
    function resize(&$model, $id = null, $width = 600, $height = 400, $writeTo = false, $aspect = true, $fullPath = null, $is_beyond_original = false, $is_watermark_logo = false, $is_text_watermark = false, $watermark_image_url = false)
    {
        if ($id === null && $model->id) {
            $id = $model->id;
        } elseif (!$id) {
            $id = null;
        }
        extract($this->settings[$model->name]);
        return $this->resizeFile($model, $fullPath, $width, $height, $writeTo, $aspect, $is_beyond_original, $is_watermark_logo, $is_text_watermark, $watermark_image_url);
    }
    //http://www.php.net/imagecreatefromjpeg#60241 && http://in2.php.net/imagecreatefrompng#73546
    function _setMemoryLimitForImage($image_path)
    {
        $imageInfo = getimagesize($image_path);
        $imageInfo['channels']=1;
        $memoryNeeded = round(($imageInfo[0] * $imageInfo[1] * $imageInfo['bits'] * $imageInfo['channels'] / 8 + Pow(2, 16)) * 1.65);
        if (function_exists('memory_get_usage') && memory_get_usage() + $memoryNeeded > (integer)ini_get('memory_limit') * pow(1024, 2)) {
            ini_set('memory_limit', (integer)ini_get('memory_limit') + ceil(((memory_get_usage() + $memoryNeeded) - (integer)ini_get('memory_limit') * pow(1024, 2)) / pow(1024, 2)) . 'M');
        }
    }
    function resizeFile(&$model, $fullPath, $width = 600, $height = 400, $writeTo = false, $aspect = true, $is_beyond_original = false, $is_watermark_logo = false, $is_text_watermark = false, $watermark_image_url = false)
    {
        if (!$width || !$height) {
            return false;
        }
        extract($this->settings[$model->name]);
        if (!($size = getimagesize($fullPath))) {
            // image doesn't exist
            return false;
        }
        list($currentWidth, $currentHeight, $currentType) = $size;
        $return = false;
        if ($this->_is_use_imagick) {
            $new_image_obj = new imagick($fullPath);
            $new_image = $new_image_obj->clone();
            $new_image->setImageColorspace(Imagick::COLORSPACE_RGB);
            $new_image->flattenImages();
            if ($is_beyond_original && ($width > $currentWidth || $height > $currentHeight)) {
                $width = $currentWidth;
                $height = $currentHeight;
            }
            if (!$aspect) {
                $new_image->cropThumbnailImage($width, $height);
            } else {
                $new_image->scaleImage($width, $height, false);
            }
            if ($new_image->writeImage($writeTo)) {
                $return = true;
            }
        } else {
            $target['width'] = $currentWidth;
            $target['height'] = $currentHeight;
            $target['x'] = $target['y'] = 0;
            $types = array(
                1 => "gif",
                "jpeg",
                "png",
                "swf",
                "psd",
                "wbmp"
            );
            //rajesh_04ag02 // 2008-09-25 // fix for memory error
            $this->_setMemoryLimitForImage($fullPath);
            $image = call_user_func('imagecreatefrom' . $types[$currentType], $fullPath);
            ini_restore('memory_limit');
            // adjust to aspect.
            if ($aspect) {
                if (($currentHeight / $height) > ($currentWidth / $width)) {
                    $width = ceil(($currentWidth / $currentHeight) * $height);
                } else {
                    $height = ceil($width / ($currentWidth / $currentHeight));
                }
            } else {
                //rajesh_04ag02 // 2008-02-20
                // Optimized crop adopted from http://in2.php.net/imagecopyresized#71182
                $proportion_X = $currentWidth / $width;
                $proportion_Y = $currentHeight / $height;
                if ($proportion_X > $proportion_Y) {
                    $proportion = $proportion_Y;
                } else {
                    $proportion = $proportion_X;
                }
                $target['width'] = $width * $proportion;
                $target['height'] = $height * $proportion;
                $original['diagonal_center'] = round(sqrt(($currentWidth * $currentWidth) + ($currentHeight * $currentHeight)) / 2);
                $target['diagonal_center'] = round(sqrt(($target['width'] * $target['width']) + ($target['height'] * $target['height'])) / 2);
                $crop = round($original['diagonal_center'] - $target['diagonal_center']);
                if ($proportion_X < $proportion_Y) {
                    $target['x'] = 0;
                    $target['y'] = round((($currentHeight / 2) * $crop) / $target['diagonal_center']);
                } else {
                    $target['x'] = round((($currentWidth / 2) * $crop) / $target['diagonal_center']);
                    $target['y'] = 0;
                }
            }
            if ($is_beyond_original && ($width > $currentWidth || $height > $currentHeight)) {
                $width = $currentWidth;
                $height = $currentHeight;
            }
            if (function_exists("imagecreatetruecolor") && ($temp = imagecreatetruecolor($width, $height))) {
                imagecopyresampled($temp, $image, 0, 0, $target['x'], $target['y'], $width, $height, $target['width'], $target['height']);
            } else {
                $temp = imagecreate($width, $height);
                imagecopyresized($temp, $image, 0, 0, 0, 0, $width, $height, $currentWidth, $currentHeight);
            }
            if (!empty($is_watermark_logo)) {
                if ($is_text_watermark) {
                    $font = APP . WEBROOT_DIR . DS . 'files' . DS . 'fonts' . DS . 'arial.ttf';
                    $grey = imagecolorallocate($temp, 128, 128, 128);
                    $watermark_text = (Configure::read('Watermark.watermark_text') !== null) ? Configure::read('Watermark.watermark_text') : Configure::read('site.name');
                    $op_watermark_text = '';
                    for ($i = 0; $i < 10; $i++) {
                        $op_watermark_text.= '   ' . $watermark_text;
                    }
                    imagettftext($temp, 20, 325, 0, 20, $grey, $font, $op_watermark_text);
                } else {
                    $watermark_image_info = getimagesize($watermark_image_url);
                    $watermark_position_x = $currentWidth;
                    $watermark_position_y = $currentHeight;
                    $watermark_image_width = $watermark_image_height = 0;
                    if (!empty($watermark_image_info)) {
                        $watermark_position_x = $width - ($watermark_image_info[0] + 10);
                        $watermark_position_y = $height - ($watermark_image_info[1] + 10);
                        $watermark_image_width = $watermark_image_info[0];
                        $watermark_image_height = $watermark_image_info[1];
                    }
                    $watermark = imagecreatefrompng($watermark_image_url);
                    imagecopymerge($temp, $watermark, $watermark_position_x, $watermark_position_y, 0, 0, $watermark_image_width, $watermark_image_height, 20);
                    imagedestroy($watermark);
                }
            }
            if ($writeTo) {
                App::import('Core', 'File');
                new File($writeTo, true);
                if ($types[$currentType] == 'jpeg') {
                    if (call_user_func("image" . $types[$currentType], $temp, $writeTo, 100)) {
                        $return = true;
                    }
                } else {
                    if (call_user_func("image" . $types[$currentType], $temp, $writeTo)) {
                        $return = true;
                    }
                }
            } else {
                ob_start();
                call_user_func("image" . $types[$currentType], $temp);
                $return = ob_get_clean();
            }
            imagedestroy($image);
            imagedestroy($temp);
        }
		$info = pathinfo($writeTo);
		if (!empty($info['extension']) && $info['extension'] == 'png') {
			exec('pngcrush -reduce -brute ' . $writeTo . ' ' . $writeTo);
		}
        return $return;
    }
}
?>