<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="friendStatuses index">
<h2><?php echo __l('Friend Statuses');?></h2>
<?php echo $this->element('paging_counter');?>
<ol class="list" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
<?php
if (!empty($friendStatuses)):

$i = 0;
foreach ($friendStatuses as $friendStatus):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<li<?php echo $class;?>>
		<p><?php echo $this->Html->cInt($friendStatus['FriendStatus']['id']);?></p>
		<p><?php echo $this->Html->cDateTime($friendStatus['FriendStatus']['created']);?></p>
		<p><?php echo $this->Html->cDateTime($friendStatus['FriendStatus']['modified']);?></p>
		<p><?php echo $this->Html->cText($friendStatus['FriendStatus']['name']);?></p>
		<div class="actions"><?php echo $this->Html->link(__l('Edit'), array('action'=>'edit', $friendStatus['FriendStatus']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?><?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $friendStatus['FriendStatus']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></div>
	</li>
<?php
    endforeach;
else:
?>
	<li>
		<p class="notice"><?php echo __l('No friend statuses available');?></p>
	</li>
<?php
endif;
?>
</ol>

<?php
if (!empty($friendStatuses)) {
    echo $this->element('paging_links');
}
?>
</div>
