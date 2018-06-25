<?php /* SVN: $Id: index.ctp 2276 2009-09-18 10:21:32Z boopathi_026ac09 $ */ ?>
<h3><?php echo __l('Tags'); ?></h3>
<div class="form-content-block">
	<?php
		//1. Need to set the config variables for min, max tag class, no of tags
		//2. Need to give the links for tags
		//3. Need to add more link if necessary
		if (!empty($tag_arr)) {
			$min_tag_classes = 1;
			$max_tag_classes = 6;
			// set min max count
			$max_qty = ($tag_arr) ? max(array_values($tag_arr)) : 0;
			$min_qty = ($tag_arr) ? min(array_values($tag_arr)) : 0;
			// Find spread range and  Set step size
			$spread = $max_qty - $min_qty;
			$spread	= (0 == $spread) ? 1 : $spread;
			$step =	($max_tag_classes - $min_tag_classes) / ($spread);
			// Sort tag by name
			ksort($tag_arr);
			// print tags clouds
			$i = 1;
			foreach ($tag_arr AS $key => $value) {
				if($i != 1) {
					echo ", ";
				}
				$size = ceil($min_tag_classes + (($value - $min_qty) * $step));
				echo $this->Html->link($tag_name_arr[$key], array('controller'=> 'videos', 'action'=> 'index', 'tag'=>$key),array('class'=>'tag'.$size));
				$i++;
			}
	?>
	<?php } else { ?>
		<p class="notice"><?php echo __l('Sorry, no tags found');?></p>
	<?php } ?>
</div>