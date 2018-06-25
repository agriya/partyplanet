<?php /* SVN: $Id: index.ctp 2273 2009-09-18 09:36:07Z boopathi_026ac09 $ */ ?>
<?php if (empty($this->request->params['requested']) && empty($this->request->params['isAjax']) && empty($this->request->params['prefix'])): ?>
	<div class="crumb">
		<?php
			$this->Html->addCrumb(__l('Videos'));
			echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
		?>
	</div>
<?php endif; ?>
<div class="js-response">
		<h2>
			<?php
				if (!empty($this->request->params['named']['type'])) {
					if  ($this->request->params['named']['type'] == 'recent') {
						echo __l('Most Recent Videos');
					} elseif ($this->request->params['named']['type'] == 'site') {
						echo Configure::read('site.name') . __l(' Recommends');
					} else if ($this->request->params['named']['type'] == 'popular') {
						echo __l('Most Popular Videos');
					}
				} else if (isset($tag_name)) {
					echo __l(' Videos') . ' - ' . $tag_name;
				} else {
					if (!empty($this->request->params['named']['username'])){
						echo ucfirst($this->request->params['named']['username']);
					}
					echo __l(' Videos');
				}
			?>
		</h2>
	<?php if(isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'search'): ?>
		 <div class="form-content-block">
			<?php echo $this->Form->create('Video' , array('type' => 'get', 'id' => 'VideoTypeSearch', 'class' => 'normal search-form', 'action' => 'index'.'/type:search')); ?>
				<div class="filter-section">
					<div>
						<?php echo $this->Form->input('keyword', array('label' => 'Keyword')); ?>
					</div>
					<div>
						<div class="submit-block clearfix">
							<?php echo $this->Form->submit(__l('Search'));?>
						</div>
					</div>
				</div>
			<?php echo $this->Form->end(); ?>
		</div>
	<?php endif; ?>
	<?php echo $this->element('paging_counter');?>
	<ol class="list feature-list clearfix">
		<?php if (!empty($videos)): ?>
			<?php
            $i = 0;
            foreach ($videos as $video):
                $class = null;
				if ($i++ % 2 == 0)
                {
					$class = 'altrow';
				} ?>
				<li class="clearfix <?php echo $class; ?>">
					<div class="grid_4 omega alpha">
						<?php
							$video['Thumbnail']['id'] = (!empty($video['Video']['default_thumbnail_id'])) ? $video['Video']['default_thumbnail_id'] : '';
							echo $this->Html->link($this->Html->showImage('Video', $video['Thumbnail'], array('dimension' => 'featured_event_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($video['Video']['title'], false)), 'title' => $this->Html->cText($video['Video']['title'], false))) , array('controller' => 'videos', 'action' => 'view', $video['Video']['slug']) , array('escape' => false));
						?>
					</div>
					<div class="grid_9 omega alpha">
					
								<h3><?php
                            	echo $this->Html->link($this->Html->cText($video['Video']['title']) , array('controller' => 'videos', 'action' => 'view', $video['Video']['slug']) , array('escape' => false)) . ' '?>
                                </h3>
                            	<?php if($video['Video']['class'] == 'Venue') {
								echo ' ' . $this->Html->link($this->Html->truncate($this->Html->cText($video['Venue']['name'],false), 40), array('controller'=> 'venues', 'action' => 'view', $video['Venue']['slug']), array('title'=>$this->Html->cText($video['Venue']['name'],false),'escape' => false)). ' ';?><br/>
        						<?php } elseif($video['Video']['class'] == 'Event') {
       								 echo ' ' . $this->Html->link($this->Html->truncate($this->Html->cText($video['Event']['title'],false), 40), array('controller'=> 'events', 'action' => 'view', $video['Event']['slug']), array('title'=>$this->Html->cText($video['Event']['title'],false),'escape' => false)). ' ';?><br/>
							<?php	}
								?>
								<p>
								by <?php echo $this->Html->link($this->Html->cText($video['User']['username']) , array('controller' => 'users', 'action' => 'view', $video['User']['username']) , array('escape' => false)); ?> on <?php echo $this->Html->cDateTime($video['Video']['created']); ?>
                                </p>
                                 <?php if(empty($this->request->params['named']['favorite'])):?>
                                <p><?php  echo __l('Views'); ?> <?php   echo $this->Html->cInt($video['Video']['video_view_count']); ?></p>
						
							<?php endif;?>
							</div>
							<div class="grid_right">
    						<ul class="share-list clearfix">
								<li>
									<a href="http://twitter.com/share?url=<?php echo Router::url(array('controller' => 'videos', 'action' => 'view', $video['Video']['slug']),true); ?>&amp;text=<?php echo $video['Video']['title'];?>&amp;lang=en&amp;via=<?php echo $this->Html->cText($video['Video']['title'], false); ?>" class="twitter-share-button" data-count="none"><?php echo __l('Tweet!');?></a>
									<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
								</li>
								<li class="article-fb-share">
									<a href="http://www.facebook.com/sharer.php?u=<?php echo Router::url(array('controller' => 'videos', 'action' => 'view', $video['Video']['slug']),true); ?>&amp;t=<?php echo $video['Video']['title'];?>" target="_blank" class="fb-share-button"><?php echo __l('fbshare'); ?></a>
								</li>
							</ul>
					
						</div>
				
				</li>
			<?php endforeach; ?>
		<?php else: ?>
			<li><p class="notice"><?php echo __l('No videos available'); ?></p></li>
		<?php endif; ?>
	</ol>
	<div class="js-pagination">
		<?php
			if (!empty($events)) :
				echo $this->element('paging_links');
			endif;
		?>
	</div>
</div>
