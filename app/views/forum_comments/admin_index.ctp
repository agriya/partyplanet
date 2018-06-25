<?php /* SVN: $Id: $ */ ?>
<div class="forumComments index js-response">
     <div class="clearfix">
       <div class="grid_left">
             <?php echo $this->element('paging_counter');?>
       </div>
       <?php if(empty($this->request->params['named']['forum'])):?>
        <div class=" grid_left">
            <?php echo $this->Form->create('ForumComment', array('type' => 'get', 'class' => 'normal search-form', 'action'=>'index')); ?>
                 <?php echo $this->Form->input('q', array('label' => 'Keyword')); ?>
          		<?php echo $this->Form->submit(__l('Search'));?>
                 <?php echo $this->Form->end(); ?>
        </div>
        <?php endif;?>
    </div>
    <?php echo $this->Form->create('ForumComment' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
<table class="list">
    <tr>
        <th><?php echo __l('Select'); ?></th>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
        <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('forum_id');?></div></th>
        <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('user_id');?></div></th>
        <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('comment');?></div></th>
        <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('IP'), 'Ip.ip');?></div></th>
    </tr>
<?php
if (!empty($forumComments)):

$i = 0;
foreach ($forumComments as $forumComment):
 $class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
        <td><?php echo $this->Form->input('ForumComment.'.$forumComment['ForumComment']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$forumComment['ForumComment']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?></td>
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
                                    <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $forumComment['ForumComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
                                </ul>
                			 </div>
        					<div class="action-bottom-block"></div>
        				</div>
               </div>
         </td>
		<td class="dc"><?php echo $this->Html->cDateTime($forumComment['ForumComment']['created']);?></td>
		<td class="dl"><?php echo $this->Html->link($this->Html->cText($forumComment['Forum']['title'],false), array('controller' => 'forums', 'action' => 'view', $forumComment['Forum']['id'],'admin' => false), array('escape' => false, 'title' => $this->Html->cText($forumComment['Forum']['title'],false)));?></td>
		<td class="dl"><?php echo $this->Html->link($this->Html->cText($forumComment['User']['username'],false), array('controller'=> 'users', 'action'=>'view', $forumComment['User']['username'], 'admin' => false), array('escape' => false, 'title' => $this->Html->cText($forumComment['User']['username'],false)));?></td>
		<td class="dl"><div class="js-desc-to-trucate {len:'90'}"><?php echo $this->Html->cText($forumComment['ForumComment']['comment'],false);?></div></td>
        <td class="dl">
                         <?php if(!empty($forumComment['Ip']['ip'])): ?>
                            <?php echo  $this->Html->link($forumComment['Ip']['ip'], array('controller' => 'users', 'action' => 'whois', $forumComment['Ip']['ip'], 'admin' => false), array('target' => '_blank', 'title' => 'whois '.$forumComment['Ip']['host'], 'escape' => false));
							?>
							<p>
							<?php
                            if(!empty($forumComment['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($forumComment['Ip']['Country']['iso_alpha2']); ?>" title ="<?php echo $forumComment['Ip']['Country']['name']; ?>">
									<?php echo $forumComment['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($forumComment['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $forumComment['Ip']['City']['name']; ?>    </span>
                            <?php endif; ?>
                            </p>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?>
			</td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="7"><p class="notice"><?php echo __l('No Forum Comments available');?></p></td>
	</tr>
<?php
endif;
?>
</table>
<?php
if (!empty($forumComments)) : ?>
    <div class="clearfix select-block-bot">
        <div class="admin-select-block grid_left">
        <div>
    		<?php echo __l('Select:'); ?>
    		<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all', 'title' => __l('All'))); ?>
    		<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none', 'title' => __l('None'))); ?>
    	</div>
    	<div class="admin-checkbox-button"><?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
    </div>
        <div class="js-pagination grid_right">
            <?php echo $this->element('paging_links'); ?>
        </div>
    </div>
    <div class="hide">
        <?php echo $this->Form->submit('Submit'); ?>
     </div>
    <?php
endif;
echo $this->Form->end();
?>


</div>