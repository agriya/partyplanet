<?php /* SVN: $Id: $ */ ?>
<div class="states form form-content-block">
       
            <?php echo $this->Form->create('State',  array('class' => 'normal','action'=>'edit'));?>
			<legend class="crumb"><?php echo $this->Html->link(__l('States'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit State');?></legend>
            <?php
                echo $this->Form->input('id');
                echo $this->Form->input('country_id',array('empty'=>'Please Select'));
                echo $this->Form->input('name');
                ?>
                <div class="submit-block clearfix">
                <?php
                echo $this->Form->end(__l('Update'));?>
                </div>
        </div>
    </div>
</div>

