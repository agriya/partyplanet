<div class="js-response">
	<ol class="list feature-list clearfix" id='js-listing-block'>
		<?php
		 		if (!empty($venues)):
				$j = 0;
				foreach ($venues as $venue):
					$class = null;
					if ($j++ % 2 == 0) {
						$class = 'altrow';
					}
		?>
		<li class="clearfix <?php echo $class; ?>">
			<div class="grid_4 alpha">
				<?php
					echo $this->Html->link($this->Html->showImage('Venue', $venue['Attachment'], array('dimension' => 'home_newest_thumb','title'=>$venue['Venue']['slug'],'alt'=>sprintf('[Image: %s]', $this->Html->cText($venue['Venue']['name'], false)))), array('controller' => 'venues', 'action' => 'view',   $venue['Venue']['slug'],'admin'=>false), array('title'=>$venue['Venue']['slug'],'escape' => false), null, array('inline' => false));
				?>
			</div>
				<div class="grid_9 omega">
                    <div class="clearfix">
    					<h3 class="event-title-info grid_left"><?php echo $this->Html->link($this->Html->cText($venue['Venue']['name'],false), array('controller'=> 'venues', 'action' => 'view', $venue['Venue']['slug']), array('title' => $venue['Venue']['slug'], 'escape' => false));?>  (<?php  echo $this->Html->link($this->Html->cText($venue['VenueType']['name']), array('controller'=> 'venues', 'action' => 'index', 'category'=>$venue['VenueType']['slug']), array('title'=>$venue['VenueType']['name'],'escape' => false));?>)</h3>
                       	<?php if($venue['Venue']['admin_suspend'] == 1) { ?>
        					<span class="suspended-block grid_left">
        							<?php echo __l('Suspended');?>
        					</span>
    					<?php }?>
					</div>
					<address>
						<span><?php echo $this->Html->cText($venue['Venue']['address']);?></span>
						<span><?php echo $this->Html->cText($venue['City']['name']);?></span>
						<?php if(!empty($venue['VenueCategory'])) { ?>
							<?php
								$venuecate=array();
								foreach($venue['VenueCategory'] as $venuecategory){
									$venuecate[]=$venuecategory['name'];
								}
							?>
							<span><?php echo implode(',',$venuecate); ?></span>
						<?php } ?>
						<?php if(!empty($venue['MusicType'])) { ?>
							<?php
								$venuemusic=array();
								foreach($venue['MusicType'] as $venuemusictype){
									$venuemusic[]=$venuemusictype['name'];
								}
							?>
							<span><?php echo implode(',',$venuemusic); ?></span>
						<?php } ?>
					</address>
				</div>
				<?php if($venue['Venue']['admin_suspend'] == 0) { ?>
			    	<div class="event-action-block">
					<?php // echo $this->Html->link(__l('Promote'), array('controller'=> 'payments', 'action' => 'order', $venue['Venue']['slug'], 'venue'), array('title'=>__l('Promote'), 'class' => 'promote', 'escape' => false)); ?>
					<?php 
						if($venue['Venue']['is_venue_enhanced_page'] == 1 && $venue['Venue']['is_paid'] == 1) { 
							if($venue['Venue']['venue_gallery_id'] == 0) {
								echo $this->Html->link(__l('Upload Venue Gallery'), array('controller' => 'photo_albums', 'action' => 'add', 'venue_id' => $venue['Venue']['id'], 'type' => 'venuegallery'), array('title' => __l('Upload Venue Gallery'))); 
							} else {
								echo $this->Html->link(__l('Upload Venue Gallery'), array('controller' => 'photos', 'action' => 'add', $venue['Venue']['venue_gallery_id']), array('title' => __l('Upload Venue Gallery'))); 
							}
						}
					?>
					<span>
					   <?php  echo $this->Html->link(__l('Edit'), array('controller'=> 'venues', 'action' => 'edit', $venue['Venue']['id']), array('title'=>__l('Edit'), 'class' => 'edit', 'escape' => false));?>
                    </span>
                	<?php  echo $this->Html->link(__l('Delete'), array('controller'=> 'venues', 'action' => 'delete', $venue['Venue']['id']), array('class' => 'js-delete delete', 'title'=>__l('Delete'), 'escape' => false));?>
				</div>
				<?php } ?>
		
		</li>
		<?php
				endforeach;
			else:
		?>
		<li>
			<p class="notice"><?php echo __l('No venues available');?></p>
		</li>
	<?php
		endif;
	?>
	</ol>
	<?php if (!empty($venues)): ?>
		<div class="js-pagination">
		  <?php echo $this->element('paging_links'); ?>
		</div>
	<?php endif; ?>
</div>