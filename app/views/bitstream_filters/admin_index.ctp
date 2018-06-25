<?php /* SVN: $Id: admin_index.ctp 912 2009-09-15 06:38:41Z siva_063at09 $ */ ?>
<div class="bitstreamFilters index js-response">
    <h2><?php echo __l('Bitstream Filters');?></h2>
    <div>
        <?php echo $this->Html->link(__l('Add'), array('controller' => 'bitstream_filters', 'action' => 'add'), array('class' => 'add','title' => __l('Add'))); ?>
    </div>
    <div class="form-content-block">
        <?php echo $this->Form->create('BitstreamFilter', array('type' => 'get', 'class' => 'normal', 'action'=>'index')); ?>
        <?php echo $this->Form->input('filter_id',array('type'=>'select', 'empty' => __l('Please Select'))); ?>
        <div class="submit-block clearfix">
            <?php echo $this->Form->submit(__l('Search'));?>
        </div>
        <?php echo $this->Form->end();?>
    </div>
    <div  class="record-info">
        <div>
            <span><?php echo __l('Approved Records : '); ?></span>
            <?php echo $this->Html->cInt($approved); ?>
        </div>
        <div>
            <span><?php	echo __l('Disapproved Records : '); ?></span>
            <?php echo $this->Html->cInt($pending); ?>
        </div>
        <div>
            <span><?php	echo __l('Total Records : '); ?></span>
            <?php echo $this->Html->cInt($pending + $approved); ?>
        </div>
    </div>
    <?php echo $this->Form->create('BitstreamFilter' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
    <?php echo $this->element('paging_counter');?>
    <table class="list">
        <tr>
            <th><?php echo __l('Select'); ?></th>
            <th class="actions"><?php echo __l('Actions');?></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort('name');?></div></th>
            <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Active'), 'is_active');?></div></th>
        </tr>
        <?php
        if (!empty($bitstreamFilters)):

            $i = 0;
            foreach ($bitstreamFilters as $bitstreamFilter):
                $class = null;
                if ($i++ % 2 == 0) :
                    $class = ' class="altrow"';
                endif;
                if($bitstreamFilter['BitstreamFilter']['is_active']):
            		$status_class = 'js-checkbox-active';
            	else:
            		$status_class = 'js-checkbox-inactive';
            	endif;
                ?>
                <tr<?php echo $class;?>>
                    <td><?php echo $this->Form->input('BitstreamFilter.'.$bitstreamFilter['BitstreamFilter']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$bitstreamFilter['BitstreamFilter']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
                    <td class="actions"><span><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $bitstreamFilter['BitstreamFilter']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span> <span><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $bitstreamFilter['BitstreamFilter']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span></td>
                    <td><?php echo $this->Html->cText($bitstreamFilter['BitstreamFilter']['name']);?></td>
                    <td><?php echo $this->Html->cBool($bitstreamFilter['BitstreamFilter']['is_active']);?></td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="4"><p class="notice"><?php echo __l('No Bitstream Filters available');?></p></td>
            </tr>
            <?php
        endif;
        ?>
    </table>
    <?php
    if (!empty($bitstreamFilters)) :
        ?>
        <div>
    		<?php echo __l('Select:'); ?>
    		<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
    		<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
    		<?php echo $this->Html->link(__l('Inactive'), '#', array('class' => 'js-admin-select-pending','title' => __l('Inactive'))); ?>
    		<?php echo $this->Html->link(__l('Active'), '#', array('class' => 'js-admin-select-approved','title' => __l('Active'))); ?>
    	</div>
    	<div class="js-pagination">
            <?php echo $this->element('paging_links'); ?>
        </div>
        <div class="admin-checkbox-button">
            <?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
        </div>
        <div class="hide">
        <div class="submit-block clearfix">
            <?php echo $this->Form->submit('Submit');  ?>
        </div>
        </div>
        <?php
    endif; ?>
        <?php echo $this->Form->end(); ?>
</div>