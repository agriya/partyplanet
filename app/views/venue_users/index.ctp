<?php /* SVN: $Id: index.ctp 1584 2009-05-18 06:56:25Z jayapriya_28ag08 $ */ ?>
<?php if(!isset($type)) { ?>
<div id="breadcrumb">
		<?php 
			echo $this->Html->addCrumb($this->Html->cText($venue['Venue']['name'],false), array('controller' => 'venues', 'action' => 'view', $venue['Venue']['slug']));
			echo $this->Html->addCrumb(__l('Venue User'));
			echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
		?>
	</div>
<?php } ?>

	<h3>
		<?php 
			if(!isset($type)) {	
				echo __l('Regulars of ') . '<span>' . $this->Html->cText($venue['Venue']['name'],false) . ', ' .  $this->Html->cText($venue['City']['name']) . '</span>';
			} else {
				echo __l('Regulars of ') . '<span>' . $this->Html->cText($venue['Venue']['name'],false) . ', ' .  $this->Html->cText($venue['City']['name']) . '</span>' . ' (' . $this->Html->link($venueUsercount, array('controller' => 'venue_users', 'action' => 'index', 'venue' => $venue['Venue']['slug'])) . ')';
			}
		?>
	</h3>
	<ol class="list feature-list" start="<?php echo $this->Paginator->counter(array('format' => '%start%'));?>">
		<?php
		 if (!empty($venueUsers)):
				$i = 0;
				foreach ($venueUsers as $venueUser):
					$class = null;
					if ($i++ % 2 == 0) {
						$class = 'altrow';
					}
		?>
		<li class="clearfix">
			<?php if (!empty($this->request->params['named']['venue'])) { ?>
				<div class="grid_3 omega alpah">
					<?php 
						echo $this->Html->getUserAvatar($venueUser['User'], 'sidebar_thumb');?>
     			</div>
				<div class="grid_5 omega alpha">
                <h3 ><?php echo $this->Html->link($venueUser['User']['username'], array('controller' => 'users', 'action' => 'view', $venueUser['User']['username']));?>
                </h3>
                <dl class="list user-list clearfix">
                   		<?php if (!empty($venueUser['User']['UserProfile']['dob'])): ?>
							 <dt><?php echo __l('Age:').' '; ?></dt>
							 <dd>
							<?php echo $this->Html->userAge($venueUser['User']['UserProfile']['dob']); ?>
							</dd>
                      	<?php endif; ?>
						<?php if (!empty($venueUser['User']['UserProfile']['dob']) and !empty($venueUser['User']['UserProfile']['is_show_month_date'])): ?>
						  <dt><?php echo __l('Date of Birth:').' '; ?></dt>
						  <dd><?php echo $this->Html->userDob($venueUser['User']['UserProfile']['dob'], $venueUser['User']['UserProfile']['is_show_month_date']); ?></dd>
                        <?php endif; ?>
						<?php if (!empty($venueUser['User']['UserProfile']['City']['name'])): ?>
						    <dt><?php echo __l('City:').' '; ?></dt>
							<dd><?php echo $this->Html->cText($venueUser['User']['UserProfile']['City']['name']); ?></dd>
                        <?php endif; ?>
                           <dt><?php echo __l('Joined date:').' '; ?></dt>
                           <dd><?php echo $this->Html->userDob($venueUser['User']['created'],'0'); ?></dd>
                            <?php if (!empty($venueUser['User']['UserProfile']['Country']['name'])): ?>
							<dt><?php echo __l('Country:').' '; ?></dt>
							<dd><?php echo $this->Html->cText($venueUser['User']['UserProfile']['Country']['name']); ?></dd>
     					<?php endif; ?>
                     
                </dl>
                </div>
			<?php } else { ?>
				<?php
			 	echo $this->Html->link($this->Html->showImage('VenueUser',$venueUser['Venue']['Attachment'], array('dimension' => 'sidebar_thumb', 'alt' => sprintf('[Image: %s]', $this->Html->cText($venueUser['Venue']['name'], false)), 'title' => $this->Html->cText($venueUser['Venue']['name'], false))), array('controller' => 'Venues', 'action' => 'view', $venueUser['Venue']['slug']), array('escape' => false));?>
			    <h3>
                   <?php echo $this->Html->link($this->Html->cText($venueUser['Venue']['name'],false), array('controller'=> 'venues', 'action' => 'view', $venueUser['Venue']['slug']), array('escape' => false));?>
                </h3>
			
			<?php } ?>
		</li>
		<?php
				endforeach;
			else:
		?>
			<?php if (!empty($this->request->params['named']['venue'])): ?>
				<li class="no-record">
					<p class="notice"><?php echo __l('No regulars available');?></p>
				</li>
			<?php else: ?>
				<li class="notice-block">
					<p class="notice"><?php echo __l('No venues available');?></p>
				</li>
			<?php endif; ?>
		<?php
			endif;
		?>
	</ol>
	<?php
  if (!empty($venueUsers)) {?>
  	<div class="js-pagination">
			 <?php echo $this->element('paging_links'); ?>
          </div>
	<?php	}
	?>
