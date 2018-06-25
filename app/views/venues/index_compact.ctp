<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
  <ol class="list feature-list">
	  		<?php if (!empty($venues)): ?>
			<?php $j = 0; ?>
			<?php foreach ($venues as $venue): ?>
				<?php
					$class = null;
					if ($j++ % 2 == 0) :
						$class = ' altrow';
					endif;
				?>
				<li class="clearfix<?php echo $class; ?>">
					<div class="grid_3 omega alpha">
						<?php
							echo $this->Html->link($this->Html->showImage('Venue', $venue['Attachment'], array('dimension' => 'sidebar_thumb', 'title' => $this->Html->cText($venue['Venue']['name'], false), 'alt' => sprintf('[Image: %s]', $venue['Venue']['slug']))), array('controller' => 'venues', 'action' => 'view',   $venue['Venue']['slug'], 'admin' => false), array('escape' => false));
						?>
					</div>
					<div class="content-block grid_5 omega alpha">
						<h3><?php echo $this->Html->link($this->Html->truncate($this->Html->cText($venue['Venue']['name'],false),20), array('controller'=> 'venues', 'action' => 'view', $venue['Venue']['slug']), array('title' => $venue['Venue']['slug'], 'escape' => false));?> </h3>
						
								<?php if (!empty($venue['VenueCategory'])): ?>
									<?php
										$venuecate =array();
										foreach ($venue['VenueCategory'] as $venuecategory):
											$venuecate[] = $venuecategory['name'];
										endforeach;
									?>
									<p><span><?php echo implode(', ', $venuecate); ?></span></p>
								<?php endif; ?>
								<?php if(!empty($venue['MusicType'])): ?>
								<p><span>
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
								</span></p>
								<?php endif; ?>
							
					</div>
				</li>
			<?php endforeach; ?>
		<?php else: ?>
			<li class="clearfix">
				<p class="notice"><?php echo __l('No venues available');?></p>
			</li>
		<?php endif; ?>
	</ol>
