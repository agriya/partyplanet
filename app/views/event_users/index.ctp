<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="eventUsers index js-response">
	<ol class="list comment-list" start="<?php echo $this->Paginator->counter(array(
		'format' => '%start%'
	));?>">
	<?php
		if (!empty($eventUsers)):
			$i = 0;
			foreach ($eventUsers as $eventUser):
				$class = null;
				if ($i++ % 2 == 0) {
					$class = 'altrow';
				}
	?>
		<li class="clearfix <?php echo $class; ?>">
			<div class="grid_2 omega alpha" id="avatar3<?php echo $i;?>">
				<?php 
					echo $this->Html->getUserAvatar($eventUser['User'], 'micro_normal_thumb');
				?>
			</div>
			<div class="grid_14 omega alpha">
				<h3 class="list-view-title"><?php echo $this->Html->link($eventUser['User']['username'], array('controller' => 'users', 'action' => 'view', $eventUser['User']['username']), array('title' => $eventUser['User']['username'], 'escape' => false)); ?></h3>
				<p class="meta clearfix"><?php echo __l('Joined on: ') . $this->Html->cDateTime($eventUser['EventUser']['created']);?></p>
				<?php if(!empty($type) and $type=='basic'): ?>
					<div class="actions"><?php echo $this->Html->link(__l('Remove'), array('action'=>'delete', $eventUser['EventUser']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></div>
				<?php endif; ?>
			</div>
		</li>
	<?php
				endforeach;
		else:
	?>
		<li class="notice-block">
			<p class="notice"><?php echo __l('Nobody joined yet');?></p>
		</li>
	<?php
		endif;
	?>
	</ol>
	<div class="js-pagination">
		<?php
			if (!empty($eventUsers)) {
				echo $this->element('paging_links');
			}
		?>
	</div>
</div>