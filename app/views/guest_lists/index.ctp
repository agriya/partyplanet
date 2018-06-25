<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="guestLists index">
<h2><?php echo __l('Guest Lists');?></h2>
<?php echo $this->element('paging_counter');?>
<ol class="list" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
<?php
if (!empty($guestLists)):

$i = 0;
foreach ($guestLists as $guestList):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<li<?php echo $class;?>>
		<p><?php echo $this->Html->cInt($guestList['GuestList']['id']);?></p>
		<p><?php echo $this->Html->cDateTime($guestList['GuestList']['created']);?></p>
		<p><?php echo $this->Html->cDateTime($guestList['GuestList']['modified']);?></p>
		<p><?php echo $this->Html->cText($guestList['GuestList']['name']);?></p>
		<p><?php echo $this->Html->cText($guestList['GuestList']['details']);?></p>
		<p><?php echo $this->Html->cInt($guestList['GuestList']['guest_limit']);?></p>
		<p><?php echo $this->Html->link($this->Html->cText($guestList['Event']['title'],false), array('controller'=> 'events', 'action' => 'view', $guestList['Event']['slug']), array('escape' => false));?></p>
		<p><?php echo $this->Html->cInt($guestList['GuestList']['maximum_guest_limit']);?></p>
		<p><?php echo $this->Html->cInt($guestList['GuestList']['maximum_guest_of_guest']);?></p>
		<p><?php echo $this->Html->cTime($guestList['GuestList']['website_close_time']);?></p>
		<p><?php echo $this->Html->cTime($guestList['GuestList']['guest_close_time']);?></p>
		<p><?php echo $this->Html->cText($guestList['GuestList']['fax']);?></p>
		<p><?php echo $this->Html->cText($guestList['GuestList']['email']);?></p>
		<div class="actions"><?php echo $this->Html->link(__l('Edit'), array('action'=>'edit', $guestList['GuestList']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?><?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $guestList['GuestList']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></div>
	</li>
<?php
    endforeach;
else:
?>
	<li>
		<p class="notice"><?php echo __l('No Guest Lists available');?></p>
	</li>
<?php
endif;
?>
</ol>

<?php
if (!empty($guestLists)) {
    echo $this->element('paging_links');
}
?>
</div>
