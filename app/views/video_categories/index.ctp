<?php /* SVN: $Id: index.ctp 9485 2010-05-06 13:59:31Z kanagavel_113at09 $ */ ?>
<h3><?php echo Configure::read('site.name') . '<span>' . __l(' Video Channels') . '</span>'; ?></h3>
<ul class="party-list category-list clearfix">
	<?php
		if (!empty($videoCategories)):
			$i = 0;
			foreach($videoCategories as $videoCategory):
				$class = null;
				if ($i++%2 == 0) {
					$class = 'altrow';
				}
				$videoCategory['VideoCategory']['count'] = !empty($videoCategory['VideoCategory']['count']) ? $videoCategory['VideoCategory']['count'] : 0;
	?>
	<li>
	   <?php echo $this->Html->link($this->Html->cText($videoCategory['VideoCategory']['name'], false) . ' (' . $videoCategory['VideoCategory']['count'] . ')', array('controller' => 'videos', 'action' => 'index', 'category' => $videoCategory['VideoCategory']['slug'], 'admin' => false)); ?></li>
	<?php
			endforeach;
		else:
	?>
	<li><p class="notice"><?php echo __l('No video channels available'); ?></p></li>
	<?php endif; ?>
</ul>