<?php /* SVN: $Id: $ */ ?>
<?php echo $this->element('message_message-left_sidebar', array('cache' => array('config' => 'sec')));?>
<div class="labels index">
<h2><?php echo __l('Labels');?></h2>
<div>
	<?php
	echo $this->Html->link(__l('Add'), array('action' => 'add'), array('class' => 'add','title'=>__l('Add')));
	?>
</div>
<?php echo $this->element('paging_counter');?>
<table class="list">
    <tr>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th><?php echo $this->Paginator->sort('name');?></th>
    </tr>
<?php
if (!empty($labels)):

$i = 0;
foreach ($labels as $label):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td class="actions"><span><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $label['User'][0]['LabelsUser']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span> <span><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete',  $label['User'][0]['LabelsUser']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span></td>
		<td><?php echo $this->Html->cText($label['Label']['name']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="6"><p class="notice"><?php echo __l('No Labels available');?></p></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($labels)) {
    echo $this->element('paging_links');
}
?>
</div>