<?php /* SVN: $Id: index.ctp 615 2009-07-02 08:00:35Z annamalai_40ag08 $ */ ?>
<div class="videoRatings index">
<h2><?php echo __l('Video Ratings');?></h2>
<?php echo $this->element('paging_counter');?>
<table class="list">
    <tr>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th><?php echo $this->Paginator->sort('id');?></th>
        <th><?php echo $this->Paginator->sort('created');?></th>
        <th><?php echo $this->Paginator->sort('modified');?></th>
        <th><?php echo $this->Paginator->sort('user_id');?></th>
        <th><?php echo $this->Paginator->sort('video_id');?></th>
        <th><?php echo $this->Paginator->sort('ip');?></th>
    </tr>
<?php
if (!empty($videoRatings)):

$i = 0;
foreach ($videoRatings as $videoRating):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td class="actions"><span><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $videoRating['VideoRating']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span> <span><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $videoRating['VideoRating']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span></td>
		<td><?php echo $this->Html->cInt($videoRating['VideoRating']['id']);?></td>
		<td><?php echo $this->Html->cDateTime($videoRating['VideoRating']['created']);?></td>
		<td><?php echo $this->Html->cDateTime($videoRating['VideoRating']['modified']);?></td>
		<td><?php echo $this->Html->link($this->Html->cText($videoRating['User']['username']), array('controller'=> 'users', 'action'=>'view', $videoRating['User']['username']), array('escape' => false));?></td>
		<td><?php echo $this->Html->link($this->Html->cText($videoRating['Video']['title']), array('controller'=> 'videos', 'action'=>'view', $videoRating['Video']['slug']), array('escape' => false));?></td>
		<td><?php echo $this->Html->cText($videoRating['VideoRating']['ip']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="7"><p class="notice"><?php echo __l('No Video Ratings available');?></p></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($videoRatings)) {
    echo $this->element('paging_links');
}
?>
</div>
