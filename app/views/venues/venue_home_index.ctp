<?php if($this->request->params['named']['type'] == 'home'):?>
<div class="venues index">
<?php if(!empty($this->request->params['named']['category'])) { ?>
	<div class="js-tabs">
		<ul class="clearfix ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header">
			<?php foreach($venue_types as $venue_type) {?>
				<li><?php echo $this->Html->link(__l($venue_type['VenueType']['name']), array('controller' => 'venues', 'action' => 'index','type'=>'home','limit'=>'4','category'=>$venue_type['VenueType']['slug'],'admin' => false), array('title' => __l($venue_type['VenueType']['name']),'rel' => 'address:/' . __l($venue_type['VenueType']['name'])));?></li>
			<?php } ?>
		</ul>
	</div>
<?php } else { ?>
	<div class="js-response">
		<ol class="list venue-list clearfix" id='js-listing-block'>
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
			   <?php
						echo $this->Html->link($this->Html->showImage('Venue', $venue['Attachment'], array('dimension' => 'home_featured_thumb','title'=>$venue['Venue']['slug'],'alt'=>sprintf('[Image: %s]', $this->Html->cText($venue['Venue']['name'],false)))), array('controller' => 'venues', 'action' => 'view',   $venue['Venue']['slug'],'admin'=>false), array('title'=>$venue['Venue']['slug'],'escape' => false), null, array('inline' => false));
					?>
					<h3><?php echo $this->Html->link($this->Html->cText($venue['Venue']['name'],false), array('controller'=> 'venues', 'action' => 'view', $venue['Venue']['slug']), array('title' => $venue['Venue']['slug'], 'escape' => false));?> </h3>
                    <address>
						<span><?php echo $this->Html->cText($venue['Venue']['address']);?></span>
						<span><?php echo $this->Html->cText($venue['City']['name']);?></span>
                    </address>
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
		<?php
	  if (!empty($venues)):
	?>
        <div class="js-pagination">
          <?php   echo $this->element('paging_links'); ?>
        </div>
	<?php
	endif;
	?>
	</div>
<?php } ?>
</div>
<?php else:?>
	<?php
		if (!empty($venues)):
			$j = 0;	?>
	    <ol class="list photo-list clearfix">
			 <?php
				foreach ($venues as $venue):
				    $class = null;
					if ($j++ % 2 == 0) {
						$class = 'altrow';
					}
			?>
            <li class="grid_4 alpha omega">
            <?php
					echo $this->Html->link($this->Html->showImage('Venue', $venue['Attachment'], array('dimension' => 'home_newest_thumb','title'=>$venue['Venue']['slug'],'alt'=>sprintf('[Image: %s]', $this->Html->cText($venue['Venue']['name'],false)))), array('controller' => 'venues', 'action' => 'view',   $venue['Venue']['slug'],'admin'=>false), array('title'=>$venue['Venue']['slug'],'escape' => false), null, array('inline' => false));
					?>
                  <h3><?php echo $this->Html->link($this->Html->cText($venue['Venue']['name'],false), array('controller'=> 'venues', 'action' => 'view', $venue['Venue']['slug']), array('title' => $venue['Venue']['slug'], 'escape' => false));?> </h3>
                    <p><?php echo $this->Html->cDateTime($venue['Venue']['created']); ?></p>
                    <p><span><?php echo __l('Photos galleries:');?> </span> <?php echo $this->Html->cInt($venue['Venue']['photo_album_count']);?></p> </li>
                    
                    <?php
					endforeach;?>
					</ol>
				<?php else:
			?>
			 <ol class="list photo-list clearfix">
    			<li class="notice-info">
    				<p class="notice"><?php echo __l('No venues available');?></p>
    			</li>
			</ol>
	<?php
	endif;
	?>
              
<?php endif;?>