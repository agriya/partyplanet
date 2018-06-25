<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="states index js-response">
	<ul class="filter-list clearfix">
		<li><span class="active round-5"><?php echo $this->Html->link(__l('Active'). ': ' . $this->Html->cInt($active_count, false),array('controller'=>'states','action'=>'index','filter_id' => ConstMoreAction::Active), array('title' => __l('Active')));?>
		</span></li>
		<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive'). ': ' . $this->Html->cInt($inactive_count, false), array('controller'=>'states','action'=>'index','filter_id' => ConstMoreAction::Inactive), array('title' => __l('Inactive')));?>
		</span></li>
		<li><span class="all round-5"><?php echo $this->Html->link(__l('Total'). ': ' . $this->Html->cInt($total_count, false), array('controller'=>'states','action'=>'index'), array('title' => __l('Total')));?>
		</span></li>
	</ul>
    <div class="clearfix">
    <div class="grid_left">
         <?php echo $this->element('paging_counter');?>
    </div>
    <div class="grid_left">
        <?php echo $this->Form->create('State', array('type' => 'get', 'class' => 'normal search-form', 'action'=>'index')); ?>
    	<?php echo $this->Form->input('filter_id',array('empty' => __l('Please Select'))); ?>
        <?php echo $this->Form->input('q', array('label' => 'Keyword')); ?>
        <?php echo $this->Form->submit(__l('Search'));?>
    	<?php echo $this->Form->end(); ?>
    </div>
    <div class="grid_right">
         <?php echo $this->Html->link(__l('Add'),array('controller'=>'states','action'=>'add'),array('title' => __l('Add New State') ,'class' => 'add'));?>
    </div>
    </div>
   
        <?php
        echo $this->Form->create('State' , array('action' => 'update','class'=>'normal'));?>
        <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
       
        <table class="list">
            <tr>
                <th class="select"><?php echo __l('Select'); ?></th>
                <th class="actions"><?php echo __l('Actions');?></th>
				<th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Created'),'created');?></div></th>
                <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Country'),'country_id');?></div></th>
                <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Name'),'name');?></div></th>
            </tr>
            <?php
                if (!empty($states)):
                $i = 0;
                    foreach ($states as $state):
                            $class = null;
	                        $active_class = '';
                        if ($i++ % 2 == 0) :
                            $class = 'altrow';
                        endif;
                        if($state['State']['is_approved'])  :
                            $status_class = 'js-checkbox-active';
                        else:
                            $active_class = ' inactive-record';
                            $status_class = 'js-checkbox-inactive';
                        endif;
                        ?>
                       <tr class="<?php echo $class.$active_class;?>">
                            <td>
                                <?php
                                    echo $this->Form->input('State.'.$state['State']['id'].'.id',array('type' => 'checkbox', 'id' => "admin_checkbox_".$state['State']['id'],'label' => false , 'class' => $status_class.' js-checkbox-list'));
                                ?>
                            </td>
                            <td>
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
                                                   <li>
                                                    <?php echo $this->Html->link(__l('Edit'), array('action'=>'edit', $state['State']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?>
                                                    </li>
                                                    <li>
                                                    <?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $state['State']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
                                                    </li>
                                                </ul>
                                			 </div>
                        					<div class="action-bottom-block"></div>
                        				</div>
                               </div>
                              
                            </td>
					        <td class="dc"><?php echo $this->Html->cDateTime($state['State']['created']);?></td>
                            <td class="dl"><?php echo $this->Html->cText($state['Country']['name']);?></td>
                            <td class="dl"><?php echo $this->Html->cText($state['State']['name']);?></td>
                        </tr>
                        <?php
                    endforeach;
            else:
                ?>
                <tr>
                    <td colspan="4"><p class="notice"><?php echo __l('No states available');?></p></td>
                </tr>
                <?php
            endif;
            ?>
        </table>
        <?php
         if (!empty($states)) : ?>
            <div class="admin-select-block">
            <div class="grid_left">
                <?php echo __l('Select:'); ?>
                <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title'=>__l('All'))); ?>
                <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title'=>__l('None'))); ?>
                <?php echo $this->Html->link(__l('Unapproved'), '#', array('class' => 'js-admin-select-pending','title'=>__l('Unapproved'))); ?>
                <?php echo $this->Html->link(__l('Approved'), '#', array('class' => 'js-admin-select-approved','title'=>__l('Approved'))); ?>
            </div>
              <div class="admin-checkbox-button grid_left">
                 <?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
            </div>
            </div>
            <div class="js-pagination grid_right">
            <?php  echo $this->element('paging_links'); ?>
            </div>
          
            <div class="hide">
                <?php echo $this->Form->submit('Submit');  ?>
            </div>
            <?php
         endif; ?>
        <?php echo $this->Form->end();?>
     
    </div>