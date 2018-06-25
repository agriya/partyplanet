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
class VideoEncodingProfile extends AppModel
{
    public $name = 'VideoEncodingProfile';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'VideoEncodingTemplate' => array(
            'className' => 'VideoEncodingTemplate',
            'foreignKey' => 'video_encoding_template_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'TargetFileType' => array(
            'className' => 'TargetFileType',
            'foreignKey' => 'target_file_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'BitstreamFilter' => array(
            'className' => 'BitstreamFilter',
            'foreignKey' => 'bitstream_filter_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'FrameSize' => array(
            'className' => 'FrameSize',
            'foreignKey' => 'frame_size_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'AspectRatio' => array(
            'className' => 'AspectRatio',
            'foreignKey' => 'aspect_ratio_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    function __construct($id = false, $table = null, $ds = null) 
    {
        parent::__construct($id, $table, $ds);
    }
    function getFfmpegCommand($data, $fileExt) 
    {
        if ($fileExt == ConstFileExt::Flv) {
            $ffmpeg_path = (strpos(PHP_OS, 'WIN') !== false) ? 'ffmpeg' : Configure::read('Video.ffmpeg_path');
            $ffmpeg_command = $ffmpeg_path . ' -y -i #video_source_path#';
            if (!empty($data['VideoEncodingProfile']['audio_no_of_frames_to_record'])) {
                $ffmpeg_command.= ' -aframes ' . $data['VideoEncodingProfile']['audio_no_of_frames_to_record'];
            }
            if (!empty($data['VideoEncodingProfile']['audio_sampling_frequency'])) {
                $ffmpeg_command.= ' -ar ' . $data['VideoEncodingProfile']['audio_sampling_frequency'];
            }
            if (!empty($data['VideoEncodingProfile']['audio_bitrate'])) {
                $ffmpeg_command.= ' -ab ' . $data['VideoEncodingProfile']['audio_bitrate'];
            }
            if (!empty($data['VideoEncodingProfile']['audio_no_of_channels'])) {
                $ffmpeg_command.= ' -ac ' . $data['VideoEncodingProfile']['audio_no_of_channels'];
            }
            if (!empty($data['VideoEncodingProfile']['bitstream_filter_id'])) {
                $bitStreamFilter = $this->BitstreamFilter->find('first', array(
                    'conditions' => array(
                        'BitstreamFilter.id' => $data['VideoEncodingProfile']['bitstream_filter_id']
                    ) ,
                    'recursive' => -1
                ));
                $ffmpeg_command.= ' -absf ' . $bitStreamFilter['BitstreamFilter']['name'];
            }
            if (!empty($data['VideoEncodingProfile']['is_disable_audio_recording'])) {
                $ffmpeg_command.= ' -an ';
            }
            if (!empty($data['VideoEncodingProfile']['is_video_min_bitrate']) && !empty($data['VideoEncodingProfile']['video_min_bitrate'])) {
                $ffmpeg_command.= ' -minrate ' . $data['VideoEncodingProfile']['video_min_bitrate'];
            }
            if (!empty($data['VideoEncodingProfile']['is_video_max_bitrate']) && !empty($data['VideoEncodingProfile']['video_max_bitrate'])) {
                $ffmpeg_command.= ' -maxrate ' . $data['VideoEncodingProfile']['video_max_bitrate'];
            }
            if (!empty($data['VideoEncodingProfile']['is_video_bitrate_tolerance']) && !empty($data['VideoEncodingProfile']['video_bitrate_tolerance'])) {
                $ffmpeg_command.= ' -bt ' . $data['VideoEncodingProfile']['video_bitrate_tolerance'];
            }
            if (!empty($data['VideoEncodingProfile']['video_bitrate'])) {
                $ffmpeg_command.= ' -b ' . $data['VideoEncodingProfile']['video_bitrate'];
            }
            if (!empty($data['VideoEncodingProfile']['video_frame_rate'])) {
                $ffmpeg_command.= ' -r ' . $data['VideoEncodingProfile']['video_frame_rate'];
            }
            if (!empty($data['VideoEncodingProfile']['video_no_of_frames_to_record'])) {
                $ffmpeg_command.= ' -vframes ' . $data['VideoEncodingProfile']['video_no_of_frames_to_record'];
            }
            if (!empty($data['VideoEncodingProfile']['frame_size_id'])) {
                $frameSize = $this->FrameSize->find('first', array(
                    'conditions' => array(
                        'FrameSize.id' => $data['VideoEncodingProfile']['frame_size_id']
                    ) ,
                    'recursive' => -1
                ));
                $ffmpeg_command.= ' -s ' . $frameSize['FrameSize']['name'];
            }
            if (!empty($data['VideoEncodingProfile']['is_disable_video_recording'])) {
                $ffmpeg_command.= ' -vn ';
            }
            if (!empty($data['VideoEncodingProfile']['is_same_quality_as_source'])) {
                $ffmpeg_command.= ' -sameq ';
            }
            if (!empty($data['VideoEncodingProfile']['is_buffer_size']) && !empty($data['VideoEncodingProfile']['buffer_size_value'])) {
                $ffmpeg_command.= ' -bufsize ' . $data['VideoEncodingProfile']['buffer_size_value'];
            }
            if (!empty($data['VideoEncodingProfile']['is_aspect_ratio']) && !empty($data['VideoEncodingProfile']['aspect_ratio_id'])) {
                $aspectRatio = $this->AspectRatio->find('first', array(
                    'conditions' => array(
                        'AspectRatio.id' => $data['VideoEncodingProfile']['aspect_ratio_id']
                    ) ,
                    'recursive' => -1
                ));
                $ffmpeg_command.= ' -aspect ' . $aspectRatio['AspectRatio']['name'];
            }
            if (!empty($data['VideoEncodingProfile']['encode_pass'])) {
                $ffmpeg_command.= ' -pass ' . $data['VideoEncodingProfile']['encode_pass'];
            }
        } elseif ($fileExt == ConstFileExt::Jpeg || $fileExt == ConstFileExt::Gif || $fileExt == ConstFileExt::Png) {
            $ffmpeg_path = (strpos(PHP_OS, 'WIN') !== false) ? 'ffmpeg' : Configure::read('Video.ffmpeg_path');
            $ffmpeg_command = $ffmpeg_path . ' -i #image_source_path# -an -ss 00:00:03 -r 1 -vframes ' . Configure::read('Video.no_of_thumbnail');
            if (!empty($data['VideoEncodingProfile']['image_thumbnail_width']) && !empty($data['VideoEncodingProfile']['image_thumbnail_height'])) {
                $ffmpeg_command.= ' -s ' . $data['VideoEncodingProfile']['image_thumbnail_width'] . 'x' . $data['VideoEncodingProfile']['image_thumbnail_height'];
            }
        }
        return $ffmpeg_command;
    }
}
?>