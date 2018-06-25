<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="userOpenids index">
<h2><?php echo __l('User Openids');?></h2>
<?php echo $this->element('paging_counter');?>
<ol class="list" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
<?php
if (!empty($userOpenids)):

$i = 0;
foreach ($userOpenids as $userOpenid):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<li<?php echo $class;?>>
		<p><?php echo $this->Html->cInt($userOpenid['UserOpenid']['id']);?></p>
		<p><?php echo $this->Html->cDateTime($userOpenid['UserOpenid']['created']);?></p>
		<p><?php echo $this->Html->cDateTime($userOpenid['UserOpenid']['modified']);?></p>
		<p><?php echo $this->Html->link($this->Html->cText($userOpenid['User']['username']), array('controller'=> 'users', 'action' => 'view', $userOpenid['User']['username']), array('escape' => false));?></p>
		<p><?php echo $this->Html->cText($userOpenid['UserOpenid']['openid']);?></p>
		<div class="actions"><?php echo $this->Html->link(__l('Edit'), array('action'=>'edit', $userOpenid['UserOpenid']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?><?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $userOpenid['UserOpenid']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></div>
	</li>
<?php
    endforeach;
else:
?>
	<li>
		<p class="notice"><?php echo __l('No user openids available');?></p>
	</li>
<?php
endif;
?>
</ol>

<?php
if (!empty($userOpenids)) {
    echo $this->element('paging_links');
}
?>
</div>
