<?php /* SVN: $Id: admin_index.ctp 615 2009-07-02 08:00:35Z annamalai_40ag08 $ */ ?>
<div class="videoFlagCategories index">
    <ul class="filter-list clearfix">
		<li><span class="active round-5"><?php echo $this->Html->link(__l('Active'). ': ' . $this->Html->cInt($active_count, false),array('controller'=>'video_flag_categories','action'=>'index','filter_id' => ConstMoreAction::Active), array('title' => __l('Active')));?>
		</span></li>
		<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive'). ': ' . $this->Html->cInt($inactive_count, false), array('controller'=>'video_flag_categories','action'=>'index','filter_id' => ConstMoreAction::Inactive), array('title' => __l('Inactive')));?>
		</span></li>
		<li><span class="all round-5"><?php echo $this->Html->link(__l('Total'). ': ' . $this->Html->cInt($total_count, false), array('controller'=>'video_flag_categories','action'=>'index'), array('title' => __l('Total')));?>
		</span></li>
	</ul>
	<div class="clearfix">
    <div class="grid_left">
          <?php echo $this->element('paging_counter');?>
    </div>
     <div class="grid_right">
           <?php echo $this->Html->link(__l('Add Video flag category'), array('action'=>'add'), array('class' => 'add', 'title' => __l('Add Video flag category')));?>
         </div>
        </div>
<?php echo $this->Form->create('VideoFlagCategory' , array('class' => 'normal','action' => 'update'));
echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
?>
<table class="list">
    <tr>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Created'),'created');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Name'),'name');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Video Flags'),'video_flag_count');?></th>
    </tr>
<?php
if (!empty($videoFlagCategories)):

$i = 0;
foreach ($videoFlagCategories as $videoFlagCategory):
	$class = null;
    $active_class = '';
	if ($i++ % 2 == 0) {
		$class = 'altrow';
	}
	if(!$videoFlagCategory['VideoFlagCategory']['is_active']):
		$active_class = ' inactive-record';
	endif;
?>
		<tr class="<?php echo $class.$active_class;?>">
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
                            <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $videoFlagCategory['VideoFlagCategory']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                            <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $videoFlagCategory['VideoFlagCategory']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
   					   </ul>
    				</div>
    					<div class="action-bottom-block"></div>
    				</div>
          </div>
        </td>
		<td class="dc"><?php echo $this->Html->cDateTime($videoFlagCategory['VideoFlagCategory']['created']);?></td>
		<td class="dl"><?php echo $this->Html->cText($videoFlagCategory['VideoFlagCategory']['name']);?></td>
		<td class="dc"><?php echo $this->Html->link($this->Html->cInt($videoFlagCategory['VideoFlagCategory']['video_flag_count'],false), array('controller'=> 'video_flags', 'action'=>'index', 'category'=>$videoFlagCategory['VideoFlagCategory']['id'],'admin'=>true), array('escape' => false));?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="6"><p class="notice"><?php echo __l('No Video Flag Categories available');?></p></td>
	</tr>
<?php
endif;
?>
</table>
 <div class="clearfix select-block-bot">
 <div class="grid_right">
<?php
if (!empty($videoFlagCategories)) {
    echo $this->element('paging_links');
}
?></div>
</div>
<?php echo $this->Form->end(); ?>
</div>
