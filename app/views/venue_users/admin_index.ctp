<?php /* SVN: $Id: admin_index.ctp 1367 2009-05-14 12:40:53Z jayapriya_28ag08 $ */ ?>
<div class="venueUsers index">
<?php echo $this->element('paging_counter');?>
<table class="list">
    <tr>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th class="dc"><?php echo $this->Paginator->sort('created');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('user_id');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('venue_id');?></th>
    </tr>
<?php
if (!empty($venueUsers)):

$i = 0;
foreach ($venueUsers as $venueUser):
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
                        <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $venueUser['VenueUser']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
   					 </ul>
    					</div>
    					<div class="action-bottom-block"></div>
    				  </div>
          </div>
        </td>
		<td class="dc"><?php echo $this->Html->cDateTime($venueUser['VenueUser']['created']);?></td>
		<td class="dl"><?php echo $this->Html->link($this->Html->cText($venueUser['User']['username']), array('controller'=> 'users', 'action'=>'view', $venueUser['User']['username'], 'admin' => false), array('escape' => false));?></td>
		<td class="dl"><?php echo $this->Html->link($this->Html->cText($venueUser['Venue']['name'],false), array('controller'=> 'venues', 'action'=>'view', $venueUser['Venue']['slug'],'admin'=>false), array('escape' => false));?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="6"><p class="notice"><?php echo __l('No Venue Users available');?></p></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($venueUsers)) {
    echo $this->element('paging_links');
}
?>
</div>
