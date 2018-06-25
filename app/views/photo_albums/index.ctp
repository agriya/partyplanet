<?php /* SVN: $Id: index.ctp 620 2009-07-14 14:04:22Z boopathi_23ag08 $ */ ?>
<?php if (empty($this->request->params['requested']) && empty($this->request->params['isAjax']) && empty($this->request->params['prefix'])): ?>
	<div class="crumb">
		<?php
			$this->Html->addCrumb(__l('Galleries'));
			echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
		?>
	</div>
<?php endif; ?>
<div class="photoAlbums index js-response clearfix">
	<div class="js-corner round-5 clearfix">
		<?php if(isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'search'): ?>
		 <div class="form-content-block">
			<?php echo $this->Form->create('PhotoAlbum' , array('type' => 'get','id'=>'PhotoAlbumTypeSearch', 'class' => 'normal search-form','action' => 'index'.'/type:search')); ?>
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
		<?php if (!empty($this->request->params['named']['location'])): ?>
			<ol class="list clearfix">
				<?php
					if (!empty($photoAlbums)):
					$i = 0;
					foreach ($photoAlbums as $photoAlbum):
						$class = null;
    						if($i==0)
							{
                                $img_thumb='menu_thumb';
                            }
                            else
                            {
                                $img_thumb='normalhigh_thumb';
                            }
						if ($i++ % 2 == 0) {
							$class = 'altrow';
						}
						$album_defalut_image = !empty($photoAlbum['Photo'][0]['Attachment']) ? $photoAlbum['Photo'][0]['Attachment'] : array();
				?>
						<li class="clearfix">
							<div class="image-block">
								<?php
									echo $this->Html->link($this->Html->showImage('Photo', $album_defalut_image, array('dimension' => $img_thumb, 'alt' => sprintf('[Image: %s]', $this->Html->cText($photoAlbum['PhotoAlbum']['title'], false)), 'title' => $this->Html->cText($photoAlbum['PhotoAlbum']['title'], false))), array('controller' => 'photos', 'action' => 'index', 'album' => $photoAlbum['PhotoAlbum']['slug']), array('escape' => false));
								?>
							</div>
						</li>
				<?php
					endforeach;
					else:?>
                    <li class="clearfix">
						<p class="notice">
					<?php echo __l('No photo galleries available');?>
                        </p></li><?php endif;?>
					</ol>
		<?php else: ?>
			<?php $list_class = ''; ?>
			<?php if (empty($this->request->params['named']['type'])): ?>
				<h2><?php echo __l('Gallery Results'); ?></h2>
				<?php echo $this->element('paging_counter');?>
				
					<?php
						if (!empty($this->request->params['named']['sort_by'])): ?>
						<div class="event-link clearfix">
						<?php
							$url = Router::url(array('controller' => 'photo_albums', 'action' => 'index') , true);
							echo $this->Form->input('sort_by', array('label' => __l('Sort by: '), 'empty' => __l('All'), 'options' => array('date' => __l('Date'), 'name' => __l('Name')), 'class' => "js-sort {'url':'".$url."'}"));
                        ?>
                        	</div>
                    <?php	endif;
					?>
			
			<?php endif; ?>
			<?php if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'list'): ?>
			
					<?php
						if (!empty($venue['Venue']['id'])): ?>
							<div class="event-link clearfix">
    							<p><?php echo __l('More Photos from') . ' ' . $this->Html->link($venue['Venue']['name'] . ' ' . ' Gallery' , array('controller' => 'photo_albums', 'action' => 'index', 'venue_id' => $venue['Venue']['id']), array('title' => sprintf(__l('More %s %s'), $venue['Venue']['name'], $venue['Country']['name']))); ?></p>
    							<p><?php echo __l('Upload') . ' ' . $this->Html->link($venue['Venue']['name'] . ' ' . $venue['Country']['name']. ' ' .'Gallery', array('controller' => 'photo_albums', 'action' => 'add', 'venue_id' => $venue['Venue']['id']), array('title' => sprintf(__l('Upload %s %s'), $venue['Venue']['name'], $venue['Country']['name']))); ?></p>
                            </div>
                	<?php elseif (!empty($event['Event']['id'])): ?>
                		  <div class="event-link clearfix">
						    <p><?php echo __l('More Photos from') . ' ' . $this->Html->link($event['Event']['title'] . ' ' . ' Gallery', array('controller' => 'photo_albums', 'action' => 'index', 'event_id' => $event['Event']['id']), array('title' => sprintf(__l('More %s'), $event['Event']['title']))); ?> </p>
                            <p><?php echo __l('Upload') . ' ' . $this->Html->link($event['Event']['title'] . ' Gallery', array('controller' => 'photo_albums', 'action' => 'add', 'event_id' => $event['Event']['id']), array('title' => sprintf(__l('Upload %s'), $event['Event']['title']))); ?> </p>
                         </div>
                	<?php endif;
						$list_class = ' gallery-list1';
					?>
				
			<?php endif; ?>
         <?php
			if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'last_night') {
				$grid_class="grid_11";
			} else {
				$grid_class="grid_4";
			}
		?>
          <ol class="list feature-list clearfix <?php echo $list_class; ?>">
			    <?php
					if (!empty($photoAlbums)):
						$i = 0;
						foreach ($photoAlbums as $photoAlbum):
							$class = null;
							if ($i++ % 2 == 0) :
								$class = 'altrow';
							endif;
							$album_defalut_image = isset($photoAlbum['Photo'][0]['Attachment']) ? $photoAlbum['Photo'][0]['Attachment'] : array();
				?>
						<li class="list-row clearfix <?php echo $class;?>">
						  <div class=" grid_4  alpha">
                            <?php
									echo $this->Html->link($this->Html->showImage('Photo', $album_defalut_image, array('dimension' => 'home_newest_thumb', 'alt' => sprintf('[Image: %s]', $this->Html->cText($photoAlbum['PhotoAlbum']['title'], false)), 'title' => $this->Html->cText($photoAlbum['PhotoAlbum']['title'], false))), array('controller' => 'photos', 'action' => 'index', 'album' => $photoAlbum['PhotoAlbum']['slug']), array('escape' => false));
								?>
							</div>
							<div class=" grid_11 omega ">
                                  	<?php if (empty($this->request->params['named']['venue_id']) || empty($this->request->params['named']['event_id'])): ?>
									<h3><?php echo $this->Html->link($this->Html->cText($photoAlbum['PhotoAlbum']['title'], false), array('controller' => 'photos', 'action' => 'index', 'album' => $photoAlbum['PhotoAlbum']['slug']), array('escape' => false)); ?></h3>
									<div class="js-desc-to-trucate {len:'100'}"><p><?php echo $this->Html->cText($photoAlbum['PhotoAlbum']['description']);?></p></div>
									<?php if (!empty($photoAlbum['Venue']['id'])): ?>
										<p><?php echo $this->Html->link($this->Html->cText($photoAlbum['Venue']['name'], false), array('controller' => 'venues', 'action' => 'view', $photoAlbum['Venue']['slug']), array('escape' => false)); ?></p>
									<?php elseif(!empty($photoAlbum['Event']['id'])): ?>
										<p><?php echo $this->Html->link($this->Html->cText($photoAlbum['Event']['title'], false), array('controller' => 'events', 'action' => 'view', $photoAlbum['Event']['slug']), array('escape' => false)); ?></p>
									<?php endif; ?>
									<p>
									by <?php echo $this->Html->link($this->Html->cText($photoAlbum['User']['username']) , array('controller' => 'users', 'action' => 'view', $photoAlbum['User']['username']) , array('escape' => false)); ?> on <?php echo $this->Html->cDate($photoAlbum['PhotoAlbum']['captured_date']); ?>
                                    </p>
                                    <p class="photos-count">
									     <span><?php echo __l('Photos: '); ?></span>
									     <span class="count-info">
								        <?php echo $this->Html->cInt($photoAlbum['PhotoAlbum']['photo_count']); ?>
                                        </span>
                                    </p>
								<?php endif; ?>
								<?php if ($this->Auth->user('id') && $photoAlbum['PhotoAlbum']['user_id'] == $this->Auth->user('id')): ?>
									<?php if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'last_night'): ?>
									<?php else: ?>
								    	<?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $photoAlbum['PhotoAlbum']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?>
								    	<?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $photoAlbum['PhotoAlbum']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
									
									<?php endif; ?>
								<?php endif; ?>
							</div>
						</li>
				<?php
						endforeach;
					else:
				?>
					<li>
						<p class="notice">
							<?php if (!empty($venue['Venue']['id'])): ?>
								<?php echo sprintf(__l('There are no photo galleries for %s yet.'), $this->Html->cText($venue['Venue']['name'])); ?>
							<?php elseif (!empty($event['Event']['id'])): ?>
								<?php echo sprintf(__l('There are no photo galleries for %s yet.'), $this->Html->cText($event['Event']['title'])); ?>
							<?php else: ?>
								<?php echo __l('No photo galleries available');?>
							<?php endif; ?>
						</p>
					</li>
				<?php
					endif;
				?>
			</ol>
			<?php
				if (!empty($photoAlbums)):
					if (!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'last_night' || $this->request->params['named']['type'] == 'latest')):
					echo $this->Html->link(__l('View More'), array('controller' => 'photo_albums', 'action' => 'index'),array('title'=>__l('View More')));
					else:
					?>
					<div class="js-pagination">
						<?php
							echo $this->element('paging_links');
						?>
					</div>
				<?php
					endif;
				endif;
			?>
		<?php endif; ?>
	</div>
</div>