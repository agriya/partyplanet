<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="sexualOrientations index">
<h2><?php echo __l('Sexual Orientations');?></h2>
<?php echo $this->element('paging_counter');?>
<ol class="list" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
<?php
if (!empty($sexualOrientations)):

$i = 0;
foreach ($sexualOrientations as $sexualOrientation):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<li<?php echo $class;?>>
		<p><?php echo $this->Html->cInt($sexualOrientation['SexualOrientation']['id']);?></p>
		<p><?php echo $this->Html->cDateTime($sexualOrientation['SexualOrientation']['created']);?></p>
		<p><?php echo $this->Html->cDateTime($sexualOrientation['SexualOrientation']['modified']);?></p>
		<p><?php echo $this->Html->cText($sexualOrientation['SexualOrientation']['name']);?></p>
		<p><?php echo $this->Html->cBool($sexualOrientation['SexualOrientation']['is_active']);?></p>
		<div class="actions"><?php echo $this->Html->link(__l('Edit'), array('action'=>'edit', $sexualOrientation['SexualOrientation']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?><?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $sexualOrientation['SexualOrientation']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></div>
	</li>
<?php
    endforeach;
else:
?>
	<li>
		<p class="notice"><?php echo __l('No Sexual Orientations available');?></p>
	</li>
<?php
endif;
?>
</ol>

<?php
if (!empty($sexualOrientations)) {
    echo $this->element('paging_links');
}
?>
</div>
