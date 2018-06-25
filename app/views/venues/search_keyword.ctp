<div class="js-response">
	<?php echo $this->element('paging_counter');?>
	<ol class="list feature-list clearfix">
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
			<div class="grid_12 omega">
				<h3><?php echo $this->Html->link($this->Html->cText($venue['Venue']['name'],false), array('controller'=> 'venues', 'action' => 'view', $venue['Venue']['slug']), array('title' => $venue['Venue']['slug'], 'escape' => false));?>  (<?php  echo $this->Html->link($this->Html->cText($venue['VenueType']['name']), array('controller'=> 'venues', 'action' => 'index', 'category'=>$venue['VenueType']['slug']), array('title'=>$venue['VenueType']['name'],'escape' => false));?>)</h3>
				<address>
					<span><?php echo $this->Html->cText($venue['Venue']['address']);?></span>
					<?php if (!empty($venue['City'])): ?>
						<span><?php echo $this->Html->cText($venue['City']['name']);?></span>
					<?php endif; ?>
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