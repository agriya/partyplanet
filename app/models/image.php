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
include_once ('attachment.php');
class Image extends Attachment
{
    public $name = 'Image';
    var $useTable = 'attachments';
    public $actsAs = array(
        'ImageUpload'
    );
}
?>
