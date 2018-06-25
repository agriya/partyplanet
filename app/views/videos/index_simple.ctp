<?php /* SVN: $Id: index_simple.ctp 1662 2009-08-17 19:34:56Z siva_063at09 $ */ ?>
<div class="js-response">
<?php if (isset($username) and !empty($username)): ?>
<?php $css_class = 'album-menu clearfix'; ?>
	<h2><span><?php echo __l('More videos by ').$this->Html->link($this->Html->cText($username) , array('controller' => 'videos', 'action' => 'index', 'username' => $username) , array('escape' => false)); ?></span></h2>
<?php else: 
	if(empty($this->request->params['named']['favorite'])):
?>
    <h2><?php echo __l('Most') . ' ' . '<span>' . ' ' . __l('Popular Videos') . '</span>'; ?></h2>
<?php endif;
endif;
?>
     <?php if (!empty($videos)): ?>
	<?php echo $this->element('paging_counter');?>
	<?php endif;?>
	<ol class="list feature-list clearfix">
		<?php if (!empty($videos)): ?>
			<?php
            $i = 0;
            foreach ($videos as $video):
                $class = null;
				if ($i++ % 2 == 0)
                {
					$class = 'altrow';
				}                ?>
				<li class="clearfix <?php echo $class; ?>">
					<div class="grid_3 omega alpha">
						<?php
							$video['Thumbnail']['id'] = (!empty($video['Video']['default_thumbnail_id'])) ? $video['Video']['default_thumbnail_id'] : '';
							echo $this->Html->link($this->Html->showImage('Video', $video['Thumbnail'], array('dimension' => 'sidebar_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($video['Video']['title'], false)), 'title' => $this->Html->cText($video['Video']['title'], false))) , array('controller' => 'videos', 'action' => 'view', $video['Video']['slug']) , array('escape' => false));
						?>
					</div>
					<div class="grid_5 omega alpha">
                   		<h3><?php
                    	echo $this->Html->link($this->Html->cText($video['Video']['title']) , array('controller' => 'videos', 'action' => 'v', 'action' => 'view', $video['Video']['slug']) , array('escape' => false)) . ' '?>
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
                        <p>
					      <?php  echo __l('Views'); ?>
							<?php   echo $this->Html->cInt($video['Video']['video_view_count']); ?>
                            </p>
					   
					    <?php endif;?>
                		</div>
            		  <?php
                         if(empty($this->request->params['named']['tweet'])):?>
						<div class="grid_right">
						 <ul class="share-list clearfix">
							<li>
								<a href="http://twitter.com/share?url=<?php echo Router::url(array('controller' => 'videos', 'action' => 'view', $video['Video']['slug']),true); ?>&amp;text=<?php echo $video['Video']['title'];?>&amp;lang=en&amp;via=<?php echo $this->Html->cText($video['Video']['title'], false); ?>" class="twitter-share-button" data-count="none"><?php echo __l('Tweet!');?></a>
								<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
							</li>
							<li class="article-fb-share">
								<a href="http://www.facebook.com/sharer.php?u=<?php echo Router::url(array('controller' => 'videos', 'action' => 'view', $video['Video']['slug']),true); ?>&amp;t=<?php echo $this->Html->cText($video['Video']['title'], false); ?>" target="_blank" class="fb-share-button"><?php echo __l('fbshare'); ?></a>
							</li>
						</ul>
						 </div>
                         <?php endif;?>
				</li>
			<?php endforeach; ?>
		<?php else: ?>
			<li><p class="notice"><?php echo __l('No videos available'); ?></p></li>
		<?php endif; ?>
	</ol>
	</div>
