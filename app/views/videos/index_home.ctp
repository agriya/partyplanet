<ol class="list photo-list clearfix">
<?php if (!empty($videos)): ?>

	<?php
            $i = 0;
            foreach ($videos as $video):
                $class = null;
				if ($i++ % 2 == 0)
                {
					$class = 'altrow';
				} ?>
                <li class="grid_4 alpha omega"> 
                 <?php
					$video['Thumbnail']['id'] = (!empty($video['Video']['default_thumbnail_id'])) ? $video['Video']['default_thumbnail_id'] : '';
					echo $this->Html->link('<span class="play-block">&nbsp;</span>'.$this->Html->showImage('Video', $video['Thumbnail'], array('dimension' => 'featured_event_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($video['Video']['title'], false)), 'title' => $this->Html->cText($video['Video']['title'], false))), array('controller' => 'videos', 'action' => 'view', $video['Video']['slug']) , array('escape' => false));
				 ?>
                  <h3><?php
                    	echo $this->Html->link($this->Html->cText($video['Video']['title']) , array('controller' => 'videos', 'action' => 'v', 'action' => 'view', $video['Video']['slug']) , array('escape' => false)) . ' '?>
                  </h3>
                  <p><?php echo $this->Html->cDateTime($video['Video']['created']); ?></p>
                    <p><span><?php  echo __l('Views:'); ?> </span> <?php   echo $this->Html->cInt($video['Video']['video_view_count']); ?></p> </li>
                
              	<?php endforeach; ?>
		<?php else: ?>
			<li class="notice-info"><p class="notice"><?php echo __l('No videos available'); ?></p></li>
		<?php endif; ?>
	</ol>
   <div class="view-all-links">
        <span>
             <?php echo $this->Html->link(__l('View More'), array('controller' => 'videos', 'action' => 'index'), array('escape' => false)); ?>
        </span>
    </div>