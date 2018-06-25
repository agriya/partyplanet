<?php
$current_rating_percentage = $current_rating*20;
?>
<ul class="small-star star-rating">
	<li class="current-rating" style="width:<?php echo $current_rating_percentage;?>%;" title="<?php echo $current_rating;?>/5 <?php echo __l('Stars');?>"><?php echo $current_rating;?>/5 <?php echo __l('Stars');?></li>
<?php
	if ($canRate) :
		$ajax_class = '';
		if($this->Auth->user('id')):
			$ajax_class = ' js-rating';
		endif;
?>
	<li><?php echo $this->Html->link('1', array('controller' => 'video_ratings', 'action' => 'add', $video_id, 1), array('class' => 'one-star'.$ajax_class, 'title' => __l('1 star out of 5')))?></li>
	<li><?php echo $this->Html->link('2', array('controller' => 'video_ratings', 'action' => 'add', $video_id, 2), array('class' => 'two-stars'.$ajax_class, 'title' => __l('2 star out of 5')))?></li>
    <li><?php echo $this->Html->link('3', array('controller' => 'video_ratings', 'action' => 'add', $video_id, 3), array('class' => 'three-stars'.$ajax_class, 'title' => __l('3 star out of 5')))?></li>
    <li><?php echo $this->Html->link('4', array('controller' => 'video_ratings', 'action' => 'add', $video_id, 4), array('class' => 'four-stars'.$ajax_class, 'title' => __l('4 star out of 5')))?></li>
    <li><?php echo $this->Html->link('5', array('controller' => 'video_ratings', 'action' => 'add', $video_id, 5), array('class' => 'five-stars'.$ajax_class, 'title' => __l('5 star out of 5')))?></li>
<?php
    endif;
?>
</ul>