<?php /* SVN: $Id: admin_index.ctp 801 2009-07-25 13:22:35Z boopathi_026ac09 $ */ ?>
<div class="photoFlagCategories index js-response">

       <ul class="filter-list clearfix">
			<li><span class="active round-5"><?php echo $this->Html->link(__l('Active'). ': ' . $this->Html->cInt($active_count, false),array('controller'=>'photo_flag_categories','action'=>'index','filter_id' => ConstMoreAction::Active), array('title' => __l('Active')));?>
			</span></li>
			<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive'). ': ' . $this->Html->cInt($inactive_count, false), array('controller'=>'photo_flag_categories','action'=>'index','filter_id' => ConstMoreAction::Inactive), array('title' => __l('Inactive')));?>
			</span></li>
			<li><span class="all round-5"><?php echo $this->Html->link(__l('Total'). ': ' . $this->Html->cInt($total_count, false), array('controller'=>'photo_flag_categories','action'=>'index'), array('title' => __l('Total')));?>
			</span></li>
		</ul>
        <div class="clearfix">
            <div class="grid_left"><?php echo $this->element('paging_counter');?></div>
            <div class="grid_right">
                <?php echo $this->Html->link(__l('Add'), array('controller' => 'photo_flag_categories', 'action' => 'add'), array('class' => 'add','title' => __l('Add'))); ?>
            </div>
        </div>
        <?php echo $this->Form->create('PhotoFlagCategory' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
    <table class="list">
        <tr>
            <th class="select"><?php echo __l('Select'); ?></th>
            <th class="actions"><?php echo __l('Actions');?></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('name');?></div></th>
            <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort('photo_flag_count');?></div></th>
        </tr>
        <?php
        if (!empty($photoFlagCategories)):

            $i = 0;
            foreach ($photoFlagCategories as $photoFlagCategory):
                $class = null;
                $active_class = '';
                if ($i++ % 2 == 0) :
                   $class = 'altrow';
                endif;
                if($photoFlagCategory['PhotoFlagCategory']['is_active']):
            		$status_class = 'js-checkbox-active';
            	else:
                   $active_class = ' inactive-record';
            		$status_class = 'js-checkbox-inactive';
            	endif;
                ?>
             <tr class="<?php echo $class.$active_class;?>">
                    <td class="select"><?php echo $this->Form->input('PhotoFlagCategory.'.$photoFlagCategory['PhotoFlagCategory']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$photoFlagCategory['PhotoFlagCategory']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
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
                                            <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $photoFlagCategory['PhotoFlagCategory']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                                            <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $photoFlagCategory['PhotoFlagCategory']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
                   					   </ul>
                    				</div>
                    					<div class="action-bottom-block"></div>
                    				  </div>
                          </div>
                    </td>
                    <td class="dl"><?php echo $this->Html->cText($photoFlagCategory['PhotoFlagCategory']['name']);?></td>
                    <td class="dc"><?php echo $this->Html->link($this->Html->cInt($photoFlagCategory['PhotoFlagCategory']['photo_flag_count'], false), array('controller' => 'photo_flags', 'action' => 'index', 'category' => $photoFlagCategory['PhotoFlagCategory']['id']));?></td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="4"><p class="notice"><?php echo __l('No Photo Flag Categories available');?></p></td>
            </tr>
            <?php
        endif;
        ?>
    </table>
    <?php
    if (!empty($photoFlagCategories)) :
        ?>
        <div class="clearfix select-block-bot">
            	 <div class="admin-select-block grid_left">
                    <div>
                		<?php echo __l('Select:'); ?>
                		<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
                		<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
                		<?php echo $this->Html->link(__l('Inactive'), '#', array('class' => 'js-admin-select-pending','title' => __l('Inactive'))); ?>
                		<?php echo $this->Html->link(__l('Active'), '#', array('class' => 'js-admin-select-approved','title' => __l('Active'))); ?>
                	</div>
                	<div class="admin-checkbox-button">
                        <?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
                    </div>
                </div>
            	<div class="js-pagination">
                    <?php echo $this->element('paging_links'); ?>
                </div>
        </div>
        <div class="hide">
            <div class="submit-block clearfix">
                <?php echo $this->Form->submit('Submit');  ?>
            </div>
        </div>
        <?php
    endif;
    echo $this->Form->end();
    ?>
 
</div>
</div>