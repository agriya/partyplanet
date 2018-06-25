<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="eventTypes index">
<h2><?php echo __l('Event Types');?></h2>
<?php echo $this->element('paging_counter');?>
<ol class="list" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
<?php
if (!empty($eventTypes)):

$i = 0;
foreach ($eventTypes as $eventType):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<li<?php echo $class;?>>
		<p><?php echo $this->Html->cInt($eventType['EventType']['id']);?></p>
		<p><?php echo $this->Html->cDateTime($eventType['EventType']['created']);?></p>
		<p><?php echo $this->Html->cDateTime($eventType['EventType']['modified']);?></p>
		<p><?php echo $this->Html->cText($eventType['EventType']['name']);?></p>
		<p><?php echo $this->Html->cText($eventType['EventType']['slug']);?></p>
		<p><?php echo $this->Html->cBool($eventType['EventType']['is_active']);?></p>
		<div class="actions"><?php echo $this->Html->link(__l('Edit'), array('action'=>'edit', $eventType['EventType']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?><?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $eventType['EventType']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></div>
	</li>
<?php
    endforeach;
else:
?>
	<li>
		<p class="notice"><?php echo __l('No Event Types available');?></p>
	</li>
<?php
endif;
?>
</ol>

<?php
if (!empty($eventTypes)) {
    echo $this->element('paging_links');
}
?>
</div>
