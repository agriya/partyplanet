<?php /* SVN: $Id: $ */ ?>
<div class="eventUsers js-response index">
<?php echo $this->element('paging_counter');?>
<table class="list">
    <tr>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Joined'),'created');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('event_id');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('user_id');?></th>
    </tr>
<?php
if (!empty($eventUsers)):

$i = 0;
foreach ($eventUsers as $eventUser):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td class="actions">
		    <div class="action-block">
                <span class="action-information-block">
                    <span class="action-left-block">&nbsp;&nbsp;</span>
                        <span class="action-center-block">
                            <span class="action-info">
                                <?php echo __l('Action');?>
                             </span>
                        </span>
                    </span>
                    <div class="action-inner-block">
                    <div class="action-inner-left-block">
                        <ul class="action-link clearfix">
                        <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $eventUser['GuestListUser']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
   					 </ul>
    					</div>
    					<div class="action-bottom-block"></div>
    				  </div>
          </div>
		
        </td>
		<td class="dc"><?php echo $this->Html->cDateTime($eventUser['GuestListUser']['created']);?></td>
		<td class="dl"><?php echo $this->Html->link($this->Html->cText($eventUser['GuestList']['Event']['title']), array('controller'=> 'events', 'action'=>'view', $eventUser['GuestList']['Event']['slug'], 'admin' => false), array('escape' => false));?></td>
		<td class="dl">
			<?php 
				echo $this->Html->link($this->Html->cText($eventUser['User']['username']), array('controller'=> 'users', 'action' => 'view', $eventUser['User']['username'], 'admin' => false), array('escape' => false));
				if($eventUser['GuestListUser']['in_party_count'] > 1) {
						echo " + " . ($eventUser['GuestListUser']['in_party_count'] - 1);
				} 
			?>
		</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="6"><p class="notice"><?php echo __l('No event users available');?></p></td>
	</tr>
<?php
endif;
?>
</table>

<div class="clearfix select-block-bot">
<?php
if (!empty($eventUsers)) { ?>
<div class="js-pagination grid_right"><?php echo $this->element('paging_links'); ?> </div>
<?php }
?>
</div>

</div>
