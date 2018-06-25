<?php /* SVN: $Id: add.ctp 1302 2009-07-25 15:09:25Z boopathi_026ac09 $ */ ?>
<?php
    $average_rating = (!empty($video['Video']['video_rating_count'])) ? ($video['Video']['total_ratings']/$video['Video']['video_rating_count']) : 0;
    echo $this->element('_star-rating-video', array('video_id' => $video['Video']['id'], 'current_rating' => $average_rating, 'canRate' => false));
?>