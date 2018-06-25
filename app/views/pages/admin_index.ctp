<?php /* SVN: $Id: admin_index.ctp 739 2009-07-21 16:22:44Z boopathi_026ac09 $ */ ?>
<div class='js-response'>
<div class="staticpage index">
<div class="clearfix">
    <div class="grid_left"><?php echo $this->element('paging_counter');?></div>
    <div class="grid_right"> <span class="dd-event"><?php echo $this->Html->link(__l('Add'), array('action' => 'admin_add'), array('class' => 'add', 'title' => __l('Add'))); ?></span></div>
</div>
<?php 	echo $this->Form->create('Page' , array('class' => 'normal', 'action'=>'update'));?>
<?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
<table class="list">
    <tr>
         <th class="actions"><?php echo __l('Actions');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Title'),'title');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Content'),'content');?></th>
    </tr>
<?php
if (!empty($pages)):

$i = 0;
foreach ($pages as $page):
    $class = null;
     if ($i++ % 2 == 0) :
		$class = 'altrow';
		
    endif;
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
                            <li><?php echo $this->Html->link(__l('View'), array('controller' => 'pages', 'action' => 'view', $page['Page']['slug']), array('class' => 'view', 'title' => __l('View')));?></li>
                            <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $page['Page']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                            <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $page['Page']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
       					 </ul>
        			 </div>
        					<div class="action-bottom-block"></div>
        				  </div>
               </div>
        </td>
		<td class="dl"><?php echo $this->Html->cText($page['Page']['title']);?></td>
		<td class="dl"><?php echo $this->Html->cText($page['Page']['content']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="4"><p class="notice"><?php echo __l('No Pages available');?></p></td>
	</tr>
<?php
endif;
?>
</table>
<div class="clearfix select-block-bot">
 <?php
if (!empty($pages)) { ?>
   <div class="js-pagination grid_right"><?php echo $this->element('paging_links'); ?></div>
<?php } ?>
</div>
	<div class="hide">
		<?php echo $this->Form->submit(); ?>
	</div>

<?php echo $this->Form->end(); ?>
</div>
</div>
