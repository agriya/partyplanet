<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="venues index js-response">
	<?php if (empty($this->request->params['named']['joined'])) { ?>
		<div class="crumb-block">
			<?php echo $this->Html->addCrumb(__l('Venue')); ?>
			<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
		</div>
		<?php if (empty($requested)): ?>
				<?php if (empty($this->request->params['named']['type']) or $this->request->params['named']['type'] != 'featured-all'): ?>
			<h2><?php echo __l('Venues');?></h2>
			  <ul class="add-venue-list add-venue-list1 grid_right omega clearfix">
        			<?php if ($this->Auth->user('user_type_id') != ConstUserTypes::User): ?>
        			<li><?php echo $this->Html->link(__l('Add venue'), array('action' => 'add'), array('class' => 'add', 'title' => __l('Add Venue')));?></li>
        			<?php endif; ?>
        			 <li><?php echo $this->Html->link(__l('Advanced search'), array('controller' => 'venues', 'action' => 'search'), array('class' => 'add', 'title' => __l('Advanced search')));?></li>
                </ul>
			<?php else:?>
						<h2><?php echo __l('Featured Venues');?></h2>
			<?php endif;?>
              
   <?php if (!empty($venues) and (empty($this->request->params['named']['type']) or $this->request->params['named']['type'] != 'featured-all')): ?>
 		<div class="filter-block clearfix">
				<h4 class="filter-title grid_left"><?php echo __l('Filter by: '); ?></h4>
				<ul class="venue-seach grid_left clearfix">
					<li>
						<span><em><?php echo __l('Neighborhood'); ?></em></span>
						<ul class="venue-seach-list filter-links">
							<?php foreach ($venueCities as $venueCity): ?>
								<li <?php if (!empty($city) && $venueCity['City']['slug'] == $city) { ?>class="active"<?php }?>>
									<?php echo $this->Html->link(__l(sprintf('%s (%s)',$venueCity['City']['name'],$venueCity[0]['venue_count'])), array('controller'=> 'venues', 'action' => 'index', 'city' => $venueCity['City']['slug']), array('title' => __l(sprintf('%s (%s)',$venueCity['City']['name'],$venueCity[0]['venue_count'])), 'escape' => false));?>
								</li>    
							<?php endforeach; ?>
						</ul>
					</li>
					<li>
						<span><em><?php echo __l('Beginning with...'); ?></em></span>
						<ul class="filter-links">
							<?php foreach ($venueKeywords as $venueKeyword): ?>
								<li<?php if (!empty($this->request->params['named']['venue_beginning']) && $venueKeyword[0]['keyword'] == $this->request->params['named']['venue_beginning']) { ?> class="active"<?php } ?>>
									<?php echo $this->Html->link(__l(sprintf('%s (%s)', strtoupper($venueKeyword[0]['keyword']), $venueKeyword[0]['venue_count'])), array('controller'=> 'venues', 'action' => 'index', 'venue_beginning' => $venueKeyword[0]['keyword']), array('title' => __l(sprintf('%s (%s)', $venueKeyword[0]['keyword'], $venueKeyword[0]['venue_count'])), 'escape' => false));?>
								</li>
							<?php endforeach; ?>
						</ul>
					</li>
					<li>
						<span><em><?php echo __l('Venue Type'); ?></em></span>
						<ul class="venue-seach_list filter-links">
							<?php foreach ($venueTypes as $venueType): ?>
								<?php
									$count = 0;
									if (!empty($venueTypeVenueCount[$venueType['VenueType']['id']])):
										$count = $venueTypeVenueCount[$venueType['VenueType']['id']];
									endif;
								?>
								<li <?php if (!empty($category) && $venueType['VenueType']['slug'] == $category) { ?>class="active"<?php }?>>
									<?php echo $this->Html->link(__l(sprintf('%s (%s)',$venueType['VenueType']['name'], $count)), array('controller' => 'venues', 'action' => 'index', 'category' => $venueType['VenueType']['slug']), array('title' => __l(sprintf('%s (%s)', $venueType['VenueType']['name'], $count)), 'escape' => false));?>
								</li>
							<?php endforeach; ?>
						</ul>
					</li>
					<li>
						<span><em><?php echo __l('Music Type'); ?></em></span>
						<ul class="venue_seach_list filter-links">
                          	<?php foreach ($musicTypes as $musicType): ?>
								<?php
									$count = 0;
									if (!empty($musicTypeVenueCount[$musicType['MusicType']['id']])):
										$count = $musicTypeVenueCount[$musicType['MusicType']['id']];
									endif;
								?>
								<li <?php if (!empty($music) && $musicType['MusicType']['slug'] == $music) { ?>class="active"<?php }?>>
									<?php echo $this->Html->link(__l(sprintf('%s (%s)', $musicType['MusicType']['name'], $count)), array('controller'=> 'venues', 'action' => 'index', 'music' => $musicType['MusicType']['slug']), array('title' => __l(sprintf('%s (%s)', $musicType['MusicType']['name'], $count)), 'escape' => false));?>
								</li>
							<?php endforeach; ?>
						</ul>
					</li>
				</ul>
			</div>
		
			<?php
				echo $this->Form->create('Venue', array('class' => 'normal search-form clearfix', 'action' => 'index', 'type' => 'get', 'id' => 'VenueFilterForm'));
				echo $this->Form->input('keyword', array('label' => __l('Keywords'))); ?>
				<div class="submit-block clearfix">
    				<?php echo $this->Form->submit(__l('Search')); ?>
                </div>
            
				<?php echo $this->Form->input('latitude',array('type' => 'hidden','value'=>'', 'id'=>'latitude'));
				echo $this->Form->input('longitude',array('type' => 'hidden','value'=>'', 'id'=>'longitude'));
				echo $this->Form->input('action',array('type' => 'hidden','value'=>'view','id'=>'action'));
				echo $this->Form->end();
			?>
        <?php endif;?>
		<?php endif; ?>
		<div class="sort-outer-block clearfix">
		<div class="grid_left">
		<?php echo $this->element('paging_counter');?>
		</div>
		<?php if (!empty($venues)and (empty($this->request->params['named']['type']) or $this->request->params['named']['type'] != 'featured-all')): ?>
		<div class="clearfix sort-block grid_right">
			<h4 class="sort-title grid_left"><?php echo __l('Sort by').': ';?></h4>
			<?php $category = (!empty($this->request->params['named']['category'])) ? 'category:'.$this->request->params['named']['category'] : ''; ?>
			<ul class="sort-links-left sort-link grid_left clearfix">
				<li <?php  if(!empty($sort) && strstr($sort, "name")){?> class ="active" <?php }?>><?php echo $this->Html->link(__l('A-Z'), array('controller'=> 'venues', 'action' => 'index', 'sort' => 'name', 'direction' => 'asc',$category), array('title' => __l('Alphabetical'), 'escape' => false));?></li>
				<li <?php  if(!empty($sort) && strstr($sort, "venue_user_count")){?> class ="active" <?php }?>><?php echo $this->Html->link(__l('Popularity'), array('controller'=> 'venues', 'action' => 'index', 'sort' => 'venue_user_count', 'direction' => 'desc',$category), array('title' => __l('Popularity'), 'escape' => false));?></li>
				<li <?php  if(!empty($sort) && strstr($sort, "created")){?> class ="active" <?php }?>><?php echo $this->Html->link(__l('Recent'), array('controller'=> 'venues', 'action' => 'index', 'sort' => 'created', 'direction' => 'desc',$category), array('title' => __l('Recent'), 'escape' => false));?></li>
				<li <?php  if(!empty($sort) && $sort == "venue_comment_count"){?> class ="active" <?php }?>><?php echo $this->Html->link(__l('Discussions'), array('controller'=> 'venues', 'action' => 'index', 'sort' => 'venue_comment_count', 'direction' => 'desc',$category), array('title' => __l('Discussions'), 'escape' => false));?></li>
			</ul>
		</div>
		<?php endif;?>
		</div>
	<?php } ?>
	<ol class="list feature-list clearfix" start="<?php echo $this->Paginator->counter(array('format' => '%start%'));?>">
      			<?php if (!empty($venues)): ?>
					<?php
						$j = 0;
						foreach ($venues as $venue):
							$class = null;
							if ($j++ % 2 == 0) {
								$class1 = 'altrow';
							}
							if (!empty($venue['Venue']['is_sponsor'])) {
								$class1=' sposor_venue';
							}
				?>
							<li class="clearfix <?php echo $class1; ?>">
								<div class="grid_4 alpha">
									<?php echo $this->Html->link($this->Html->showImage('Venue', $venue['Attachment'], array('dimension' => 'home_newest_thumb','title'=>$venue['Venue']['slug'],'alt'=>sprintf('[Image: %s]', $this->Html->cText($venue['Venue']['name'], false)))), array('controller' => 'venues', 'action' => 'view',   $venue['Venue']['slug'],'admin'=>false), array('title'=>$venue['Venue']['slug'],'escape' => false), null, array('inline' => false)); ?>
								</div>
								<div class="grid_10 omega">
									<h3><?php echo $this->Html->link($this->Html->cText($venue['Venue']['name']), array('controller'=> 'venues', 'action' => 'view', $venue['Venue']['slug']), array('title' => $venue['Venue']['slug'], 'escape' => false));?>  (<?php  echo $this->Html->link($this->Html->cText($venue['VenueType']['name'],false), array('controller'=> 'venues', 'action' => 'index', 'category'=>$venue['VenueType']['slug']), array('title'=>$venue['VenueType']['name'],'escape' => false));?>)</h3>
									<address>
										<span><?php echo $this->Html->cText($venue['Venue']['address']);?></span>
										<span><?php echo $this->Html->cText($venue['City']['name']);?>
										</span>
									</address>
										<?php if (!empty($venue['VenueCategory'])): ?>
											<?php
												$venuecate =array();
												foreach ($venue['VenueCategory'] as $venuecategory):
													$venuecate[] = $venuecategory['name'];
												endforeach;
											?>
											<span><?php echo implode(', ', $venuecate); ?></span>
										<?php endif; ?>
										<?php if(!empty($venue['MusicType'])): ?>
										<span>
											<?php
												$i=1;
												foreach($venue['MusicType'] as $venuemusictype):
													echo $this->Html->link($this->Html->cText($venuemusictype['name'], false), array('controller' => 'venues', 'action' => 'index', 'music' => $venuemusictype['slug']), array('title' => $this->Html->cText($venuemusictype['name'], false), 'escape' => false));
													if($i != count($venue['MusicType'])){
														echo ', ';
													}
													$i++;
												endforeach;
											?>
										</span>
										<?php endif; ?>
									
								</div>
        						<div class="action-block grid_3 grid_right omega alpha">
									<p class="action-info"><span><?php echo $this->Html->link($this->Html->cInt($venue['Venue']['venue_user_count']), array('controller' => 'venue_users', 'action' => 'index', 'venue' => $venue['Venue']['slug']), array('title' => $this->Html->cInt($venue['Venue']['venue_user_count'],false), 'escape' => false));?></span><?php echo __l('People like it'); ?></p>
									<?php echo $this->Html->link(sprintf('(%s) ', $venue['Venue']['venue_comment_count']).__l('Reviews'), array('controller' => 'venues', 'action' => 'view', $venue['Venue']['slug'] . '#reviews'), array('title' => __l(sprintf('(%s) reviews', $venue['Venue']['venue_comment_count'])), 'escape' => false)); ?>
								</div>
                       </li>
					<?php endforeach; ?>
				<?php else: ?>
    					<li>
    						<p class="notice"><?php echo __l('No venues available');?></p>
    					</li>
				<?php endif; ?>
			</ol>

		<?php
			if (!empty($venues)) {?>
  	<div class="js-pagination">
			 <?php echo $this->element('paging_links'); ?>
          </div>
	<?php
			}
		?>
</div>