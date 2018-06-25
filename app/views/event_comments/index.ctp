<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="eventComments index clearfix">
	<ol class="list comment-list js-index-event-comment-response" start="<?php echo $this->Paginator->counter(array('format' => '%start%')); ?>">
		<?php if (!empty($eventComments)): ?>
			<?php 
			$i=0;
			foreach($eventComments as $eventComment):
					$class = null;
				if ($i++ % 2 == 0) {
					$class = 'altrow';
				}
			
			?>
			<li class="list-row comment clearfix <?php echo $class; ?> " id="comment-<?php echo $eventComment['EventComment']['id']; ?>" >
				<div class="grid_2 omega alpha">
					<?php
						$eventComment['User']['UserAvatar'] = !empty($eventComment['User']['UserAvatar']) ? $eventComment['User']['UserAvatar'] : array();
						if ($eventComment['EventComment']['user_id']):
							echo $this->Html->getUserAvatar($eventComment['User'], 'micro_medium_thumb');
						else:
							echo $this->Html->getUserAvatar($eventComment['User'], 'micro_medium_thumb');
						endif;
					?>
				</div>
			    <div class="grid_14 omega alpha">
                    <div class="clearfix">
                         <h3 class="grid_left">
        					<?php echo $this->Html->link('#', '#comment-'.$eventComment['EventComment']['id'], array('class' => 'js-scrollto')); ?>
                				<span class="author">
            						<?php
            							if ($eventComment['EventComment']['user_id']):
            								echo $this->Html->link($eventComment['User']['username'], array('controller' => 'users', 'action' => 'view', $eventComment['User']['username']), array('title' => $eventComment['User']['username'], 'escape' => false));
            							elseif(empty($eventComment['EventComment']['user_id'])&& !empty($eventComment['EventComment']['name'])):
            							 echo $this->Html->cText($eventComment['EventComment']['name']);
            							else:
            							 echo __l('(unregistered)');
            							endif;
            						?>
            					</span>
        						<?php echo __l('says');?>
                            </h3>
                            <p class="meta clearfix posted-date grid_right">
    							<span class="publish"><?php echo __l('Posted'); ?></span>
    							<span class="date"><?php echo $this->Html->cDateTimeHighlight($eventComment['EventComment']['created']); ?></span>
    						</p>
						</div>
					
							<p><?php echo $this->Html->cText($this->Html->truncate($eventComment['EventComment']['title']));?></p>
							<p><?php echo $this->Html->cText($this->Html->truncate($eventComment['EventComment']['comment']));?></p>
					
						<?php if ($this->Auth->sessionValid() && $eventComment['User']['id'] == $this->Auth->user('id')) { ?>
							<div class="actions">
								<?php echo $this->Html->link(__l('Delete'), array('controller' => 'event_comments', 'action' => 'delete', $eventComment['EventComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
							</div>
						<?php } ?>
					</div>
			
			</li>
			<?php endforeach; ?>
		<?php else: ?>
			<li>
				<p class="notice"><?php echo __l('No reviews available'); ?></p>
			</li>
		<?php endif; ?>
	</ol>
	<div class="js-pagination">
		<?php
			if (!empty($eventComments)) {
				echo $this->element('paging_links');
			}
		?>
	</div>
</div>