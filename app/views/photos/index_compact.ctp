<?php if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'hotties' && empty($this->request->params['requested'])): ?>
	<h2><?php echo Configure::read('site.name'); ?> <?php echo __l('Hotties'); ?></h2>
<?php elseif (empty($this->request->params['requested']) && empty($this->request->params['named']['location']) && (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] !='favorite')): ?>
	<h2><?php echo __l('Most Popular Gallery Images');?></h2>
<?php endif; ?>
<div class="photos index">
	<ol class="list feature-list clearfix">
		<?php if (!empty($photos)): ?>
			<?php
			$class = null;
			$j=1;
				$i=1;
				foreach($photos as $photo):
						$img_thumb = 'normalhigh_thumb';
					if ($i == 1 && !empty($this->request->params['requested'])):
						$img_thumb = 'menu_thumb';
					endif;
					$i++;
					if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] =='favorite'){
					$class = null;
						if ($j++ % 2 == 0) :
							$class = 'altrow';
						endif;
						$img_thumb = 'home_newest_thumb';
					}
			?>
			<li class="clearfix <?php echo $class;?>">
			<div class="grid_4  alpha"><?php echo $this->Html->link($this->Html->showImage('Photo', $photo['Attachment'], array('dimension' => $img_thumb, 'alt' => sprintf('[Image: %s]', $this->Html->cText($photo['Photo']['title'], false)), 'title' => $this->Html->cText($photo['Photo']['title'], false))), array('controller' => 'photos', 'action' => 'index', 'album' => $photo['PhotoAlbum']['slug'], 'photo' => $photo['Photo']['slug']), array('escape' => false)); ?>
            </div>
			<?php if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] =='favorite'){?>
            <div class="grid_11 omega">
                  	<?php if (empty($this->request->params['named']['venue_id']) || empty($this->request->params['named']['event_id'])): ?>
					<h3><?php echo !empty($photo['PhotoAlbum']['title']) ? $this->Html->link($this->Html->cText($photo['PhotoAlbum']['title'], false), array('controller' => 'photos', 'action' => 'index', 'album' => $photo['PhotoAlbum']['slug']), array('escape' => false)) : ''; ?></h3>
					<?php if (!empty($photo['PhotoAlbum']['Venue']['id'])): ?>
						<h3><?php echo $this->Html->link($this->Html->cText($photo['PhotoAlbum']['Venue']['name'], false), array('controller' => 'venues', 'action' => 'view', $photo['PhotoAlbum']['Venue']['slug']), array('escape' => false)); ?></h3>
					<?php elseif(!empty($photo['PhotoAlbum']['Event']['id'])): ?>
						<p><?php echo $this->Html->link($this->Html->cText($photo['PhotoAlbum']['Event']['title'], false), array('controller' => 'events', 'action' => 'view', $photo['PhotoAlbum']['Event']['slug']), array('escape' => false)); ?></p>
					<?php endif; ?>
					<p>by <?php echo $this->Html->link($this->Html->cText($photo['User']['username']) , array('controller' => 'users', 'action' => 'view', $photo['User']['username']) , array('escape' => false)); ?> on <?php echo !empty($photo['PhotoAlbum']['captured_date']) ? $this->Html->cDateTime($photo['PhotoAlbum']['captured_date']) : ''; ?></p>
             	<p><span><?php echo __l('Photos: '); ?></span>
				<?php echo $this->Html->cInt($photo['PhotoAlbum']['photo_count']); ?></p>
				<?php endif; ?>
				<?php if ($this->Auth->user('id') && !empty($photo['PhotoAlbum']['user_id']) && $photo['PhotoAlbum']['user_id'] == $this->Auth->user('id')): ?>
				<div><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $photo['PhotoAlbum']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></div>
				<div><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $photo['PhotoAlbum']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></div>
					<?php endif; ?>
				</div>
           <?php  }?>
			</li>

			<?php
				endforeach;
			else:
			?>
			<li class="no-record"><p class="notice"><?php echo __l('No photos available'); ?></p></li>
	<?php endif; ?>
	</ol>
</div>