<?php
    $average_rating = (!empty($photo['Photo']['photo_rating_count'])) ? ($photo['Photo']['total_ratings']/$photo['Photo']['photo_rating_count']) : 0;
    echo $this->element('_star-rating', array('photo_id' => $photo['Photo']['id'], 'current_rating' => $average_rating, 'canRate' => false));
?>